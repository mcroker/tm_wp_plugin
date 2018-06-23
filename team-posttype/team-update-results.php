<?php
/* == UPDATE ALL Results ============================================================ */
if ( ! function_exists('tm_team_update_all_results') ):
  function tm_team_update_all_results() {
    $teamposts = get_posts(array (
      'numberposts' 	=> -1,
      'post_type'		 => 'tm_team',
      'meta_query'	 => array(
        'relation'	=> 'AND',
        array(
          'key'	 	   => 'tm_team_useautofetch',
          'value'	   => 1,
          'compare'  => '='
        ) ,
        array(
          'key'	 	   => 'tm_team_leagueteam',
          'value'	   => '',
          'compare'  => '!='
        ) ,
      ),
      'tax_query'    => array(
        array(
          'taxonomy' => 'tm_competition',
          'operator' => 'EXISTS', // or 'EXISTS'
        )),
      ));
      foreach($teamposts as $teampost) {
        tm_team_update_team_results($teampost->ID);
      }
    }
  endif;

  /* == UPDATE Results ============================================================ */
  if ( ! function_exists('tm_team_update_team_results') ):
    function tm_team_update_team_results($team_id) {
      $competitions = tm_team_get_competitions($team_id);
      foreach($competitions as $competition) {
        $autofetcher = tm_competition_get_autofetcher($competition->term_id);
        if ( tm_autofetch_isvalidplugin($autofetcher) ) {
          $autofetcheropts = tm_competition_get_autofetcher_options($competition->term_id);
          $autofetcheropts['tm_team_leagueteam'] = tm_team_get_leagueteam($team_id);
          $fixturedata = tm_autofetch_fetch_results($autofetcher, $autofetcheropts);
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
                array(
                  'key'	  	=> 'tm_fixture_createdbyautofetch',
                  'value'	  => 1,
                  'compare' => '='
                )
              ),
              'tax_query' => array(
                'relation'	=> 'AND',
                array(
                  'taxonomy' => 'tm_opposition',
                  'field'    => 'slug',
                  'terms'    => $result->opposition
                ),
                array(
                  'taxonomy' => 'tm_competition',
                  'field'    => 'slug',
                  'terms'    => $competition->slug
                )
              ),
            ));

            //   echo $team_id, $result->opposition , $result->fixturedate->format('Y-m-d') . sizeof( $fixtures ); wp_die();

            // Create fixture if no existing fixture matches opposition, team and date
            // Add this to fixtures (as if found origionally) - to include in update Loop.
            // i.e. we do create and then update (to avoid code duplciation)
            if ( sizeof( $fixtures ) == 0 ) {
              switch($result->homeaway) {
                case 'H': $newtitle = $result->opposition . ' (Home)'; break;
                case 'A': $newtitle = $result->opposition . ' (Away)'; break;
                default: $newtitle = $result->opposition;
              }
              $newfixture = wp_insert_post ( array(
                'post_title' => $newtitle,
                'post_status' => 'publish',
                'post_type' => 'tm_fixture'
              ) );
              tm_fixture_update_createdbyautofetch( true, $newfixture );
              tm_fixture_update_useautofetch(true, $newfixture );
              $fixtures[] = get_post ($newfixture);
            }

            // fixture posts updating post post-netadata and post-terms
            foreach ($fixtures as $fixture) {
              tm_fixture_update_date( $result->fixturedate->getTimestamp(), $fixture->ID );
              tm_fixture_update_team( $team_id , $fixture->ID );
              tm_fixture_update_homeaway( $result->homeaway , $fixture->ID );
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
    }
  endif;
  ?>
