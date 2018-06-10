<?php

require_once('upsert_rfu_results.php');
require_once('upsert_rfu_leaguetables.php');


function tm_utils_shortcode($atts = [], $content = null, $tag = '')
{
  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);

  // override default attributes with user attributes
  $parsed_atts = shortcode_atts([
    'util' => '',
    'competition' => '',
    'seasons' => ''
  ], $atts, $tag);

  ob_start();

  if ( $parsed_atts['util'] == 'upsert_rfu_results' ) {
		tm_upsert_rfu_results($parsed_atts['seasons']);
  } else if ( $parsed_atts['util'] == 'upsert_rfu_leaguetables' ) {
    tm_upsert_rfu_leaguetables($parsed_atts['competition'], $parsed_atts['seasons']);
  }

  $o = ob_get_clean();
  return $o;

}

?>
