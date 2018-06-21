<?php
/*
Plugin Name: TM Plugin
Description: Sports club management developed by Martin Croker
*/

/* Start Adding Functions Below this Line */


// CSS
function tm_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'style', $plugin_url . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'tm_load_plugin_css' );

// Sidebars
// require_once('templates/fixture-sidebar.php');
// require_once('templates/team-sidebar.php');

// Widgets
require_once('fixturelist-widget/fixturelist-widget.php');
require_once('leaguetable-widget/leaguetable-widget.php');

// Shortcodes
// require_once('leaguetable-widget/leaguetable-shortcode.php');

// Post types
require_once('fixture-posttype/fixture-posttype.php');
require_once('team-posttype/team-posttype.php');

// Taxonomy
require_once('opposition-taxonomy/opposition-taxonomy.php');
require_once('season-taxonomy/season-taxonomy.php');
require_once('section-taxonomy/section-taxonomy.php');
require_once('competition-taxonomy/competition-taxonomy.php');

// require_once('tm-plugin-template.php');
require_once('tm-plugin-options.php');
require_once('tm-plugin-autofetch.php');
require_once('tm-plugin-api.php');

require_once('logo-integration/logo-integration.php');

/* Stop Adding Functions Below this Line */
?>
