<?php
/* == UPDATE LEAGUETABLE ============================================================ */
if ( ! function_exists( 'tm_competition_update_leguetable' ) ):
  function tm_competition_update_leguetable( $term_id ){

    $autofetcher = tm_competition_get_autofetcher ( $term_id );

    // Only do something if the autofetcher is still registered
    if ( tm_autofetch_isvalidplugin($autofetcher) ) {
      $autofetcheropts = tm_competition_get_autofetcher_options ( $term_id );

      // Start with existing saved value
      $leaguetable = tm_competition_get_leaguetable( $term_id );

      // Update for each in scope season (leaving other values unchanged)
      foreach ($autofetcheropts['tm_competition_seasons'] as $season) {
        $autofetcheropts['tm_competition_season'] = $season;
        $leaguetable[$season] = tm_autofetch_fetch_leaguetable($autofetcher, $autofetcheropts );
      }

      // Save update as term meta
      tm_competition_update_leaguetable( $term_id , $leaguetable );
    }
  }
endif;

/* == UPDATE ALL Competitions ============================================================ */
if ( ! function_exists( 'tm_competition_update_all_competitions' ) ):
  function tm_competition_update_all_competitions( $competitions  = Array() ) {
    if ( sizeof($competitions) == 0 ) {
      $competitions = tm_competition_getall();
    }
    foreach($competitions as $competition) {
      tm_competition_update_leguetable( $competition->term_id );

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
  }
endif;
?>
