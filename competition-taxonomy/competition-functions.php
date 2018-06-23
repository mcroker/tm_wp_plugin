<?php
if ( ! class_exists('TM_LeagueTableEntry')):
  class TM_LeagueTableEntry {
    public $position;
    public $team;
    public $played;
    public $wins;
    public $draws;
    public $lost;
    public $pointsfor;
    public $pointsagainst;
    public $pointsdiff;
    public $trybonus;
    public $losingbonus;
    public $points;
  };
endif;

if ( ! function_exists( 'tm_competition_getall' ) ):
  function tm_competition_getall() {
    return get_terms([
      'taxonomy' => 'tm_competition',
      'hide_empty' => false
    ]);
  }
endif;

if ( ! function_exists( 'tm_competition_get_byname' ) ):
  function tm_competition_get_byname($competitionname) {
    return get_term( Array ( 'name' => $competitionname ), 'tm_competition' );
  }
endif;

if ( ! function_exists( 'tm_competition_get_byid' ) ):
  function tm_competition_get_byid($competitionid) {
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
    $teams = get_term_meta( $term_id, 'tm_competition_teams' , true );
    if ( ! is_array ( $teams ) ) {
      return Array();
    } else {
      return $teams;
    }
  }
endif;

if ( ! function_exists( 'tm_competition_update_teams' ) ):
  function tm_competition_update_teams($term_id, $data) {
    return update_term_meta( $term_id, 'tm_competition_teams' , $data );
  }
endif;

if ( ! function_exists( 'tm_competition_get_sortkey' ) ):
  function tm_competition_get_sortkey($term_id) {
    $autofetcheropts = tm_competition_get_autofetcher_options($term_id);
    return $autofetcheropts['tm_competition_sortkey'];
  }
endif;

if ( ! function_exists( 'tm_competition_get_autofetcher' ) ):
  function tm_competition_get_autofetcher($term_id) {
    return get_term_meta( $term_id, 'tm_competition_autofetcher' , true );
  }
endif;

// Autofetcher options ==================================================
if ( ! function_exists( 'tm_competition_get_autofetcher_options' ) ):
  function tm_competition_get_autofetcher_options($term_id) {
    $autofetcheropts = get_term_meta( $term_id, 'tm_competition_autofetcher_options' , true );
    if ( $autofetcheropts == '' ) {
      $autofetcheropts = Array();
    }
    $term = tm_competition_get_byid($term_id);
    $autofetcheropts['tm_competition_name'] = $term->name;
    $autofetcheropts['tm_competition_id'] = $term->term_id;
    $autofetcheropts['tm_competition_slug'] = $term->slug;
    $autofetcheropts['tm_competition_description'] = $term->description;
    return $autofetcheropts;
  }
endif;

if ( ! function_exists( 'tm_competition_update_autofetcher_options' ) ):
  function tm_competition_update_autofetcher_options($term_id, $data) {
    return update_term_meta( $term_id, 'tm_competition_autofetcher_options' , $data );
  }
endif;

// ==================================================
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


if ( ! function_exists( 'tm_competition_getfrom_object' ) ):
  function tm_competition_getfrom_object( $object_id = 0 ) {
    if ( $object_id == 0 ) {
      $object_id = get_the_id();
    }
    $terms = wp_get_object_terms( $object_id, 'tm_competition');
    if ( is_array ($terms ) ) {
      return $terms;
    } else {
      return Array();
    }
  }
endif;

if ( ! function_exists( 'tm_competition_updateon_object' ) ):
  function tm_competition_updateon_object( $term_id , $object_id = 0 ) {
    if ( $object_id == 0 ) {
      $object_id = get_the_id();
    }
    $term = tm_competition_get_byid( $term_id );
    if ($term) {
      wp_set_object_terms( $object_id, $term->slug, 'tm_competition' , false);
    }
  }
endif;

?>
