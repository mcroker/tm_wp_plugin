<?php

function tm_create_shortcode_results() {
    add_shortcode('tm-results', 'tm_results_shortcode');
}

function tm_results_shortcode($atts = [], $content = null, $tag = '')
{
  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);

  // override default attributes with user attributes
  $parsed_atts = shortcode_atts([
    'competition' => '',
    'group' => '',
    'team' => '',
    'season' => '',
    'maxrows' => '',
  ], $atts, $tag);

  ob_start();
  $competition = $parsed_atts['sourceurl'];
  $compgroup = $parsed_atts['group'];
  $team = $parsed_atts['team'];
  $season = $parsed_atts['season'];
  $maxrows = $parsed_atts['maxrows'];
  include ('results-content.php');
  $o = ob_get_clean();
  // return output
  return $o;
}

?>
