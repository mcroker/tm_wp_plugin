<?php
/**
 * WCBaseGeneric
 *
 * @category
 * @package  WordCider
 * @author   Martin Croker <oss@croker.ltd>
 * @license  Apache2
 * @link
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require_once('types/class-wctypecheck.php');
require_once('types/class-wctypecode.php');
require_once('types/class-wctypedate.php');
require_once('types/class-wctypenumber.php');
require_once('types/class-wctypeobject.php');
require_once('types/class-wctypestring.php');
require_once('types/class-wctypetext.php');
require_once('types/class-wctypetime.php');

// TODO - Need to implement readonly text field.
if ( ! class_exists( 'WCBaseGeneric' ) ) :
	/**
	 * WCBaseGeneric
	 */
	abstract class WCBaseGeneric {

		/**
		 * $id
		 *
		 * @var number $id
		 */
		protected $id;

		/**
		 * $obj
		 *
		 * @var number $obj
		 */
		protected $obj;

		/**
		 * $meta_keys
		 *
		 * @var object[] $meta_keys
		 */
		protected static $meta_keys = [];

		/**
		 * $attributes
		 *
		 * @var object[] $attributes
		 */
		protected $attributes = [];

		/**
		 * __construct
		 *
		 * @param string $id  (Required) id of object.
		 * @param string $obj Post/Tax object to persist (or null if new record).
		 */
		public function __construct( $id, $obj = null ) {
			$this->id  = $id;
			$this->obj = $obj;
		}

		/**
		 * Abstract function to handle WordPress meta integration
		 *
		 * @param string $meta_key WordPress meta_key.
		 * @param string $value    Value of attribute to persit.
		 *
		 * @return void
		 */
		abstract protected function update_meta_value( $meta_key, $value );

		/**
		 * Abstract function to handle WordPress meta integration
		 *
		 * @param string $meta_key WordPress meta_key to retrieve.
		 *
		 * @return undefined value returned from WordPress as persisted as an atribute
		 */
		abstract protected function get_meta_value( $meta_key );

		/**
		 * Function to return all instances - needs to be implemented by children
		 *
		 * @param String[] $filters Array of field_filters to apply.
		 *
		 * @return TMBaseGeneric[] Array of all object instances
		 */
		protected static function get_all( $filters = [] ) {
			return [];
		}

		/**
		 * Load attribute object from database
		 *
		 * @param string $key key name to return based on index to meta_keys.
		 *
		 * @return object Attribute object.
		 * @throws class:Exception If $key is not found.
		 */
		public function create_attribute( $key ) {
			$stemkey   = static::get_stemkey( $key );
			if ( array_key_exists( $stemkey, static::$meta_keys ) ) {
				if ( ! array_key_exists( $key, $this->attributes ) ) {
					$classname   = get_called_class();
					$conf        = static::$meta_keys[ $stemkey ];
					$type        = $conf['type'];
					$builtintype = 'WCType' . $type;
					if ( class_exists( $type ) ) {
						$this->attributes[ $key ] = new $type( $classname, $key, $conf );
					} elseif ( class_exists ( $builtintype ) ) {
						$this->attributes[ $key ] = new $builtintype( $classname, $key, $conf );
					} else {
						$this->attributes[ $key ] = new WCTypeString( $classname, $key, $conf );
					}
				}
				return $this->attributes[ $key ];
			} else {
				throw( new Exception( 'Key ' + $key + ' not configured' ) );
			}
		}

		/**
		 * Load attribute object from database
		 *
		 * @param string $key key name to return based on index to meta_keys.
		 *
		 * @return object Attribute object.
		 * @throws class:Exception If $key is not found.
		 */
		public function get_attribute( $key ) {
			if ( ! array_key_exists( $key, $this->attributes ) ) {
				$this->create_attribute( $key );
				$this->attributes[ $key ]->unpack_value( $this->get_meta_value( $this->attributes[ $key ]->get_meta_key() ) );
			}
			return $this->attributes[ $key ];
		}

		/**
		 * Default getter
		 *
		 * @param string $key key name to return based on index to meta_keys.
		 *
		 * @return $value of persisted meta attribute.
		 * @throws class:Exception If $key is not found.
		 */
		public function __get( $key ) {
			switch ( $key ) {
				case 'ID':
					return $this->id;
				default:
					$attrib = $this->get_attribute( $key );
					return $attrib->get_value();
			}
		}

		/**
		 * Default getter
		 *
		 * @param string $key   (Required) key name to set based on index to meta_keys.
		 * @param string $value (Required) value to set - type based on meta_type.
		 *
		 * @return void
		 *
		 * @throws class:Exception If $key is not found.
		 */
		public function __set( $key, $value ) {
			if ( ! array_key_exists( $key, $this->attributes ) ) {
				$this->create_attribute( $key );
			}
			$this->attributes[ $key ]->set_value( $value );
			$this->update_meta_value( $this->attributes[ $key ]->get_meta_key(), $this->attributes[ $key ]->get_packed_value() );
		}

		/**
		 * Clears the in memory cache of objects
		 *
		 * @return void
		 */
		public function clear_cache() {
			$this->attributes = [];
		}

		/**
		 * Return the plural label name base on name and/or sungualr name
		 *
		 * @return string plural label name
		 */
		public static function get_plural_name() {
			if ( array_key_exists( 'name', static::$labels ) ) {
				$plural_name = static::$labels['name'];
			} else {
				$plural_name = static::$labels['singular_name'] . 's';
			}
			return $plural_name;
		}

		/**
		 * Get parameter key (removes _id if required)
		 *
		 * @param string $key (Required) base key potentially sufficed by _id.
		 *
		 * @return $key with '_id' removed if present.
		 */
		protected function get_stemkey( $key ) {
			if ( substr( $key, -3 ) === '_id' ) {
				return substr( $key, 0, strlen( $key ) - 3 );
			} else {
				return $key;
			}
		}

		/**
		 * Enques a script locting $file in /assets/js of child object plugin
		 *
		 * @param string $id   (Required) script id used to refer in WordPress.
		 * @param string $file (Required) filenamee (without path).
		 * @param string $obj  Object to localise script with.
		 *
		 * @return void
		 */
		public static function enqueue_script_helper( $id, $file, $obj = [] ) {
			// Assumc child class has checked the post_type / tax.
			$classname    = get_called_class();
			$basefilename = ( new ReflectionClass( static::class ) )->getFileName();
			$plugin_dir   = plugin_dir_path( $basefilename );
			$plugin_url   = plugin_dir_url( $basefilename );
			$default_obj  = array(
				'ajax_url'  => admin_url( 'admin-ajax.php' ),
				'post_id'   => get_the_id(),
				'post_type' => get_post_type(),
				'classname' => $classname,
			);
			$default_obj  = array_replace( $default_obj, $obj );

			if ( file_exists( $plugin_dir . '/assets/js/' . $file ) ) {
				wp_enqueue_script( $id, $plugin_url . '/assets/js/' . $file, array( 'jquery' ), 'v4.0.0', false );
				wp_localize_script( $id, 'tmphpobj', $default_obj );
			}
		}

		/**
		 * Enques a stylesheet locting $file in /assets/css of child object plugin
		 *
		 * @param string $id   (Required) script id used to refer in WordPress.
		 * @param string $file (Required) filenamee (without path).
		 *
		 * @return void
		 */
		public static function enqueue_style_helper( $id, $file ) {
			// Assumc child class has checked the post_type / tax.
			$classname    = get_called_class();
			$basefilename = ( new ReflectionClass( static::class ) )->getFileName();
			$plugin_dir   = plugin_dir_path( $basefilename );
			$plugin_url   = plugin_dir_url( $basefilename );

			if ( file_exists( $plugin_dir . '/assets/css/' . $file ) ) {
				wp_enqueue_style( $id, $plugin_url . '/assets/css/' . $file, [], 'v4.0.0' );
			}
		}

		/**
		 * Get prefix for HTML Object Names
		 *
		 * @param String $suffix String to use for element specific identifier.
		 *
		 * @return String Prefix to add to HTML obkects
		 */
		public static function get_elem_name( $suffix ) {
			$classname = get_called_class();
			return $classname . '_' . $suffix;
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
		public static function formfield_select( $fieldkey, $value = '', $label = '', $settings = [] ) {
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
		public static function formfield_button( $fieldkey, $label, $onclick, $status = '' ) {
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

		/**
		 * Create a nonce form field
		 *
		 * @param string $boxid Override nonce name.
		 *
		 * @return void
		 */
		public static function formfield_nonce( $boxid = 'default' ) {
			$nonceaction = static::get_elem_name( $boxid . '_nonce' );
			$noncename   = static::get_elem_name( $boxid . '_field_nonce' );
			// Use nonce for verification.
			wp_nonce_field( $nonceaction, $noncename );
		}

		/**
		 * Gets a field from $_GET.
		 *
		 * @param String  $fieldkey     (required) The key name for the field.
		 * @param String  $default      Default value (default='').
		 * @param Boolean $check_nonce  Check the default nonce is set (default=true).
		 *
		 * @return String Value or '' if not set/no-nonce.
		 */
		public static function http_get_param( $fieldkey, $default = '', $check_nonce = true ) {
			global $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$value = $default;
			if ( static::verify_nonce() || ! $check_nonce ) {
				if ( isset( $_GET[ $fieldkey ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$value = sanitize_text_field( wp_unslash( $_GET[ $fieldkey ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				}
			}
			// TODO - Should this convert based on type?
			return $value;
		}

		/**
		 * Gets a field from $_GET.
		 *
		 * @param String $fieldkey     (required) The key name for the field.
		 * @param String $default      Default value (default='').
		 *
		 * @return String Value or '' if not set/no-nonce.
		 */
		public static function http_post_param( $fieldkey, $default = '' ) {
			global $_POST; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$value = $default;
			if ( static::verify_nonce() ) {
				if ( isset( $_POST[ $fieldkey ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
					$value = sanitize_text_field( wp_unslash( $_POST[ $fieldkey ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
				}
			}
			// TODO - Should this convert based on type?
			return $value;
		}

		/**
		 * Create a nonce form field
		 *
		 * @param string $boxid Override nonce name.
		 *
		 * @return boolean True if nonce is valid
		 */
		public static function verify_nonce( $boxid = 'default' ) {
			global $_POST; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			global $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$nonceaction = static::get_elem_name( $boxid . '_nonce' );
			$noncename   = static::get_elem_name( $boxid . '_field_nonce' );
			if ( isset( $_GET[ $noncename ] ) ) {
				return wp_verify_nonce( sanitize_key( $_GET[ $noncename ] ), $nonceaction );
			} elseif ( isset( $_POST[ $noncename ] ) ) {
				return wp_verify_nonce( sanitize_key( $_POST[ $noncename ] ), $nonceaction );
			} else {
				return false;
			}
		}

		/**
		 * Create a form field based on the meta_key config for the fieldkey
		 *
		 * @param string $key      (Required) key index from meta_keys.
		 *
		 * @return void
		 *
		 * @throws Exception Invalid or unknown key.
		 */
		public function echo_formfield( $key ) {
			$outerclass = '';
			?>
			<div class="<?php echo esc_attr( $outerclass ); ?>">
				<?php
				$attrib = $this->get_attribute( $key );
				$attrib->echo_formfield();
				?>
			</div>
			<?php
		}

		/**
		 * Fomats a field for output as html (not as a field)
		 *
		 * @param string $key      (Required) key index from meta_keys.
		 *
		 * @return void
		 *
		 * @throws Exception Type not set in meta structure, or invalid key.
		 */
		public function echo_html( $key ) {
			$attrib = $this->get_attribute( $key );
			$attrib->echo_html();
		}

	}
endif;
