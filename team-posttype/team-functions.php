<?php

if ( ! function_exists( 'tm_team_getall' ) ):
  function tm_team_getall() {
    return get_posts(array (
       'numberposts'	=> -1,
      'post_type' => 'tm_team'
    ));
  }
endif;

if ( ! function_exists( 'tm_team_get_competitions' ) ):
  function tm_team_get_competitions( $team_post_id = 0 ) {
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

// Autofetch fixtures =========================================
if ( ! function_exists( 'tm_team_get_useautofetch' ) ):
  function tm_team_get_useautofetch( $team_post_id = 0 ) {
    if ( $team_post_id == 0 ) {
      $team_post_id = get_the_id();
    }
    $useautofetch = get_post_meta( $team_post_id, 'tm_team_useautofetch' , true );
    return $useautofetch;
  }
endif;

if ( ! function_exists( 'tm_team_update_useautofetch' ) ):
  function tm_team_update_useautofetch($data, $team_post_id = 0 ) {
    if ( $team_post_id == 0 ) {
      $team_post_id = get_the_id();
    }
    return update_post_meta( $team_post_id, 'tm_team_useautofetch' , $data );
  }
endif;

// team logo =========================================
if ( ! function_exists( 'tm_team_get_logo' ) ):
  function tm_team_get_logo( $team_post_id = 0 ) {
    if ( $team_post_id == 0 ) {
      $team_post_id = get_the_id();
    }
    $teamlogoid = get_post_meta( $team_post_id, 'tm_team_logo_id' , true );
    return $teamlogoid;
  }
endif;

if ( ! function_exists( 'tm_team_update_logo' ) ):
  function tm_team_update_logo($data, $team_post_id = 0 ) {
    if ( $team_post_id == 0 ) {
      $team_post_id = get_the_id();
    }
    return update_post_meta( $team_post_id, 'tm_team_logo_id' , $data );
  }
endif;

// Team Players ============================================================
if ( ! function_exists( 'tm_team_get_playerstext' ) ):
  function tm_team_get_playerstext($team_post_id = 0) {
    if ( $team_post_id == 0) {
      $team_post_id = get_the_id();
    }
    return get_post_meta( get_the_ID(), 'tm_team_playerstext', true );
  }
endif;

if ( ! function_exists( 'tm_team_update_playerstext' ) ):
  function tm_team_update_playerstext($data, $team_post_id = 0) {
    if ( $team_post_id == 0) {
      $team_post_id = get_the_id();
    }
    return update_post_meta( $team_post_id, 'tm_team_playerstext', $data);
  }
endif;

// Team Coaches ============================================================
if ( ! function_exists( 'tm_team_get_coachestext' ) ):
  function tm_team_get_coachestext($team_post_id = 0) {
    if ( $team_post_id == 0) {
      $team_post_id = get_the_id();
    }
    return get_post_meta( get_the_ID(), 'tm_team_coachestext', true );
  }
endif;

if ( ! function_exists( 'tm_team_update_coachestext' ) ):
  function tm_team_update_coachestext($data, $team_post_id = 0) {
    if ( $team_post_id == 0) {
      $team_post_id = get_the_id();
    }
    return update_post_meta( $team_post_id, 'tm_team_coachestext', $data );
  }
endif;


?>
