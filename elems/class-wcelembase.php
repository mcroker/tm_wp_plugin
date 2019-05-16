<?php
/**
* WCElemBase
*
* @category
* @package  WordCider
* @author   Martin Croker <oss@croker.ltd>
* @license  Apache2
* @link
*/

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

if ( ! class_exists( 'WCElemBase' ) ) :
	/**
	* WCBaseElement
	*/
	abstract class WCElemBase {

		private $parent_class;
		private $parent_object;
		private $elem_name;
		private $options;
		private $meta_key;

		/**
		* __construct
		*
		* @param string $attrib_name   (Required) Name of attributre referenced from meta_keys.
		* @param string $parent_object (Required) Parent BaseGeneric object - used for persists.
		* @param string $options       Type specific options.
		*/
		public function __construct( $parent_object, $elem_name, $value = null, $options = [] ) {
			if ( ! is_object( $parent_object ) ) throw( new Exception( 'Expected $parent_object to be object' ) );
			if ( ! is_string( $elem_name ) ) throw( new Exception( 'Expected $elem_name to be string' ) );
			if ( ! is_array( $options ) )      throw( new Exception( 'Expected $options to be array or null' ) );

			$this->parent_object = $$parent_object;
			$this->parent_class  = get_class ( $parent_object );
			$this->elem_name     = $elem_name;
			$this->value         = $value;
			$this->options       = $options;

		}

		/**
		* Get meta key for attribute in WordPress
		*
		* @return String Meta value persisted on WordPress record.
		*/
		public function get_elem_name() {
			return $this->parent_class . '_' . $this->elem_name;
		}

		abstract function echo_html();
	}
endif;

?>
