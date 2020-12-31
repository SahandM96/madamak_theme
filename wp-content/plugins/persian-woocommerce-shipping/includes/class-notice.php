<?php
/**
 * Developer : MahdiY
 * Web Site  : MahdiY.IR
 * E-Mail    : M@hdiY.IR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class PWS_Notice {

	public function __construct() {

		add_action( 'admin_notices', [ $this, 'admin_notices' ], 5 );
		add_action( 'wp_ajax_pws_dismiss_notice', [ $this, 'dismiss_notice' ] );
	}

	public function admin_notices() {

		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'manage_woocommerce' ) ) {
			return false;
		}

		foreach ( $this->notices() as $notice ) {

			if ( $notice['condition'] == false || $this->is_dismiss( $notice['id'] ) ) {
				continue;
			}

			$dismissible = $notice['dismiss'] ? 'is-dismissible' : '';

			printf( '<div class="notice pws_notice notice-success %s" id="pws_%s"><p>%s</p></div>', $dismissible, $notice['id'], $notice['content'] );

			break;
		}

		?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                $(document.body).on('click', '.notice-dismiss', function () {

                    let notice = $(this).closest('.pws_notice');
                    notice = notice.attr('id');

                    if (notice !== undefined && notice.indexOf('pws_') !== -1) {

                        notice = notice.replace('pws_', '');

                        $.ajax({
                            url: "<?php echo admin_url( 'admin-ajax.php' ) ?>",
                            type: 'post',
                            data: {
                                notice: notice,
                                action: 'pws_dismiss_notice',
                                nonce: "<?php echo wp_create_nonce( 'pws_dismiss_notice' ); ?>"
                            }
                        });
                    }

                });
            });
        </script>
		<?php
	}

	public function notices() {
		global $pagenow;

		$page = $_GET['page'] ?? null;
		$tab  = $_GET['tab'] ?? null;

		return [
			[
				'id'        => 'tapin_banner',
				'content'   => '<a href="https://yun.ir/pwstd" target="_blank"><img src="' . PWS_URL . '/assets/images/banner.jpg" style="width: 100%;"></a>',
				'condition' => ! PWS_Tapin::is_enable() && $pagenow == 'index.php',
				'dismiss'   => 6 * MONTH_IN_SECONDS,
			],
			[
				'id'        => 'tapin_fixed',
				'content'   => '<b>تاپین:</b> با پیشخوان مجازی پست آشنایی دارید؟ بدون مراجعه به پست، بارکد پستی بگیرید و بسته هایتان را ارسال کنید. از <a href="https://yun.ir/pwstt" target="_blank">اینجا</a> راهنمای نصب و پیکربندی آن را مطالعه کنید.',
				'condition' => ! PWS_Tapin::is_enable() && $page == 'pws-tools',
				'dismiss'   => false,
			],
			[
				'id'        => 'tapin_shipping',
				'content'   => '<b>تاپین:</b> هزینه پست سفارشی و پیشتاز را بصورت دقیق محاسبه کنید و بدون مراجعه به پست، بارکد پستی بگیرید و بسته هایتان را ارسال کنید. از <a href="https://yun.ir/pwsts" target="_blank">اینجا</a> راهنمای نصب و پیکربندی آن را مطالعه کنید.',
				'condition' => ! PWS_Tapin::is_enable() && $page == 'wc-settings' && $tab == 'shipping',
				'dismiss'   => 6 * MONTH_IN_SECONDS,
			],
			[
				'id'        => 'woocommerce_invoice',
				'content'   => '<b>فاکتور ووکامرس:</b> برای چاپ برچسب پستی و فاکتورهای سفارش هایتان <a href="https://yun.ir/mwooi" target="_blank">افزونه فاکتور حرفه ای ووکامرس</a> را امتحان کنید!',
				'condition' => ! PWS_Tapin::is_enable() && ! is_plugin_active( 'woocommerce-invoice/woocommerce-invoice.php' ),
				'dismiss'   => 6 * MONTH_IN_SECONDS,
			],
			[
				'id'        => 'pws_video',
				'content'   => '<b>آموزش:</b> برای پیکربندی حمل و نقل می توانید از <a href="https://yun.ir/pwsvideo" target="_blank">آپارات</a> فیلم های آموزشی افزونه را مشاهده کنید.',
				'condition' => class_exists( 'WC_Shipping_Zones' ) && ! count( WC_Shipping_Zones::get_zones() ),
				'dismiss'   => 6 * MONTH_IN_SECONDS,
			],
			[
				'id'        => 'woocommerce_invoice_tapin',
				'content'   => '<b>فاکتور ووکامرس:</b> برای چاپ برچسب های استاندارد پستی تاپین نیاز به <a href="https://yun.ir/twooi" target="_blank">افزونه فاکتور حرفه ای ووکامرس</a> دارید. لطفا آن را تهیه، نصب و فعال نمایید.',
				'condition' => PWS_Tapin::is_enable() && ! is_plugin_active( 'woocommerce-invoice/woocommerce-invoice.php' ),
				'dismiss'   => false,
			],
		];
	}

	public function dismiss_notice() {

		check_ajax_referer( 'pws_dismiss_notice', 'nonce' );

		$this->set_dismiss( $_POST['notice'] );

		die();
	}

	public function set_dismiss( $notice_id ) {

		$notices = wp_list_pluck( $this->notices(), 'dismiss', 'id' );

		if ( isset( $notices[ $notice_id ] ) && $notices[ $notice_id ] ) {
			set_transient( 'pws_notice_' . $notice_id, 'DISMISS', $notices[ $notice_id ] );
		}
	}

	public function is_dismiss( $notice_id ) {
		return get_transient( 'pws_notice_' . $notice_id ) !== false;
	}

}

new PWS_Notice();