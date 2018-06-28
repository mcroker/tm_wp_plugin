<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once('leaguetable-content.php');

if ( ! function_exists( 'tm_leaguetable_shortcode' ) ):
  function tm_leaguetable_shortcode($atts = [], $content = null, $tag = '')
  {
    // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    // override default attributes with user attributes
    $parsed_atts = shortcode_atts([
      'competition' => '',
      'team' => '',
      'title' => '',
    ], $atts, $tag);

    ob_start();
    $competition = $parsed_atts['competition'];
    $team = $parsed_atts['team'];
    $displaytitle = $parsed_atts['title'];
    tm_leaguetable_widget_content($displaytitle, $competition, $team, Array() );
    $o = ob_get_clean();
    // return output
    return $o;
  }
  add_shortcode('tm-leaguetable', 'tm_leaguetable_shortcode');
endif;
?>
