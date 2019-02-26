<?php
// ==================================================
/**
 * TMBaseWidget
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'TMBaseWidget' ) ) :
	/**
	 * Widget Base Class
	 */
	abstract class TMBaseWidget extends WP_Widget {


		/**
		 * Widget Title (Display)
		 */
		public static $title = 'Unnamed Widget';

		/**
		 * Widget Description (Display)
		 */
		public static $description = 'Unnamed Widget';

		/**
		 * Meta-Keys Structure
		 */
		protected static $meta_keys = [];

		/**
		 * Constructor
		 */
		function __construct() {
			$classname = get_called_class();
			parent::__construct( $classname, __( $classname::$title, 'tm' ), array( 'description' => __( $classname::$description, 'tm' ) ) );
		}

		// ==================================================
		/**
		 * Static Initialisation Routine
		 *
		 * Should be called by subclass prior to useage
		 * Registers the widget and shortcode
		 */
		static function init() {
			$classname = get_called_class();
			add_action( 'widgets_init', $classname . '::register_widget' );
			add_shortcode( $classname, $classname . '::register_shortcode' );
		}

		// ==================================================
		/**
		 * Register Widget
		 *
		 * Registers the widget into WordPress
		 */
		static function register_widget() {
			$classname = get_called_class();
			register_widget( $classname );
		}

		// ==================================================
		/**
		 * Content - Displayed on front screen.
		 *
		 * Intended to be overridden by child widget class
		 *
		 * @param string[] $metavalues (array) (Required) of all parsed metavales (as defined by class::$meta_keys)
		 */
		function content( $metavalues ) {
		}

		// Creating widget front-end ==================================================
		/**
		 * Intended to be overridden by child widget class
		 *
		 * @param string[] $args     (array) (Required) Display arguments including 'before_title', 'after_title',
		 *                           'before_widget', and 'after_widget'.
		 * @param string[] $instance (array) (Required) The settings for the particular instance of the widget.
		 */
		public function widget( $args, $instance ) {
			$classname  = get_called_class();
			$metavalues = [];
			foreach ( $classname::$meta_keys as $key => $fieldmeta ) {
				if ( isset( $instance[ $key ] ) ) {
					$metavalues[ $key ] = apply_filters( $key, $instance[ $key ] );
					if ( ! empty( $metavalues[ $key ] ) ) {
						$metavalues[ $key ] = $args[ 'before_' . $key ] . $metavalues[ $key ] . $args[ 'after_' . $key ];
					};
				} else {
					// Empty value
					switch ( $metavalues['type'] ) {
						// METATYPELIST
						case 'meta_attrib':
							$metavalues[ $key ] = '';
							break;
						case 'meta_attrib_number':
							$metavalues[ $key ] = 0;
							break;
						case 'meta_attrib_date':
							$metavalues[ $key ] = 0;
							break;
						case 'meta_attrib_time':
							$metavalues[ $key ] = 0;
							break;
						case 'meta_attrib_text':
							$metavalues[ $key ] = '';
							break;
						case 'meta_attrib_code':
							$metavalues[ $key ] = '';
							break;
						case 'meta_attrib_string':
							$metavalues[ $key ] = '';
							break;
						case 'meta_attrib_check':
							$metavalues[ $key ] = false;
							break;
						case 'meta_attrib_logo':
							$metavalues[ $key ] = '';
							break;
						case 'meta_attrib_select':
							$metavalues[ $key ] = '';
							break;
						case 'meta_attrib_object':
							$metavalues[ $key ] = null;
							break;
						case 'related_post':
							throw(new Exception( 'Not implemented ' . $type ));
						break;
						case 'related_posts':
							throw(new Exception( 'Not implemented ' . $type ));
						break;
						case 'related_tax':
							throw(new Exception( 'Not implemented ' . $type ));
						break;
						default:
							throw(new Exception( 'Type not recognised ' . $type ));
					}
				}
			}
			echo $args['before_widget'];
			$this->content( $metavalues );
			echo $args['after_widget'];
		}

		// Widget Backend Admin Form ==================================================
		/**
		 * Backend Admin Form (called automatically by WordPress)
		 *
		 * @param TM_Widget[] $instance (array) (Required) Current settings.
		 */
		public function form( $instance ) {
			$classname  = get_called_class();
			$metavalues = [];
			foreach ( $classname::$meta_keys as $key => $fieldmeta ) {
				if ( isset( $instance[ $key ] ) ) {
					$metavalues[ $key ] = $instance[ $key ];
				} else {
					$metavalues[ $key ] = __( $fieldmeta['default'], 'tm' );
				}

				$this->widget_admin_field( $key, $metavalues[ $key ] );
			}
		}

		// Updating widget replacing old instances with new ==================================================
		/**
		 * Updating widget replacing old instances with new
		 *
		 * @param string[] $new_instance (array) (Required) New settings for this instance as input by the user via WP_Widget::form().
		 * @param string[] $old_instance (array) (Required) Old settings for this instance.
		 */
		public function update( $new_instance, $old_instance ) {
			$classname = get_called_class();
			$instance  = array();
			foreach ( $classname::$meta_keys as $key => $fieldmeta ) {
				$instance[ $key ] = ( ! empty( $new_instance[ $key ] ) ) ? strip_tags( $new_instance[ $key ] ) : '';
			}
			return $instance;
		}

		// ==================================================
		/**
		 * Display admin_field (for settings)
		 *
		 * @param string                          $key      (Required) Key of field (matching meta_keys)
		 * @param $value    (Required) Field value
		 * @param string                          $type     Meta_type of filed (based on)
		 * @param object[]                        $settings Additional settings to be passed to display based on $post_type
		 */
		public function widget_admin_field( $key, $value, $label = '_AUTO', $type = '_AUTO', $settings = '_AUTO' ) {
			$classname = get_called_class();
			if ( $label == '_AUTO' ) {
				$label = $classname::$meta_keys[ $key ]['label'];
			}
			if ( $type == '_AUTO' ) {
				$type = $classname::$meta_keys[ $key ]['type'];
			}
			if ( $settings == '_AUTO' ) {
				$settings = $classname::$meta_keys[ $key ]['settings'];
			}
			?>
	  <p>
		<label for="<?php echo $this->get_field_id( $key ); ?>"><?php _e( $label, 'tm' ); ?></label>
			<?php
			switch ( $type ) {
				// METATYPELIST
				case 'meta_attrib':
					$classname::widget_admin_field_string( $fieldkey, $value, $label, $settings );
					break;
				case 'meta_attrib_number':
					throw(new Exception( 'Not implemented ' . $type ));
				break;
				case 'meta_attrib_date':
					throw(new Exception( 'Not implemented ' . $type ));
				break;
				case 'meta_attrib_time':
					throw(new Exception( 'Not implemented ' . $type ));
				break;
				case 'meta_attrib_text':
					throw(new Exception( 'Not implemented ' . $type ));
				break;
				case 'meta_attrib_code':
					throw(new Exception( 'Not implemented ' . $type ));
				break;
				case 'meta_attrib_string':
					$classname::widget_admin_field_string( $fieldkey, $value, $label, $settings );
					break;
				case 'meta_attrib_check':
					throw(new Exception( 'Not implemented ' . $type ));
				break;
				case 'meta_attrib_logo':
					throw(new Exception( 'Not implemented ' . $type ));
				break;
				case 'meta_attrib_select':
					$classname::widget_admin_field_select( $key, $value, $label, $settings );
					break;
				case 'meta_attrib_object':
					throw(new Exception( 'Not implemented ' . $type ));
				break;
				case 'related_post':
					$classname::widget_admin_field_relatedpost( $key, $value, $label, $settings );
					break;
				case 'related_posts':
					throw(new Exception( 'Not implemented ' . $type ));
				break;
				case 'related_tax':
					throw(new Exception( 'Not implemented ' . $type ));
				break;
				default:
					throw(new Exception( 'Type not recognised ' . $type ));
			}
			?>
	  </p>
			<?php
		}

		// ==================================================
		public function widget_admin_field_string( $key, $value = '', $label = '', $settings = [] ) {
			?>
	  <input class="widefat" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
			<?php
		}

		// ==================================================
		public function widget_admin_field_select( $key, $value = '', $label = '', $settings = [] ) {
			?>
	  <select class="widefat" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" >
		<option value=''>Page default</option>
			<?php
			foreach ( $settings['options'] as $optionkey => $optiontext ) {
				?>
		  <option value=<?php echo $optionkey; ?> <?php selected( $value, $optionkey ); ?> > <?php echo $optiontext; ?> </option>
			<?php } ?>
	  </select>
			<?php
		}

		// ==================================================
		public function widget_admin_field_relatedpost( $key, $value = '', $label = '', $settings = [] ) {
			$classname           = get_called_class();
			$fieldmeta           = $classname::$meta_keys[ $key ];
			$relatedclass        = $fieldmeta['classname'];
			$allposts            = $relatedclass::getAll();
			$settings['options'] = [];
			foreach ( $allposts as $relatedpost ) {
				$settings['options'][ $relatedpost->ID ] = $relatedpost->title;
			}
			$this->widget_admin_field_select( $key, $value, $label, $settings );
		}

		// ==================================================
		function shortcode( $atts = [], $content = null, $tag = '' ) {
			// normalize attribute keys, lowercase
			$atts = array_change_key_case( (array) $atts, CASE_LOWER );

			// override default attributes with user attributes
			$classname  = get_called_class();
			$atts_pairs = [];
			foreach ( $classname::$meta_keys as $key => $fieldmeta ) {
				$atts_pairs[ $key ] = $fieldmeta['default'];
			};
			$parsed_atts = shortcode_atts( $atts_pairs, $atts, $tag );

			ob_start();
			$classname::content( $parsed_atts );
			$o = ob_get_clean();
			// return output
			return $o;
		}


	} // Class wpb_widget ends here
endif;
?>
