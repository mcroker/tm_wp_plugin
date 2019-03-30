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

if ( ! class_exists( 'WCTypeDate' ) ) :
	/**
	 * WCTypeNumber
	 */
	class WCTypeDate extends WCTypeBase {

		/**
		* Update attribute
		*
		* @param undefined $value    (Required) Value.
		*/
		public function set_value( $value ) {
			if ( is_string( $value ) ) {
				$value = strtotime( $value );
			}
			parent::set_value( $value );
		}

		/**
		* Get number attribute
		*
		* @param string $meta_key (Required) Meta attribute key in WP.
		*
		* @return undefined Meta value persisted on WordPress record.
		*/
		public function get_value() {
			return date( 'Y-m-d', parent::get_value() );
		}

		/**
		 * Update number attribute
		 *
		 * @param String $packedvalue    (Required) Value.
		 *
		 * @return void
		 */
		public function unpack_value( $packedvalue ) {
			// TODO: Switch serialise to JSON.
			if ( is_null( $packedvalue ) || '' === $packedvalue ) {
				parent::unpack_value(0);
			} else {
				parent::unpack_value( strtotime( $packedvalue ) );
			}
		}

		/**
		 * Get checkbox attribute
		 *
		 * @return String Meta value persisted on WordPress record.
		 */
		public function get_packed_value() {
			return date( 'Y-m-d', parent::get_packed_value() );
		}

		public function echo_formfield(  $settings = []  ) {
			$settings = $this->get_formfield_settings( $settings );
			$value = parent::get_value();
			?>
			<input class="<?php echo esc_attr( $settings['inputclass'] ); ?>"
			type="datetime-local"
			name="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			id="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			value="<?php echo esc_attr( date( 'Y-m-d\TH:i', $value ) ); ?>"
			/>
			<?php
		}

	}
endif;
