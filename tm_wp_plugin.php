<?php
/*
Plugin Name: TM Plugin
Description: TM WordPress framwork developer by Martin Croker
*/

/* Start Adding Functions Below this Line */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once 'TMBasePlugin.php';
require_once 'class-wcbasegeneric.php';
require_once 'class-wcbasepost.php';
require_once 'class-wcbasetax.php';
require_once 'TMBaseWidget.php';

if ( ! class_exists( 'TMPlugin' ) ) :
	class TMPlugin extends TMBasePlugin {


		public static function init() {
			parent::init();
			error_reporting( -1 );            // reports all errors
			ini_set( 'display_errors', '1' ); // shows all errors
			ini_set( 'log_errors', 1 );
			ini_set( 'error_log', 'php-error.log' );
			do_action( 'tm_plugin_load_children' );
		}

	}
	TMPlugin::init();
endif;
