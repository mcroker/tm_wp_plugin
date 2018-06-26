<?php
if ( ! class_exists('TMBasePost')):
  class TMBasePost {
    protected static $post_type;
    protected static $meta_keys = [];
    private $_post_type;
    protected $post_id;
    protected $post_obj;
    protected $cache = [];

    function __construct($post = 0) {
      if ($post instanceof WP_Post) {
        $this->post_id = $post->ID;
        $this->post_obj = $post;
      } else { // hopefully an id
        if ( $post_id == 0 ) {
          $post_id = get_the_id();
        }
        $this->post_id = $post;
      }
      $classname = get_class();
      $this->_post_type = $classname::$post_type;
    }

    public static function WPPost_to_TMPost($wpposts) {
      $classname = get_called_class();
      $postobjs = [];
      foreach($wpposts as $post) {
        $postobjs[] = new $classname($post);
      }
      return $postobjs;
    }

    public static function getAll() {
      $classname = get_called_class();
      $posts = get_posts(array (
         'numberposts'	=> -1,
         'post_type'    => $classname::$post_type
      ));
      return $classname::WPPost_to_TMPost($posts);
    }

    public static function createPost($title) {
      $classname = get_called_class();
      $post_id  = wp_insert_post ( array(
        'post_title'  => 'New Object',
        'post_status' => 'publish',
        'post_type'   => $classname::$post_type
      ) );
      $classname = get_called_class();
      return new $classname($post_id);
    }

    public static function getRelatedToTax($taxonomy, $term_id) {
      $posts = get_posts(array (
        'numberposts' => -1,
        'post_type'	  => $this->_post_type,
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

    public static function getWithMetaValue($meta_value, $meta_key) {
      $classname = get_called_class();
      $posts = get_posts(array (
        'numberposts' => -1,
        'post_type'	  => $classname::$post_type,
        'meta_query'	 => array(
            'key'	 	   => $meta_key,
            'value'	   => $meta_value,
            'compare'  => '='
        ),
      ));
      return $classname::WPPost_to_TMPost($posts);
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
        switch($conf['type']) {
          case 'meta_attrib':
          if ( ! array_key_exists( $key, $this->cache ) ) {
            $this->cache[$stemkey] = get_post_meta( $this->post_id, $conf['meta_key'], true );
          }
          return $this->cache[$stemkey];
          break;

          case 'meta_attrib_date':
          if ( ! array_key_exists( $key, $this->cache ) ) {
            $value = get_post_meta( $this->post_id, $conf['meta_key'], true );
            if ( is_null($value) || $value == '') {
              $this->cache[$stemkey] = 0;
            } else {
              $this->cache[$stemkey] = strtotime($value);
            }
          }
          return $this->cache[$stemkey];

          case 'related_post':
          $idkey = $stemkey . '_id';
          if ( ! array_key_exists( $idkey, $this->cache ) ) {
            $this->cache[$idkey] = get_post_meta( $this->post_id, $conf['id_meta_key'], true );
          }
          if ( substr($key, -3) == '_id') { // If what was asked for was the _id
            return $this->cache[$idkey];
          } else { // The object was asked for
            if ( is_null($this->cache[$idkey]) || empty($this->cache[$idkey]) ) {
              $this->cache[$stemkey] = null;
            } else {
              $this->cache[$stemkey] = new $conf['classname']( $this->cache[$idkey] );
            }
            return $this->cache[$stemkey];
          }
          break;

          case 'related_posts':
          $conf = $classname::$meta_keys[$stemkey];
          return $conf['classname']::getWithMetaValue($conf['meta_key'], $this->post_id );
          break;

          case 'related_tax':
          $classname = $conf['classname'];
          $terms = wp_get_object_terms( $this->post_id, $classname::$taxonomy);
          if ( $conf['single'] ) {
            if ( sizeof ($terms) > 0 ) {
              return new $classname($terms[0]);
            } else {
              return null;
            }
          } else {
            $termobjs = [];
            foreach($terms as $term) {
              $termobjs[] = new $classname($term);
            }
            return $termobjs;
          }
          break;

          default:
          throw(new Error('Type of meta config not recognised'));
        }
      } else {
        switch ( $key ) {
          case 'ID': // ============================================================
          return $this->post_id;
          break;

          case 'title': // ============================================================
          return $this->post->post_title;
          break;

          case 'post': // ============================================================
          if ( is_null($this->post_obj) ) {
            $this->post_obj = get_post( $this->post_id , $this->_post_type );
          }
          return $this->post_obj;
          break;

          default:
          throw(new Error('Key ' + $key + ' not configured'));
        }
      }
    }

    protected function update_cached_value($key, $value) {
      $classname = get_called_class();
      // Remove _id if present at end of key
      if ( substr($key, -3) == '_id') {
        $stemkey = substr($key, 0, length($key) - 3);
      } else {
        $stemkey = $key;
      }

      if ( array_key_exists($stemkey, $classname::$meta_keys) ) {
        $conf = $classname::$meta_keys[$stemkey];
        switch($conf['type']) {

          case 'meta_attrib':
          update_post_meta( $post_id, $conf->meta_key, $value );
          $this->cache[$key] = $value;
          break;

          case 'meta_attrib_date':
          if ( is_string($value) ) {
            $value=strtotime($value);
          }
          $value = date('Y-m-d', $value);
          update_post_meta( $post_id, $conf->meta_key, $value );
          $this->cache[$key] = $value;
          break;

          case 'related_post':
          if ( substr($key, -3) == '_id' ) { // Only do _id updates
            $idkey = $stemkey . '_id';
            update_post_meta( $this->post_id, $conf->id_meta_key, $value );
            $this->cache[$idkey] = $value;
            $this->cache[$stemkey] = null;
          } else {
            throw(new Error($stemkey . ' can only be updated when suffixed _id'));
          }
          break;

          default:
          throw(new Error('Type of meta config not recognised'));
        }
      } else {
        switch ( $key ) {
          case 'title':
          $this->post->post_title = $value;
          update_post($this->post);
          break;

          default:
          throw(new Error('Key ' + $key + ' not configured'));
        }
      }
    }

    public function attachTerm($termobj) {
      $termclass = get_class($termobj);
      if ( !($termobj instanceof TMBaseTax ) ) {
        throw(new Error('Object ' . $termclass . ' is not a child of TMBaseTax'));
      }
      // Add the term to the post
      return wp_set_object_terms( $this->post_id, $termobj->slug , $termclass::$taxonomy , false);
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
