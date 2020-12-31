<?php
/**
 * Developer : MahdiY
 * Web Site  : MahdiY.IR
 * E-Mail    : M@hdiY.IR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class PWS_Order {

	public static function get_order_weight( WC_Order $order ) {

		$weight = $order->get_meta( 'tapin_weight' );

		if ( $weight != '' ) {
			return $weight;
		}

		$weight = PWS()->get_option( 'tapin.package_weight', 500 );

		foreach ( $order->get_items() as $order_item ) {

			/** @var WC_Product $product */
			$product = $order_item->get_product();

			if ( $product->is_virtual() ) {
				continue;
			}

			if ( $product->has_weight() ) {
				$_weight = wc_get_weight( $product->get_weight(), 'g' );
			} else {
				$_weight = PWS()->get_option( 'tapin.product_weight', 500 );
			}

			$weight += $_weight * $order_item->get_quantity();
		}

		return $weight;
	}

	public static function get_shipping_method( WC_Order $order, $label = false ) {

		$shipping_method = null;

		foreach ( $order->get_shipping_methods() as $shipping_item ) {
			if ( $shipping_item->get_method_id() == 'Tapin_Pishtaz_Method' ) {
				$shipping_method = 1;
			} else if ( $shipping_item->get_method_id() == 'Tapin_Sefareshi_Method' ) {
				$shipping_method = 0;
			}
		}

		$labels = [
			'سفارشی',
			'پیشتاز'
		];

		if ( $label ) {
			return $labels[ $shipping_method ] ?? null;
		}

		return $shipping_method;
	}

}
