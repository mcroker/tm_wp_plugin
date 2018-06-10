<?php
/*
Plugin Name: TM Plugin
Description: Sports club management developed by Martin Croker
*/
/* Start Adding Functions Below this Line */


/* Stop Adding Functions Below this Line */

require_once('inc/simple_html_dom.php');

// CSS
function tm_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'style', $plugin_url . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'tm_load_plugin_css' );

// Sidebars
require_once('templates/fixture-sidebar.php');
require_once('templates/team-sidebar.php');
function tm_create_sidebars() {
  tm_create_sidebars_fixture();
  tm_create_sidebars_team();
}
add_action('init', 'tm_create_sidebars');

// Widgets
require_once('results-widget/results-widget.php');
require_once('leaguetable-widget/leaguetable-widget.php');
function tm_register_widgets() {
    register_widget( 'tm_results');
    register_widget( 'tm_leaguetable' );
}
add_action( 'widgets_init', 'tm_register_widgets' );

// Shortcodes
require_once('results-widget/results-shortcode.php');
require_once('leaguetable-widget/leaguetable-shortcode.php');
require_once('utils/utils-shortcode.php');
function tm_add_shortcodes()
{
  add_shortcode('tm-results', 'tm_results_shortcode');
  add_shortcode('tm-leaguetable', 'tm_leaguetable_shortcode');
  add_shortcode('tm-util', 'tm_utils_shortcode');
}
add_action('init', 'tm_add_shortcodes');

// Post types
require_once('fixture-posttype/fixture-posttype.php');
require_once('team-posttype/team-posttype.php');

// Taxonomy
require_once('taxonomy-oppo.php');
require_once('taxonomy-season.php');
require_once('taxonomy-comp.php');
require_once('taxonomy-section.php');
function tm_register_taxonomies() {
  tm_registertaxonomy_oppo();
  tm_registertaxonomy_season();
  tm_registertaxonomy_competition();
  tm_registertaxonomy_section();
}
add_action('init', 'tm_register_taxonomies');

// Set custom templates for custom page_types
require_once('tm-plugin-template.php');
add_filter( 'template_include', 'tm_plugin_templates' );

// Plugin Options page;
require_once('tm-plugin-options.php');
add_action( 'admin_init', 'tm_register_settings' );
add_action('admin_menu', 'tm_register_options_page');

?>
