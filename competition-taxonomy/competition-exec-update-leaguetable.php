<?php
if ( ! class_exists('TM_CompetitionResponse') ):
  class TM_CompetitionResponse {
    public $status;
    public $plugin;
    public $options;
    public $seasons;
    function __construct() {
      $this->seasons = Array();
      $this->status = 'OK';
    }
  }
endif;

/* == UPDATE LEAGUETABLE ============================================================ */
if ( ! function_exists( 'tm_competition_exec_update_leguetable' ) ):
  function tm_competition_exec_update_leguetable( $term_id ){

    $response = new TM_CompetitionResponse();
    $saved_autofetcher = tm_competition_get_autofetcher ( $term_id );
    $response->plugin = $saved_autofetcher;
    // Only do something if the autofetcher is still registered
    if ( tm_autofetch_isvalidplugin($saved_autofetcher) ) {
      $saved_autofetcheropts = tm_competition_get_autofetcher_options ( $term_id );
      if ( ! isset($saved_autofetcheropts['tm_competition_seasons']) || $saved_autofetcheropts['tm_competition_seasons'] == '' ) {
        $saved_autofetcheropts['tm_competition_seasons'] = get_option( 'tm_default_season' );
      }
      $response->options = $saved_autofetcheropts;
      $leaguetable = tm_competition_get_leaguetable( $term_id );

      $pluginResponse = new TM_PluginResponse();
      foreach ($saved_autofetcheropts['tm_competition_seasons'] as $season) {
        $saved_autofetcheropts['tm_competition_season'] = $season;
        try {
          $leaguetable[$season] = tm_autofetch_fetch_leaguetable($saved_autofetcher, $saved_autofetcheropts );
          $pluginResponse->status = 'OK';
        } catch (Exception $e) {
          $response->status = 'ERROR';
          $pluginResponse->status = 'ERROR';
          $pluginResponse->message = $e->getMessage() ;
        }
        $response->seasons[$season] = $pluginResponse;
      }
      tm_competition_update_leaguetable( $term_id , $leaguetable );
    } else {
      $response->status = "NOTREGISTERED";
      $response->message = "Plugin " . $saved_autofetcher . ' not active';
    }
    return $response;
  }
endif;
?>
