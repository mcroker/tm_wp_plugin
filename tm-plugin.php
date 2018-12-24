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
    wp_enqueue_style( 'style', $plugin_url . '/assets/css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'tm_load_plugin_css' );

require_once('TMBaseGeneric.php');
require_once('TMBasePost.php');
require_once('TMBaseTax.php');
require_once('TMBaseWidget.php');

do_action('tm_plugin_load_children');

/* Stop Adding Functions Below this Line */
?>
