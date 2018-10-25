<?php
/*
Plugin Name: TM Plugin
Description: Sports club management developed by Martin Croker
*/

/* Start Adding Functions Below this Line */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

error_reporting(-1); // reports all errors
ini_set("display_errors", "1"); // shows all errors
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

// CSS
function tm_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'style', $plugin_url . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'tm_load_plugin_css' );

require_once('classes/TMCompetition.php');
require_once('classes/TMFixture.php');
require_once('classes/TMAutofetchFixture.php');
require_once('classes/TMOpposition.php');
require_once('classes/TMSeason.php');
require_once('classes/TMSection.php');
require_once('classes/TMTeam.php');
require_once('classes/TMLeagueTableEntry.php');

// Widgets
require_once('fixturelist-widget/fixturelist-widget.php');
require_once('leaguetable-widget/leaguetable-widget.php');

// Shortcodes
// require_once('leaguetable-widget/leaguetable-shortcode.php');

// Post types
require_once('fixture-posttype/fixture-posttype.php');
require_once('team-posttype/team-posttype.php');
require_once('festival-posttype/festival-posttype.php');

// Taxonomy
require_once('opposition-taxonomy/opposition-taxonomy.php');
require_once('season-taxonomy/season-taxonomy.php');
require_once('section-taxonomy/section-taxonomy.php');
require_once('competition-taxonomy/competition-taxonomy.php');

// require_once('tm-plugin-template.php');
require_once('tm-plugin-options.php');
require_once('tm-plugin-autofetch.php');
require_once('tm-plugin-api.php');

do_action('tm_plugin_load_children');

/* Stop Adding Functions Below this Line */
?>
