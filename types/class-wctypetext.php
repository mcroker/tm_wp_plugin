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

if ( ! class_exists( 'WCTypeText' ) ) :
	/**
	 * WCTypeNumber
	 */
	class WCTypeText extends WCTypeBase {

		public function echo_formfield(  $settings = []  ) {
			$settings = $this->get_formfield_settings( $settings );
			wp_editor( $this->get_value(), $this->get_elem_name(), $settings );
		}

	}
endif;
