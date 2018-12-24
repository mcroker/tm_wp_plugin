<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once('TMBaseGeneric.php');

if ( ! class_exists('TMBaseTax')):
  class TMBaseTax extends TMBaseGeneric {
    public static $taxonomy;
    protected static $labels = [];
    protected static $args = [];
    protected static $associate_post_types = [];
    protected static $meta_keys = [];
    private $_taxonomy;
    protected $cache = [];

    // ==================================================
    function __construct($term) {
      if ($term instanceof WP_Term) {
        parent::__construct($term->term_id, $term);
      } else { // hopefully an id
        parent::__construct($term);
      }
      $classname = get_called_class();
      $this->_taxonomy = $classname::$taxonomy;
    }

    // ==================================================
    public static function init() {
      $classname = get_called_class();
      add_action('init', $classname . '::register_taxonomy');
      if ( is_admin() ) {
        add_action( 'admin_enqueue_scripts',  $classname . '::enqueue_adminscripts');
        add_action( $classname::$taxonomy . '_add_form_fields', $classname . '::add_form_fields', 10, 2 );
        add_action( $classname::$taxonomy . '_edit_form_fields', $classname . '::edit_form_fields', 10, 2 );
        add_action( 'created_' . $classname::$taxonomy, $classname . '::save_fields', 10, 2 );
        add_action( 'edited_' . $classname::$taxonomy, $classname . '::save_fields', 10, 2 );
      }
      add_action( 'wp_enqueue_scripts',  $classname . '::enqueue_scripts');
    }

    // ==================================================
    public static function register_taxonomy() {
      $classname = get_called_class();
      $singular_name = $classname::$labels['singular_name'];
      $plural_name = $classname::get_pluralname();

      $default_labels = array(
        'name' => _x( $plural_name, 'taxonomy general name', 'tm' ),
        'singular_name' => _x($singular_name, 'taxonomy singular name', 'tm'),
        'search_items' => __('Search ' . $singluar_name, 'tm'),
        'popular_items' => __('Common ' . $plural_name, 'tm'),
        'all_items' => __('All ' . $plural_name, 'tm'),
        'edit_item' => __('Edit ' . $singluar_name, 'tm'),
        'update_item' => __('Update ' . $singluar_name, 'tm'),
        'add_new_item' => __('Add new ' . $singluar_name, 'tm'),
        'new_item_name' => __('New ' . $singluar_name . ':', 'tm'),
        'add_or_remove_items' => __('Remove ' . $singluar_name, 'tm'),
        'choose_from_most_used' => __('Choose from common ' . $singluar_name, 'tm'),
        'not_found' => __('No ' . $singluar_name . ' found.', 'tm'),
        'menu_name' => __($plural_name, 'tm'),
      );
      $default_labels = array_replace( $default_labels, $classname::$labels );

      $default_args = array(
        'hierarchical' => false,
        'labels' => $default_labels,
        'show_ui' => true
      );
      $default_args = array_replace( $default_args, $classname::$args );

      register_taxonomy($classname::$taxonomy, $classname::$associate_post_types, $default_args );
    }
    // ==================================================
    public static function enqueue_scripts() {
      $classname = get_called_class();
      if ( is_object_in_taxonomy ( get_post_type() , $classname::$taxonomy ) ) {
        parent::enqueue_script_helper( $classname . '-script', $classname . '.js' );
        parent::enqueue_style_helper( $classname . '-css', $classname . '.css' );
      }
    }

    // ==================================================
    public static function enqueue_adminscripts( $hook_suffix ){
      $classname = get_called_class();
      if( in_array($hook_suffix, array('term.php', 'edit-tags.php') ) ){
        $screen = get_current_screen();
        if( is_object( $screen ) && $classname::$taxonomy == $screen->taxonomy ){
          parent::enqueue_script_helper( $classname . '-admin-script', $classname . '-admin.js' );
          parent::enqueue_style_helper( $classname . '-admin-css', $classname . '-admin.css');
		      wp_enqueue_media();
          parent::enqueue_script_helper( $classname . '-logo-field-js', 'TMLogoField.js' , [], __FILE__ );
        }
      }
    }

    // ==================================================
    public static function getAll() {
      $classname = get_called_class();
      $terms = get_terms([
        'taxonomy'   => $classname::$taxonomy,
        'hide_empty' => false
      ]);
      $termobjs = [];
      foreach($terms as $term) {
        $termobjs[] = new $classname($term->term_id);
      }
      return $termobjs;
    }

    // ==================================================
    public static function getBySlug($term_slug) {
      $classname = get_called_class();
      $term = get_term_by('slug' , $term_slug, $classname::$taxonomy );
      if ($term) {
        return new $classname($term->term_id);
      } else {
        return null;
      }
    }

    // ==================================================
    public static function getByName($term_name) {
      $classname = get_called_class();
      $term = get_term( Array ( 'name' => $term_name ), $classname::$taxonomy );
      return new $classname($term->term_id);
    }

    // ==================================================
    public static function getByID($term_id) {
      $classname = get_called_class();
      $term = get_term( $term_id , $classname::$taxonomy );
      return new $classname($term->term_id);
    }

    // ==================================================
    public static function insertTerm($term_slug) {
      $classname = get_called_class();
      $resp = wp_insert_term( $term_slug, $classname::$taxonomy, $args = array() );
      $classname = get_called_class();
      return new $classname($resp->term_id);
    }

    // Getters and setters ==================================================
    protected function get_value($key) {
      $classname = get_called_class();
      $stemkey = TMBaseTax::get_stemkey($key);
      if ( array_key_exists($stemkey, $classname::$meta_keys) ) {
        $conf = $classname::$meta_keys[$stemkey];
        switch ( $conf['type'] ) {
          case 'related_posts':      return $this->get_related_posts($key, $conf['classname']); break;
          default:                   return parent::get_value($key);
        }
      } else {
        switch ( $key ) {
          case 'name': return $this->term->name; break;
          case 'slug': return $this->term->slug; break;

          case 'term':
          if ( is_null($this->_obj) ) {
            $this->_obj = get_term( $this->_id , $this->_taxonomy );
          }
          return $this->_obj;
          break;

          default:
          return parent::get_value($key);
        }
      }
    }

    /*
    protected function update_value($key, $value) {
    $classname = get_called_class();
    $stemkey = TMBaseTax::get_stemkey($key);
    if ( array_key_exists($stemkey, $classname::$meta_keys) ) {
    $conf = $classname::$meta_keys[$stemkey];
    switch ( $conf['type'] ) {
    default:                   return parent::update_value($key, $value);;
  }
} else {
switch ( $key ) {
default:                   return parent::update_value($key, $value);;
}
}
}
*/

// attrib_string ==================================================
protected function update_meta_value($meta_key, $value) {
  update_term_meta( $this->_id, $meta_key , $value );
}

protected function get_meta_value($meta_key) {
  return get_term_meta( $this->_id, $meta_key , true );
}

// related_posts =================================================
// protected function update_attrib_string($key, $meta_key, $value) {
// }

protected function get_related_posts($key, $postclass) {
  $classname = get_called_class();
  return $postclass::getRelatedToTax($classname::$taxonomy, $this->_id);
}

// Sorters ==================================================
public static function sort_by_slug_asc($a, $b) {
  return ($a->slug > $b->slug);
}
public static function sort_by_slug_desc($a, $b) {
  return ($a->slug < $b->slug);
}

// ==================================================
public static function add_form_fields($taxonomy) {
  $classname = get_called_class();
  $classname::base_form_field_nonce();
  foreach ($classname::$meta_keys as $key => $value) {
    $classname::add_form_field($key);
  }
}

// ==================================================
public static function edit_form_fields($term) {
   $classname = get_called_class();
   $classname::base_form_field_nonce();
   foreach ($classname::$meta_keys as $key => $value) {
     $classname::edit_form_field($term, $key);
   }
}

// Custom Fields Add ==================================================
public static function add_form_field($key, $value = "_AUTO", $label = "_AUTO", $type = "_AUTO", $settings = "_AUTO") {
  $classname = get_called_class();
  $fieldkey = $classname . "_" . $key;
  if ( $value == "_AUTO" ) {
    $obj = new $classname($taxonomy);
    $value = $obj->$key;
  }
  if ( $label  == "_AUTO" ) {
    $label = $classname::$meta_keys[$key]['label'];
  }
  if ( $type == "_AUTO" ) {
    $type = $classname::$meta_keys[$key]['type'];
  }
  if ( $settings == "_AUTO" ) {
    $settings = $classname::$meta_keys[$key]['settings'];
  }
  ?>
  <div class="form-field term-group">
    <label for="<?php echo $fieldkey ?>"><?php _e($label, 'tm'); ?></label>
  <?php
  switch($type) {
    // case 'meta_attrib_number': $classname::base_form_field_number($fieldkey, $value, $label, $settings); break;
    // case 'meta_attrib_date':   $classname::base_form_field_date($fieldkey, $value, $label, $settings); break;
    // case 'meta_attrib_time':   $classname::base_form_field_time($fieldkey, $value, $label, $settings); break;
    // case 'meta_attrib_text':   $classname::base_form_field_editor($fieldkey, $value, $label, $settings); break;
    // case 'meta_attrib_code':   $classname::base_form_field_code($fieldkey, $value, $label, $settings); break;
    // case 'meta_attrib_check':  $classname::base_form_field_check($fieldkey, $value, $label, $settings); break;
    case 'meta_attrib_logo':   $classname::base_form_field_logo($fieldkey, $value, $label, $settings); break;
    default:                   $classname::base_form_field_string($fieldkey, $value, $label, $settings);
  }
  ?>
</div>
  <?php
}

// Custom Fields Edit ==================================================
public static function edit_form_field($term, $key, $value = "_AUTO", $label = "_AUTO", $type = "_AUTO", $settings = "_AUTO") {
  $classname = get_called_class();
  $fieldkey = $classname . "_" . $key;
  if ( $value == "_AUTO" ) {
    $obj = new $classname($term);
    $value = $obj->$key;
  }
  if ( $label  == "_AUTO" ) {
    $label = $classname::$meta_keys[$key]['label'];
  }
  if ( $type == "_AUTO" ) {
    $type = $classname::$meta_keys[$key]['type'];
  }
  if ( $settings == "_AUTO" ) {
    $settings = $classname::$meta_keys[$key]['settings'];
  }
  ?>
  <tr class="form-field term-group-wrap">
    <th scope="row">
      <label for="<?php echo $fieldkey ?>"><?php _e($label, 'tm'); ?></label>
    </th>
    <td>
      <?php
      switch($type) {
        // case 'meta_attrib_number': $classname::base_form_field_number($fieldkey, $value, $label, $settings); break;
        // case 'meta_attrib_date':   $classname::base_form_field_date($fieldkey, $value, $label, $settings); break;
        // case 'meta_attrib_time':   $classname::base_form_field_time($fieldkey, $value, $label, $settings); break;
        // case 'meta_attrib_text':   $classname::base_form_field_editor($fieldkey, $value, $label, $settings); break;
        // case 'meta_attrib_code':   $classname::base_form_field_code($fieldkey, $value, $label, $settings); break;
        // case 'meta_attrib_check':  $classname::base_form_field_check($fieldkey, $value, $label, $settings); break;
        case 'meta_attrib_logo':   $classname::base_form_field_logo($fieldkey, $value, $label, $settings); break;
        default:                   $classname::base_form_field_string($fieldkey, $value, $label, $settings); break;
      }
      ?>
    </td>
  </tr>
  <?php
}

// ==================================================
public static function save_fields( $term_id, $tt_id ){
  $classname = get_called_class();
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

  if (! isset( $_POST[$classname::$taxonomy . '_defaulttm_nonce']) ) return;
  if ( !wp_verify_nonce( $_POST[$classname::$taxonomy . '_defaulttm_nonce'], $classname::$taxonomy . '_defaulttm_field_nonce' ) ) return;

  $obj = new $classname($term_id);

  foreach ($classname::$meta_keys as $key => $value) {
    $fieldkey = $classname . "_" . $key;
    switch($value['type']) {
      case 'meta_attrib_check':
        $obj->$key = isset($_POST[$fieldkey]);
        break;

      // case 'related_post':
      //   if ( isset($_POST[$fieldkey]) ) {
      //     $obj->{$key . '_id'} = $_POST[$fieldkey];
      //   };
      //   break;

      // case 'related_tax':
      //   if ( isset($_POST[$fieldkey]) ) {
      //     $obj->attachTerm(new $value['classname']($_POST[$fieldkey]));
      //   };
      //   break;

      default:
        if ( isset($_POST[$fieldkey]) ) {
          $obj->$key = $_POST[$fieldkey];
        };

    }
  }
}

// ==================================================
public static function base_form_field_nonce($boxid = 'defaulttm') {
  $classname = get_called_class();
  // Use nonce for verification
  wp_nonce_field( $classname::$taxonomy . '_' .$boxid . '_field_nonce', $classname::$taxonomy . '_' . $boxid . '_nonce' );
}

// ==================================================
public static function base_form_field_string($fieldkey, $value = "", $label = "", $settings = []) {
  ?>
  <input type="text" name="<?php echo esc_attr($fieldkey) ?>" value="<?php echo esc_attr($value) ?>" id="<<?php echo esc_attr($fieldkey) ?>"/><br>
  <?php
}

// ==================================================
public static function base_form_field_logo ($fieldkey, $value = "", $label = "", $settings = []) {
  global $content_width, $_wp_additional_image_sizes;

  $classname = get_called_class();
  $removebuttonid = $classname . '_logo_remove';
  $uploadbuttonid = $classname . '_logo_upload';
  $logodivid = $classname . '_logo_div';
  $fieldid = $classname . '_logo';

  $old_content_width = $content_width;
  $content_width = 254;
  if ( $value && get_post( $value ) ) {
    if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
      $thumbnail_html = wp_get_attachment_image( $value, array( $content_width, $content_width ) );
    } else {
      $thumbnail_html = wp_get_attachment_image( $value, 'post-thumbnail' );
    }
    if ( ! empty( $thumbnail_html ) ) {
      ?>
      <div id="<?php echo esc_attr($logodivid) ?>">
        <?php echo $thumbnail_html ?>
        <p class="hide-if-no-js">
          <a href="javascript:;"
             id="<?php echo esc_attr($removebuttonid) ?>" >
             <?php echo esc_html__( 'Remove listing image', 'tm' ) ?>
           </a>
        </p>
        <input type="hidden" id="<?php echo esc_attr($fieldid) ?>" name="<?php echo esc_attr($fieldid) ?>" value="<?php esc_attr( $value )?>"/>
      </div>
      <?php
    }
    $content_width = $old_content_width;
  } else {
    ?>
    <div id="<?php echo esc_attr($logodivid) ?>">
      <img src="" style="width:<?php echo esc_attr( $content_width ) ?>px;height:auto;border:0;display:none;" />
      <p class="hide-if-no-js">
        <a title="<?php echo esc_attr__( 'Set listing image', 'tm' ) ?>"
          href="javascript:;"
          id="<?php echo esc_attr($uploadbuttonid) ?>"
          data-uploader_title="<?php echo esc_attr__( 'Choose an logo', 'tm' ) ?>"
          data-uploader_button_text="<?php echo esc_attr__( 'Set logo', 'tm' ) ?>" >
          <?php echo esc_html__( 'Set logo', 'tm' ) ?>
        </a>
      </p>
      <input type="hidden" id="<?php echo esc_attr($fieldid) ?>" name="<?php echo esc_attr($fieldid) ?>" value="" />
    </div>
    <?php
  }
}

}
endif;
