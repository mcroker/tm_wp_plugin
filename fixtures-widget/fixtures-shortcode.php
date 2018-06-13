<?php

require_once('fixtures-content.php');

if ( ! function_exists( 'tm_fixtures_shortcode' ) ):
  function tm_fixtures_shortcode($atts = [], $content = null, $tag = '')
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
      'maxfuture' => '',
    ], $atts, $tag);

    ob_start();
    $competition = $parsed_atts['sourceurl'];
    $compgroup = $parsed_atts['group'];
    $team = $parsed_atts['team'];
    $season = $parsed_atts['season'];
    $maxrows = $parsed_atts['maxrows'];
    $maxfuture = $parsed_atts['maxfuture'];
    // TODO : Need to validate and parse arguements
    tm_fixtures_widget_content();
    $o = ob_get_clean();
    // return output
    return $o;
  }
  add_shortcode('tm-fixtures', 'tm_fixtures_shortcode');
endif;
?>
