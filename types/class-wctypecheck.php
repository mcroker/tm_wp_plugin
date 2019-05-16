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

		public function get_value() {
			if ( null == $this->value ) {
			  $value = $this->get_from_db();
			  if ( '1' === $value || $value ) {
				$this->value = true;
			  } else {
				$this->value = false;
			  }
		    }
			return $this->value;
		}

		public function echo_formfield( $settings = [] ) {
			$htmlsettings = $this->get_html_settings( $settings );
			?>
			<input class="<?php echo esc_attr( $htmlsettings['inputclass'] ); ?>"
			type="checkbox"
			name="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			id="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			<?php checked( $this->get_value() ); ?>
			/>
			<?php
		}

	}
endif;
