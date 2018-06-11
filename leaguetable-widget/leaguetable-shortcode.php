<?php

require_once('leaguetable-content.php');

function tm_create_shortcode_leaguetable() {
  add_shortcode('tm-leaguetable', 'tm_leaguetable_shortcode');
}

function tm_leaguetable_shortcode($atts = [], $content = null, $tag = '')
{
  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);

  // override default attributes with user attributes
  $parsed_atts = shortcode_atts([
    'competition' => '',
    'seasons' => '',
    'team' => '',
    'title' => '',
    'maxrows' => ''
  ], $atts, $tag);

  ob_start();
  $competition = $parsed_atts['competition'];
  $seasons = $parsed_atts['seasons'];
  $team = $parsed_atts['team'];
  $displaytitle = $parsed_atts['title'];
  $maxrows = $parsed_atts['maxrows'];
  tm_leaguetable_widget_content($displaytitle, $competition, $seasons, $team, $maxrows);
  $o = ob_get_clean();
  // return output
  return $o;
}

?>
