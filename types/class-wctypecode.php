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

if ( ! class_exists( 'WCTypeCode' ) ) :
	/**
	 * WCTypeNumber
	 */
	class WCTypeCode extends WCTypeBase {

		public function echo_formfield(  $settings = []  ) {
			$settings = $this->get_formfield_settings( $settings );
			?>
			<textarea
			class="<?php echo esc_attr( $settings['inputclass'] ); ?>"
			style="width:100%"
			rows=15
			name="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			id="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			><?php echo esc_html( $this->get_value() ); ?></textarea>
			<?php
		}

	}
endif;
