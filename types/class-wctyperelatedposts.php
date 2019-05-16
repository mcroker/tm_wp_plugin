<?php
/**
 * WCTypeNumber
 *
 * @category
 * @package  WordCider
 * @author   Martin Croker <oss@croker.ltd>
 * @license  Apache2
 * @link
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require_once 'class-wctypebase.php';

if ( ! class_exists( 'WCTypeRelatedPosts' ) ) :
	/**
	 * WCTypeNumber
	 */
	class WCTypeRelatedPosts extends WCTypeBase {

		/**
		 * Throw an exception if somebody tries to create a relatedPosts field
		 *
		 * It is expected if this field type is required it is implemented by the subclass
		 *
		 * @param string[] $settings Additional settings to pass to display.
		 *
		 * @return void
		 */
		public function echo_formfield( $settings = [] ) {
			if ( ! is_array( $settings ) ) throw( new Exception( 'Expected $settings to be array|null' ) );

			$htmlsettings = $this->get_html_settings( $settings );

			$classname           = get_called_class();
			$relatedclass        = $settings['classname'];
			$allposts            = $relatedclass::getAll();
			$settings['options'] = [];
			foreach ( $allposts as $relatedpost ) {
				$settings['options'][ $relatedpost->ID ] = $relatedpost->title;
			}
			static::base_form_field_Select( $fieldkey, $value, $label, $settings );
		}

	}

endif;
