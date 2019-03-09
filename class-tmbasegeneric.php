<?php
/**
 * TMBaseGeneric
 *
 * @category
 * @package  TMWPPlugin
 * @author   Martin Croker <martin@croker.family>
 * @license  Apache2
 * @link
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

// TODO - Need to implement readonly text field.
if ( ! class_exists( 'TMBaseGeneric' ) ) :
	/**
	 * TMBaseTax
	 *
	 * @package TMWPPlugin
	 * @author  Martin Croker <martin@croker.family>
	 * @license Apache2
	 * @link
	 */
	abstract class TMBaseGeneric {

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
		 * $cache
		 *
		 * @var object[] $cache
		 */
		protected $cache = [];

		/**
		 * $meta_keys
		 *
		 * @var object[] $meta_keys
		 */
		protected static $meta_keys = [];

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
		 * Default getter
		 *
		 * @param string $key key name to return based on index to meta_keys.
		 *
		 * @return $value of persisted meta attribute.
		 * @throws class:Exception If $key is not found.
		 */
		public function __get( $key ) {
			$classname = get_called_class();
			$stemkey   = self::get_stemkey( $key );
			if ( array_key_exists( $stemkey, $classname::$meta_keys ) ) {
				$conf = $classname::$meta_keys[ $stemkey ];
				$type = $conf['type'];
				if ( method_exists( $this, 'get_attrib_' . $type ) ) {
					return call_user_func( array( $this, 'get_attrib_' . $type ), $key, $conf['meta_key'] );
				} else {
					// Default to string handling.
					if ( ! array_key_exists( $key, $this->cache ) ) {
						$this->cache[ $key ] = $this->get_meta_value( $conf['meta_key'] );
					}
					return $this->cache[ $key ];
				}
			} else {
				switch ( $key ) {
					case 'ID':
						return $this->id;
					default:
						throw( new Exception( 'Key ' + $key + ' not configured' ) );
				}
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
			$classname = get_called_class();
			$stemkey   = self::get_stemkey( $key );
			if ( array_key_exists( $stemkey, $classname::$meta_keys ) ) {
				$conf = $classname::$meta_keys[ $stemkey ];
				$type = $conf['type'];
				if ( method_exists( $this, 'update_attrib_' . $type ) ) {
					$stringvalue = call_user_func( array( $this, 'update_attrib_' . $type ), $key, $conf['meta_key'], $value );
				} else {
					// Default to string handling.
					$this->update_meta_value( $conf['meta_key'], $value );
					$this->cache[ $key ] = $value;
				}
			} else {
				throw( new Exception( 'Key ' + $key + ' not configured' ) );
			}
		}

		/**
		 * Clears the in memory cache of objects
		 *
		 * @return void
		 */
		public function clear_cache() {
			$this->cache = [];
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
		 * Update number attribute
		 *
		 * @param string  $key      (Required) Cache key.
		 * @param string  $meta_key (Required) Meta attribute key in WP.
		 * @param numeric $value    (Required) Value.
		 *
		 * @return void
		 *
		 * @throws Exception Invalid number.
		 */
		protected function update_attrib_number( $key, $meta_key, $value ) {
			if ( is_numeric( $value ) ) {
				$this->update_meta_value( $meta_key, $value );
				$this->cache[ $key ] = $value;
			} else {
				throw( new Exception( 'Expected value to be numberic' ) );
			}
		}

		/**
		 * Get number attribute
		 *
		 * @param string $key      (Required) Cache key.
		 * @param string $meta_key (Required) Meta attribute key in WP.
		 *
		 * @return numeric Meta value persisted on WordPress record.
		 */
		protected function get_attrib_number( $key, $meta_key ) {
			if ( ! array_key_exists( $key, $this->cache ) ) {
				$this->cache[ $key ] = $this->get_meta_value( $meta_key );
			}
			return $this->cache[ $key ];
		}

		/**
		 * Update check attribute
		 *
		 * @param string  $key      (Required) Cache key.
		 * @param string  $meta_key (Required) Meta attribute key in WP.
		 * @param Boolean $value    (Required) Value.
		 *
		 * @return void
		 */
		protected function update_attrib_check( $key, $meta_key, $value ) {
			$this->update_meta_value( $meta_key, $value );
			$this->cache[ $key ] = $value;
		}

		/**
		 * Get check attribute
		 *
		 * @param string $key      (Required) Cache key.
		 * @param string $meta_key (Required) Meta attribute key in WP.
		 *
		 * @return Boolean Meta value persisted on WordPress record.
		 */
		protected function get_attrib_check( $key, $meta_key ) {
			if ( ! array_key_exists( $key, $this->cache ) ) {
				$value = $this->get_meta_value( $meta_key );
				if ( '1' === $value || $value ) {
					$this->cache[ $key ] = true;
				} else {
					$this->cache[ $key ] = false;
				}
			}
			return $this->cache[ $key ];
		}

		/**
		 * Update object attribute
		 *
		 * @param string $key      (Required) Cache key.
		 * @param string $meta_key (Required) Meta attribute key in WP.
		 * @param object $value    (Required) Value.
		 *
		 * @return void
		 */
		protected function update_attrib_object( $key, $meta_key, $value ) {
			// TODO: Switch serialise to JSON.
			$serialvalue = serialize( $value ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
			$this->update_meta_value( $meta_key, $serialvalue );
			$this->cache[ $key ] = $value;
		}

		/**
		 * Get string attribute
		 *
		 * @param string $key      (Required) Cache key.
		 * @param string $meta_key (Required) Meta attribute key in WP.
		 *
		 * @return object Meta value persisted on WordPress record.
		 */
		protected function get_attrib_object( $key, $meta_key ) {
			if ( ! array_key_exists( $key, $this->cache ) ) {
				$serialvalue         = $this->get_meta_value( $meta_key );
				$value               = unserialize( $serialvalue ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize
				$this->cache[ $key ] = $value;
			}
			return $this->cache[ $key ];
		}

		/**
		 * Update tate attribute
		 *
		 * @param string    $key      (Required) Cache key.
		 * @param string    $meta_key (Required) Meta attribute key in WP.
		 * @param timevalue $value    (Required) Value.
		 *
		 * @return void
		 */
		protected function update_attrib_date( $key, $meta_key, $value ) {
			if ( is_string( $value ) ) {
				$value = strtotime( $value );
			}
			$value = date( 'Y-m-d', $value );
			$this->update_meta_value( $meta_key, $value );
			$this->cache[ $key ] = $value;
		}

		/**
		 * Get string attribute
		 *
		 * @param string $key      (Required) Cache key.
		 * @param string $meta_key (Required) Meta attribute key in WP.
		 *
		 * @return timevalue Meta value persisted on WordPress record.
		 */
		protected function get_attrib_date( $key, $meta_key ) {
			if ( ! array_key_exists( $key, $this->cache ) ) {
				$value = $this->get_meta_value( $meta_key );
				if ( is_null( $value ) || '' === $value ) {
					$this->cache[ $key ] = 0;
				} else {
					$this->cache[ $key ] = strtotime( $value );
				}
				if ( is_numeric( $this->cache[ $key ] ) ) {
					$this->cache[ $key ] = strftime( '%Y-%m-%d', $this->cache[ $key ] );
				}
			}
			return $this->cache[ $key ];
		}

		/**
		 * Update time attribute
		 *
		 * @param string    $key      (Required) Cache key.
		 * @param string    $meta_key (Required) Meta attribute key in WP.
		 * @param timevalue $value    (Required) Value.
		 *
		 * @return void
		 */
		protected function update_attrib_time( $key, $meta_key, $value ) {
			if ( is_string( $value ) ) {
				$value = strtotime( $value, 0 );
			}
			if ( is_a( $value, 'DateTime' ) ) {
				$value = $value->getTimestamp();
			}
			$this->update_meta_value( $meta_key, $value );
			$this->cache[ $key ] = $value;
		}

		/**
		 * Get string attribute
		 *
		 * @param string $key      (Required) Cache key.
		 * @param string $meta_key (Required) Meta attribute key in WP.
		 *
		 * @return DateTime Meta value persisted on WordPress record.
		 */
		protected function get_attrib_time( $key, $meta_key ) {
			if ( ! array_key_exists( $key, $this->cache ) ) {
				$value = $this->get_meta_value( $meta_key );
				if ( is_null( $value ) || '' === $value ) {
					$this->cache[ $key ] = 0;
				} else {
					$this->cache[ $key ] = (int) $value;
				}
			}
			$dt = new DateTime();
			$dt->setTimestamp( $this->cache[ $key ] );
			$tz = get_option( 'timezone_string' );
			if ( ! empty( $tz ) ) {
				$dt->setTimezone( new DateTimeZone( get_option( 'timezone_string' ) ) );
			}
			return $dt;
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
		public static function formfield_string( $fieldkey, $value = '', $label = '', $settings = [] ) {
			$inputclass = isset( $settings['inputclass'] ) ? $settings['inputclass'] : '';
			?>
			<input
			class="<?php echo esc_attr( $inputclass ); ?>"
			type="text"
			name="<?php echo esc_attr( $fieldkey ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			id="<?php echo esc_attr( $fieldkey ); ?>"
			/><br>
			<?php
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
		 *
		 * @throws Exception Infalid DateTime instance.
		 */
		public static function formfield_time( $fieldkey, $value = null, $label = '', $settings = [] ) {
			$inputclass = isset( $settings['inputclass'] ) ? $settings['inputclass'] : '';
			if ( is_null( $value ) ) {
				$value = new DateTime();
			}
			if ( ! $value instanceof DateTime ) {
				throw( new Exception( '$value must be instance of DateTime or null' ) );
			}
			?>
			<input class="<?php echo esc_attr( $inputclass ); ?>"
			type="datetime-local"
			name="<?php echo esc_attr( $fieldkey ); ?>"
			id="<?php echo esc_attr( $fieldkey ); ?>"
			value="<?php echo esc_attr( $value->format( 'TH:i' ) ); ?>"
			/>
			<?php
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
		public static function formfield_date( $fieldkey, $value = '', $label = '', $settings = [] ) {
			$inputclass = isset( $settings['inputclass'] ) ? $settings['inputclass'] : '';
			if ( is_string( $value ) ) {
				$value = strtotime( $value );
			}
			?>
			<input class="<?php echo esc_attr( $inputclass ); ?>"
			type="datetime-local"
			name="<?php echo esc_attr( $fieldkey ); ?>"
			id="<?php echo esc_attr( $fieldkey ); ?>"
			value="<?php echo esc_attr( date( 'Y-m-d\TH:i', $value ) ); ?>"
			/>
			<?php
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
		public static function formfield_check( $fieldkey, $value = '', $label = '', $settings = [] ) {
			$inputclass = isset( $settings['inputclass'] ) ? $settings['inputclass'] : '';
			?>
			<input class="<?php echo esc_attr( $inputclass ); ?>"
			type="checkbox"
			name="<?php echo esc_attr( $fieldkey ); ?>"
			id="<?php echo esc_attr( $fieldkey ); ?>"
			<?php checked( $value ); ?>
			/>
			<?php
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
		public static function formfield_number( $fieldkey, $value = '', $label = '', $settings = [] ) {
			$inputclass = isset( $settings['inputclass'] ) ? $settings['inputclass'] : '';
			?>
			<input class="<?php echo esc_attr( $inputclass ); ?>"
			type="number"
			name="<?php echo esc_attr( $fieldkey ); ?>"
			id="<?php echo esc_attr( $fieldkey ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			/>
			<?php
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
		public static function formfield_text( $fieldkey, $value = '', $label = '', $settings = [] ) {
			wp_editor( $value, $fieldkey, $settings );
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
		public static function formfield_code( $fieldkey, $value = '', $label = '', $settings = [] ) {
			$inputclass = isset( $settings['inputclass'] ) ? $settings['inputclass'] : '';
			?>
			<textarea class="<?php echo esc_attr( $inputclass ); ?>" style="width:100%" rows=15 name="<?php echo esc_attr( $fieldkey ); ?>" id="<?php echo esc_attr( $fieldkey ); ?>"><?php echo esc_attr( $value ); ?></textarea>
			<?php
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
		public static function formfield_label( $fieldkey, $value = '', $label = '', $settings = [] ) {
			$labelclass = isset( $settings['labelclass'] ) ? $settings['labelclass'] : '';
			?>
			<?php if ( '_NONE' !== $label ) { ?>
				<label class="<?php echo esc_attr( $labelclass ); ?>" for="<?php echo esc_attr( $fieldkey ); ?>"><?php echo esc_html( $label ); ?></label>
				<?php
			}
		}

		/**
		 * Throw an exception if somebody tries to create a logo field
		 *
		 * It is expected if this field type is required it is implemented by the subclass
		 *
		 * @param string   $fieldkey (Required) ID&Name for field - conventially
		 *                           Classname_MetaKey.
		 * @param string   $value    Field value to display.
		 * @param string   $label    Textual label to display.
		 * @param string[] $settings Additional settings to pass to display.
		 *
		 * @return void
		 * @throws Exception Not implemented.
		 */
		public static function formfield_logo( $fieldkey, $value = '', $label = '', $settings = [] ) {
			throw( new Exception( 'Not implemented ' . $type ) ); // May be implemented in child.
		}

		/**
		 * Throw an exception if somebody tries to create a Object field
		 *
		 * It is expected if this field type is required it is implemented by the subclass
		 *
		 * @param string   $fieldkey (Required) ID&Name for field - conventially
		 *                           Classname_MetaKey.
		 * @param string   $value    Field value to display.
		 * @param string   $label    Textual label to display.
		 * @param string[] $settings Additional settings to pass to display.
		 *
		 * @return void
		 * @throws Exception Not implemented.
		 */
		public static function formfield_object( $fieldkey, $value = '', $label = '', $settings = [] ) {
			throw( new Exception( 'Not implemented ' . $fieldkey ) ); // May be implemented in child.
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
			$classname   = get_called_class();
			$nonceaction = $classname . '_' . $boxid . '_nonce';
			$noncename   = $classname . '_' . $boxid . '_field_nonce';
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
			$classname = get_called_class();
			$value     = $default;
			if ( $classname::verify_nonce() || ! $check_nonce ) {
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
			$classname = get_called_class();
			$value     = $default;
			if ( $classname::verify_nonce() ) {
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
			$classname   = get_called_class();
			$nonceaction = $classname . '_' . $boxid . '_nonce';
			$noncename   = $classname . '_' . $boxid . '_field_nonce';
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
		public function formfield( $key ) {
			$classname  = get_called_class();
			$metafields = [];
			$outerclass = '';
			if ( array_key_exists( $key, $classname::$meta_keys ) ) {
				$metafields = $classname::$meta_keys[ $key ];
			} else {
				throw ( new Exception( 'Uknown key ' . $key ) );
			}
			$value = $this->$key;
			if ( array_key_exists( 'label', $metafields ) ) {
				$label = $metafields['label'];
			} else {
				$label = '';
			}
			if ( array_key_exists( 'settings', $metafields ) ) {
				$settings = $metafields['settings'];
				if ( array_key_exists( 'outerclass', $settings ) ) {
					$outerclass = $settings['outerclass'];
				}
			} else {
				$settings = [];
			}
			if ( array_key_exists( 'type', $metafields ) ) {
				$type = $metafields['type'];
			} else {
				throw( new Exception( 'Meta value type not set' ) );
			}
			$fieldkey = $classname . '_' . $key;
			?>
			<div class="tm_<?php echo esc_attr( $type ); ?> <?php echo esc_attr( $outerclass ); ?>">
				<?php
				if ( method_exists( __CLASS__, 'formfield_' . $type ) ) {
					call_user_func( array( $classname, 'formfield_' . $type ), $fieldkey, $value, $label, $settings );
				} else {
					self::formfield_string( $fieldkey, $value, $label, $settings );
				}
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
			$classname = get_called_class();
			if ( array_key_exists( $key, $classname::$meta_keys ) ) {
				$metafields = $classname::$meta_keys[ $key ];
			} else {
				throw ( new Exception( 'Uknown key ' . $key ) );
			}
			if ( array_key_exists( 'settings', $metafields ) ) {
				$settings = $metafields['settings'];
			} else {
				$settings = [];
			}
			if ( array_key_exists( 'type', $metafields ) ) {
				$type = $metafields['type'];
			} else {
				throw( new Exception( 'Meta value type not set' ) );
			}
			if ( method_exists( $this, 'echo_html_' . $type ) ) {
				call_user_func( array( $this, 'echo_html_' . $type ), $key, $settings );
			} else {
				echo esc_html( $this->$key );
			}
		}

	}
endif;
