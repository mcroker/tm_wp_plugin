<?php

if ( ! function_exists( 'tm_get_team_competition' ) ):
  function tm_get_team_competition( $team_post_id = 0 ) {
    return tm_get_object_competition($team_post_id);
  }
endif;

if ( ! function_exists( 'tm_update_team_competition' ) ):
  function tm_update_team_competition( $competition_slug , $team_post_id = 0 ) {
    return tm_update_object_competition($competition_slug , $team_post_id );
  }
endif;

// Team Section =========================================
if ( ! function_exists( 'tm_get_team_section' ) ):
  function tm_get_team_section( $team_post_id = 0 ) {
    return tm_get_object_section($team_post_id);
  }
endif;

if ( ! function_exists( 'tm_update_team_section' ) ):
  function tm_update_team_section( $section_slug , $team_post_id = 0 ) {
    return tm_update_object_section($section_slug , $team_post_id);
  }
endif;

// Team Fixtures =========================================
if ( ! function_exists( 'tm_get_team_fixtures' ) ):
  function tm_get_team_fixtures( $team_post_id = 0 ) {
    if ( $team_post_id == 0 ) {
      $team_post_id = get_the_id();
    }
    $fixtures = get_posts(array(
      'numberposts'	=> -1,
      'post_type'		=> 'tm_fixture',
      'post_status' => 'publish',
      'meta_key'	  => 'tm_fixture_team',
      'meta_value'	=> get_the_id()
    ));
    return $fixtures;
  }
endif;

if ( ! function_exists( 'tm_get_team_fixtures_objs' ) ):
  function tm_get_team_fixtures_objs( $team_post_id = 0 ) {
    $fixtures = tm_get_team_fixtures( $team_post_id );
    $fixtureobjs = Array();
    foreach( $fixtures as $fixture) {
      $fixtureobjs[] = tm_get_fixture_obj($fixture->ID);
    }
    return $fixtureobjs;
  }
endif;

?>
