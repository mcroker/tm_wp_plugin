<?php
if ( ! function_exists( 'tm_opposition_insert_term' ) ):
  function tm_opposition_insert_term($term_slug) {
    wp_insert_term( $term_slug, 'tm_opposition', $args = array() );
  }
endif;

if ( ! function_exists( 'tm_opposition_get_byslug' ) ):
  function tm_opposition_get_byslug($term_slug) {
    return get_term( Array ( 'slug' => $term_slug ), 'tm_opposition' );
  }
endif;
?>
