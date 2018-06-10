<?php

function tm_create_shortcode_leaguetable() {
  add_shortcode('tm-leaguetable', 'tm_leaguetable_shortcode');
}

function tm_leaguetable_shortcode($atts = [], $content = null, $tag = '')
{
  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);

  // override default attributes with user attributes
  $parsed_atts = shortcode_atts([
    'sourceurl' => '',
    'team' => '',
  ], $atts, $tag);

  ob_start();
  $sourceurl = $parsed_atts['sourceurl'];
  $team = $parsed_atts['team'];
  include ('leaguetable-content.php');
  $o = ob_get_clean();
  // return output
  return $o;
}

?>
