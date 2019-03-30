<?php
/**
 * TMBaseTax
 *
 * @category
 * @package  WordCider
 * @author   Martin Croker <oss@croker.ltd>
 * @license  Apache2
 * @link
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require_once 'class-wcbasegeneric.php';

if ( ! class_exists( 'WCBaseTax' ) ) :
	/**
	 * TMBaseTax
	 */
	abstract class WCBaseTax extends WCBaseGeneric {

		/**
		 * $taxonomy
		 *
		 * TODO - this needs to be protected.
		 *
		 * @var string $taxonomy
		 */
		public static $taxonomy;

		/**
		 * $labels
		 *
		 * @var string $labels
		 */
		protected static $labels = [];

		/**
		 * $args
		 *
		 * @var string $args
		 */
		protected static $args = [];

		/**
		 * $associate_post_types
		 *
		 * @var string $associate_post_types
		 */
		protected static $associate_post_types = [];

		/**
		 * $wp_taxonomy
		 *
		 * @var string $wp_taxonomy
		 */
		private $wp_taxonomy;

		/**
		 * __construct
		 *
		 * @param string $term (Required) instance of WP_Term or id for term.
		 */
		public function __construct( $term ) {
			if ( $term instanceof WP_Term ) {
				parent::__construct( $term->term_id, $term );
			} else { // hopefully an id.
				parent::__construct( $term );
			}
			$this->wp_taxonomy = static::$taxonomy;
		}

		/**
		 * Static class initiatizer
		 *
		 * @return void
		 */
		public static function init() {
			$classname = get_called_class();
			add_action( 'init', $classname . '::registerTaxonomy' );
			if ( is_admin() ) {
				add_action( 'admin_enqueue_scripts', $classname . '::enqueueAdminScripts' );
				add_action( static::$taxonomy . '_add_form_fields', $classname . '::add_form_fields', 10, 2 );
				add_action( static::$taxonomy . '_edit_form_fields', $classname . '::edit_form_fields', 10, 2 );
				add_action( 'created_' . static::$taxonomy, $classname . '::save_tax', 10, 2 );
				add_action( 'edited_' . static::$taxonomy, $classname . '::save_tax', 10, 2 );
			}
			add_action( 'wp_enqueue_scripts', $classname . '::enqueueScripts' );
		}

		/**
		 * Register taxoonmy with WordPress core
		 *
		 * @return void
		 */
		public static function register_taxonomy() {
			$singular_name = static::$labels['singular_name'];
			$plural_name   = static::get_plural_name();

			$default_labels = array(
				'name'                  => esc_attr( $plural_name, 'taxonomy general name', 'wordcider' ),
				'singular_name'         => esc_attr( $singular_name, 'taxonomy singular name', 'wordcider' ),
				'search_items'          => esc_attr( 'Search ' . $singular_name, 'wordcider' ),
				'popular_items'         => esc_attr( 'Common ' . $plural_name, 'wordcider' ),
				'all_items'             => esc_attr( 'All ' . $plural_name, 'wordcider' ),
				'edit_item'             => esc_attr( 'Edit ' . $singular_name ),
				'update_item'           => esc_attr( 'Update ' . $singular_name ),
				'add_new_item'          => esc_attr( 'Add new ' . $singular_name ),
				'new_item_name'         => esc_attr( 'New ' . $singular_name . ':' ),
				'add_or_remove_items'   => esc_attr( 'Remove ' . $singular_name ),
				'choose_from_most_used' => esc_attr( 'Choose from common ' . $singular_name ),
				'not_found'             => esc_attr( 'No ' . $singular_name . ' found.' ),
				'menu_name'             => esc_attr( $plural_name ),
			);
			$default_labels = array_replace( $default_labels, static::$labels );

			$default_args = array(
				'hierarchical' => false,
				'labels'       => $default_labels,
				'show_ui'      => true,
			);
			$default_args = array_replace( $default_args, static::$args );

			register_taxonomy( static::$taxonomy, static::$associate_post_types, $default_args );
		}

		/**
		 * Enqueue non-admin scripts based on file-name convention
		 *
		 * @return void
		 */
		public static function enqueue_scripts() {
			$classname = get_called_class();
			if ( is_object_in_taxonomy( get_post_type(), static::$taxonomy ) ) {
				parent::enqueue_style_helper( $classname . '-script', $classname . '.js' );
				parent::enqueue_style_helper( $classname . '-css', $classname . '.css' );
			}
		}

		/**
		 * Enqueue admin scripts based on file-name convention
		 *
		 * @param string $hook_suffix (Required) passed by WordPress - URL php file.
		 *
		 * @return void
		 */
		public static function enqueue_admin_scripts( $hook_suffix ) {
			$classname = get_called_class();
			if ( in_array( $hook_suffix, array( 'term.php', 'edit-tags.php' ), true ) ) {
				$screen = get_current_screen();
				if ( is_object( $screen ) && static::$taxonomy === $screen->taxonomy ) {
					parent::enqueue_script_helper( $classname . '-admin-script', $classname . '-admin.js' );
					parent::enqueue_style_helper( $classname . '-admin-css', $classname . '-admin.css' );
					wp_enqueue_media();
					parent::enqueue_script_helper( $classname . '-logo-field-js', 'TMLogoField.js', [], __FILE__ );
				}
			}
		}

		/**
		 * Return all terms within the taxonomy
		 *
		 * @param String[] $filters Array of field_filters to apply.
		 *
		 * @return object[] Array of term objects
		 */
		public static function get_all( $filters = [] ) {
			$classname = get_called_class();
			$terms     = get_terms(
				[
					'taxonomy'   => static::$taxonomy,
					'hide_empty' => false,
				]
			);
			$termobjs  = [];
			foreach ( $terms as $term ) {
				$termobjs[] = new $classname( $term->term_id );
			}
			return $termobjs;
		}

		/**
		 * Return a class instance based on the slug provided
		 *
		 * @param string $term_slug (Required) the slug for the term to be returned.
		 *
		 * @return TMBaseTax class object of term matching slug.
		 */
		public static function get_by_slug( $term_slug ) {
			$classname = get_called_class();
			$term      = get_term_by( 'slug', $term_slug, static::$taxonomy );
			if ( $term ) {
				return new $classname( $term->term_id );
			} else {
				return null;
			}
		}

		/**
		 * Return a term by name
		 *
		 * @param string $term_name (Required) the name of the term to be returned.
		 *
		 * @return TMBaseTax class object of term matching name.
		 */
		public static function get_by_name( $term_name ) {
			$classname = get_called_class();
			$term      = get_term( array( 'name' => $term_name ), static::$taxonomy );
			return new $classname( $term->term_id );
		}

		/**
		 * Return a term by id
		 *
		 * @param string $term_id (Required) the id of the term to be returned.
		 *
		 * @return TMBaseTax class object of term matching id.
		 */
		public static function get_by_id( $term_id ) {
			$classname = get_called_class();
			$term      = get_term( $term_id, static::$taxonomy );
			return new $classname( $term->term_id );
		}

		/**
		 * Insert a term
		 *
		 * @param string $term_slug (Required) the slug for the term to be inserted.
		 *
		 * @return TMBaseTax class object of inserted term.
		 */
		public static function insert_term( $term_slug ) {
			$classname = get_called_class();
			$resp      = wp_insert_term( $term_slug, static::$taxonomy, $args = array() );
			return new $classname( $resp['term_id'] );
		}

		/**
		 * Implements getter function for all meta_attributes.
		 *
		 * @param string[] $key (Required) Attribute key (from meta_keys) to be returned.
		 *
		 * @return undefined Value of meta_attribute.
		 */
		public function __get( $key ) {
			$stemkey = static::get_stemkey( $key );
			if ( array_key_exists( $stemkey, static::$meta_keys ) ) {
				$conf = static::$meta_keys[ $stemkey ];
				switch ( $conf['type'] ) {
					case 'related_posts':
						return $this->get_related_posts( $key, $conf['classname'] );
					default:
						return parent::__get( $key );
				}
			} else {
				switch ( $key ) {
					case 'name':
						return $this->term->name;
					case 'slug':
						return $this->term->slug;
					case 'term':
						if ( is_null( $this->_obj ) ) {
							$this->_obj = get_term( $this->id, $this->wp_taxonomy );
						}
						return $this->_obj;
					default:
						return parent::__get( $key );
				}
			}
		}


		/**
		 * Implements WordPress meta update.
		 *
		 * This enables TMBaseGeneric to handle updates for all meta_data types by
		 * taking care of the actual term/post interface as required.
		 *
		 * @param string $meta_key (Required) Meta key as defined in WP.
		 * @param string $value    (Required) The value to be updated.
		 *
		 * @return void
		 */
		protected function update_meta_value( $meta_key, $value ) {
			update_term_meta( $this->id, $meta_key, $value );
		}

		/**
		 * Implements WordPress meta retrieve.
		 *
		 * This enables TMBaseGeneric to handle retrieve for all meta_data types by
		 * taking care of the actual term/post interface as required.
		 *
		 * @param string $meta_key (Required) Meta key as defined in WP.
		 *
		 * @return undefined meta_value stored against WP_Term.
		 */
		protected function get_meta_value( $meta_key ) {
			return get_term_meta( $this->id, $meta_key, true );
		}

		/**
		 * Returns all posts which are related to the taxonomy term.
		 *
		 * @param string $key       (Required) IS THIS NOT USED? TODO.
		 * @param string $postclass (Required) Class extending TMBasePost
		 *                          representing class of the post_type to be search.
		 *
		 * @return TMBasePost[] Array of TMBasePosts associated to term.
		 */
		protected function get_related_posts( $key, $postclass ) {
			return $postclass::get_related_to_tax( static::$taxonomy, $this->id );
		}

		/**
		 * Sorts terms in ascending order of slug.
		 *
		 * @param string $a (Required) First term to be sorted.
		 * @param string $b (Required) Second term to be sorted.
		 *
		 * @return boolean function indicating which is higher in sort order.
		 */
		public static function sort_by_slug_asc( $a, $b ) {
			return ( $a->slug > $b->slug );
		}

		/**
		 * Sorts terms in descending order of slug.
		 *
		 * @param string $a (Required) First term to be sorted.
		 * @param string $b (Required) Second term to be sorted.
		 *
		 * @return boolean function indicating which is higher in sort order.
		 */
		public static function sort_by_slug_desc( $a, $b ) {
			return ( $a->slug < $b->slug );
		}

		/**
		 * Generate form fields for add term diaglogue.
		 *
		 * Creates the fields automatically based on the term_meta static field.
		 *
		 * @param string $taxonomy (Required) Meta key as defined in WP TODO is this used.
		 *
		 * @return void
		 */
		public static function add_form_fields( $taxonomy ) {
			static::base_form_field_nonce();
			foreach ( static::$meta_keys as $key => $value ) {
				static::add_form_field( $key );
			}
		}

		/**
		 * Generate form fields for edit term diaglogue.
		 *
		 * Creates the fields automatically based on the term_meta static field.
		 *
		 * @param string $term (Required) Meta key as defined in WP.
		 *
		 * @return void
		 */
		public static function edit_form_fields( $term ) {
			static::base_form_field_nonce();
			foreach ( static::$meta_keys as $key => $value ) {
				static::edit_form_field( $term, $key );
			}
		}

		/**
		 * Generate a form field for add term diaglogue.
		 *
		 * Creates a field automatically based on the term_meta static field.
		 *
		 * @param string $key      (Required) meta_key as defined in meta_keys.
		 * @param string $value    value of field.
		 * @param string $label    label to display.
		 * @param string $type     meta_type of field.
		 * @param string $settings additional settings to pass to the display routine.
		 *
		 * @return void
		 */
		public static function add_form_field( $key, $value = '_AUTO', $label = '_AUTO', $type = '_AUTO', $settings = '_AUTO' ) {
			// TODO Work out how to promote this code to Generic.
			if ( '_AUTO' === $value ) {
				// TODO A default would be great here.
				$value = '';
			}
			if ( '_AUTO' === $label ) {
				$label = static::$meta_keys[ $key ]['label'];
			}
			if ( '_AUTO' === $type ) {
				$type = static::$meta_keys[ $key ]['type'];
			}
			if ( '_AUTO' === $settings && array_key_exists( 'settings', static::$meta_keys[ $key ] ) ) {
				$settings = static::$meta_keys[ $key ]['settings'];
			}
			if ( null === $settings || '_AUTO' === $settings ) {
				$settings = [];
			}
			?>
			<div class="form-field term-group">
				<?php static::base_form_label( $key, $value, $label, $settings ); ?>
				<?php static::base_form_field( $key, $type, $value, $label, $settings ); ?>
			</div>
			<?php
		}

		/**
		 * Generate a form field for edit term diaglogue.
		 *
		 * Creates a field automatically based on the term_meta static field.
		 *
		 * @param string $term     (Required) ID of term.
		 * @param string $key      (Required) meta_key as defined in meta_keys.
		 * @param string $value    value of field.
		 * @param string $label    label to display.
		 * @param string $type     meta_type of field.
		 * @param string $settings additional settings to pass to the display routine.
		 *
		 * @return void
		 */
		public static function edit_form_field( $term, $key, $value = '_AUTO', $label = '_AUTO', $type = '_AUTO', $settings = '_AUTO' ) {
			if ( '_AUTO' === $value ) {
				$obj   = new $classname( $term );
				$value = $obj->$key;
			}
			if ( '_AUTO' === $label ) {
				$label = static::$meta_keys[ $key ]['label'];
			}
			if ( '_AUTO' === $type ) {
				$type = static::$meta_keys[ $key ]['type'];
			}
			if ( '_AUTO' === $settings && array_key_exists( 'settings', static::$meta_keys[ $key ] ) ) {
				$settings = static::$meta_keys[ $key ]['settings'];
			}
			if ( null === $settings || '_AUTO' === $settings ) {
				$settings = [];
			}
			?>
			<tr class="form-field term-group-wrap">
				<th scope="row">
					<?php static::base_form_label( $key, $value, $label, $settings ); ?>
				</th>
				<td>
					<?php static::base_form_field( $key, $type, $value, $label, $settings ); ?>
				</td>
			</tr>
			<?php
		}

		/**
		 * Save all the generated fields on a form.
		 *
		 * @param string $term_id (Required) ID of term.
		 * @param string $tt_id   (Required) What is this used for TODO.
		 *
		 * @return void
		 *
		 * @throws Exception Not implemented.
		 */
		public static function save_tax( $term_id, $tt_id ) {
			// TODO - What does TT id do?
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( static::verify_nonce() ) {
				$obj = new $classname( $term_id );
				foreach ( static::$meta_keys as $key => $value ) {
					$fieldkey = static::get_elem_name( $key );
					switch ( $value['type'] ) {
						case 'meta_attrib_check':
							$obj->$key = isset( $_POST[ $fieldkey ] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
							break;
						case 'related_post':
							throw(new Exception( 'not implemented' ));
						case 'related_tax':
							throw(new Exception( 'not implemented' ));
						default:
							if ( isset( $_POST[ $fieldkey ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
								$obj->$key = static::get_post_param( $fieldkey );
							};
					}
				}
			}
		}

		/**
		 * Generate a logo field on the form.
		 *
		 * Uses WordPress media dialoges to add a media form to the tax.
		 *
		 * @param string $fieldkey (Required) name & id of field (conventially CLASS_KEY).
		 * @param string $value    value of field.
		 * @param string $label    label to display.
		 * @param string $settings additional settings to pass to the display routine.
		 *
		 * @return void
		 */
		public static function base_form_field_logo( $fieldkey, $value = '', $label = '', $settings = [] ) {
			global $content_width, $_wp_additional_image_sizes;

			$removebuttonid = static::get_elem_name( 'logo_remove' );
			$uploadbuttonid = static::get_elem_name( 'logo_upload' );
			$logodivid      = static::get_elem_name( 'logo_div' );
			$fieldid        = static::get_elem_name( 'logo' );

			$old_content_width = $content_width;
			// TODO - Come back and see if we should be ignoring this.
			$content_width = 254; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
			if ( $value && get_post( $value ) ) {
				if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
					$thumbnail_html = wp_get_attachment_image( $value, array( $content_width, $content_width ) );
				} else {
					$thumbnail_html = wp_get_attachment_image( $value, 'post-thumbnail' );
				}
				if ( ! empty( $thumbnail_html ) ) {
					?>
					<div id="<?php echo esc_attr( $logodivid ); ?>">
						<?php echo esc_html( $thumbnail_html ); ?>
						<p class="hide-if-no-js">
							<a href="javascript:;"
							id="<?php echo esc_attr( $removebuttonid ); ?>" >
							<?php echo esc_html__( 'Remove listing image', 'wordcider' ); ?>
						</a>
					</p>
					<input type="hidden" id="<?php echo esc_attr( $fieldid ); ?>" name="<?php echo esc_attr( $fieldid ); ?>" value="<?php esc_attr( $value ); ?>"/>
				</div>
					<?php
				}
				$content_width = $old_content_width; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
			} else {
				?>
			<div id="<?php echo esc_attr( $logodivid ); ?>">
				<img src="" style="width:<?php echo esc_attr( $content_width ); ?>px;height:auto;border:0;display:none;" />
				<p class="hide-if-no-js">
					<a title="<?php echo esc_attr__( 'Set listing image', 'wordcider' ); ?>"
						href="javascript:;"
						id="<?php echo esc_attr( $uploadbuttonid ); ?>"
						data-uploader_title="<?php echo esc_attr__( 'Choose an logo', 'wordcider' ); ?>"
						data-uploader_button_text="<?php echo esc_attr__( 'Set logo', 'wordcider' ); ?>" >
						<?php echo esc_html__( 'Set logo', 'wordcider' ); ?>
					</a>
				</p>
				<input type="hidden" id="<?php echo esc_attr( $fieldid ); ?>" name="<?php echo esc_attr( $fieldid ); ?>" value="" />
			</div>
				<?php
			}
		}

	}
endif;
