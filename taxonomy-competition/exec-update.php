<?php

class TMCompetitionResponse {
  public $status;
  public $plugin;
  public $options;
  public $seasons;
  function __construct() {
    $this->seasons = Array();
    $this->status = 'OK';
  }
}

class TMPluginResponse {
  public $status;
  function __construct() {
    $this->status = 'OK';
  }
}

class TMResponse {
  public $status;
  public $competitons;
  function __construct() {
    $this->status = 'OK';
    $this->competitons = Array();
  }
}

if ( ! function_exists( 'tm_competition_exec_update' ) ):
  function tm_competition_exec_update( $term_id ){
    $response = new TMCompetitionResponse();
    global $tm_competition_autofetchers;
    $saved_autofetcher = tm_get_competition_autofetcher ( $term_id );
    $response->plugin = $saved_autofetcher;
    // Only do something if the autofetcher is still registered
    if ( array_key_exists ($saved_autofetcher, $tm_competition_autofetchers ) && $saved_autofetcher != 'none' ) {
      if ( function_exists( $saved_autofetcher . '_competition_exec_update' ) ) {
        $saved_autofetcheropts = tm_get_competition_autofetcher_options ( $term_id );
        $term = tm_get_competiton_byid( $term_id );
        $saved_autofetcheropts['tm_competition_name'] = $term->name;
        $saved_autofetcheropts['tm_competition_id'] = $term_id;
        $saved_autofetcheropts['tm_competition_slug'] = $term->slug;
        $saved_autofetcheropts['tm_competition_description'] = $term->descriotion;
        if ( ! isset($saved_autofetcheropts['tm_competition_seasons']) || $saved_autofetcheropts['tm_competition_seasons'] == '' ) {
          $saved_autofetcheropts['tm_competition_seasons'] = get_option( 'tm_default_season' );
        }
        $response->options = $saved_autofetcheropts;
        $leaguetable = tm_get_competition_leaguetable_data( $term_id );

        $pluginResponse = new TMPluginResponse();
        $seasons = explode(',', $saved_autofetcheropts['tm_competition_seasons']);
        foreach ($seasons as $season) {
          $saved_autofetcheropts['tm_competition_season'] = $season;
          try {
            $leaguetable[$season] = call_user_func($saved_autofetcher . '_competition_exec_update', $saved_leaguetable, $saved_autofetcheropts );
            $pluginResponse->status = 'OK';
          } catch (Exception $e) {
            $response->status = 'ERROR';
            $pluginResponse->status = 'ERROR';
            $pluginResponse->message = $e->getMessage() ;
          }
          $response->seasons[$season] = $pluginResponse;
        }
      }
      tm_update_competition_leaguetable_data( $term_id , $leaguetable );
    } else {
      $response->status = "NOTREGISTERED";
      $response->message = "Plugin " . $saved_autofetcher . ' not active';
    }
    return $response;
  }
endif;

if ( ! function_exists( 'tm_competition_exec_update_all' ) ):
  function tm_competition_exec_update_all() {
    $response = new TMResponse();
    foreach(tm_get_competitons() as $competition) {
      $response->competitons[$competition->slug] = tm_competition_exec_update( $competition->term_id );
      if ( $response->competitons[$competition->slug]->status != 'OK' ) {
        $response->status = 'ERROR';
      }
    }
    return $response;
  }
endif;

?>
