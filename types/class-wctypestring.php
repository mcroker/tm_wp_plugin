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

if ( ! class_exists( 'WCTypeString' ) ) :
	/**
	 * WCTypeNumber
	 */
	class WCTypeString extends WCTypeBase {

		public function __construct( $parent_name, $attrib_name, $options = [], $value = null ) {
			if ( ! is_string( $value ) && ! is_null( $value ) ) throw( new Exception( 'Expected $value to be string|null' ) );
			parent::__construct( $parent_name, $attrib_name, $options, $value );
		}

		/**
		 * Update string attribute
		 *
		 * @param string $value    (Required) Value.
		 *
		 * @return void
		 */
		public function set_value( $value ) {
			if ( ! is_string( $value ) && ! is_null( $value ) ) throw( new Exception( 'Expected $value to be string|null' ) );
			parent::set_value( $value );
		}

	}
endif;
