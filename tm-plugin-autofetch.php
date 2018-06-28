<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Autofetchers ===================================================
$tm_autofetch_registeredplugins = Array (
  'none' => 'No Automatic Data Update'
);

if ( ! function_exists( 'tm_autofetch_init' ) ):
  function tm_autofetch_init() {
    do_action( 'tm_autofetch_register_plugins' );
  }
  add_action('init', 'tm_autofetch_init');
endif;

if ( ! function_exists( 'tm_autofetch_register_plugin' ) ):
  function tm_autofetch_register_plugin( $autofetchid , $autofetchdescription = '' ) {
    global $tm_autofetch_registeredplugins;
    if ( $autofetchdescription == '' ) {
      $autofetchdescription = $autofetchid;
    }
    $tm_autofetch_registeredplugins[$autofetchid] = $autofetchdescription;
  }
endif;

if ( ! function_exists( 'tm_autofetch_get_plugins' ) ):
  function tm_autofetch_get_plugins() {
    global $tm_autofetch_registeredplugins;
    return $tm_autofetch_registeredplugins;
  }
endif;

if ( ! function_exists( 'tm_autofetch_fetch_results' ) ):
  function tm_autofetch_isvalidplugin($plugin_id) {
    global $tm_autofetch_registeredplugins;
    if ( array_key_exists ($plugin_id, $tm_autofetch_registeredplugins ) && $plugin_id != 'none' ) {
      return true;
    } else {
      return false;
    }
  }
endif;
/* == UPDATE ALL Results ============================================================ */
if ( ! function_exists('tm_autofetch_update_all_results') ):
  function tm_autofetch_update_all_results() {
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
        )
      ),
    ));
    foreach($teamposts as $teampost) {
      tm_autofetch_update_team_results($teampost->ID);
    }
  }
endif;

/* == UPDATE Results ============================================================ */
if ( ! function_exists('tm_autofetch_update_team_results') ):
  function tm_autofetch_update_team_results($team_id) {
    $team = new TMTeam($team_id);
    foreach($team->competitions as $competition) {
      $autofetcher = $competition->autofetcher;
      if ( tm_autofetch_isvalidplugin($autofetcher) ) {
        $autofetcheropts = $competition->autofetcheropts;
        $autofetcheropts['tm_team_leagueteam'] = $team->title;
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
            $fixture = TMFixture::createPost($newtitle);
            $fixture->useautofetch = true;
            $fixture->createdbyautofetch = true;
            $fixtures[] = $fixture->post;
          }

          // fixture posts updating post post-netadata and post-terms
          foreach ($fixtures as $fixturepost) {
            $fixture = new TMFixture($fixturepost);
            $fixture->fixturedate = $result->fixturedate->getTimestamp();
            $fixture->team_id = $team_id;
            $fixture->homeaway = $result->homeaway;
            $fixture->author = $fixture->team->author;
            if ( $result->scoreagainst != '' ) {
              $fixture->scoreagainst = $result->scoreagainst;
            }
            if ( $result->scorefor != '' ) {
              $fixture->scorefor = $result->scorefor;
            }
            $fixture->attachTermBySlug('TMSeason', $result->season);
            $fixture->attachTermBySlug('TMOpposition', $result->opposition);
            $fixture->attachTerm($competition);
          }
        }
      }
    }
  }
endif;

/* == UPDATE ALL Competitions ============================================================ */
if ( ! function_exists( 'tm_autofetch_update_all_competitions' ) ):
  function tm_autofetch_update_all_competitions( $competitions  = Array() ) {
    if ( sizeof($competitions) == 0 ) {
      $competitions = TMCompetition::getAll();
    }
    foreach($competitions as $competition) {
      $competition->autoFetch();
    }
  }
endif;

// Invoke plugin functions interface ========================================
if ( ! function_exists( 'tm_autofetch_competition_saveoptions' ) ):
  function tm_autofetch_competition_saveoptions($plugin_id, $postdata) {
    if ( tm_autofetch_isvalidplugin($plugin_id) && function_exists($plugin_id . '_competition_saveoptions') ) {
      return call_user_func($plugin_id . '_competition_saveoptions', $postdata);
    } else {
      return Array();
    }
  }
endif;

if ( ! function_exists( 'tm_autofetch_fetch_seasons' ) ):
  function tm_autofetch_fetch_seasons($plugin_id, $autofetcheropts) {
    if ( tm_autofetch_isvalidplugin($plugin_id) && function_exists($plugin_id . '_fetch_seasons') ) {
      return call_user_func($plugin_id . '_fetch_seasons', $autofetcheropts);
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_autofetch_fetch_leaguetable' ) ):
  function tm_autofetch_fetch_leaguetable($plugin_id, $autofetcheropts) {
    if ( tm_autofetch_isvalidplugin($plugin_id) && function_exists($plugin_id . '_fetch_leaguetable') ) {
      return call_user_func($plugin_id . '_fetch_leaguetable', $autofetcheropts);
    } else {
      return Array();
    }
  }
endif;

if ( ! function_exists( 'tm_autofetch_fetch_results' ) ):
  function tm_autofetch_fetch_results($plugin_id, $autofetcheropts) {
    if ( tm_autofetch_isvalidplugin($plugin_id) && function_exists($plugin_id . '_fetch_results') ) {
      return call_user_func($plugin_id . '_fetch_results', $autofetcheropts);
    } else {
      return '';
    }
  }
endif;

?>
