<?php
if ( ! function_exists( 'tm_get_sections' ) ):
  function tm_get_sections() {
    return get_terms([
      'taxonomy' => 'tm_section',
      'hide_empty' => false
    ]);
  }
endif;

if ( ! function_exists( 'tm_get_object_section' ) ):
  function tm_get_object_section( $object_id = 0 ) {
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

if ( ! function_exists( 'tm_update_object_section' ) ):
  function tm_update_object_section( $section_slug , $object_id = 0 ) {
    if ( $object_id == 0 ) {
      $object_id = get_the_id();
    }
    wp_set_object_terms( $object_id, $section_slug, 'tm_section' , false);
  }
endif;

?>
