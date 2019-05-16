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

require_once 'class-wctypebase.php';

if ( ! class_exists( 'WCTypeObject' ) ) :
	/**
	 * WCTypeNumber
	 */
	class WCTypeObject extends WCTypeBase {

		/**
		 * Update number attribute
		 *
		 * @param String $packedvalue    (Required) Value.
		 *
		 * @return void
		 */
		public function set_serialized( $packedvalue ) {
			// TODO: Switch serialise to JSON.
			parent::set_serialized( unserialize( $packedvalue ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize
		}

		/**
		 * Get checkbox attribute
		 *
		 * @return String Meta value persisted on WordPress record.
		 */
		public function get_serialized() {
			return( serialize( parent::get_serialized() ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
		}

		public function echo_formfield( $settings = [] ) {
		}

	}
endif;
