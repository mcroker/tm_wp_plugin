<?php
if ( ! class_exists('TMBaseTax')):
  class TMBaseTax {
    public static $taxonomy;
    protected static $meta_keys = [];
    private $_taxonomy;
    protected $term_id;
    protected $term_obj;
    protected $cache = [];

    function __construct($term) {
      if ($term instanceof WP_Term) {
        $this->term_id = $term->term_id;
        $this->term_obj = $term;
      } else { // hopefully an id
        $this->term_id = $term;
      }
      $classname = get_called_class();
      $this->_taxonomy = $classname::$taxonomy;
    }

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

    public static function getBySlug($term_slug) {
      $classname = get_called_class();
      $term = get_term_by('slug' , $term_slug, $classname::$taxonomy );
      return new $classname($term->term_id);
    }

    public static function getByName($term_name) {
      $classname = get_called_class();
      $term = get_term( Array ( 'name' => $term_name ), $classname::$taxonomy );
      return new $classname($term->term_id);
    }

    public static function getByID($term_id) {
      $classname = get_called_class();
      $term = get_term( $term_id , $classname::$taxonomy );
      return new $classname($term->term_id);
    }

    public static function insertTerm($term_slug) {
      $resp = wp_insert_term( $term_slug, $this->taxonomy, $args = array() );
      $classname = get_called_class();
      return new $classname($resp->term_id);
    }

    public function __get ($key) {
      return $this->get_cached_value($key);
    }

    public function __set ($key, $value) {
      $this->update_cached_value($key, $value);
    }

    protected function get_cached_value($key) {
      $classname = get_called_class();
      // Remove _id if present at end of key
      if ( substr($key, -3) == '_id') {
        $stemkey = substr($key, 0, length($key) - 3);
      } else {
        $stemkey = $key;
      }

      if ( array_key_exists($stemkey, $classname::$meta_keys) ) {
        $conf = $classname::$meta_keys[$stemkey];
        switch ( $conf['type'] ) {
          case 'meta_attrib':
          if ( ! array_key_exists( $key, $this->cache ) ) {
            $this->cache[$stemkey] = get_term_meta( $this->term_id, $conf['meta_key'] , true );
          }
          return $this->cache[$stemkey];
          break;

          case 'related_posts':
          $conf = $classname::$meta_keys[$stemkey];
          return $conf['classname']::getRelatedToTax($classname::$taxonomy, $this->term_id);
          break;

          default:
          throw(new Error('Type of meta config not recognised'));
        }
      } else {
        switch ( $key ) {
          case 'ID': // ============================================================
          return $this->term_id;
          break;

          case 'name': // ============================================================
          return $this->term->name;
          break;

          case 'slug': // ============================================================
          return $this->term->slug;
          break;

          case 'term': // ============================================================
          if ( is_null($this->term_obj) ) {
            $this->term_obj = get_term( $this->term_id , $this->_taxonomy );
          }
          return $this->term_obj;
          break;

          default:
          throw(new Error('Key ' + $key + ' not configured'));
        }
      }
    }
    protected function update_cached_value($key, $value) {
      //TODO!!!!!!!! NOT DONE§
      $classname = get_called_class();

      if ( array_key_exists($key, $classname::$meta_keys) ) {
        $conf = $classname::$meta_keys[$stemkey];
      }
    }

  }
endif;
