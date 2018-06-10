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

if ( ! function_exists( 'tm_get_competitons' ) ):
  function tm_get_competitons() {
    return get_terms( 'tm_competition' );
  }
endif;

if ( ! function_exists( 'tm_get_competiton_byname' ) ):
  function tm_get_competiton_byname($competitionname) {
    return get_term( Array ( 'name' => $competitionname ), 'tm_competition' );
  }
endif;

if ( ! function_exists( 'tm_get_competiton_byid' ) ):
  function tm_get_competiton_byid($competitionid) {
    return get_term( $competitionid , 'tm_competition' );
  }
endif;

if ( ! function_exists( 'tm_get_competition_leaguetable_data' ) ):
  function tm_get_competition_leaguetable_data($term_id) {
    return get_term_meta( $term_id, 'tm_competition_leaguetable' , true );
  }
endif;

if ( ! function_exists( 'tm_get_competition_leaguetable' ) ):
  function tm_get_competition_leaguetable($term_id, $season) {
    $data = tm_get_competition_leaguetable_data($term_id);
    return $data[$season];
  }
endif;

if ( ! function_exists( 'tm_get_competition_autofetcher' ) ):
  function tm_get_competition_autofetcher($term_id) {
    return get_term_meta( $term_id, 'tm_competition_autofetcher' , true );
  }
endif;

if ( ! function_exists( 'tm_get_competition_autofetcher_options' ) ):
  function tm_get_competition_autofetcher_options($term_id) {
    $$autofetcheropts = get_term_meta( $term_id, 'tm_competition_autofetcher_options' , true );
    if ( $$autofetcheropts == '' ) {
      $$autofetcheropts = Array();
    }
    return $$autofetcheropts;
  }
endif;

if ( ! function_exists( 'tm_update_competition_leaguetable_data' ) ):
  function tm_update_competition_leaguetable_data($term_id, $data) {
    return update_term_meta( $term_id, 'tm_competition_leaguetable' , $data );
  }
endif;

if ( ! function_exists( 'tm_update_competition_autofetcher' ) ):
  function tm_update_competition_autofetcher($term_id, $data) {
    return update_term_meta( $term_id, 'tm_competition_autofetcher' , $data );
  }
endif;

if ( ! function_exists( 'tm_update_competition_autofetcher_options' ) ):
  function tm_update_competition_autofetcher_options($term_id, $data) {
    return update_term_meta( $term_id, 'tm_competition_autofetcher_options' , $data );
  }
endif;

?>
