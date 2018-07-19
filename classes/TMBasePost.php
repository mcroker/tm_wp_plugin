<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once('TMBaseGeneric.php');

if ( ! class_exists('TMBasePost')):
  class TMBasePost extends TMBaseGeneric {
    protected static $post_type;
    protected static $meta_keys = [];
    private $_post_type;
    protected $cache = [];

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
        'post_title'  => $title,
        'post_status' => 'publish',
        'post_type'   => $classname::$post_type
      ) );
      $classname = get_called_class();
      return new $classname($post_id);
    }

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

    public static function getWithMetaValue($meta_key, $meta_value) {
      $classname = get_called_class();
      $posts = get_posts(array (
        'numberposts' => -1,
        'post_type'	  => $classname::$post_type,
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
