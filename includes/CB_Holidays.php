<?php
/**
 * Provide holidays
 *
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
class CB_Holidays {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
	/**
	 * holiday_provider short name
	 *
	 * @var string
	 */
	static $holiday_provider = 'none';

	/**
	 * Return an instance of this class.
	 *
	 * @since 2.0.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			try {
				self::$instance = new self;
				self::initialize();
			} catch ( Exception $err ) {
				do_action( 'commons_booking_holidays_failed', $err );
				if ( WP_DEBUG ) {
					throw $err->getMessage();
				}
			}
		}
		return self::$instance;
    }
	/**
	 * Initialize
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public static function initialize() {
		}
	/**
	 * Get holiday providers
	 *
	 * @since 2.0.0
	 *
	 * @return array $providers
	 */
	public static function get_providers() {
		$providers = Yasumi\Yasumi::getProviders();
		return $providers;
	}
	/**
	 * Return an array of holidays
	 *
	 * @since 2.0.0
	 *
	 * @param array $years
	 * @return array $holidays
	 */
	public static function get_holidays_list ( $years_array = array( 2018 ) ) {

		$holiday_provider = CB_Settings::get('calendar', 'holiday_provider');
		$holidays_array = array();

		if ( isset( $holiday_provider) && ! empty ( $holiday_provider ) ) {

			$holiday_provider_full_name = self::get_providers()[$holiday_provider];


			if ( ! empty ($years_array)) {

				foreach ($years_array as $year ) {

					$holiday_object = Yasumi\Yasumi::create(	$holiday_provider_full_name, $year, 'de_DE' );
					// filter for official holidays
					$official = new Yasumi\Filters\OfficialHolidaysFilter($holiday_object->getIterator());

					foreach ($official as $day) {
							$name = $day->getName();
							$date = $day->__toString();
							$holidays_array[$date] = $name;
					}
				}
			}
		}
		return $holidays_array;
	}


}
add_action( 'plugins_loaded', array( 'CB_Holidays', 'get_instance' ) );
