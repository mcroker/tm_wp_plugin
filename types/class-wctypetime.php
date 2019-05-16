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

if ( ! class_exists( 'WCTypeTime' ) ) :
	/**
	 * WCTypeNumber
	 */
	class WCTypeTime extends WCTypeBase {

		/**
		 * Set DateTime Value
		 *
		 * @return String Meta value persisted on WordPress record.
		 */
		public function set_value( $value ) {
			if ( is_a( $value, 'DateTime' ) ) {
				parent::set_value($value);
			} else {
				if ( is_string( $value ) ) {
					$value = strtotime( $value, 0 );
				}
				if ( is_numeric( $value ) ) {
					$dt = new DateTime();
					$dt->setTimestamp( $value );
					$tz = get_option( 'timezone_string' );
					if ( ! empty( $tz ) ) {
						$dt->setTimezone( new DateTimeZone( get_option( 'timezone_string' ) ) );
					}
					parent::set_value($dt);
				} else {
					throw new Exception('Cannot convert ' . $value . ' to date time object');
				}
			}
		}

		/**
		 * Set DateTime Value
		 *
		 * @return String Meta value persisted on WordPress record.
		 */
		public function get_serialized() {
			$value = parent::get_serialized();
			return $value->getTimestamp();
		}

		public function echo_formfield( $settings = [] ) {
			$htmlsettings = $this->get_html_settings( $settings );
			$value = $this->get_value();
			if ( is_null( $value ) ) {
				$value = new DateTime();
			}
			if ( ! $value instanceof DateTime ) {
				throw( new Exception( '$value must be instance of DateTime or null' ) );
			}
			?>
			<input class="<?php echo esc_attr( $htmlsettings['inputclass'] ); ?>"
			type="datetime-local"
			name="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			id="<?php echo esc_attr( $this->get_elem_name() ); ?>"
			value="<?php echo esc_attr( $value->format( 'TH:i' ) ); ?>"
			/>
			<?php
		}


	}
endif;
