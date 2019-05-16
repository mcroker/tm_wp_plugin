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

if ( ! class_exists( 'WCElemButton' ) ) :
	/**
	 * WCElemButton
	 */
	class WCElemButton {

		/**
		 * Create a button formfield
		 *
		 * @param string $fieldkey (Required) ID&Name for field - conventially
		 *                         Classname_MetaKey.
		 * @param string $label    (Required) Field Label to display.
		 * @param string $onclick  (Required) Action to perform on button click.
		 * @param string $status   Default contents of status field.
		 *
		 * @return void
		 */
		public function echo_html( $fieldkey, $label, $onclick, $status = '' ) {
			$inputclass = isset( $settings['inputclass'] ) ? $settings['inputclass'] : '';
			?>
			<input
			id='<?php echo esc_attr( $fieldkey ); ?>'
			class='button <?php echo esc_attr( $inputclass ); ?>'
			type='button'
			onclick='<?php echo esc_attr( $onclick ); ?>'
			value='<?php echo esc_attr( $label ); ?>' />
			<label class="<?php echo esc_attr( $settings['labelclass'] ); ?>" id="<?php echo esc_attr( $fieldkey ); ?>_status" for="<?php echo esc_attr( $fieldkey ); ?>"><?php echo esc_html( $status ); ?></label>
			<?php
		}

	}
endif;
