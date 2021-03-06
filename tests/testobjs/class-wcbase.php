<?php
class WCBase extends WCBaseGeneric {
	protected static $meta_keys = array(
		'meta_attrib_number' => array(
			'type'     => 'number',
			'meta_key' => 'meta_attrib_number',
		),
		'meta_attrib_date'   => array(
			'type'     => 'date',
			'meta_key' => 'meta_attrib_date',
		),
		'meta_attrib_time'   => array(
			'type'     => 'time',
			'meta_key' => 'meta_attrib_time',
		),
		'meta_attrib_text'   => array(
			'type'     => 'text',
			'meta_key' => 'meta_attrib_text',
		),
		'meta_attrib_code'   => array(
			'type'     => 'code',
			'meta_key' => 'meta_attrib_code',
		),
		'meta_attrib_string' => array(
			'type'     => 'string',
			'meta_key' => 'meta_attrib_string',
		),
		'meta_attrib_check'  => array(
			'type'     => 'check',
			'meta_key' => 'meta_attrib_check',
		),
		'meta_attrib_logo'   => array(
			'type'     => 'logo',
			'meta_key' => 'meta_attrib_logo',
		),
		'meta_attrib_select' => array(
			'type'     => 'select',
			'meta_key' => 'meta_attrib_select',
		),
		'meta_attrib_object' => array(
			'type'     => 'object',
			'meta_key' => 'meta_attrib_object',
		),
	);

	public $values = [];

	function update_meta_value( $meta_key, $value ) {
		if ( array_key_exists( $meta_key, static::$meta_keys ) ) {
			$this->values[ $meta_key ] = $value;
		} else {
			throw new Exception('Key not recognsied ' + $meta_key);
		}
	}
	function get_meta_value( $meta_key ) {
		if ( array_key_exists( $meta_key, static::$meta_keys ) ) {
			if ( array_key_exists( $meta_key, $this->values ) ) {
				return $this->values[ $meta_key ];
			} else {
				return null;
			}
		} else {
			throw new Exception('Key not recognsied ' + $meta_key);
		}
	}
}
