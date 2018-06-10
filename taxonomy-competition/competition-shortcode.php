<?php

if ( ! function_exists( 'tm_competition_shortcode_exec_uodate' ) ):
  function tm_competition_shortcode_exec_uodate($atts = [], $content = null, $tag = '')
  {
    // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    // override default attributes with user attributes
    $parsed_atts = shortcode_atts([
    ], $atts, $tag);

    ob_start();
    tm_competition_exec_update_all();
    $o = ob_get_clean();
    return $o;

  }
  add_shortcode('tm-competition-exec-update', 'tm_competition_shortcode_exec_uodate');
endif;

?>
