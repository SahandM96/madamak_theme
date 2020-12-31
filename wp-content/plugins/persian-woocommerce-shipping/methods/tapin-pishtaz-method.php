<?php
/**
 * Developer : MahdiY
 * Web Site  : MahdiY.IR
 * E-Mail    : M@hdiY.IR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( class_exists( 'Tapin_Pishtaz_Method' ) ) {
	return;
} // Stop if the class already exists

/**
 * Class WC_Tapin_Method
 *
 * @author mahdiy
 *
 */
class Tapin_Pishtaz_Method extends PWS_Tapin_Method {

	protected $post_type = 1;

	public function __construct( $instance_id = 0 ) {

		$this->id                 = 'Tapin_Pishtaz_Method';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = __( 'پست تاپین - پیشتاز' );
		$this->method_description = 'پیشخوان مجازی تاپین - ارسال کالا با استفاده از پست پیشتاز';

		parent::__construct();
	}

	public function is_available( $package = array() ) {

		$weight = PWS_Tapin::get_cart_weight();

		if ( $weight > 30000 ) {
			return false;
		}

		return parent::is_available( $package );
	}
}
