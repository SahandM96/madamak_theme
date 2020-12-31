<?php
/**
 * Plugin Name: افزونه حمل و نقل ووکامرس
 * Plugin URI: http://MahdiY.ir
 * Description: افزونه قدرتمند حمل و نقل ووکامرس با قابلیت ارسال از طریق پست پیشتاز، سفارشی، پیک موتوری و تیپاکس
 * Version: 2.2.3
 * Author: MahdiY
 * Author URI: http://MahdiY.ir
 * WC requires at least: 3.0.0
 * WC tested up to: 4.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! defined( 'PWS_VERSION' ) ) {
	define( 'PWS_VERSION', '2.2.3' );
}

if ( ! defined( 'PWS_DIR' ) ) {
	define( 'PWS_DIR', __DIR__ );
}

if ( ! defined( 'PWS_FILE' ) ) {
	define( 'PWS_FILE', __FILE__ );
}

if ( ! defined( 'PWS_URL' ) ) {
	define( 'PWS_URL', plugin_dir_url( __FILE__ ) );
}

function PWS() {

	if ( PWS_Tapin::is_enable() ) {
		return PWS_Tapin::instance();
	}

	return PWS_Core::instance();
}

add_action( 'woocommerce_loaded', function () {

	include( "includes/class-pws.php" );
	include( "includes/class-ajax.php" );
	include( "includes/class-tapin.php" );
	include( "includes/class-order.php" );
	include( "includes/class-sms.php" );
	include( "includes/class-tools.php" );
	include( "includes/class-notice.php" );
	include( "includes/class-status.php" );
	include( "includes/class-version.php" );

	include( "includes/admin/class-admin.php" );

	if ( ! PWS_Tapin::is_enable() ) {
		include( "data/state_city.php" );
		include( "includes/taxonomy-shipping.php" );
	}

} );
