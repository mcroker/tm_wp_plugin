<?php
/**
 * TMBaseTax
 *
 * @category
 * @package  TMWPPlugin
 * @author   Martin Croker <martin@croker.family>
 * @license  Apache2
 * @link
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once 'class-wcbasegeneric.php';

if ( ! class_exists( 'TMBaseTax' ) ) :
	/**
	 * TMBaseTax
	 *
	 * @package TMWPPlugin
	 * @author  Martin Croker <martin@croker.family>
	 * @license Apache2
	 * @link
	 */
	abstract class TMBaseTax extends WCBaseGeneric {

		public static $taxonomy;

		protected static $labels               = [];
		protected static $args                 = [];
		protected static $associate_post_types = [];

		private $_taxonomy;

		/**
		 * __construct
		 *
		 * @param string $term (Required) instance of WP_Term or id for term
		 */
		function __construct( $term ) {
			if ( $term instanceof WP_Term ) {
				parent::__construct( $term->term_id, $term );
			} else { // hopefully an id
				parent::__construct( $term );
			}
			$classname       = get_called_class();
			$this->_taxonomy = $classname::$taxonomy;
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
				add_action(
					'admin_enqueue_scripts',
					$classname . '::enqueueAdminScripts'
				);
				add_action(
					$classname::$taxonomy . '_add_form_fields',
					$classname . '::addFormFields',
					10,
					2
				);
				add_action(
					$classname::$taxonomy . '_edit_form_fields',
					$classname . '::editFormFields',
					10,
					2
				);
				add_action(
					'created_' . $classname::$taxonomy,
					$classname . '::saveTax',
					10,
					2
				);
				add_action(
					'edited_' . $classname::$taxonomy,
					$classname . '::saveTax',
					10,
					2
				);
			}
			add_action( 'wp_enqueue_scripts', $classname . '::enqueueScripts' );
		}

		/**
		 * Register taxoonmy with WordPress core
		 *
		 * @return void
		 */
		public static function registerTaxonomy() {
			$classname     = get_called_class();
			$singular_name = $classname::$labels['singular_name'];
			$plural_name   = $classname::getPluralname();

			$default_labels = array(
				'name'                  => _x( $plural_name, 'taxonomy general name', 'tm' ),
				'singular_name'         => _x( $singular_name, 'taxonomy singular name', 'tm' ),
				'search_items'          => __( 'Search ' . $singular_name, 'tm' ),
				'popular_items'         => __( 'Common ' . $plural_name, 'tm' ),
				'all_items'             => __( 'All ' . $plural_name, 'tm' ),
				'edit_item'             => __( 'Edit ' . $singular_name, 'tm' ),
				'update_item'           => __( 'Update ' . $singular_name, 'tm' ),
				'add_new_item'          => __( 'Add new ' . $singular_name, 'tm' ),
				'new_item_name'         => __( 'New ' . $singular_name . ':', 'tm' ),
				'add_or_remove_items'   => __( 'Remove ' . $singular_name, 'tm' ),
				'choose_from_most_used' => __( 'Choose from common ' . $singular_name, 'tm' ),
				'not_found'             => __( 'No ' . $singular_name . ' found.', 'tm' ),
				'menu_name'             => __( $plural_name, 'tm' ),
			);
			$default_labels = array_replace( $default_labels, $classname::$labels );

			$default_args = array(
				'hierarchical' => false,
				'labels'       => $default_labels,
				'show_ui'      => true,
			);
			$default_args = array_replace( $default_args, $classname::$args );

			register_taxonomy( $classname::$taxonomy, $classname::$associate_post_types, $default_args );
		}

		/**
		 * Enqueue non-admin scripts based on file-name convention
		 *
		 * @return void
		 */
		public static function enqueueScripts() {
			$classname = get_called_class();
			if ( is_object_in_taxonomy( get_post_type(), $classname::$taxonomy ) ) {
				parent::enqueueScriptHelper( $classname . '-script', $classname . '.js' );
				parent::enqueueStyleHelper( $classname . '-css', $classname . '.css' );
			}
		}

		/**
		 * Enqueue admin scripts based on file-name convention
		 *
		 * @param string $hook_suffix (Required) passed by WordPress - URL php file
		 *
		 * @return void
		 */
		public static function enqueueAdminScripts( $hook_suffix ) {
			$classname = get_called_class();
			if ( in_array( $hook_suffix, array( 'term.php', 'edit-tags.php' ) ) ) {
				$screen = get_current_screen();
				if ( is_object( $screen ) && $classname::$taxonomy == $screen->taxonomy ) {
					parent::enqueueScriptHelper( $classname . '-admin-script', $classname . '-admin.js' );
					parent::enqueueStyleHelper( $classname . '-admin-css', $classname . '-admin.css' );
					wp_enqueue_media();
					parent::enqueueScriptHelper( $classname . '-logo-field-js', 'TMLogoField.js', [], __FILE__ );
				}
			}
		}

		/**
		 * Return all terms within the taxonomy
		 *
		 * @return object[] Array of term objects
		 */
		public static function getAll() {
			$classname = get_called_class();
			$terms     = get_terms(
				[
					'taxonomy'   => $classname::$taxonomy,
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
		 * @param string $term_slug (Required) the slug for the term to be returned
		 *
		 * @return TMBaseTax class object of term matching slug
		 */
		public static function getBySlug( $term_slug ) {
			$classname = get_called_class();
			$term      = get_term_by( 'slug', $term_slug, $classname::$taxonomy );
			if ( $term ) {
				return new $classname( $term->term_id );
			} else {
				return null;
			}
		}

		/**
		 * Return a term by name
		 *
		 * @param string $term_name (Required) the name of the term to be returned
		 *
		 * @return TMBaseTax class object of term matching name
		 */
		public static function getByName( $term_name ) {
			$classname = get_called_class();
			$term      = get_term( array( 'name' => $term_name ), $classname::$taxonomy );
			return new $classname( $term->term_id );
		}

		/**
		 * Return a term by id
		 *
		 * @param string $term_id (Required) the id of the term to be returned
		 *
		 * @return TMBaseTax class object of term matching id
		 */
		public static function getByID( $term_id ) {
			$classname = get_called_class();
			$term      = get_term( $term_id, $classname::$taxonomy );
			return new $classname( $term->term_id );
		}

		/**
		 * Insert a term
		 *
		 * @param string $term_slug (Required) the slug for the term to be inserted
		 *
		 * @return TMBaseTax class object of inserted term
		 */
		public static function insertTerm( $term_slug ) {
			$classname = get_called_class();
			$resp      = wp_insert_term( $term_slug, $classname::$taxonomy, $args = array() );
			$classname = get_called_class();
			return new $classname( $resp->term_id );
		}

		/**
		 * Implements getter function for all meta_attributes
		 *
		 * @param string[] $key (Required) Attribute key (from meta_keys) to be returned
		 *
		 * @return undefined Value of meta_attribute
		 */
		public function __get( $key ) {
			$classname = get_called_class();
			$stemkey   = self::getStemkey( $key );
			if ( array_key_exists( $stemkey, $classname::$meta_keys ) ) {
				$conf = $classname::$meta_keys[ $stemkey ];
				switch ( $conf['type'] ) {
					case 'related_posts':
						return $this->getRelatedPosts( $key, $conf['classname'] );
					break;
					default:
						return parent::__get( $key );
				}
			} else {
				switch ( $key ) {
					case 'name':
						return $this->term->name;
					break;
					case 'slug':
						return $this->term->slug;
					break;
					case 'term':
						if ( is_null( $this->_obj ) ) {
							$this->_obj = get_term( $this->_id, $this->_taxonomy );
						}
						return $this->_obj;
					break;
					default:
						return parent::__get( $key );
				}
			}
		}


		/**
		 * Implements WordPress meta update
		 *
		 * This enables TMBaseGeneric to handle updates for all meta_data types by
		 * taking care of the actual term/post interface as required.
		 *
		 * @param string $meta_key (Required) Meta key as defined in WP
		 * @param string $value    (Required) The value to be updated
		 *
		 * @return void
		 */
		protected function updateMetaValue( $meta_key, $value ) {
			update_term_meta( $this->_id, $meta_key, $value );
		}

		/**
		 * Implements WordPress meta retrieve
		 *
		 * This enables TMBaseGeneric to handle retrieve for all meta_data types by
		 * taking care of the actual term/post interface as required.
		 *
		 * @param string $meta_key (Required) Meta key as defined in WP
		 *
		 * @return undefined meta_value stored against WP_Term
		 */
		protected function getMetaValue( $meta_key ) {
			return get_term_meta( $this->_id, $meta_key, true );
		}

		/**
		 * Returns all posts which are related to the taxonomy term
		 *
		 * @param string $key       (Required) IS THIS NOT USED? TODO
		 * @param string $postclass (Required) Class extending TMBasePost
		 *                          representing class of the post_type to be search
		 *
		 * @return TMBasePost[] Array of TMBasePosts associated to term
		 */
		protected function getRelatedPosts( $key, $postclass ) {
			$classname = get_called_class();
			return $postclass::getRelatedToTax( $classname::$taxonomy, $this->_id );
		}

		/**
		 * Sorts terms in ascending order of slug
		 *
		 * @param string $a (Required) First term to be sorted
		 * @param string $b (Required) Second term to be sorted
		 *
		 * @return boolean function indicating which is higher in sort order
		 */
		public static function sortBySlugAsc( $a, $b ) {
			return ( $a->slug > $b->slug );
		}

		/**
		 * Sorts terms in descending order of slug
		 *
		 * @param string $a (Required) First term to be sorted
		 * @param string $b (Required) Second term to be sorted
		 *
		 * @return boolean function indicating which is higher in sort order
		 */
		public static function sortBySlugDesc( $a, $b ) {
			return ( $a->slug < $b->slug );
		}

		/**
		 * Generate form fields for add term diaglogue
		 *
		 * Creates the fields automatically based on the term_meta static field
		 *
		 * @param string $taxonomy (Required) Meta key as defined in WP TODO is this used
		 *
		 * @return void
		 */
		public static function addFormFields( $taxonomy ) {
			$classname = get_called_class();
			$classname::baseFormFieldNonce();
			foreach ( $classname::$meta_keys as $key => $value ) {
				$classname::addFormField( $key );
			}
		}

		/**
		 * Generate form fields for edit term diaglogue
		 *
		 * Creates the fields automatically based on the term_meta static field
		 *
		 * @param string $term (Required) Meta key as defined in WP
		 *
		 * @return void
		 */
		public static function editFormFields( $term ) {
			$classname = get_called_class();
			$classname::baseFormFieldNonce();
			foreach ( $classname::$meta_keys as $key => $value ) {
				$classname::editFormField( $term, $key );
			}
		}

		/**
		 * Generate a form field for add term diaglogue
		 *
		 * Creates a field automatically based on the term_meta static field
		 *
		 * @param string $key      (Required) meta_key as defined in meta_keys
		 * @param string $value    value of field
		 * @param string $label    label to display
		 * @param string $type     meta_type of field
		 * @param string $settings additional settings to pass to the display routine
		 *
		 * @return void
		 */
		public static function addFormField( $key, $value = '_AUTO', $label = '_AUTO', $type = '_AUTO', $settings = '_AUTO' ) {
			// TODO Work out how to promote this code to Generic
			$classname = get_called_class();
			if ( $value == '_AUTO' ) {
				// TODO A default would be great here
				$value = '';
			}
			if ( $label == '_AUTO' ) {
				$label = $classname::$meta_keys[ $key ]['label'];
			}
			if ( $type == '_AUTO' ) {
				$type = $classname::$meta_keys[ $key ]['type'];
			}
			if ( $settings == '_AUTO' && array_key_exists( 'settings', $classname::$meta_keys[ $key ] ) ) {
				  $settings = $classname::$meta_keys[ $key ]['settings'];
			}
			if ( $settings == null || $settings == '_AUTO' ) {
				$settings = [];
			}
			?>
			<div class="form-field term-group">
				<?php self::baseFormLabel( $key, $value, $label, $settings ); ?>
				<?php self::baseformField( $key, $type, $value, $label, $settings ); ?>
			</div>
			<?php
		}

		/**
		 * Generate a form field for edit term diaglogue
		 *
		 * Creates a field automatically based on the term_meta static field
		 *
		 * @param string $term     (Required) ID of term
		 * @param string $key      (Required) meta_key as defined in meta_keys
		 * @param string $value    value of field
		 * @param string $label    label to display
		 * @param string $type     meta_type of field
		 * @param string $settings additional settings to pass to the display routine
		 *
		 * @return void
		 */
		public static function editFormField( $term, $key, $value = '_AUTO', $label = '_AUTO', $type = '_AUTO', $settings = '_AUTO' ) {
			$classname = get_called_class();
			if ( $value == '_AUTO' ) {
				$obj   = new $classname( $term );
				$value = $obj->$key;
			}
			if ( $label == '_AUTO' ) {
				$label = $classname::$meta_keys[ $key ]['label'];
			}
			if ( $type == '_AUTO' ) {
				$type = $classname::$meta_keys[ $key ]['type'];
			}
			if ( $settings == '_AUTO' && array_key_exists( 'settings', $classname::$meta_keys[ $key ] ) ) {
				  $settings = $classname::$meta_keys[ $key ]['settings'];
			}
			if ( $settings == null || $settings == '_AUTO' ) {
				$settings = [];
			}
			?>
			<tr class="form-field term-group-wrap">
				<th scope="row">
					<?php self::baseFormLabel( $key, $value, $label, $settings ); ?>
				</th>
				<td>
					<?php self::baseFormField( $key, $type, $value, $label, $settings ); ?>
				</td>
			</tr>
			<?php
		}

		/**
		 * Save all the generated fields on a form
		 *
		 * @param string $term_id (Required) ID of term
		 * @param string $tt_id   (Required) What is this used for TODO
		 *
		 * @return void
		 */
		public static function saveTax( $term_id, $tt_id ) {
			// TODO - What does TT id do?
			$classname = get_called_class();
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! $classname::verifyNonce() ) {
				return;
			}

			$obj = new $classname( $term_id );

			foreach ( $classname::$meta_keys as $key => $value ) {
				$fieldkey = $classname . '_' . $key;
				switch ( $value['type'] ) {
					case 'meta_attrib_check':
						$obj->$key = isset( $_POST[ $fieldkey ] );
						break;
					case 'related_post':
						throw(new Exception( 'not implemented' ));
					break;
					case 'related_tax':
						throw(new Exception( 'not implemented' ));
					break;
					default:
						if ( isset( $_POST[ $fieldkey ] ) ) {
							$obj->$key = $_POST[ $fieldkey ];
						};
				}
			}
		}

		/**
		 * Generate a logo field on the form
		 *
		 * Uses WordPress media dialoges to add a media form to the tax
		 *
		 * @param string $fieldkey (Required) name & id of field (conventially CLASS_KEY)
		 * @param string $value    value of field
		 * @param string $label    label to display
		 * @param string $settings additional settings to pass to the display routine
		 *
		 * @return void
		 */
		public static function baseFormFieldLogo( $fieldkey, $value = '', $label = '', $settings = [] ) {
			global $content_width, $_wp_additional_image_sizes;

			$classname      = get_called_class();
			$removebuttonid = $classname . '_logo_remove';
			$uploadbuttonid = $classname . '_logo_upload';
			$logodivid      = $classname . '_logo_div';
			$fieldid        = $classname . '_logo';

			$old_content_width = $content_width;
			$content_width     = 254;
			if ( $value && get_post( $value ) ) {
				if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
					$thumbnail_html = wp_get_attachment_image( $value, array( $content_width, $content_width ) );
				} else {
					$thumbnail_html = wp_get_attachment_image( $value, 'post-thumbnail' );
				}
				if ( ! empty( $thumbnail_html ) ) {
					?>
					<div id="<?php echo esc_attr( $logodivid ); ?>">
						<?php echo $thumbnail_html; ?>
						<p class="hide-if-no-js">
							<a href="javascript:;"
							id="<?php echo esc_attr( $removebuttonid ); ?>" >
							<?php echo esc_html__( 'Remove listing image', 'tm' ); ?>
						</a>
					</p>
					<input type="hidden" id="<?php echo esc_attr( $fieldid ); ?>" name="<?php echo esc_attr( $fieldid ); ?>" value="<?php esc_attr( $value ); ?>"/>
				</div>
					<?php
				}
				$content_width = $old_content_width;
			} else {
				?>
			<div id="<?php echo esc_attr( $logodivid ); ?>">
				<img src="" style="width:<?php echo esc_attr( $content_width ); ?>px;height:auto;border:0;display:none;" />
				<p class="hide-if-no-js">
					<a title="<?php echo esc_attr__( 'Set listing image', 'tm' ); ?>"
						href="javascript:;"
						id="<?php echo esc_attr( $uploadbuttonid ); ?>"
						data-uploader_title="<?php echo esc_attr__( 'Choose an logo', 'tm' ); ?>"
						data-uploader_button_text="<?php echo esc_attr__( 'Set logo', 'tm' ); ?>" >
						<?php echo esc_html__( 'Set logo', 'tm' ); ?>
					</a>
				</p>
				<input type="hidden" id="<?php echo esc_attr( $fieldid ); ?>" name="<?php echo esc_attr( $fieldid ); ?>" value="" />
			</div>
				<?php
			}
		}

	}
endif;
