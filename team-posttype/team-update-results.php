<?php
/* == UPDATE ALL Results ============================================================ */
if ( ! function_exists('tm_team_update_all_results') ):
  function tm_team_update_all_results() {
    $teamposts = get_posts(array (
      'numberposts'	=> -1,
      'post_type'		=> 'tm_team',
      'post_status' => 'publish',
      'title'       => $result->opposition,
      'tax_query' => array(
        array(
          'taxonomy' => 'tm_competition',
          'operator' => 'EXISTS', // or 'EXISTS'
        )),
      ));
      foreach($teamposts as $teampost) {
        tm_competition_exec_update_team_results($teampost->ID);
      }
    }
  endif;
  /*
  'meta_query'	=> array (
  array(
  'key'	 	  => 'tm_team_rfucompetition',
  'compare' => 'EXISTS'
  )
  )
  */

  /* == UPDATE Results ============================================================ */
  if ( ! function_exists('tm_team_update_team_results') ):
    function tm_team_update_team_results($team_id) {
      $competition = tm_team_get_competition($team_id);
      $autofetcher = tm_competition_get_autofetcher($competition->term_id);
      if ( tm_autofetch_isvalidplugin($autofetcher) ) {
        $autofetcheropts = tm_competition_get_autofetcher_options($competition->term_id);
        $autofetcheropts['tm_team_leagueteam'] = tm_team_get_leagueteam($team_id);
        // if ( array_key_exists( 'ALL', $seasons ) ) {
        //  $seasons = tm_autofetch_fetch_seasons($autofetcher, $autofetcheropts);
        // }
        $fixturedata = array();
        foreach ($autofetcheropts['tm_competition_seasons'] as $season) {
          $autofetcheropts['tm_competition_season'] = $season;
          $fixturedata = array_merge ( $fixturedata , tm_autofetch_fetch_results($autofetcher, $autofetcheropts) );
        }
        // Match each post against opposition, team, and fixture date
        // With each matched fixture updated based on fetched results
        foreach($fixturedata as $result) {
          $fixtures = get_posts(array(
            'numberposts'	=> -1,
            'post_type'		=> 'tm_fixture',
            'post_status' => 'publish',
            'meta_query'	=> array(
              'relation'	=> 'AND',
              array(
                'key'	 	  => 'tm_fixture_team',
                'value'	  => $team_id,
                'compare' => '='
              ) ,
              array(
                'key'	  	=> 'tm_fixture_date',
                'value'	  => $result->fixturedate->format('Y-m-d'),
                'compare' => '='
              ),
            ),
            'tax_query' => array(
              array(
                'taxonomy' => 'tm_opposition',
                'field'    => 'slug',
                'terms'    => $result->opposition
              )
            ),
          ));

          //   echo $team_id, $result->opposition , $result->fixturedate->format('Y-m-d') . sizeof( $fixtures ); wp_die();

          // Create fixture if no existing fixture matches opposition, team and date
          // Add this to fixtures (as if found origionally) - to include in update Loop.
          // i.e. we do create and then update (to avoid code duplciation)
          if ( sizeof( $fixtures ) == 0 ) {
            $newfixture = wp_insert_post ( array(
              'post_title' => $result->opposition,
              'post_status' => 'publish',
              'post_type' => 'tm_fixture'
            ) );
            $fixtures[] = get_post ($newfixture);
          }

          // fixture posts updating post post-netadata and post-terms
          foreach ($fixtures as $fixture) {
            /* wp_insert_post (  array(
              'ID' => $fixture->ID,
              'post_title' => $result->opposition,
              'post_status' => 'publish',
              'post_type' => 'tm_fixture'
            ) ); */
            // TODO : Need to capture home vs away
            tm_fixture_update_date( $result->fixturedate->getTimestamp(), $fixture->ID );
            tm_fixture_update_team( $team_id , $fixture->ID );
            if ( $result->scoreagainst != '' ) {
              tm_fixture_update_scoreagainst($result->scoreagainst,  $fixture->ID);
            }
            if ( $result->scorefor != '' ) {
              tm_fixture_update_scorefor($result->scorefor,  $fixture->ID);
            }
            tm_fixture_update_season_withslug($result->season, $fixture->ID);
            tm_fixture_update_opposition_withslug($result->opposition, $fixture->ID);
            tm_fixture_update_competition($competition->term_id, $fixture->ID);
          }
        }
      }
    }
  endif;
  ?>
