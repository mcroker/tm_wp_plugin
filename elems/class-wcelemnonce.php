<?php
/**
* WCTypeNumber
*
* @category
* @package  WordCider
* @author   Martin Croker <oss@croker.ltd>
* @license  Apache2
* @link
*/

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require_once 'class-wcelembase.php';

if ( ! class_exists( 'WCElemNonce' ) ) :
	/**
	* WCElemButton
	*/
	class WCElemNonce {

		// Hold the class instance.
		private static $instances = [];

		private $boxid;
		private $nonceaction;
		private $noncename;

		// The constructor is private
		// to prevent initiation with outer code.
		public function __construct( $boxid ) {
			// The expensive process (e.g.,db connection) goes here.
			$this->boxid       = $boxid;
			$this->nonceaction = 'WCElemNonce_' . $this->boxid . '_nonce';
			$this->noncename   = 'WCElemNonce_' . $this->boxid . '_field_nonce';
		}

		// The object is created from within the class itself
		// only if the class has no instance.
		public static function getInstance( $boxid = 'default' ) {
			if ( ! array_key_exists( $boxid, self::$instances ) ) {
				self::$instances[$boxid] = new WCElemNonce( $boxid );
			}

			return self::$instances[$boxid];
		}

		/**
		* Create a nonce form field
		*
		* @param string $boxid Override nonce name.
		*
		* @return void
		*/
		public function echo_html() {
			// Use nonce for verification.
			wp_nonce_field( $this->nonceaction, $this->noncename );
		}

		/**
		* Create a nonce form field
		*
		* @param string $boxid Override nonce name.
		*
		* @return boolean True if nonce is valid
		*/
		public function verify() {
			global $_POST; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			global $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET[ $this->noncename ] ) ) {
				return wp_verify_nonce( sanitize_key( $_GET[ $this->noncename ] ), $this->nonceaction );
			} elseif ( isset( $_POST[ $this->noncename ] ) ) {
				return wp_verify_nonce( sanitize_key( $_POST[ $this->noncename ] ), $this->nonceaction );
			} else {
				return false;
			}
		}

	}
endif;
