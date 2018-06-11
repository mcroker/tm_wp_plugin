<?php

if ( ! function_exists( 'tm_get_team_section' ) ):
  function tm_get_team_section( $team_post_id = 0 ) {
    if ( $term_post_id == 0 ) {
      $term_post_id = get_the_id();
    }
    $fixture_terms = wp_get_post_terms( $team_post_id, 'tm_section');
    if ( sizeof ($fixture_terms ) > 0 ) {
      return esc_html(htmlspecialchars_decode($fixture_terms[0]->name));
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_get_team_competition' ) ):
  function tm_get_team_competition( $team_post_id = 0 ) {
    if ( $team_post_id == 0 ) {
      $team_post_id = get_the_id();
    }
    $terms = wp_get_post_terms( $team_post_id, 'tm_competition');
    if ( sizeof ($terms ) > 0 ) {
      return $terms[0];
    } else {
      return '';
    }
  }
endif;

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
