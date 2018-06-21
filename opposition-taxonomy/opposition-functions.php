<?php
if ( ! function_exists( 'tm_opposition_insert_term' ) ):
  function tm_opposition_insert_term($term_slug) {
    $resp = wp_insert_term( $term_slug, 'tm_opposition', $args = array() );
    return get_term( $resp->term_id , 'tm_opposition' );
  }
endif;

if ( ! function_exists( 'tm_opposition_get_byid' ) ):
  function tm_opposition_get_byid($oppositionid) {
    return get_term( $oppositionid , 'tm_opposition' );
  }
endif;

if ( ! function_exists( 'tm_opposition_get_byslug' ) ):
  function tm_opposition_get_byslug($term_slug) {
    return get_term_by('slug' , $term_slug, 'tm_opposition' );
  }
endif;

if ( ! function_exists( 'tm_opposition_getfrom_object' ) ):
  function tm_opposition_getfrom_object( $object_id = 0 ) {
    if ( $object_id == 0 ) {
      $object_id = get_the_id();
    }
    $terms = wp_get_object_terms( $object_id, 'tm_opposition');
    if ( sizeof ($terms ) > 0 ) {
      return $terms[0];
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_opposition_updateon_object' ) ):
  function tm_opposition_updateon_object( $term_id , $object_id = 0 ) {
    if ( $object_id == 0 ) {
      $object_id = get_the_id();
    }
    $term = tm_opposition_get_byid( $term_id );
    wp_set_object_terms( $object_id, $term->slug , 'tm_opposition' , false);
  }
endif;

// URL ============================================================
if ( ! function_exists( 'tm_opposition_get_url' ) ):
  function tm_opposition_get_url($term_id) {
    return get_term_meta( $term_id, 'tm_opposition_url' , true );
  }
endif;

if ( ! function_exists( 'tm_opposition_update_url' ) ):
  function tm_opposition_update_url($term_id, $data) {
    return update_term_meta( $term_id, 'tm_opposition_url' , $data );
  }
endif;

// ICON ============================================================
if ( ! function_exists( 'tm_opposition_get_logo' ) ):
  function tm_opposition_get_logo($term_id) {
    return get_term_meta( $term_id, 'tm_opposition_logo' , true );
  }
endif;

if ( ! function_exists( 'tm_opposition_update_logo' ) ):
  function tm_opposition_update_logo($term_id, $data) {
    return update_term_meta( $term_id, 'tm_opposition_logo' , $data );
  }
endif;



?>
