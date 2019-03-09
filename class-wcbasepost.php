<?php
/**
 * WCBasePost
 *
 * @category
 * @package  WordCider
 * @author   Martin Croker <oss@croker.ltd>
 * @license  Apache2
 * @link
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require_once 'class-wcbasegeneric.php';

if ( ! class_exists( 'WCBasePost' ) ) :
	/**
	 * WCBasePost
	 */
	abstract class WCBasePost extends WCBaseGeneric {

		/**
		 * $post_type
		 *
		 * @var string $post_type
		 */
		protected static $post_type;
		/**
		 * $id
		 *
		 * @var string[] $labels
		 */

		protected static $labels;
		/**
		 * $id
		 *
		 * @var string[] $args
		 */

		protected static $args = [];
		/**
		 * $id
		 *
		 * @var string[] $setting_keys
		 */

		protected static $setting_keys = [];
		/**
		 * $id
		 *
		 * @var string[] $wcargs
		 */

		protected static $wcargs = [];
		/**
		 * $id
		 *
		 * @var number $id
		 */

		private static $default_wcargs = array(
			'create_metadatabox' => true,
			'enqueue_scripts'    => true,
			'register_settings'  => true,
			'customise_list'     => true,
		);

		/**
		 * __construct
		 *
		 * @param number|WP_Post $post Instance of WP_Post or post_id. If not
		 *                             provided, will attempt to instantiate
		 *                             from post_id of current page.
		 *
		 * @throws Exception Unrecognised constructor argument.
		 */
		public function __construct( $post = 0 ) {
			$classname = get_called_class();
			if ( $post instanceof WP_Post ) {
				parent::__construct( $post->ID, $post );
			} elseif ( is_numeric( $post ) ) {
				$id = ( 0 === $post ) ? get_the_id() : $post;
				parent::__construct( $id );
			} else {
				throw(new Exception( 'Unrecognosed constructor for type ' + $classname ));
			}
		}

		/**
		 * Static initialiser - call after child class defined to register post_type
		 *
		 * @return void
		 */
		public static function init() {
			$classname = get_called_class();
			$wcargs    = array_replace( self::$default_wcargs, static::$wcargs );
			add_action( 'init', $classname . '::register_post_type' );
			if ( is_admin() ) {
				if ( $wcargs['create_metadatabox'] ) {
					add_action( 'add_meta_boxes', $classname . '::create_metadata_box' );
				}
				add_action( 'save_post', $classname . '::save_post' );
				if ( ! empty( static::$setting_keys ) && $wcargs['register_settings'] ) {
					add_action( 'admin_menu', $classname . '::create_settings_menu' );
					add_action( 'admin_init', $classname . '::register_settings' );
				}
				if ( $wcargs['enqueue_scripts'] ) {
					add_action( 'admin_enqueue_scripts', $classname . '::enqueue_admin_scripts' );
				}
				if ( $wcargs['customise_list'] ) {
					add_filter( 'manage_' . static::$post_type . '_posts_columns', $classname . '::manage_post_columns' );
					add_action( 'manage_' . static::$post_type . '_posts_custom_column', $classname . '::manage_posts_custom_column', 10, 2 );
					add_filter( 'manage_edit-' . static::$post_type . '_sortable_columns', $classname . '::sortable_columns' );
					add_action( 'restrict_manage_posts', $classname . '::restrict_manage_posts' );
					add_filter( 'parse_query', $classname . '::parse_query' );
				}
				add_action( 'admin_head', $classname . '::admin_head_wrapper' );
				add_action( 'admin_menu', $classname . '::admin_menu_wrapper' );
			}
			if ( $wcargs['enqueue_scripts'] ) {
				add_action( 'wp_enqueue_scripts', $classname . '::enqueue_scripts' );
			}
			add_action( 'init', $classname . '::add_rewrite_rule' );
			add_filter( 'post_type_link', $classname . '::post_type_link_wrapper' );
			add_filter( 'query_vars', $classname . '::add_query_vars' );
		}

		/**
		 * Adds rewrite rule - default does nothing but can be overridden
		 *
		 * @return void
		 */
		public static function add_rewrite_rule() {
		}

		/**
		 *  Filter applied to the permalink URL prior to being returned by the function get_post_permalink.
		 *
		 * @param string  $url  (Required) The post URL.
		 * @param WP_Post $post The post object.
		 *
		 * @return string post_link I don't know what this is TODO
		 */
		public static function post_type_link( $url, $post ) {
			return $url;
		}

		/**
		 * Wraps post_type_link function and only calls for this post_type
		 *
		 * Added into post_type_link filter
		 *
		 * @param string  $url  (Required) The post URL.
		 * @param WP_Post $post The post object.
		 *
		 * @return string post_link I don't know what this is TODO.
		 */
		public static function post_type_link_wrapper( $url, $post = null ) {
			$classname = get_called_class();
			if ( is_object( $post ) && $post->post_type === static::$post_type ) {
				$url = static::post_type_link( $url, $post );
			}
			return $url;
		}

		/**
		 * Adds query variables - default does nothing but can be overridden
		 *
		 * Acts as a filter with any new variables added to the passed array
		 *
		 * @param string[] $vars Baseline query variables.
		 *
		 * @return string[] Amended list of variables.
		 */
		public static function add_query_vars( $vars ) {
			return $vars;
		}

		/**
		 * Creates admin page headers.
		 *
		 * Called by admin_head_wrapper when on relevant admin pages for the post_type.
		 *
		 * @return void
		 */
		public static function admin_head() {
		}

		/**
		 * Wraps admin_head function calling it only for this post_type
		 *
		 * @return void
		 */
		public static function admin_head_wrapper() {
			if ( static::is_admin_screen() ) {
				static::base_form_field_nonce();
				static::admin_head();
			}
		}

		/**
		 * Returns true if on admin screen for post_type.
		 *
		 * @return Boolean true if on admin screen for post_type.
		 */
		public static function is_admin_screen() {
			$screen = get_current_screen();
			return ( static::$post_type === $screen->post_type );
		}

		/**
		 * Gets the type of the current post
		 *
		 * @return String Post type
		 */
		public static function is_current_posttype() {
			return ( static::http_get_param( 'post_type', 'post', false ) === static::$post_type );
		}

		/**
		 * Creates admin page menu.
		 *
		 * @param string $context (Requried) Empty context.
		 *
		 * @return void
		 */
		public static function admin_menu( $context ) {
		}

		/**
		 * Wraps admin_menu calling only if for this post_type.
		 *
		 * @param string $context (Requried) Empty context.
		 *
		 * @return void
		 */
		public static function admin_menu_wrapper( $context ) {
			if ( static::is_admin_screen() ) {
				static::admin_menu();
			}
		}

		/**
		 * Attached to manage_post_columns filter to adjust the columns that
		 * appear in post list admin view.
		 *
		 * WordPress: manage_{$post_type}_posts_columns is a filter applied to
		 * the columns shown when listing posts of a custom type.
		 *
		 * @param string[] $defaults (Requried) An array of column name ⇒ label.
		 *                           The name is passed to functions to identify
		 *                           the column. The label is shown as the column
		 *                           header.
		 *
		 * @return string[] Updated orray f table head columns.
		 */
		public static function manage_post_columns( $defaults ) {
			$columns = static::$meta_keys;
			uasort( $columns, array( 'WCBasePost', 'post_columns_sort' ) );

			foreach ( array_keys( $columns ) as $key ) {
				$fieldmeta = static::$meta_keys[ $key ];
				if ( array_key_exists( 'postlist', $fieldmeta ) ) {
					$columntitle = $key;
					if ( array_key_exists( 'title', $fieldmeta['postlist'] ) ) {
						$columntitle = $fieldmeta['postlist']['title'];
					} else {
						if ( array_key_exists( 'label', $fieldmeta ) ) {
							$columntitle = $fieldmeta['label'];
						}
					}
					$defaults[ static::get_elem_name( $key ) ] = $columntitle;
				}
			}
			return $defaults;
		}

		/**
		 * Sorts table columns based on meta_key. Removes fields not included in header
		 *
		 * The sort order is based on the index key within the postlist array of
		 * $meta_keys. Items are sorted in order:
		 *   lowest $index
		 *   Mon-numeric Indexes
		 *   Null/missing index field
		 *   Null/missing postlist array
		 *
		 * @param object[] $a (Requried) meta_field array to be sorted a.
		 * @param object[] $b (Requried) meta_field array to be sorted b.
		 *
		 * @return numeric 1,0,-1 if the first argument is <, =, or > than the second argument.
		 */
		public static function post_columns_sort( $a, $b ) {
			if ( is_array( $a ) && array_key_exists( 'postlist', $a ) ) {
				if ( is_array( $a['postlist'] ) && array_key_exists( 'index', $a['postlist'] ) ) {
					$indexa = $a['postlist']['index'];
					if ( ! is_numeric( $indexa ) ) {
						$indexa = 999997;
					}
				} else {
					$indexa = 999998;
				}
			} else {
				$indexa = 999999;
			}
			if ( is_array( $b ) && array_key_exists( 'postlist', $b ) ) {
				if ( is_array( $b['postlist'] ) && array_key_exists( 'index', $b['postlist'] ) ) {
					$indexb = $b['postlist']['index'];
					if ( ! is_numeric( $indexb ) ) {
						$indexb = 999997;
					}
				} else {
					$indexb = 999998;
				}
			} else {
				$indexb = 999999;
			}
			if ( $indexa === $indexb ) {
				return 0;
			} elseif ( $indexa > $indexb ) {
				return 1;
			} else {
				return -1;
			}
		}

		/**
		 * Echos the content field for each item in a post list.
		 *
		 * Attached to the manage_post_custom_column filter.
		 *
		 * Default behavior is to respond for all fields - this function can be
		 * supressed for a field using postlist value of 'content' = false in The
		 * meta_key definition for the field.
		 *
		 * @param string $column_name (Requried) Name of column (provided by WP).
		 * @param string $post_id     (Requried) ID of post for which value
		 *                            required (provided by WP).
		 *
		 * @return void
		 *
		 * @throws Exception Field type not implemented.
		 */
		public static function manage_posts_custom_column( $column_name, $post_id ) {
			$classname = get_called_class();
			$obj       = new $classname( $post_id );
			foreach ( static::$meta_keys as $key => $fieldmeta ) {
				$showcontent = true;
				if ( array_key_exists( 'postlist', $fieldmeta )
				&& is_array( $fieldmeta['postlist'] )
				&& array_key_exists( 'content', $fieldmeta['postlist'] ) ) {
					$showcontent = $fieldmeta['postlist']['content'];
				}
				if ( $showcontent && static::get_elem_name( $key ) === $column_name ) {
					$obj->echo_html( $key );
				}
			}
		}

		/**
		 * Returns a list of columsn which are sortable based on meta_key
		 *
		 * @param string[] $columns (Requried) Array of default sortable columns.
		 *
		 * @return string[] Array containing sortable columns.
		 */
		public static function sortable_columns( $columns ) {
			$classname = get_called_class();
			foreach ( static::$meta_keys as $key => $fieldmeta ) {
				if ( array_key_exists( 'postlist', $fieldmeta ) ) {
					if ( array_key_exists( 'sortable', $fieldmeta['postlist'] )
					&& true === $fieldmeta['postlist']['sortable'] ) {
						$columns[ $classname . '_' . $key ] = $classname . '_' . $key;
					}
				}
			};
			return $columns;
		}

		/**
		 * Creates the dropdown filters in the post_list view
		 *
		 * @return void
		 *
		 * @throws Exception Not implemented.
		 */
		public static function restrict_manage_posts() {
			$classname = get_called_class();
			if ( static::is_current_posttype() ) {
				foreach ( static::$meta_keys as $key => $fieldmeta ) {
					if ( array_key_exists( 'postlist', $fieldmeta ) ) {
						if ( array_key_exists( 'filter', $fieldmeta['postlist'] )
						&& true === $fieldmeta['postlist']['filter'] ) {
							$fieldkey = $classname . '_' . $key;
							?>
							<select name="<?php echo esc_attr( $fieldkey ); ?>">
								<option value=""><?php echo esc_html( 'Filter by ' . $key . ':' ); ?></option>
								<?php
								$current_v = static::http_get_param( $fieldkey );
								// TODO - Promote creation of a select htmlthis to genericbase.
								switch ( $fieldmeta['type'] ) {
									case 'related_post':
										foreach ( $fieldmeta['classname']::get_all() as $value ) {
											printf(
												'<option value="%s"%s>%s</option>',
												esc_attr( $value->ID ),
												selected( $value->ID, $current_v, false ),
												esc_html( $value->title )
											);
										};
										break;

									case 'related_tax':
										foreach ( $fieldmeta['classname']::get_all() as $value ) {
											printf(
												'<option value="%s"%s>%s</option>',
												esc_attr( $value->ID ),
												selected( $value->ID, $current_v, false ),
												esc_html( $value->name )
											);
										};
										break;

									default:
										throw( new Exception( 'Not implemented' ) );
								}
								?>
							</select>
							<?php
						}
					}
				}
			}
		}

		/**
		 * Processes the filters provided in list_view to add where clauses
		 *
		 * @param WP_Query $query (Required) Default php query object.
		 */
		public static function parse_query( $query ) {
			global $pagenow;
			if ( static::is_current_posttype() && 'edit.php' === $pagenow && $query->is_main_query() ) {
				$filters = [];
				foreach ( static::$meta_keys as $key => $fieldmeta ) {
					if ( array_key_exists( 'postlist', $fieldmeta ) ) {
						if ( array_key_exists( 'filter', $fieldmeta['postlist'] ) ) {
							$fieldkey = static::get_elem_name( $key );
							if ( static::http_get_param( $fieldkey ) !== '' ) {
								$filters[ $key ] = static::http_get_param( $fieldkey );
							}
						}
					}
				}

				$queryargs = static::create_filter_query( $filters );
				if ( array_key_exists( 'tax_query', $queryargs ) ) {
					$query->query_vars['tax_query'] = $queryargs['tax_query']; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				}
				if ( array_key_exists( 'meta_query', $queryargs ) ) {
					$query->query_vars['meta_query'] = $queryargs['meta_query']; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				}
			}
		}

		/**
		 * Enques default scripts based on classname from plugin dir
		 *
		 * By default looks for CLASSNAME.js and CLASSNAME.css in
		 * /assets/js and /assets/css respectively.
		 *
		 * @return void
		 */
		public static function enqueue_scripts() {
			$classname = get_called_class();
			if ( get_post_type() === static::$post_type ) {
				parent::enqueue_script_helper( $classname . '-script', $classname . '.js' );
				parent::enqueue_style_helper( $classname . '-css', $classname . '.css' );
			}
		}

		/**
		 * Enques default admin scripts based on classname from plugin dir
		 *
		 * By default looks for CLASSNAME-admin.js and CLASSNAME-admin.css in
		 * /assets/js and /assets/css respectively.
		 *
		 * @param string $hook_suffix (Required) php file at end of URL provided
		 *                            by WordPress.
		 *
		 * @return void
		 */
		public static function enqueue_admin_scripts( $hook_suffix ) {
			$classname = get_called_class();
			if ( in_array( $hook_suffix, array( 'edit.php', 'post.php', 'post-new.php' ), true ) ) {
				$screen = get_current_screen();
				if ( is_object( $screen ) && static::$post_type === $screen->post_type ) {
					parent::enqueue_script_helper( $classname . '-admin-script', $classname . '-admin.js' );
					parent::enqueue_style_helper( $classname . '-admin-css', $classname . '-admin.css' );
				}
			}
		}

		/**
		 * Returns the slug on the post_type
		 *
		 * @return string post_slug
		 */
		public static function get_slug() {
			$slug = get_theme_mod( static::$post_type . '_permalink' );
			if ( empty( $slug ) ) {
				$slug = ( array_key_exists( 'slug', static::$labels ) ) ? static::$labels['slug'] : static::get_pluralname();
			}
			return strtolower( $slug );
		}

		/**
		 * Registers the post type in WordPress
		 *
		 * @return (WP_Post_Type|WP_Error) The registered post type object, or an error object.
		 */
		public static function register_post_type() {
			if ( ! post_type_exists( static::$post_type ) ) {
				$singular_name = static::$labels['singular_name'];
				$plural_name   = static::get_plural_name();

				$default_labels = array(
					'name'               => esc_attr( $plural_name ),
					'singular_name'      => esc_attr( $singular_name ),
					'menu_name'          => esc_attr( $plural_name ),
					'parent_item_colon'  => esc_attr( 'Parent ' . $singular_name ),
					'all_items'          => esc_attr( 'All ' . $plural_name ),
					'view_item'          => esc_attr( 'View ' . $singular_name ),
					'add_new_item'       => esc_attr( 'Add New ' . $singular_name ),
					'add_new'            => esc_attr( 'Add New' ),
					'edit_item'          => esc_attr( 'Edit ' . $singular_name ),
					'update_item'        => esc_attr( 'Update ' . $singular_name ),
					'search_items'       => esc_attr( 'Search ' . $singular_name ),
					'not_found'          => esc_attr( 'Not Found' ),
					'not_found_in_trash' => esc_attr( 'Not found in Trash' ),
				);
				$default_labels = array_replace( $default_labels, static::$labels );

				$slug = static::get_slug();

				$default_args = array(
					'label'               => esc_attr( $plural_name ),
					'description'         => esc_attr( $plural_name . ' details' ),
					'labels'              => $default_labels,
					'supports'            => array( 'title', 'editor', 'revisions' ),
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'show_in_nav_menus'   => true,
					'show_in_admin_bar'   => true,
					'menu_position'       => 5,
					'can_export'          => true,
					'has_archive'         => false,
					'exclude_from_search' => false,
					'publicly_queryable'  => true,
					'rewrite'             => array(
						'slug'       => $slug,
						'with_front' => false,
					),
					'capability_type'     => 'post',
				);
				$default_args = array_replace( $default_args, static::$args );
				$result       = register_post_type( static::$post_type, $default_args );
				return $result;
			}
		}

		/**
		 * Creates the setting menu
		 *
		 * TODO When?
		 *
		 * @return void
		 */
		public static function create_settings_menu() {
			global $submenu;
			$classname = get_called_class();
			add_submenu_page(
				'edit.php?post_type=' . static::$post_type,
				static::get_plural_name() . ' Settings',
				static::get_plural_name() . ' Settings',
				'administrator',
				$classname . '::settings_page',
				plugins_url( '/images/icon.png', __FILE__ )
			);
		}

		/**
		 * Registers any settings specified in CLASSNAME::$setting_keys
		 *
		 * @return void
		 */
		public static function register_settings() {
			$classname = get_called_class();
			foreach ( static::$setting_keys as $key => $value ) {
				$args = array_replace( [], $value['args'] );
				register_setting( static::$post_type . '-settings-group', $classname . '-' . $key, $args );
			}
		}

		/**
		 * Returns an options for the class from WP Options tabble
		 *
		 * @param string $key Key.
		 *
		 * @return string value of option.
		 */
		public static function get_option( $key ) {
			$classname = get_called_class();
			return get_option( $classname . '-' . $key );
		}

		/**
		 * Creates settings page with fields for any registered settings
		 *
		 * @return void
		 */
		public static function settings_page() {
			$classname = get_called_class();
			?>
			<div class="wrap">
				<h1><?php echo esc_html( static::$labels['singular_name'] ); ?> Settings</h1>

				<form method="post" action="options.php">
					<?php settings_fields( static::$post_type . '-settings-group' ); ?>
					<?php do_settings_sections( static::$post_type . '-settings-group' ); ?>
					<table class="form-table">
						<?php foreach ( static::$setting_keys as $key => $value ) { ?>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( $value['label'] ); ?></th>
								<td><input type="text" name="<?php echo esc_attr( $classname . '-' . $key ); ?>" value="<?php echo esc_attr( get_option( $classname . '-' . $key ) ); ?>" /></td>
							</tr>
						<?php } ?>
					</table>
					<?php submit_button(); ?>
				</form>
			</div>
			<?php
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
		 * Creates default page metadatabox
		 *
		 * Added to WordPress add_meta_boxes hook by init()
		 *
		 * @return void
		 */
		public static function create_metadata_box() {
			if ( is_admin() ) {
				$classname   = get_called_class();
				$plural_name = static::get_plural_name();
				add_meta_box(
					static::get_elem_name( 'default_metabox' ),
					'Metadata',
					$classname . '::inner_custom_box',
					static::$post_type,
					'normal',
					'default'
				);
			}
		}

		/**
		 * Inner custom box for detault page metadatabox.
		 *
		 * Provided as an argument to add_meta_box call in
		 * self::CreateMetadatabox().
		 *
		 * @param WP_Post $post (Required) post object provided by WP.
		 *
		 * @return void
		 */
		public static function inner_custom_box( $post ) {
			foreach ( static::$meta_keys as $key => $fieldmeta ) {
				$fielddisplay = true;
				if ( array_key_exists( 'display', $fieldmeta ) ) {
					$fielddisplay = $fieldmeta['display'];
				}
				if ( $fielddisplay ) {
					static::base_form_field( $post, $key );
				}
			}
		}

		/**
		 * Displays a HTML form field based on details of meta_keys
		 *
		 * @param WCBasePost $post     (Required) Post.
		 * @param string     $key      (Required) meta_keyfor field.
		 * @param string     $value    Field value to display.
		 * @param string     $label    Textual label to display.
		 * @param string     $type     Meta_type of post.
		 * @param string[]   $settings Additional settings to pass to display.
		 *
		 * @return void
		 */
		public static function form_field( $post, $key, $value = '_AUTO', $label = '_AUTO', $type = '_AUTO', $settings = '_AUTO' ) {
			$classname = get_called_class();
			if ( '_AUTO' === $value ) {
				$obj   = new $classname( $post );
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
			self::base_form_label( $key, $value, $label, $settings );
			switch ( $type ) {
				case 'related_post':
					self::base_form_field_relatedpost( $fieldkey, $value, $label, $settings );
					break;
				case 'related_posts':
					self::base_form_field_relatedposts( $fieldkey, $value, $label, $settings );
					break;
				case 'related_tax':
					self::base_form_field_relatedtax( $fieldkey, $value, $label, $settings );
					break;
				default:
					parent::base_form_field( $key, $type, $value, $label, $settings );
			}
		}

		/**
		 * Throw an exception if somebody tries to create a relatedPosts field
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
		 */
		public static function base_form_field_relatedposts( $fieldkey, $value = '', $label = '', $settings = [] ) {
			$classname           = get_called_class();
			$relatedclass        = $settings['classname'];
			$allposts            = $relatedclass::getAll();
			$settings['options'] = [];
			foreach ( $allposts as $relatedpost ) {
				$settings['options'][ $relatedpost->ID ] = $relatedpost->title;
			}
			static::base_form_field_Select( $fieldkey, $value, $label, $settings );
		}

		/**
		 * Throw an exception if somebody tries to create a relatedPost field
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
		public static function base_form_field_relatedpost( $fieldkey, $value = '', $label = '', $settings = [] ) {
			throw( new Exception( 'Not implemented ' . $fieldkey ) ); // May be implemented in child.
		}


		/**
		 * Throw an exception if somebody tries to create a relatedtax field
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
		public static function base_form_field_relatedtax( $fieldkey, $value = '', $label = '', $settings = [] ) {
			throw( new Exception( 'Not implemented ' . $fieldkey ) ); // May be implemented in child.
		}

		/**
		 * Saves all compatable fields in the post from $_POST
		 *
		 * Added to save_fields WP hook by init
		 *
		 * @param number $post_id (Required) Post_id.
		 *
		 * @return void
		 */
		public static function save_post( $post_id ) {
			$post_type = get_post_type( $post_id );
			if ( static::$post_type !== $post_type ) {
				return;
			}

			// verify if this is an auto save routine.
			// If it is our form has not been submitted, so we dont want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// verify this came from the our screen and with proper authorization,
			// because save_fields can be triggered at other times.
			if ( static::verify_nonce() ) {
				$obj = new $classname( $post_id );
				foreach ( static::$meta_keys as $key => $value ) {
					$fieldkey = $classname . '_' . $key;
					switch ( $value['type'] ) {
						case 'meta_attrib_check':
							$obj->$key = isset( $_POST[ $fieldkey ] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
							break;

						case 'related_post':
							if ( isset( $_POST[ $fieldkey ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
								$obj->{$key . '_id'} = sanitize_key( wp_unslash( $_POST[ $fieldkey ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
							};
							break;

						case 'related_tax':
							if ( isset( $_POST[ $fieldkey ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
								$obj->attach_term( new $value['classname']( sanitize_key( wp_unslash( $_POST[ $fieldkey ] ) ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
							};
							break;

						default:
							if ( isset( $_POST[ $fieldkey ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
								$obj->$key = sanitize_text_field( wp_unslash( $_POST[ $fieldkey ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
							};
					}
				}
			}
		}

		/**
		 * Converts a WP_Post to a WCBasePost object
		 *
		 * @param WP_Post[] $wpposts (Required) Array of WP_Posts to convert.
		 *
		 * @return WCBasePost[] Array of converted objects.
		 */
		public static function convert_WPPost_to_TMPost( $wpposts ) {
			$classname = get_called_class();
			$postobjs  = [];
			foreach ( $wpposts as $post ) {
				$postobjs[] = new $classname( $post );
			}
			return $postobjs;
		}

		/**
		 * Return all items matching $filters
		 *
		 * @param String[] $filters Array of filters to apply.
		 *
		 * @return WCBasePost[] Array of WCBasePost objects matching query
		 */
		public static function get_all( $filters = [] ) {
			$queryargs = static::create_filter_query( $filters );
			$posts     = get_posts( $queryargs );
			return static::convert_WPPost_to_TMPost( $posts );
		}

		/**
		 * Create a filter query for WP_Query
		 *
		 * @param String[] $filters Array of filters to apply.
		 *
		 * @return String[] Returns arguments for WP_Query
		 */
		public static function create_filter_query( $filters = [] ) {
			$classname  = get_called_class();
			$queryargs  = array(
				'numberposts' => -1,
				'post_type'   => static::$post_type,
			);
			$meta_query = [];
			$tax_query  = [];

			foreach ( $filters as $filter => $value ) {
				if ( array_key_exists( $filter, static::$meta_keys ) ) {
					$fieldmeta = static::$meta_keys[ $filter ];
					switch ( $fieldmeta['type'] ) {
						case 'related_post':
							$meta_query[] = array(
								'key'     => $fieldmeta['meta_key'],
								'value'   => $value,
								'compare' => '=',
							);
							break;
						case 'related_tax':
							$tax_query[] = array(
								'taxonomy' => $fieldmeta['classname']::$taxonomy,                      // taxonomy name.
								'field'    => 'term_id',                                               // term_id, slug or name.
								'terms'    => $value, // term id, term slug or term name.
							);
							break;
						default:
							$meta_query[] = array(
								'key'     => $fieldmeta['meta_key'],
								'value'   => $value,
								'compare' => '=',
							);
							break;
					}
				}
			}

			switch ( count( $meta_query ) ) {
				case 0:
					break; // Do nothing.
				case 1:
					$queryargs['meta_query'] = $meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					break; // Nest n array.
				default:
					$queryargs['meta_query'] = array_merge( array( 'relation' => 'AND' ), $meta_query ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					break;
			}

			switch ( count( $tax_query ) ) {
				case 0:
					break; // Do nothing.
				case 1:
					$queryargs['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					break; // Nest n array.
				default:
					$queryargs['tax_query'] = array_merge( array( 'relation' => 'AND' ), $tax_query ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					break;
			}

			return $queryargs;
		}

		/**
		 * Get a WCBasePost by it's slug
		 *
		 * @param string $slug The slug to search for.
		 *
		 * @return WCBasePost|null Matching post or null if not found.
		 */
		public static function get_by_slug( $slug ) {
			$classname = get_called_class();
			$args      = array(
				'name'        => $slug,
				'post_type'   => static::$post_type,
				'post_status' => 'publish',
				'numberposts' => 1,
			);
			$posts     = get_posts( $args );
			if ( $posts ) {
				return new $classname( $posts[0]->ID );
			} else {
				return null;
			}
		}

		/**
		 * Create a new post
		 *
		 * @param string $title Title of the new post_type.
		 *
		 * @return WCBasePost Newly created post object.
		 */
		public static function create_post( $title ) {
			$classname = get_called_class();
			$post_id   = wp_insert_post(
				array(
					'post_title'  => $title,
					'post_status' => 'publish',
					'post_type'   => static::$post_type,
				)
			);
			$classname = get_called_class();
			return new $classname( $post_id );
		}

		/**
		 * Returns all posts of post_type related to a taxonomy term
		 *
		 * @param TMBaseTax $taxonomy The taxonomy to search.
		 * @param number    $term_id  The term id search for.
		 *
		 * @return WCBasePost[] Array of all matching posts.
		 */
		public static function get_related_to_tax( $taxonomy, $term_id ) {
			$posts = get_posts(
				array(
					'numberposts' => -1,
					'post_type'   => static::$post_type,
					'tax_query'   => array(  // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						array(
							'taxonomy' => $taxonomy,
							'field'    => 'term_id',
							'terms'    => $term_id,
						),
					),
				)
			);
			return static::convert_WPPost_to_TMPost( $posts );
		}

		/**
		 * Returns all posts of post_type with a particular meta_value
		 *
		 * @param string $meta_key   The meta_key to search.
		 * @param string $meta_value The value to search for.
		 *
		 * @return WCBasePost[] Array of all matching posts.
		 */
		public static function get_with_meta_value( $meta_key, $meta_value ) {
			$classname = get_called_class();
			$posts     = get_posts(
				array(
					'numberposts' => -1,
					'post_type'   => static::$post_type,
					'post_status' => 'publish',
					'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => $meta_key,
							'value'   => $meta_value,
							'compare' => '=',
						),
					),
				)
			);
			return static::convert_WPPost_to_TMPost( $posts );
		}

		/**
		 * Default getter which persists meta_key to wp meta_attributes
		 *
		 * @param string $key The meta_key to search.
		 *
		 * @return object Value of type based on mete_type.
		 */
		public function __get( $key ) {
			$classname = get_called_class();
			$stemkey   = self::get_stemkey( $key );
			if ( array_key_exists( $stemkey, static::$meta_keys ) ) {
				$conf = static::$meta_keys[ $stemkey ];
				switch ( $conf['type'] ) {
					case 'related_post':
						return $this->get_related_post( $key, $conf['meta_key'], $conf['classname'] );
					case 'related_posts':
						return $this->get_related_posts( $key, $conf['meta_key'], $conf['classname'] );
					case 'related_tax':
						return $this->get_related_tax( $key, $conf['meta_key'], $conf['classname'], $conf['single'] );
					default:
						return parent::__get( $key );
				}
			} else {
				switch ( $key ) {
					case 'title':
						return $this->post->post_title;
					case 'author':
						return $this->post->post_author;
					case 'slug':
						return $this->post->post_name;
					case 'post':
						if ( is_null( $this->obj ) ) {
							$this->obj = get_post( $this->id, self::$post_type );
						}
						return $this->obj;
					default:
						return parent::__get( $key );
				}
			}
		}

		/**
		 * Default setter which persists meta_key to wp meta_attributes
		 *
		 * @param string $key   The meta_key to search.
		 * @param object $value Value to be set.
		 *
		 * @return object value
		 *
		 * @throws Exception Not implemented.
		 */
		public function __set( $key, $value ) {
			$classname = get_called_class();
			$stemkey   = self::get_stemkey( $key );
			if ( array_key_exists( $stemkey, static::$meta_keys ) ) {
				$conf = static::$meta_keys[ $stemkey ];
				switch ( $conf['type'] ) {
					case 'related_post':
						$this->update_related_post( $key, $conf['meta_key'], $value );
						break;
					case 'related_posts':
						throw(new Exception( 'not implmented' ));
					default:
						return parent::__set( $key, $value );
				}
			} else {
				switch ( $key ) {
					case 'title':
						$this->post->post_title = $value;
						wp_update_post( $this->post );
						break;

					case 'author':
						$this->post->post_author = $value;
						wp_update_post( $this->post );
						break;

					case 'slug':
						$this->post->post_name = $value;
						wp_update_post( $this->post );
						break;

					default:
						parent::__set( $key, $value );
				}
			}
		}

		/**
		 * Implements WordPress meta update
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
			update_post_meta( $this->id, $meta_key, $value );
		}

		/**
		 * Implements WordPress meta retrieve
		 *
		 * This enables TMBaseGeneric to handle retrieve for all meta_data types by
		 * taking care of the actual term/post interface as required.
		 *
		 * @param string $meta_key (Required) Meta key as defined in WP.
		 *
		 * @return undefined meta_value stored against WP_Term.
		 */
		protected function get_meta_value( $meta_key ) {
			return get_post_meta( $this->id, $meta_key, true );
		}

		/**
		 * Updates the related post for a related_post field
		 *
		 * @param string $key      (Required) meta_key index.
		 * @param string $meta_key (Required) WP meta_key.
		 * @param string $value    (Required) ID of post object to relate TODO.
		 *
		 * @return void
		 *
		 * @throws Exception Related posts are updated using _ID.
		 */
		protected function update_related_post( $key, $meta_key, $value ) {
			if ( '_id' === substr( $key, -3 ) ) { // Only do _id updates.
				$objkey = substr( $key, 0, strlen( $key ) - 3 );
				$idkey  = $key;
				update_post_meta( $this->id, $meta_key, $value );
				$this->cache[ $idkey ]  = $value;
				$this->cache[ $objkey ] = null;
			} else {
				throw(new Exception( $meta_key . ' can only be updated when suffixed _id' ));
			}
		}

		/**
		 * Get the post related based on meta_attrib type related_post
		 *
		 * @param string $key       (Required) meta_key index.
		 * @param string $meta_key  (Required) WP meta_key.
		 * @param string $postclass (Required) Classname of post to return.
		 *
		 * @return WCBasePost Related post object.
		 */
		protected function get_related_post( $key, $meta_key, $postclass ) {
			if ( '_id' === substr( $meta_key, -3 ) ) {
				$objkey = substr( $key, 0, strlen( $key ) - 3 );
			} else {
				$objkey = $key;
			}
			$idkey = $objkey . '_id';
			if ( ! array_key_exists( $idkey, $this->cache ) ) {
				$this->cache[ $idkey ] = get_post_meta( $this->id, $meta_key, true );
			}
			if ( '_id' === substr( $key, -3 ) ) { // If what was asked for was the _id.
				return $this->cache[ $idkey ];
			} else { // The object was asked for.

				if ( is_null( $this->cache[ $idkey ] ) || empty( $this->cache[ $idkey ] ) ) {
					$this->cache[ $objkey ] = null;
				} else {
					$this->cache[ $objkey ] = new $postclass( $this->cache[ $idkey ] );
				}
				return $this->cache[ $objkey ];
			}
		}

		/**
		 * Get all related posts (related_posts meta_type)
		 *
		 * @param string $key       (Required) meta_key index.
		 * @param string $meta_key  (Required) WP meta_key.
		 * @param string $postclass (Required) Classname of post to return.
		 *
		 * @return WCBasePost[] Array of related post object.
		 */
		protected function get_related_posts( $key, $meta_key, $postclass ) {
			return $postclass::get_with_meta_value( $meta_key, $this->id );
		}

		/**
		 * TODO
		 *
		 * @param string $key      (Required) meta_key index.
		 * @param string $meta_key (Required) WP meta_key.
		 * @param string $taxclass (Required) Classname of tax to return.
		 * @param bool   $single   Should only a single term be returned?.
		 *
		 * @return TMBaseTax|number Tf called with _id the term_id otherwise
		 *                          the term object is returned.
		 */
		protected function get_related_tax( $key, $meta_key, $taxclass, $single = true ) {
			$terms = wp_get_object_terms( $this->id, $taxclass::$taxonomy );
			if ( $single ) {
				if ( count( $terms ) > 0 ) {
					if ( '_id' === substr( $key, -3 ) ) { // If what was asked for was the _id.
						return $terms[0]->term_id;
					} else {
						return new $taxclass( $terms[0] );
					}
				} else {
					return null;
				}
			} else {
				$termobjs = [];
				foreach ( $terms as $term ) {
					$termobjs[] = new $taxclass( $term );
				}
				return $termobjs;
			}
		}

		/**
		 * Attach a taxomony term object to the post
		 *
		 * @param TMBaseTax $termobj (Required) The term to attach.
		 *
		 * @return object result Not sure what set_object_terms returns TODO.
		 *
		 * @throws Exception Passed termobj must inherit from TMBaseTax.
		 */
		public function attach_term( $termobj ) {
			$termclass = get_class( $termobj );
			if ( ! ( $termobj instanceof TMBaseTax ) ) {
				throw(new Exception( 'Object ' . $termclass . ' is not a child of TMBaseTax' ));
			}
			// Add the term to the post.
			return wp_set_object_terms( $this->id, $termobj->slug, $termclass::$taxonomy, false );
		}

		/**
		 * Lookup a term by it's slug and attach it to the post
		 *
		 * @param TMBaseTax $termclass (Required) The class for the term
		 *                             taxonomy to attach.
		 * @param string    $term_slug (Required) The term slug to search for
		 *                             and attach.
		 *
		 * @return void
		 */
		public function attach_term_by_slug( $termclass, $term_slug ) {
			$term = $termclass::get_by_slug( $term_slug );
			if ( ! $term ) {
				$term = $termclass::insert_term( $term_slug );
			}
			$this->attach_term( $term );
		}
	}
endif;
?>
