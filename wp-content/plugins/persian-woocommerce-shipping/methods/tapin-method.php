<?php
/**
 * Developer : MahdiY
 * Web Site  : MahdiY.IR
 * E-Mail    : M@hdiY.IR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( class_exists( 'PWS_Tapin_Method' ) ) {
	return;
} // Stop if the class already exists

/**
 * Class WC_Tapin_Method
 *
 * @author mahdiy
 *
 */
class PWS_Tapin_Method extends PWS_Shipping_Method {

	protected $post_type = null;

	public function init() {

		parent::init();

		$this->extra_cost = $this->get_option( 'extra_cost', 0 );
		$this->fixed_cost = $this->get_option( 'fixed_cost' );

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	public function init_form_fields() {

		$currency_symbol = get_woocommerce_currency_symbol();

		$this->instance_form_fields += [
			'extra_cost' => [
				'title'       => 'هزینه های اضافی',
				'type'        => 'text',
				'description' => 'هزینه های اضافی علاوه بر نرخ پستی را می توانید وارد نمائید، (مثل: هزینه های بسته بندی و ...) مبلغ ثابت را به ' . $currency_symbol . ' وارد نمائید',
				'default'     => 0,
				'desc_tip'    => true,
			],
			'fixed_cost' => [
				'title'       => 'هزینه ثابت',
				'type'        => 'text',
				'description' => "<b>توجه:</b>
								<ul>
									<li>1. برای محاسبه آنلاین هزینه توسط تاپین خالی بگذارید.</li>
									<li>2. صفر به معنی رایگان است. یعنی هزینه حمل و نقل برعهده فروشگاه شما است.</li>
									<li>3. در صورت تعیین هزینه ثابت حمل و نقل این قیمت دقیقا به مشتری نمایش داده می شود.</li>
									<li>4. این گزینه مناسب فروشگاه هایی است که وزن محصولات خود را وارد نکرده اند.</li>
								</ul>
								",
				'default'     => ''
			],
		];
	}

	public function is_available( $package = array() ) {

		if ( ! PWS_Tapin::is_enable() ) {
			return false;
		}

		return parent::is_available( $package );
	}

	public function calculate_shipping( $package = array() ) {

		if ( $this->free_shipping( $package ) ) {
			return true;
		}

		if ( $this->fixed_cost !== '' ) {

			$shipping_total = $this->fixed_cost;

		} else {

			$weight = PWS_Tapin::get_cart_weight();

			$price = 0;

			foreach ( WC()->cart->get_cart() as $cart_item ) {

				if ( $cart_item['data']->is_virtual() ) {
					continue;
				}

				$price += $cart_item['data']->get_price() * $cart_item['quantity'];
			}

			$destination = $package['destination'];

			$payment_method = WC()->session->get( 'chosen_payment_method' );

			$pay_type = 0;

			if ( $payment_method !== 'cod' ) {
				$pay_type = 1;
			}

			if ( get_woocommerce_currency() == 'IRT' ) {
				$price *= 10;
			}

			if ( get_woocommerce_currency() == 'IRHR' ) {
				$price *= 1000;
			}

			if ( get_woocommerce_currency() == 'IRHT' ) {
				$price *= 10000;
			}

			$shop = PWS_Tapin::shop();

			$data = [
				'price'         => $price,
				'weight'        => ceil( $weight ),
				'order_type'    => $this->post_type,
				'pay_type'      => $pay_type,
				'to_province'   => intval( $destination['state'] ),
				'from_province' => intval( $shop->province_code ?? 1 ),
				'to_city'       => intval( $destination['city'] ),
				'from_city'     => intval( $shop->city_code ?? 1 ),
			];

			PWS_Tapin::set_gateway( PWS()->get_option( 'tapin.gateway' ) );

			$response = PWS_Tapin::price( $data );

			if ( $response->returns->status != 200 ) {
				PWS()->log( __METHOD__ . ' Line: ' . __LINE__ );
				PWS()->log( $data );
				PWS()->log( $response );

				return false;
			}

			$shipping_total = $response->entries->total + $shop->total_price;

			$shipping_total = ceil( $shipping_total / 1000 ) * 1000;

			$shipping_total = PWS()->convert_currency( $shipping_total );

			$shipping_total += $this->extra_cost;
		}

		$this->add_rate_cost( $shipping_total, $package );
	}
}
