<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

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
      $team = new TMTeam($team_id);
      foreach($team->competitions as $competition) {
        $autofetcher = tm_competition_get_autofetcher($competition->term_id);
        if ( tm_autofetch_isvalidplugin($autofetcher) ) {
          $autofetcheropts = tm_competition_get_autofetcher_options($competition->term_id);
          $autofetcheropts['tm_team_leagueteam'] = $team->ID;
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

            // Create fixture if no existing fixture matches opposition, team and date
            // Add this to fixtures (as if found origionally) - to include in update Loop.
            // i.e. we do create and then update (to avoid code duplciation)
            if ( sizeof( $fixtures ) == 0 ) {
              switch($result->homeaway) {
                case 'H': $newtitle = $result->opposition . ' (Home)'; break;
                case 'A': $newtitle = $result->opposition . ' (Away)'; break;
                default: $newtitle = $result->opposition;
              }
              $fixtureobj = TMFixture::createPost($newtitle);
              $fixtureobj->useautofetch = true;
              $fixtureobj->createdbyautofetch = true;
              $fixtures[] = $fixtureobj->wp_post;
            }

            // fixture posts updating post post-netadata and post-terms
            foreach ($fixtures as $fixturepost) {
              $fixture = new TMFixture($fixturepost);
              $fixture->fixturedate = $result->fixturedate->getTimestamp();
              $fixture->team_id = $team_id;
              $fixture->homeaway = $result->homeaway;
              if ( $result->scoreagainst != '' ) {
                $fixture->scoreagainst = $result->scoreagainst;
              }
              if ( $result->scorefor != '' ) {
                $fixture->scorefor = $result->scorefor;
              }
              $fixture->season = $result->season;
              $fixture->updateOppositionWithSlug($result->opposition, $fixture->ID);
              $fixture->competition = $competition->term_id;
            }
          }
        }
      }
    }
  endif;
  ?>
