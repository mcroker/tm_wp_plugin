<?php
if ( ! function_exists( 'tm_season_getall' ) ):
  function tm_season_getall() {
    return get_terms([
      'taxonomy' => 'tm_season',
      'hide_empty' => false
    ]);
  }
endif;

if ( ! function_exists( 'tm_season_insert_term' ) ):
  function tm_season_insert_term($term_slug) {
    $resp = wp_insert_term( $term_slug, 'tm_season', $args = array() );
    return get_term( $resp->term_id , 'tm_season' );
  }
endif;

if ( ! function_exists( 'tm_season_get_byid' ) ):
  function tm_season_get_byid($seasonid) {
    return get_term( $seasonid , 'tm_season' );
  }
endif;

if ( ! function_exists( 'tm_season_get_byslug' ) ):
  function tm_season_get_byslug($term_slug) {
    return get_term_by('slug' , $term_slug, 'tm_season' );
  }
endif;

if ( ! function_exists( 'tm_season_getfrom_object' ) ):
  function tm_season_getfrom_object( $object_id = 0 ) {
    if ( $object_id == 0 ) {
      $object_id = get_the_id();
    }
    $terms = wp_get_object_terms( $object_id, 'tm_season');
    if ( sizeof ($terms ) > 0 ) {
      return $terms[0];
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_season_updateon_object' ) ):
  function tm_season_updateon_object( $term_id , $object_id = 0 ) {
    if ( $object_id == 0 ) {
      $object_id = get_the_id();
    }
    $term = tm_season_get_byid( $term_id );
    wp_set_object_terms( $object_id, $term->slug , 'tm_season' , false);
  }
endif;

?>
