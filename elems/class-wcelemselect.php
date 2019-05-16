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

require_once 'class-wcelembase.php';

if ( ! class_exists( 'WCElemSelect' ) ) :
	/**
	 * WCTypeNumber
	 */
	class WCElemSelect extends WCElemBase {

		/**
		 * Create a String form field
		 *
		 * @param string   $fieldkey (Required) ID&Name for field - conventially
		 *                           Classname_MetaKey.
		 * @param string   $value    Field value to display.
		 * @param string   $label    Textual label to display.
		 * @param string[] $settings Additional settings to pass to display.
		 *
		 * @return void
		 */
		//public function echo_html( $fieldkey, $value = '', $label = '', $settings = [] ) {
		public function echo_html() {
			$inputclass = isset( $settings['inputclass'] ) ? $settings['inputclass'] : '';
			?>
			<select class="<?php echo esc_attr( $inputclass ); ?>"
				id="<?php echo esc_attr( $fieldkey ); ?>"
				name="<?php echo esc_attr( $fieldkey ); ?>" >
				<option value=''>Page default</option>
				<?php foreach ( $settings['options'] as $optionkey => $optiontext ) { ?>
					<option value=<?php echo esc_attr( $optionkey ); ?> <?php selected( $value, $optionkey ); ?> > <?php echo esc_attr( $optiontext ); ?> </option>
				<?php } ?>
			</select>
			<?php
		}


	}
endif;
