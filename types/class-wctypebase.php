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
		protected $value;

		protected $parent_object;
		protected $parent_class;
		protected $attrib_name;
		protected $options;
		protected $meta_key;

		/**
		* __construct
		*
		* @param string $attrib_name   (Required) Name of attributre referenced from meta_keys.
		* @param string $parent_object (Required) Parent BaseGeneric object - used for persists.
		* @param string $options       Type specific options.
		*/
		public function __construct( $parent_object, $attrib_name, $options = [], $value = null ) {
			if ( ! is_object( $parent_object ) ) throw( new Exception( 'Expected $parent_object to be object' ) );
			if ( ! $parent_object instanceof WCBaseGeneric ) throw( new Exception( 'Expected $parent_object to implement WCBaseGeneric' ) );
			if ( ! is_string( $attrib_name ) ) throw( new Exception( 'Expected $attrib_name to be string' ) );
			if ( ! is_array( $options ) )      throw( new Exception( 'Expected $options to be array or null' ) );

			$this->parent_object = $parent_object;
			$this->parent_class  = get_class ($parent_object );
			$this->attrib_name   = $attrib_name;
			$this->value         = $value;
			$this->options       = $options;

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
			if ( null == $this->value ) {
			  $this->value = $this->get_from_db();
		    }
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
			return $this->parent_class . '_' . $this->attrib_name;
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
			return $this->parent_object->get_meta_value( $this->meta_key );
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
		public function get_formfield_settings( $settings = [] ) {
			if ( ! is_array( $settings ) ) throw( new Exception( 'Expected $settings to be array|null' ) );

			if ( array_key_exists( 'formfield', $this->options ) ) {
				$settings = array_replace( $this->options['formfield'], $settings );
			}
			return $settings;
		}

		public function get_html_settings( $settings = [] ) {
			if ( ! is_array( $settings ) ) throw( new Exception( 'Expected $settings to be array|null' ) );

			if ( array_key_exists( 'html', $this->options ) ) {
				$settings = array_replace( $this->options['html'], $settings );
			}
			if ( ! array_key_exists( 'labelclass', $settings ) ) {
				$settings['labelclass']='';
			}
			if ( ! array_key_exists( 'valueclass', $settings ) ) {
				$settings['valueclass']='';
			}
			if ( ! array_key_exists( 'inputclass', $settings ) ) {
				$settings['inputclass']='';
			}
			return $settings;
		}

		public function get_label( $label = '' ) {
			if ( ! is_string( $label ) ) throw( new Exception( 'Expected $label to be string|null' ) );

			if ( array_key_exists( 'label', $this->options ) ) {
				$label = $this->options['label'];
			}
			if ( $label === '' ) {
				$label = $this->attrib_name;
			}
			return $label;
		}

		public function echo_formfield( $settings = [] ) {
			if ( ! is_array( $settings ) ) throw( new Exception( 'Expected $settings to be array|null' ) );

			$htmlsettings = $this->get_html_settings( $settings );
			?>
			<input
			class="<?php echo esc_attr( $htmlsettings['inputclass'] ); ?>"
			type="text"
			name="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			value="<?php echo esc_attr( $this->get_value() ); ?>"
			id="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			/><br>
			<?php
		}

		public function echo_html( $settings = [] ) {
			if ( ! is_array( $settings ) ) throw( new Exception( 'Expected $settings to be array|null' ) );

			$settings = $this->get_html_settings( $settings );
			echo esc_html( $this->get_value() );
		}

		/**
		* Create a String form field
		*
		* @return void
		*/
		public function echo_label( $label = '', $settings = [] ) {
			if ( ! is_string( $label ) )   throw( new Exception( 'Expected $label to be string|null' ) );
			if ( ! is_array( $settings ) ) throw( new Exception( 'Expected $settings to be array|null' ) );

			$label    = $this->get_label( $label );
			$settings = $this->get_html_settings( $settings );

			?>
			<label class="<?php echo esc_attr( $settings['labelclass'] ); ?>" for="<?php echo esc_attr( $this->get_elem_name() ); ?>" >
				<?php echo esc_html( $label ); ?>
			</label>
			<?php
		}

	}
endif;
