<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once('TMBaseGeneric.php');

if ( ! class_exists('TMBasePost')):
  class TMBasePost extends TMBaseGeneric {
    protected static $post_type;
    protected static $labels;
    protected static $args = [];
    protected static $meta_keys = [];
    protected static $setting_keys = [];
    private $_post_type;
    protected $cache = [];

  // ==================================================
    function __construct($post = 0) {
      $classname = get_called_class();
      if ($post instanceof WP_Post) {
        parent::__construct($post->ID, $post);
      }
      else if ( is_numeric($post) ) {
        $id = ( $post == 0 ) ? get_the_id() : $post;
        parent::__construct($id);
      }
      else {
        throw(new Exception('Unrecognosed constructor for type ' + $classname));
      }
      $this->_post_type = $classname::$post_type;
    }

  // ==================================================
    public static function init() {
      $classname = get_called_class();
      add_action( 'init', $classname . '::register_post_type' );
      if ( is_admin() ) {
        add_action( 'add_meta_boxes', $classname . '::create_metadatabox' );
        add_action( 'save_post', $classname . '::save_metadatabox' );
        if ( ! empty( $classname::$setting_keys ) ) {
          add_action('admin_menu', $classname . '::create_settings_menu');
          add_action('admin_init', $classname . '::register_settings' );
        }
        add_action( 'admin_enqueue_scripts',  $classname . '::enqueue_adminscripts');
      }
      add_action( 'wp_enqueue_scripts',  $classname . '::enqueue_scripts');
      if ( !empty($classname::$path_params) ) {
        // add_action('init', $classname . '::rewriteurl');
        // add_filter( 'post_type_link', $classname . '::permalinks', 1, 2 );
      }
    }

  // ==================================================
    public static function enqueue_scripts() {
      $classname = get_called_class();
      if ( $classname::$post_type == get_post_type() ) {
        $basefilename = (new ReflectionClass(static::class))->getFileName();
        $plugin_dir = plugin_dir_path($basefilename);
        $plugin_url = plugin_dir_url($basefilename);
        if (file_exists($plugin_dir . '/assets/js/' . $classname . '.js')) {
          wp_enqueue_script( $classname . '-script', $plugin_url . '/assets/js/' . $classname . '.js', array('jquery'), 'v4.0.0', false );
          wp_localize_script( $classname . '-script', 'tmphpobj', array(
            'ajax_url'  => admin_url( 'admin-ajax.php' ),
            'post_id'   => get_the_id(),
            'post_type' => $classname::$post_type,
            'classname' => $classname
          ) );
        }
        if (file_exists($plugin_dir . '/assets/css/' . $classname . '.css')) {
          wp_enqueue_style( $classname . '-css', $plugin_url . '/assets/css/'. $classname . '.css', array(), 'v4.0.0');
        }
      }
    }

  // ==================================================
    public static function enqueue_adminscripts( $hook_suffix ){
      $classname = get_called_class();
      if( in_array($hook_suffix, array('post.php', 'post-new.php') ) ){
        $screen = get_current_screen();
        if( is_object( $screen ) && $classname::$post_type == $screen->post_type ){
          $basefilename = (new ReflectionClass(static::class))->getFileName();
          $plugin_dir = plugin_dir_path($basefilename);
          $plugin_url = plugin_dir_url($basefilename);
          if (file_exists($plugin_dir . '/assets/js/' . $classname . '-admin.js')) {
            wp_enqueue_script( $classname . '-admin-script', $plugin_url . '/assets/js/' . $classname . '-admin.js', array('jquery'), 'v4.0.0', false );
            wp_localize_script( $classname . '-admin-script', 'tmphpobj', array(
              'ajax_url'  => admin_url( 'admin-ajax.php' ),
              'post_id'   => get_the_id(),
              'post_type' => $classname::$post_type,
              'classname' => $classname
            ) );
          }
          if (file_exists($plugin_dir . '/assets/css/' . $classname . '-admin.css')) {
            wp_enqueue_style( $classname . '-admin-css', $plugin_url . '/assets/css/'. $classname . '-admin.css', array(), 'v4.0.0');
          }
        }
      }
    }

  // ==================================================
  public static function get_slug() {
    $classname = get_called_class();
    $slug = get_theme_mod( $classname::$post_type . '_permalink' );
    if ( empty($slug) ) {
      $slug = ( array_key_exists( 'slug' , $classname::$labels ) ) ? $classname::$labels['slug'] : $classname::get_pluralname();
    }
    return strtolower($slug);
  }


  // ==================================================
    public static function register_post_type() {
      $classname = get_called_class();
      if (! post_type_exists($classname::$post_type)) {
        $singular_name = $classname::$labels['singular_name'];
        $plural_name = $classname::get_pluralname();

        $default_labels = array(
          'name'                => _x( $plural_name, 'Post Type General Name', 'tm' ),
          'singular_name'       => _x( $singular_name, 'Post Type Singular Name', 'tm' ),
          'menu_name'           => __( $plural_name, 'tm' ),
          'parent_item_colon'   => __( 'Parent ' . $singular_name , 'tm' ),
          'all_items'           => __( 'All ' . $plural_name, 'tm' ),
          'view_item'           => __( 'View ' .  $singular_name, 'tm' ),
          'add_new_item'        => __( 'Add New ' . $singular_name, 'tm' ),
          'add_new'             => __( 'Add New', 'tm' ),
          'edit_item'           => __( 'Edit ' . $singular_name, 'tm' ),
          'update_item'         => __( 'Update ' . $singular_name, 'tm' ),
          'search_items'        => __( 'Search ' . $singular_name, 'tm' ),
          'not_found'           => __( 'Not Found', 'tm' ),
          'not_found_in_trash'  => __( 'Not found in Trash', 'tm' ),
        );
        $default_labels = array_replace( $default_labels, $classname::$labels );
        // TODO :: Need to internationalise here

        $slug = $classname::get_slug();

        $default_args = array(
          'label'               => __( $plural_name, 'tm' ),
          'description'         => __( $plural_name  . ' details', 'tm' ),
          'labels'              => $default_labels,
          'supports'            => array( 'title', 'editor', 'revisions'),
          //'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
          // 'taxonomies'          => array( 'genres' ),
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
          'rewrite'             => array( 'slug' => $slug, 'with_front' => false ),
          'capability_type'     => 'post'
        );
        $default_args = array_replace( $default_args, $classname::$args );
        register_post_type( $classname::$post_type , $default_args );
      }
    }

  // ==================================================
    public static function create_metadatabox() {
      if ( is_admin() ) {
        $classname = get_called_class();
        $plural_name = $classname::get_pluralname();
        add_meta_box(
          $classname::$post_type . '_defaulttm',
          $plural_name . ' Metadata',
          $classname . '::inner_custom_box',
          $classname::$post_type,
          'normal',
          'default'
        );
      }
    }

  // ==================================================
    public static function inner_custom_field_number($key, $value, $label) {
      ?>
      <div class="tm-meta-number">
        <label for="<?php echo $key ?>"><?php echo esc_html__($label,'tm') ?></label>
        <input class=""
        type="number"
        name="<?php echo $key ?>"
        id="<?php echo $key ?>"
        value="<?php echo esc_attr($value) ?>"
        />
      </div>
      <?php
    }

  // ==================================================
    public static function inner_custom_field_date($key, $value, $label) {
      ?>
      <div class="tm-meta-text">
        <label for="<?php echo $key ?>"><?php echo esc_html__($label,'tm') ?></label>
        <input class=""
        type="datetime-local"
        name="<?php echo $key ?>"
        id="<?php echo $key ?>"
        value="<?php echo $value->format('Y-m-d\TH:i') ?>"
        />
      </div>
      <?php
    }

  // ==================================================
    public static function inner_custom_field_time($key, $value, $label) {
      ?>
      <div class="tm-meta-text">
        <label for="<?php echo $key ?>"><?php echo esc_html__($label,'tm') ?></label>
        <input class=""
        type="datetime-local"
        name="<?php echo $key ?>"
        id="<?php echo $key ?>"
        value="<?php echo $value->format('TH:i') ?>"
        />
      </div>
      <?php
    }

  // ==================================================
    public static function inner_custom_field_string($key, $value, $label) {
      ?>
      <div class="tm-meta-text">
        <label for="<?php echo $key ?>"><?php echo esc_html__($label,'tm') ?></label>
        <input class=""
        type="text"
        name="<?php echo $key ?>"
        id="<?php echo $key ?>"
        value="<?php echo esc_attr($value) ?>"
        />
      </div>
      <?php
    }

  // ==================================================
    public static function inner_custom_field_text($key, $value, $label) {
      wp_editor($value , $key);
    }

  // ==================================================
    public static function inner_custom_field_code($key, $value, $label) {
      ?>
      <div class="tm-meta-code" style="width:100%">
        <label for="<?php echo $key ?>"><?php echo esc_html__($label,'tm') ?></label>
        <textarea class=""
        style="width:100%"
        rows=15
        name="<?php echo $key ?>"
        id="<?php echo $key ?>"><?php echo esc_attr($value) ?></textarea>
      </div>
      <?php
    }

  // ==================================================
    public static function inner_custom_field_button($key, $label, $onclick, $status = '__NONE') {
      ?>
      <div class="tm-meta-button">
        <input
        id='<?php echo $key ?>'
        class='button'
        type='button'
        onclick='<?php echo esc_attr($onclick) ?>'
        value='<?php echo esc_attr__($label,'tm') ?>' />
        <?php if ( $status != '__NONE' ) { ?>
          <label id="<?php echo esc_attr($key) ?>_label" for="<?php echo $key ?>"><?php echo esc_html__($status,'tm') ?></label>
        <?php } ?>
      </div>
      <?php
    }

  // ==================================================
    public static function inner_custom_box($post) {
      $classname = get_called_class();
      // Use nonce for verification
      wp_nonce_field( $classname::$post_type . '_defaulttm_field_nonce', $classname::$post_type . '_defaulttm_nonce' );

      // Get saved value, if none exists, "default" is selected
      $obj = new $classname($post);
      foreach ($classname::$meta_keys as $key => $value) {
        $fieldkey = $classname . "_" . $key;
        $fieldvalue = $obj->$key;
        $fieldlabel = $value['label'];
        switch($value['type']) {
          case 'meta_attrib':        $classname::inner_custom_field_string($fieldkey, $fieldvalue, $fieldlabel); break;
          case 'meta_attrib_number': $classname::inner_custom_field_number($fieldkey, $fieldvalue, $fieldlabel); break;
          case 'meta_attrib_date':   $classname::inner_custom_field_date($fieldkey, $fieldvalue, $fieldlabel); break;
          case 'meta_attrib_time':   $classname::inner_custom_field_time($fieldkey, $fieldvalue, $fieldlabel); break;
          case 'meta_attrib_text':   $classname::inner_custom_field_text($fieldkey, $fieldvalue, $fieldlabel); break;
          case 'meta_attrib_code':   $classname::inner_custom_field_code($fieldkey, $fieldvalue, $fieldlabel); break;
          case 'meta_attrib_string': $classname::inner_custom_field_string($fieldkey, $fieldvalue, $fieldlabel);
        }
      }
    }

  // ==================================================
    public static function save_metadatabox( $post_id ) {
      $classname = get_called_class();
      $post_type = get_post_type($post_id);
      if ( $classname::$post_type != $post_type ) return;

      // verify if this is an auto save routine.
      // If it is our form has not been submitted, so we dont want to do anything
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

      // verify this came from the our screen and with proper authorization,
      // because save_post can be triggered at other times
      if (! isset( $_POST[$classname::$post_type . '_defaulttm_nonce']) ) return;
      if ( !wp_verify_nonce( $_POST[$classname::$post_type . '_defaulttm_nonce'], $classname::$post_type . '_defaulttm_field_nonce' ) ) return;

      $obj = new $classname($post_id);
      foreach ($classname::$meta_keys as $key => $value) {
        $fieldkey = $classname . "_" . $key;
        if ( isset($_POST[$fieldkey]) ) {
          $obj->$key = $_POST[$fieldkey];
        }
      }
    }

  // ==================================================
    public static function create_settings_menu() {
      $classname = get_called_class();
      //create new top-level menu
      add_submenu_page('edit.php?post_type=' . $classname::$post_type,
      $classname::$labels['name'] . ' Settings',
      $classname::$labels['name'] . ' Settings',
      'administrator',
      __FILE__,
      $classname . '::settings_page',
      plugins_url('/images/icon.png', __FILE__)
    );
  }

  // ==================================================
  public static function register_settings() {
    $classname = get_called_class();
    foreach($classname::$setting_keys as $key => $value) {
      $args = array_replace([], $value['args']);
      register_setting( $classname::$post_type . '-settings-group', $classname . '-' . $key, $args );
    }
  }

  // ==================================================
  public static function get_option($key) {
    $classname = get_called_class();
    return get_option($classname . '-' . $key);
  }

  // ==================================================
  public static function settings_page() {
    $classname = get_called_class();
    ?>
    <div class="wrap">
      <h1><? echo $classname::$labels['singular_name'] ?> Settings</h1>

      <form method="post" action="options.php">
        <?php settings_fields( $classname::$post_type . '-settings-group' ); ?>
        <?php do_settings_sections( $classname::$post_type . '-settings-group' ); ?>
        <table class="form-table">
          <?php foreach($classname::$setting_keys as $key => $value) { ?>
            <tr valign="top">
              <th scope="row"><?php echo $value['label'] ?></th>
              <td><input type="text" name="<?php echo esc_attr( $classname . '-' . $key ); ?>" value="<?php echo esc_attr( get_option($classname . '-' . $key) ); ?>" /></td>
            </tr>
          <?php } ?>
        </table>
        <?php submit_button(); ?>
      </form>
    </div>
    <?php
  }

  /*
  // ==================================================
  public static function rewriteurl() {
    $classname = get_called_class();
    $matchstr = '^' . $classname::get_slug() . '/([^/]+)' ;
    $replacestr = 'index.php?post_type='. $classname::$post_type . '&pagename=$matches[1]';
    $i = 2;
    foreach($classname::$path_params as $pathparam) {
      $matchstr .= '/([^/]+)';
      $replacestr .= '&' . $pathparam . '=$matches[' . $i++ . ']';
    }
    $matchstr .= '/?';
    var_dump($matchstr, $replacestr);
    add_rewrite_rule($matchstr,$replacestr,'top');
    // add_rewrite_rule('^' . $slug . '/([^/]+)/fixtures/([^/]+)/([^/]+)/?','index.php?post_type=tm_fixture&teamname=$matches[1]&season=$matches[2]&name=$matches[3]','top');
    global $wp_rewrite;
    $wp_rewrite->flush_rules(false);
  }

  // ==================================================
  public static function permalinks( $post_link, $post ){
      $classname = get_called_class();
      if ( is_object( $post ) && $classname::$post_type ) {
        $postobj = new $classname($post);
        foreach($classname::$path_params as $pathparam ) {
          $post_link = str_replace( '%' . $pathparam . '%' , $postobj->$params[$pathparam] , $post_link );
        }
      }
      return $post_link;
  }
  */

  // ==================================================
  public static function WPPost_to_TMPost($wpposts) {
    $classname = get_called_class();
    $postobjs = [];
    foreach($wpposts as $post) {
      $postobjs[] = new $classname($post);
    }
    return $postobjs;
  }

  // ==================================================
  public static function getAll() {
    $classname = get_called_class();
    $posts = get_posts(array (
      'numberposts'	=> -1,
      'post_type'    => $classname::$post_type
    ));
    return $classname::WPPost_to_TMPost($posts);
  }

  // ==================================================
  public static function getBySlug($slug) {
    $classname = get_called_class();
    $args = array(
      'name'        => $slug,
      'post_type'   => $classname::$post_type,
      'post_status' => 'publish',
      'numberposts' => 1
    );
    $posts = get_posts($args);
    if( $posts ) {
      return new $classname($posts[0]->ID);
    } else {
      return null;
    }
  }

  // ==================================================
  public static function createPost($title) {
    $classname = get_called_class();
    $post_id  = wp_insert_post ( array(
      'post_title'  => $title,
      'post_status' => 'publish',
      'post_type'   => $classname::$post_type
    ) );
    $classname = get_called_class();
    return new $classname($post_id);
  }

  // ==================================================
  public static function getRelatedToTax($taxonomy, $term_id) {
    $classname = get_called_class();
    $posts = get_posts(array (
      'numberposts' => -1,
      'post_type'	  => $classname::$post_type,
      'tax_query'   => array(
        array(
          'taxonomy' => $taxonomy,
          'field' => 'term_id',
          'terms' => $term_id
        ),
      ),
    ));
    return $classname::WPPost_to_TMPost($posts);
  }

  // ==================================================
  public static function getWithMetaValue($meta_key, $meta_value) {
    $classname = get_called_class();
    $posts = get_posts(array (
      'numberposts' => -1,
      'post_type'	  => $classname::$post_type,
      'post_status' => 'publish',
      'meta_query'	 => array(
        array(
          'key'	 	   => $meta_key,
          'value'	   => $meta_value,
          'compare'  => '='
        )
      ),
    ));
    return $classname::WPPost_to_TMPost($posts);
  }

  // ==================================================
  protected function get_value($key) {
    $classname = get_called_class();
    $stemkey = TMBasePost::get_stemkey($key);
    if ( array_key_exists($stemkey, $classname::$meta_keys) ) {
      $conf = $classname::$meta_keys[$stemkey];
      switch($conf['type']) {
        case 'related_post':     return $this->get_related_post($key, $conf['meta_key'], $conf['classname']); break;
        case 'related_posts':    return $this->get_related_posts($key, $conf['meta_key'], $conf['classname']); break;
        case 'related_tax':      return $this->get_related_tax($key, $conf['meta_key'], $conf['classname'], $conf['single']); break;
        default:                 return parent::get_value($key);;
      }
    } else {
      switch ( $key ) {
        case 'title':  return $this->post->post_title; break;
        case 'author': return $this->post->post_author; break;
        case 'slug':   return $this->post->post_name; break;

        case 'post':
        if ( is_null($this->_obj) ) {
          $this->_obj = get_post( $this->_id , $this->_post_type );
        }
        return $this->_obj;
        break;

        default:
        return parent::get_value($key);;
      }
    }
  }

  // ==================================================
  protected function update_value($key, $value) {
    $classname = get_called_class();
    $stemkey = TMBasePost::get_stemkey($key);
    if ( array_key_exists($stemkey, $classname::$meta_keys) ) {
      $conf = $classname::$meta_keys[$stemkey];
      switch($conf['type']) {
        case 'related_post':     $this->update_related_post($key, $conf['meta_key'], $value); break;
        default:                 return parent::update_value($key, $value);;
      }
    } else {
      switch ( $key ) {
        case 'title':
        $this->post->post_title = $value;
        wp_update_post($this->post);
        break;

        case 'author':
        $this->post->post_author = $value;
        wp_update_post($this->post);
        break;

        case 'slug':
        $this->post->post_name = $value;
        wp_update_post($this->post);
        break;

        default:
        parent::update_value($key, $value);;
      }
    }
  }

  // meta_value ==================================================
  protected function update_meta_value($meta_key, $value) {
    update_post_meta( $this->_id, $meta_key, $value );
  }

  protected function get_meta_value($meta_key) {
    return get_post_meta( $this->_id, $meta_key, true );
  }

  // related_post ==================================================
  protected function update_related_post($key, $meta_key, $value) {
    if ( substr($key, -3) == '_id' ) { // Only do _id updates
      $objkey = substr($key, 0, strlen($key) - 3);
      $idkey  = $key;
      update_post_meta( $this->_id, $meta_key, $value );
      $this->cache[$idkey]  = $value;
      $this->cache[$objkey] = null;
    } else {
      throw(new Exception($meta_key . ' can only be updated when suffixed _id'));
    }
  }

  protected function get_related_post($key, $meta_key, $postclass) {
    if ( substr($meta_key, -3) == '_id') {
      $objkey = substr($key, 0, strlen($key) - 3);
    } else {
      $objkey = $key;
    }
    $idkey = $objkey . '_id';
    if ( ! array_key_exists( $idkey, $this->cache ) ) {
      $this->cache[$idkey] = get_post_meta( $this->_id, $meta_key, true );
    }
    if ( substr($key, -3) == '_id') { // If what was asked for was the _id
      return $this->cache[$idkey];
    } else { // The object was asked for

      if ( is_null($this->cache[$idkey]) || empty($this->cache[$idkey]) ) {
        $this->cache[$objkey] = null;
      } else {
        $this->cache[$objkey] = new $postclass( $this->cache[$idkey] );
      }
      return $this->cache[$objkey];
    }
  }

  // related_post ==================================================
  // protected function update_related_posts($meta_key, $value) {
  // }

  protected function get_related_posts($key, $meta_key, $postclass) {
    return $postclass::getWithMetaValue($meta_key, $this->_id );
  }

  // related_tax ==================================================
  // protected function update_related_tax($meta_key, $value) {
  // }
  protected function get_related_tax($key, $meta_key, $taxclass, $single = true) {
    $terms = wp_get_object_terms( $this->_id, $taxclass::$taxonomy);
    if ( $single ) {
      if ( sizeof ($terms) > 0 ) {
        if ( substr($key, -3) == '_id') { // If what was asked for was the _id
          return $terms[0]->term_id;
        } else {
          return new $taxclass($terms[0]);
        }
      } else {
        return null;
      }
    } else {
      $termobjs = [];
      foreach($terms as $term) {
        $termobjs[] = new $taxclass($term);
      }
      return $termobjs;
    }
  }

  // === Attach Terms ==================================================
  public function attachTerm($termobj) {
    $termclass = get_class($termobj);
    if ( !($termobj instanceof TMBaseTax ) ) {
      throw(new Exception('Object ' . $termclass . ' is not a child of TMBaseTax'));
    }
    // Add the term to the post
    return wp_set_object_terms( $this->_id, $termobj->slug , $termclass::$taxonomy , false);
  }

  public function attachTermBySlug($termclass, $term_slug) {
    $term = $termclass::getBySlug($term_slug);
    if ( ! $term ) {
      $term = $termclass::insertTerm($term_slug);
    }
    $this->attachTerm($term);
  }

}
endif;
?>
