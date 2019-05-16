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

		public function __construct( $parent_name, $attrib_name, $options = [], $value = null ) {
			if ( ! is_numeric( $value ) && ! is_null( $value ) ) throw( new Exception( 'Expected $value to be number|null' ) );
			parent::__construct( $parent_name, $attrib_name, $options, $value );
		}

		/**
		 * Update number attribute
		 *
		 * @param numeric $value    (Required) Value.
		 *
		 * @return void
		 */
		public function set_value( $value ) {
			if ( ! is_numeric( $value ) && ! is_null( $value ) ) throw( new Exception( 'Expected $value to be number|null' ) );
			parent::set_value( $value );
		}

		public function echo_formfield( $settings = [] ) {
			if ( ! is_array( $settings ) ) throw( new Exception( 'Expected $settings to be array|null' ) );

			$htmlsettings = $this->get_html_settings( $settings );
			?>
			<input class="<?php echo esc_attr( $htmlsettings['inputclass'] ); ?>"
			type="number"
			name="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			id="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			value="<?php echo esc_attr( $this->get_value() ); ?>"
			/>
			<?php
		}

	}
endif;
