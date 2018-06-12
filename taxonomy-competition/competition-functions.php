<?php

if ( ! function_exists( 'tm_competition_register_autofetch' ) ):
  function tm_competition_register_autofetch( $autofetchid , $autofetchdescription = '' ) {
    global $tm_competition_autofetchers;
    if ( $autofetchdescription == '' ) {
      $autofetchdescription = $autofetchid;
    }
    $tm_competition_autofetchers[$autofetchid] = $autofetchdescription;
  }
endif;

if ( ! function_exists( 'tm_competitons_getall' ) ):
  function tm_competitons_getall() {
    return get_terms([
      'taxonomy' => 'tm_competition',
      'hide_empty' => false
    ]);
  }
endif;

if ( ! function_exists( 'tm_competiton_get_byname' ) ):
  function tm_competiton_get_byname($competitionname) {
    return get_term( Array ( 'name' => $competitionname ), 'tm_competition' );
  }
endif;

if ( ! function_exists( 'tm_competiton_get_byid' ) ):
  function tm_competiton_get_byid($competitionid) {
    return get_term( $competitionid , 'tm_competition' );
  }
endif;

if ( ! function_exists( 'tm_competition_get_leaguetable' ) ):
  function tm_competition_get_leaguetable($term_id) {
    $data = get_term_meta( $term_id, 'tm_competition_leaguetable' , true );
    if ( is_array($data) ) {
      return $data;
    } else {
      return Array();
    }
  }
endif;

if ( ! function_exists( 'tm_competition_get_teams' ) ):
  function tm_competition_get_teams($term_id) {
    return get_term_meta( $term_id, 'tm_competition_teams' , true );
  }
endif;

if ( ! function_exists( 'tm_competition_update_teams' ) ):
  function tm_competition_update_teams($term_id, $data) {
    return update_term_meta( $term_id, 'tm_competition_teams' , $data );
  }
endif;

if ( ! function_exists( 'tm_competition_get_autofetcher' ) ):
  function tm_competition_get_autofetcher($term_id) {
    return get_term_meta( $term_id, 'tm_competition_autofetcher' , true );
  }
endif;

if ( ! function_exists( 'tm_competition_get_autofetcher_options' ) ):
  function tm_competition_get_autofetcher_options($term_id) {
    $$autofetcheropts = get_term_meta( $term_id, 'tm_competition_autofetcher_options' , true );
    if ( $$autofetcheropts == '' ) {
      $$autofetcheropts = Array();
    }
    return $$autofetcheropts;
  }
endif;

if ( ! function_exists( 'tm_competition_update_leaguetable' ) ):
  function tm_competition_update_leaguetable($term_id, $data) {
    return update_term_meta( $term_id, 'tm_competition_leaguetable' , $data );
  }
endif;

if ( ! function_exists( 'tm_competition_update_autofetcher' ) ):
  function tm_competition_update_autofetcher($term_id, $data) {
    return update_term_meta( $term_id, 'tm_competition_autofetcher' , $data );
  }
endif;

if ( ! function_exists( 'tm_competition_update_autofetcher_options' ) ):
  function tm_competition_update_autofetcher_options($term_id, $data) {
    return update_term_meta( $term_id, 'tm_competition_autofetcher_options' , $data );
  }
endif;

if ( ! function_exists( 'tm_get_object_competition' ) ):
  function tm_get_object_competition( $object_id = 0 ) {
    if ( $object_id == 0 ) {
      $object_id = get_the_id();
    }
    $terms = wp_get_object_terms( $object_id, 'tm_competition');
    if ( sizeof ($terms ) > 0 ) {
      return $terms[0];
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_update_object_competition' ) ):
  function tm_update_object_competition( $competition_slug , $object_id = 0 ) {
    if ( $object_id == 0 ) {
      $object_id = get_the_id();
    }
    wp_set_object_terms( $object_id, $competition_slug, 'tm_competition' , false);
  }
endif;

?>
