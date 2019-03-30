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

if ( ! class_exists( 'WCTypeNumber' ) ) :
	/**
	 * WCTypeNumber
	 */
	class WCTypeNumber extends WCTypeBase {

		/**
		 * Update number attribute
		 *
		 * @param numeric $value    (Required) Value.
		 *
		 * @return void
		 */
		public function set_value( $value ) {
			if ( is_numeric( $value ) ) {
				parent::set_value( $value );
			} else {
				throw( new Exception( 'Expected value to be numberic' ) );
			}
		}

		public function echo_formfield( $settings = [] ) {
			$settings = $this->get_formfield_settings( $settings );
			?>
			<input class="<?php echo esc_attr( $settings['inputclass'] ); ?>"
			type="number"
			name="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			id="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			value="<?php echo esc_attr( $this->get_value() ); ?>"
			/>
			<?php
		}

	}
endif;
