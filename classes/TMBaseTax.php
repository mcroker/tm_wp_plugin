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
        parent::enqueue_scripts_helper();
      }
    }

    // ==================================================
    public static function enqueue_adminscripts( $hook_suffix ){
      $classname = get_called_class();
      if( in_array($hook_suffix, array('term.php', 'edit-tags.php') ) ){
        $screen = get_current_screen();
        if( is_object( $screen ) && $classname::$taxonomy == $screen->taxonomy ){
          parent::enqueue_adminscripts_helper( $hook_suffix );
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


}
endif;
