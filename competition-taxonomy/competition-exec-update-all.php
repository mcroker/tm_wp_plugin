<?php
if ( ! class_exists('TM_PluginResponse') ):
  class TM_PluginResponse {
    public $status;
    public $plugin;
    public $options;
    function __construct() {
      $this->status = 'OK';
    }
  }
endif;

if ( ! class_exists('TM_Response') ):
  class TM_Response {
    public $status;
    public $competitions;
    function __construct() {
      $this->status = 'OK';
      $this->competitions = Array();
    }
  }
endif;

/* == UPDATE ALL Competitions ============================================================ */
if ( ! function_exists( 'tm_competition_exec_update_all_competitions' ) ):
  function tm_competition_exec_update_all_competitions( $competitions  = Array() ) {
    if ( sizeof($competitions) == 0 ) {
      $competitions = tm_competiton_getall();
    }
    $response = new TM_Response();
    foreach($competitions as $competition) {
      $response->competitions[$competition->slug] = tm_competition_exec_update_leguetable( $competition->term_id );
      if ( $response->competitions[$competition->slug]->status != 'OK' ) {
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
