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

/* == UPDATE LEAGUETABLE ============================================================ */
if ( ! function_exists( 'tm_competition_exec_update_leguetable' ) ):
  function tm_competition_exec_update_leguetable( $term ){
    $response = new TMCompetitionResponse();
    global $tm_competition_autofetchers;
    $saved_autofetcher = tm_competition_get_autofetcher ( $term->term_id );
    $response->plugin = $saved_autofetcher;
    // Only do something if the autofetcher is still registered
    if ( array_key_exists ($saved_autofetcher, $tm_competition_autofetchers ) && $saved_autofetcher != 'none' ) {
      if ( function_exists( $saved_autofetcher . '_update_leaguetableseason' ) ) {
        $saved_autofetcheropts = tm_competition_get_autofetcher_options ( $term->term_id );
        $saved_autofetcheropts['tm_competition_name'] = $term->name;
        $saved_autofetcheropts['tm_competition_id'] = $term->term_id;
        $saved_autofetcheropts['tm_competition_slug'] = $term->slug;
        $saved_autofetcheropts['tm_competition_description'] = $term->descriotion;
        if ( ! isset($saved_autofetcheropts['tm_competition_seasons']) || $saved_autofetcheropts['tm_competition_seasons'] == '' ) {
          $saved_autofetcheropts['tm_competition_seasons'] = get_option( 'tm_default_season' );
        }
        $response->options = $saved_autofetcheropts;
        $leaguetable = tm_competition_get_leaguetable( $term->term_id );

        $pluginResponse = new TMPluginResponse();
        $seasons = explode(',', $saved_autofetcheropts['tm_competition_seasons']);
        foreach ($seasons as $season) {
          $saved_autofetcheropts['tm_competition_season'] = $season;
          try {
            $leaguetable[$season] = call_user_func($saved_autofetcher . '_update_leaguetableseason', $saved_autofetcheropts );
            $pluginResponse->status = 'OK';
          } catch (Exception $e) {
            $response->status = 'ERROR';
            $pluginResponse->status = 'ERROR';
            $pluginResponse->message = $e->getMessage() ;
          }
          $response->seasons[$season] = $pluginResponse;
        }
      }
      tm_competition_update_leaguetable( $term->term_id , $leaguetable );
    } else {
      $response->status = "NOTREGISTERED";
      $response->message = "Plugin " . $saved_autofetcher . ' not active';
    }
    return $response;
  }
endif;


/* == UPDATE ALL ============================================================ */
if ( ! function_exists( 'tm_competition_exec_update_all' ) ):
  function tm_competition_exec_update_all( $competitions  = Array() ) {
    if ( sizeof($competitions) == 0 ) {
      $competitions = tm_competitons_getall();
    }
    $response = new TMResponse();
    foreach($competitions as $competition) {
      $response->competitons[$competition->slug] = tm_competition_exec_update_leguetable( $competition );
      if ( $response->competitons[$competition->slug]->status != 'OK' ) {
        $response->status = 'ERROR';
      }

      $teams = Array();
      $leaguetables = tm_competition_get_leaguetable( $competition->term_id );
      foreach ($leaguetables as $seasonleaguetable) {
        foreach ($seasonleaguetable as $tablentry) {
          if ( ! array_key_exists( $tablentry->team, $teams) ) {
           $teams[] = $tablentry->team;
          }
        }
      }
      tm_competition_update_teams($competition->term_id , $teams);
    }

    return $response;
  }
endif;

?>
