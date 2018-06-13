<?php
if ( ! function_exists( 'tm_section_getall' ) ):
  function tm_section_getall() {
    return get_terms([
      'taxonomy' => 'tm_section',
      'hide_empty' => false
    ]);
  }
endif;

if ( ! function_exists( 'tm_section_get_byid' ) ):
  function tm_section_get_byid($sectionid) {
    return get_term( $sectionid , 'tm_section' );
  }
endif;

if ( ! function_exists( 'tm_section_getall' ) ):
  function tm_section_getall() {
    return get_terms([
      'taxonomy' => 'tm_section',
      'hide_empty' => false
    ]);
  }
endif;

if ( ! function_exists( 'tm_section_getfrom_object' ) ):
  function tm_section_getfrom_object( $object_id = 0 ) {
    if ( $object_id == 0 ) {
      $object_id = get_the_id();
    }
    $terms = wp_get_object_terms( $object_id, 'tm_section');
    if ( sizeof ($terms ) > 0 ) {
      return $terms[0];
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_section_updateon_object' ) ):
  function tm_section_updateon_object( $term_id , $object_id = 0 ) {
    if ( $object_id == 0 ) {
      $object_id = get_the_id();
    }
    $term = tm_section_get_byid( $term_id );
    wp_set_object_terms( $object_id, $term->slug , 'tm_section' , false);
  }
endif;

?>
