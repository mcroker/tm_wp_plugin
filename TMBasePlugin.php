<?php
/**
 * TMBasePlugin
 *
 * @category
 * @package  TMWPPlugin
 * @author   Martin Croker <martin@croker.family>
 * @license  Apache2
 * @link
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'TMBasePlugin' ) ) :
	/**
	 * TMBaseTax
	 *
	 * @package TMWPPlugin
	 * @author  Martin Croker <martin@croker.family>
	 * @license Apache2
	 * @link
	 */
	abstract class TMBasePlugin {


		/**
		 * A reference to an instance of this class.
		 */
		protected static $instance;

		/**
		 * Static initialiser - called after each child class defined
		 *
		 * @return void
		 */
		public static function init() {
			$classname = get_called_class();
			add_action( 'plugins_loaded', $classname . '::getInstance' );
			add_action( 'wp_enqueue_scripts', $classname . '::enqueueScripts' );
			if ( is_admin() ) {
				add_action( 'admin_enqueue_scripts', $classname . '::enqueueAdminScripts' );
			}
		}

		/**
		 * Initiate singletom
		 *
		 * @return TMBasePlugin Singleton instance
		 */
		public static function getInstance() {
			$classname = get_called_class();
			if ( null == $classname::$instance ) {
				$classname::$instance = new $classname();
			}
			return $classname::$instance;
		}

		/**
		 * Enques default scripts based on classname from plugin dir
		 *
		 * By default looks for CLASSNAME.js and CLASSNAME.css in
		 * /assets/js and /assets/css respectively.
		 *
		 * @return void
		 */
		public static function enqueueScripts() {
			$classname = get_called_class();
			self::enqueueScriptHelper( $classname . '-script', $classname . '.js' );
			self::enqueueStyleHelper( $classname . '-css', $classname . '.css' );
		}

		/**
		 * Enques default admin scripts based on classname from plugin dir
		 *
		 * By default looks for CLASSNAME-admin.js and CLASSNAME-admin.css in
		 * /assets/js and /assets/css respectively.
		 *
		 * @param string $hook_suffix (Required) php file at end of URL provided
		 *                            by WordPress
		 *
		 * @return void
		 */
		public static function enqueueAdminScripts( $hook_suffix ) {
			$classname = get_called_class();
			self::enqueueScriptHelper( $classname . '-admin-script', $classname . '-admin.js' );
			self::enqueueStyleHelper( $classname . '-admin-css', $classname . '-admin.css' );
		}

		/**
		 * Enques a script locting $file in /assets/js of child object plugin
		 *
		 * @param string $id   (Required) script id used to refer in WordPress
		 * @param string $file (Required) filenamee (without path)
		 * @param string $obj  Object to localise script with
		 *
		 * @return void
		 */
		public static function enqueueScriptHelper( $id, $file, $obj = [] ) {
			// Assumc child class has checked the post_type / tax
			$classname    = get_called_class();
			$basefilename = ( new ReflectionClass( static::class ) )->getFileName();
			$plugin_dir   = plugin_dir_path( $basefilename );
			$plugin_url   = plugin_dir_url( $basefilename );

			$default_obj = array(
				'ajax_url'  => admin_url( 'admin-ajax.php' ),
				'post_id'   => get_the_id(),
				'post_type' => get_post_type(),
				'classname' => $classname,
			);
			$default_obj = array_replace( $default_obj, $obj );
			if ( file_exists( $plugin_dir . '/assets/js/' . $file ) ) {
				wp_enqueue_script( $id, $plugin_url . '/assets/js/' . $file, array( 'jquery' ), 'v4.0.0', false );
				wp_localize_script( $id, 'tmphpobj', $default_obj );
			}
		}

		/**
		 * Enques a stylesheet locting $file in /assets/css of child object plugin
		 *
		 * @param string $id   (Required) script id used to refer in WordPress
		 * @param string $file (Required) filenamee (without path)
		 *
		 * @return void
		 */
		public static function enqueueStyleHelper( $id, $file ) {
			// Assumc child class has checked the post_type / tax
			$classname    = get_called_class();
			$basefilename = ( new ReflectionClass( static::class ) )->getFileName();
			$plugin_dir   = plugin_dir_path( $basefilename );
			$plugin_url   = plugin_dir_url( $basefilename );

			if ( file_exists( $plugin_dir . '/assets/css/' . $file ) ) {
				wp_enqueue_style( $id, $plugin_url . '/assets/css/' . $file, array(), 'v4.0.0' );
			}
		}

	}
endif;
