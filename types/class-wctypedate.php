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
				$this->value = strtotime( $value );
			} else if ( is_numeric( $value ) ) {
				$this->value = $value;
			} else {
				throw new Exeption('Expected $value to be string|number');
			}
		}

		/**
		* Get number attribute
		*
		* @param string $meta_key (Required) Meta attribute key in WP.
		*
		* @return undefined Meta value persisted on WordPress record.
		*/
		public function get_value() {
			return date( 'Y-m-d', $this->get_timevalue() );
		}

		public function get_timevalue() {
			if ( null == $this->value ) {
				$this->value = $this->get_from_db();
			}
			return $this->value;
		}

		/**
		* Get number attribute
		*/
		public function save_to_db() {
			$this->parent_object->update_meta_value( $this->meta_key, $this->value );
		}

		/**
		* Get number attribute
		*/
		public function get_from_db() {
			$value = $this->parent_object->get_meta_value( $this->meta_key );
			if ( is_null( $value ) || '' === $value ) {
				return 0;
			} else if ( is_string( $value ) ) {
				return strtotime( $value );
			} else if ( is_numeric( $value ) ) {
				return $value;
			} else {
				throw new Exeption('Expected database value to be null|string|number');
			}
		}

		public function echo_formfield(  $settings = []  ) {
			$htmlsettings = $this->get_html_settings( $settings );
			?>
			<input class="<?php echo esc_attr( $htmlsettings['inputclass'] ); ?>"
			type="datetime-local"
			name="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			id="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			value="<?php echo esc_attr( date( 'Y-m-d\TH:i', $this->get_timevalue() ) ); ?>"
			/>
			<?php
		}

	}
endif;
