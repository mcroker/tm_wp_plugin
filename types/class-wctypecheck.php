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

if ( ! class_exists( 'WCTypeCheck' ) ) :
	/**
	 * WCTypeNumber
	 */
	class WCTypeCheck extends WCTypeBase {

		/**
		 * Set Value using packed database value.
		 *
		 * @param Boolean $value Meta value persisted on WordPress record.
		 */
		public function unpack_value( $packedvalue ) {
			if ( '1' === $packedvalue || $packedvalue ) {
				parent::unpack_value(true);
			} else {
				parent::unpack_value(false);
			}
		}

		public function echo_formfield( $settings = [] ) {
			$settings = $this->get_formfield_settings( $settings );
			?>
			<input class="<?php echo esc_attr( $settings['inputclass'] ); ?>"
			type="checkbox"
			name="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			id="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			<?php checked( $this->get_value() ); ?>
			/>
			<?php
		}

	}
endif;
