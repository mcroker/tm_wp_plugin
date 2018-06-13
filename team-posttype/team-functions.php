<?php

if ( ! function_exists( 'tm_team_get_competition' ) ):
  function tm_team_get_competition( $team_post_id = 0 ) {
    return tm_competition_getfrom_object($team_post_id);
  }
endif;

if ( ! function_exists( 'tm_team_update_competition' ) ):
  function tm_team_update_competition( $competition_slug , $team_post_id = 0 ) {
    return tm_competition_updateon_object($competition_slug , $team_post_id );
  }
endif;

// Team Section =========================================
if ( ! function_exists( 'tm_team_get_section' ) ):
  function tm_team_get_section( $team_post_id = 0 ) {
    return tm_section_getfrom_object($team_post_id);
  }
endif;

if ( ! function_exists( 'tm_team_update_section' ) ):
  function tm_team_update_section( $section_id , $team_post_id = 0 ) {
    return tm_section_updateon_object($section_id , $team_post_id);
  }
endif;

// Team Fixtures =========================================
if ( ! function_exists( 'tm_team_get_fixtures' ) ):
  function tm_team_get_fixtures( $team_post_id = 0 ) {
    if ( $team_post_id == 0 || $team_post_id == '') {
      $team_post_id = get_the_id();
    }
    $fixtures = get_posts(array(
      'numberposts'	=> -1,
      'post_type'		=> 'tm_fixture',
      'post_status' => 'publish',
      'meta_key'	  => 'tm_fixture_team',
      'meta_value'	=> $team_post_id
    ));
    return $fixtures;
  }
endif;

if ( ! function_exists( 'tm_team_get_fixtures_objs' ) ):
  function tm_team_get_fixtures_objs( $team_post_id = 0 ) {
    $fixtures = tm_team_get_fixtures( $team_post_id );
    $fixtureobjs = Array();
    foreach( $fixtures as $fixture) {
      $fixtureobjs[] = tm_fixture_getobj($fixture->ID);
    }
    return $fixtureobjs;
  }
endif;

// Team Leagueteam =========================================
if ( ! function_exists( 'tm_team_get_leagueteam' ) ):
  function tm_team_get_leagueteam( $team_post_id = 0 ) {
    if ( $team_post_id == 0 ) {
      $team_post_id = get_the_id();
    }
    $teams = get_post_meta( $team_post_id, 'tm_team_leagueteam' , true );
    return $teams;
  }
endif;

if ( ! function_exists( 'tm_team_update_leagueteam' ) ):
  function tm_team_update_leagueteam($data, $team_post_id = 0 ) {
    if ( $team_post_id == 0 ) {
      $team_post_id = get_the_id();
    }
    return update_post_meta( $team_post_id, 'tm_team_leagueteam' , $data );
  }
endif;
?>
