<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists('TMBaseGeneric')):
  abstract class TMBaseGeneric {

    protected $_id;
    protected $_obj;

    abstract protected function update_meta_value($meta_key, $value);
    abstract protected function get_meta_value($meta_key);

    function __construct($id, $obj = null) {
      $this->_id = $id;
      $this->_obj = $obj;
    }

    public function __get ($key) {
      return $this->get_value($key);
    }

    public function __set ($key, $value) {
      $this->update_value($key, $value);
    }

    protected static function get_stemkey($key) {
      if ( substr($key, -3) == '_id') {
        return substr($key, 0, strlen($key) - 3);
      } else {
        return $key;
      }
    }

    protected function update_value($key, $value) {
      $classname = get_called_class();
      $stemkey = TMBaseGeneric::get_stemkey($key);
      if ( array_key_exists($stemkey, $classname::$meta_keys) ) {
        $conf = $classname::$meta_keys[$stemkey];
        switch($conf['type']) {
          case 'meta_attrib':        $this->update_attrib_string($key, $conf['meta_key'], $value); break;
          case 'meta_attrib_number': $this->update_attrib_string($key, $conf['meta_key'], $value); break;
          case 'meta_attrib_code':   $this->update_attrib_string($key, $conf['meta_key'], $value); break;
          case 'meta_attrib_text':   $this->update_attrib_string($key, $conf['meta_key'], $value); break;
          case 'meta_attrib_date':   $this->update_attrib_date($key, $conf['meta_key'], $value); break;
          case 'meta_attrib_time':   $this->update_attrib_time($key, $conf['meta_key'], $value); break;
          case 'meta_attrib_object': $this->update_attrib_serializedobject($key, $conf['meta_key'], $value); break;
          default:                   throw(new Exception('Type of meta config not recognised or invalid for update'));
        }
      } else {
        switch ( $key ) {
          case 'ID':    return $this->_id; break;
          default:      throw(new Exception('Key ' + $key + ' not configured'));
        }
      }
    }

    protected function get_value($key) {
      $classname = get_called_class();
      $stemkey = TMBaseGeneric::get_stemkey($key);
      if ( array_key_exists($stemkey, $classname::$meta_keys) ) {
        $conf = $classname::$meta_keys[$stemkey];
        switch($conf['type']) {
          case 'meta_attrib':        return $this->get_attrib_string($key, $conf['meta_key']); break;
          case 'meta_attrib_number': return $this->get_attrib_string($key, $conf['meta_key']); break;
          case 'meta_attrib_code':   return $this->get_attrib_string($key, $conf['meta_key']); break;
          case 'meta_attrib_text':   return $this->get_attrib_string($key, $conf['meta_key']); break;
          case 'meta_attrib_date':   return $this->get_attrib_date($key, $conf['meta_key']); break;
          case 'meta_attrib_time':   return $this->get_attrib_time($key, $conf['meta_key']); break;
          case 'meta_attrib_object': return $this->get_attrib_seralizedobject($key, $conf['meta_key']); break;
          default:                   throw(new Exception('Type of meta config not recognised or invalid for get'));
        }
      } else {
        switch ( $key ) {
          case 'ID':    return $this->_id; break;
          default:      throw(new Exception('Key ' + $key + ' not configured'));
        }
      }
    }

    // attrib_string ==================================================
    protected function update_attrib_string($key, $meta_key, $value) {
      $this->update_meta_value($meta_key, $value);
      $this->cache[$key] = $value;
    }

    protected function get_attrib_string($key, $meta_key) {
      if ( ! array_key_exists( $key, $this->cache ) ) {
        $this->cache[$key] = $this->get_meta_value($meta_key);
      }
      return $this->cache[$key];
    }

    // attrib_object ==================================================
    protected function update_attrib_serializedobject($key, $meta_key, $value) {
      $serialvalue = serialize($value);
      $this->update_meta_value($meta_key, $serialvalue);
      $this->cache[$key] = $value;
    }

    protected function get_attrib_seralizedobject($key, $meta_key) {
      if ( ! array_key_exists( $key, $this->cache ) ) {
        $serialvalue = $this->get_meta_value($meta_key);
        $value = unserialize($serialvalue);
        $this->cache[$key] = $value;
      }
      return $this->cache[$key];
    }

    // attrib_date --------------------------------------------------
    protected function update_attrib_date($key, $meta_key, $value) {
      if ( is_string($value) ) {
        $value=strtotime($value);
      }
      $value = date('Y-m-d', $value);
      $this->update_meta_value($meta_key, $value);
      $this->cache[$key]=$value;
    }

    protected function get_attrib_date($key, $meta_key) {
      if ( ! array_key_exists( $key, $this->cache ) ) {
        $value = $this->get_meta_value($meta_key);
        if ( is_null($value) || $value == '') {
          $this->cache[$key] = 0;
        } else {
          $this->cache[$key] = strtotime($value);
        }
      }
      return $this->cache[$key];
    }

    // attrib_datetime --------------------------------------------------
    protected function update_attrib_time($key, $meta_key, $value) {
      if ( is_string($value) ) {
        $value=strtotime($value,0);
      }
      if (is_a($value, 'DateTime')) {
        $value=$value->getTimestamp();
      }
      $this->update_meta_value($meta_key, $value);
      $this->cache[$key]=$value;
    }

    protected function get_attrib_time($key, $meta_key) {
      if ( ! array_key_exists( $key, $this->cache ) ) {
        $value = $this->get_meta_value($meta_key);
        if ( is_null($value) || $value == '') {
          $this->cache[$key] = 0;
        } else {
          $this->cache[$key] = (int)$value;
        }
      }
      $dt = new DateTime();
      $dt->setTimestamp($this->cache[$key]);
      $dt->setTimezone(new DateTimeZone(get_option('timezone_string')));
      return $dt;
    }


  }
endif;
