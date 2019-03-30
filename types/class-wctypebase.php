<?php
/**
* WCTypeString
*
* @category
* @package  WordCider
* @author   Martin Croker <oss@croker.ltd>
* @license  Apache2
* @link
*/

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

if ( ! class_exists( 'WCTypeBase' ) ) :
	/**
	* WCBaseType
	*/
	abstract class WCTypeBase {

		/**
		* Cache value to prevent unecessary WordPress calls
		*/
		private $value;

		private $parent_name;
		private $attrib_name;
		private $options;
		private $meta_key;

		/**
		* __construct
		*
		* @param string $attrib_name   (Required) Name of attributre referenced from meta_keys.
		* @param string $parent_object (Required) Parent BaseGeneric object - used for persists.
		* @param string $options       Type specific options.
		*/
		public function __construct( $parent_name, $attrib_name, $options, $value = null ) {
			$this->parent_name = $parent_name;
			$this->attrib_name = $attrib_name;
			$this->value       = $value;
			$this->options     = $options;

			if ( isset ( $options['meta_key'] ) ) {
				$this->meta_key=$options['meta_key'];
			} else {
				$this->meta_key=$attrib_name;
			};
		}

		/**
		* Update attribute
		*
		* @param undefined $value    (Required) Value.
		*/
		public function set_value( $value ) {
			$this->value = $value;
		}

		/**
		* Get number attribute
		*
		* @param string $meta_key (Required) Meta attribute key in WP.
		*
		* @return undefined Meta value persisted on WordPress record.
		*/
		public function get_value() {
			return $this->value;
		}

		/**
		* Get meta key for attribute in WordPress
		*
		* @return String Meta value persisted on WordPress record.
		*/
		public function get_meta_key() {
			return $this->meta_key;
		}

		/**
		* Get meta key for attribute in WordPress
		*
		* @return String Meta value persisted on WordPress record.
		*/
		public function get_elem_name() {
			return $this->parent_name . '_' . $this->attrib_name;
		}

		/**
		* Update attribute
		*
		* @param undefined $value    (Required) Value.
		*/
		public function unpack_value( $packedvalue ) {
			$this->value = $packedvalue;
		}

		/**
		* Get number attribute
		*
		* @param string $meta_key (Required) Meta attribute key in WP.
		*
		* @return undefined Meta value persisted on WordPress record.
		*/
		public function get_packed_value() {
			return $this->value;
		}

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
		protected function get_formfield_settings( $settings = [] ) {
			if ( array_key_exists( 'formfield', $this->options ) ) {
				$settings = array_replace( $this->options['formfield'], $settings );
			}
			if ( ! isset( $settings['inputclass'] ) ) {
				$settings['inputclass']='';
			}
			return $settings;
		}


		protected function get_html_settings( $settings = [] ) {
			if ( array_key_exists( 'html', $this->options ) ) {
				$settings = array_replace( $this->options['html'], $settings );
			}
			return $settings;
		}


		public function echo_formfield( $settings = [] ) {
			$settings = $this->get_formfield_settings( $settings );
			?>
			<input
			class="<?php echo esc_attr( $settings['inputclass'] ); ?>"
			type="text"
			name="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			value="<?php echo esc_attr( $this->get_value() ); ?>"
			id="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			/><br>
			<?php
		}

		public function echo_html( $settings = [] ) {
			$settings = $this->get_html_settings( $settings );
			echo esc_html( $this->get_value() );
		}



		/**
		 * Create a String form field
		 *
		 * @return void
		 */
		public function echo_label( $settings = [] ) {
			$labelclass = isset( $settings['labelclass'] ) ? $settings['labelclass'] : '';
			?>
			<label class="<?php echo esc_attr( $labelclass ); ?>" for="<?php echo esc_attr( $this->get_elem_name() ); ?>" >
			<?php echo esc_html( $label ); ?>
			</label>
			<?php
		}

	}
endif;
