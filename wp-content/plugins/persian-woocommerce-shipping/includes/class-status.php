<?php
/**
 * Developer : MahdiY
 * Web Site  : MahdiY.IR
 * E-Mail    : M@hdiY.IR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class PWS_Status {

	public static $status = [
		2 => 'wc-pws-ready-to-ship',

		1 => 'wc-pws-packaged',

		10 => 'wc-pws-returned',
		11 => 'wc-pws-returned',
		83 => 'wc-pws-returned',

		7  => 'wc-completed',
		70 => 'wc-completed',
		71 => 'wc-completed',
		72 => 'wc-completed',

		80 => 'wc-pws-deleted',

		5 => 'wc-pws-shipping',
		8 => 'wc-pws-shipping',

		3  => 'wc-pws-need-review',
		4  => 'wc-pws-need-review',
		6  => 'wc-pws-need-review',
		9  => 'wc-pws-need-review',
		12 => 'wc-pws-need-review',
		13 => 'wc-pws-need-review',
		81 => 'wc-pws-need-review',
		82 => 'wc-pws-need-review',
	];

	public function __construct() {
		add_action( 'init', [ $this, 'register_order_statuses' ] );
		add_filter( 'wc_order_statuses', [ $this, 'add_order_statuses' ], 10, 1 );
		add_filter( 'woocommerce_reports_order_statuses', [ $this, 'reports_statuses' ], 10, 1 );
		add_filter( 'woocommerce_order_is_paid_statuses', [ $this, 'paid_statuses' ], 10, 1 );
		add_filter( 'bulk_actions-edit-shop_order', [ $this, 'bulk_actions' ], 20, 1 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		if ( PWS_Tapin::is_enable() ) {
			add_action( 'add_meta_boxes', [ $this, 'order_meta_box' ] );
			add_action( 'save_post', [ $this, 'save_order_meta_box' ], 1000, 3 );
			add_action( 'manage_posts_extra_tablenav', [ $this, 'top_order_list' ], 20, 1 );
			add_action( 'wp_ajax_pws_change_order_status', [ $this, 'change_status_callback' ] );
			add_action( 'wp_ajax_nopriv_pws_change_order_status', [ $this, 'change_status_callback' ] );
			add_action( 'wp', [ $this, 'check_status_scheduled' ] );
			add_action( 'pws_check_status', [ $this, 'check_status_callback' ] );
		}
	}

	public function get_statues() {

		$statuses = [];

		if ( PWS()->get_option( 'tools.status_enable' ) == 1 ) {

			$statuses['wc-pws-in-stock'] = 'ارسال شده به انبار';
			$statuses['wc-pws-packaged'] = 'بسته بندی شده';
			$statuses['wc-pws-courier']  = 'تحویل پیک';

		}

		if ( PWS_Tapin::is_enable() ) {
			$statuses['wc-pws-packaged']      = 'بسته بندی شده';
			$statuses['wc-pws-ready-to-ship'] = 'آماده به ارسال';
			$statuses['wc-pws-returned']      = 'برگشتی';
			$statuses['wc-pws-deleted']       = 'حذف شده';
			$statuses['wc-pws-shipping']      = 'در حال ارسال';
			$statuses['wc-pws-need-review']   = 'نیازمند بررسی';
		}

		return $statuses;
	}

	public function register_order_statuses() {

		foreach ( $this->get_statues() as $status => $label ) {
			register_post_status( $status, array(
				'label'                     => $label,
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( $label . ' <span class="count">(%s)</span>', $label . ' <span class="count">(%s)</span>' )
			) );
		}

	}

	public function add_order_statuses( $order_statuses ) {
		$new_order_statuses = [];

		foreach ( $order_statuses as $key => $status ) {
			$new_order_statuses[ $key ] = $status;

			if ( 'wc-processing' === $key ) {

				foreach ( $this->get_statues() as $status => $label ) {
					$new_order_statuses[ $status ] = $label;
				}

			}
		}

		return $new_order_statuses;
	}

	public function reports_statuses( $order_status ) {

		$dont_report = [
			'wc-pws-returned',
			'wc-pws-deleted'
		];

		foreach ( $this->get_statues() as $status => $label ) {
			if ( ! in_array( $status, $dont_report ) ) {
				$order_status[] = str_replace( 'wc-', '', $status );
			}
		}

		return $order_status;
	}

	public function paid_statuses( $order_status ) {

		$dont_paid = [
			'wc-pws-returned',
			'wc-pws-deleted'
		];

		foreach ( $this->get_statues() as $status => $label ) {
			if ( ! in_array( $status, $dont_paid ) ) {
				$order_status[] = str_replace( 'wc-', '', $status );
			}
		}

		return $order_status;
	}

	public function bulk_actions( $actions ) {

		foreach ( $this->get_statues() as $status => $label ) {
			$key                       = str_replace( 'wc-', '', $status );
			$actions[ 'mark_' . $key ] = 'تغییر وضعیت به ' . $label;
		}

		return $actions;
	}

	public function enqueue_scripts() {

		wp_enqueue_style( 'pws_order_status', PWS_URL . 'assets/css/status.css' );

		if ( ! PWS_Tapin::is_enable() ) {
			return false;
		}

		$screen = get_current_screen();

		if ( $screen->id == 'edit-shop_order' ) {
			wp_enqueue_script( 'pws_tapin_list', PWS_URL . 'assets/js/tapin-list.js' );
		}

		if ( $screen->id == 'shop_order' ) {
			wp_enqueue_script( 'pws_tapin_list', PWS_URL . 'assets/js/tapin-order.js' );
		}
	}

	public function top_order_list( $which ) {
		global $typenow;

		if ( 'shop_order' === $typenow && 'top' === $which ) {
			?>
            <div class="alignleft actions custom">
                <button type="button" id="pws-tapin-submit" class="button-primary"
                        title="جهت ثبت سفارشات انتخاب شده در پنل تاپین و دریافت بارکد پستی، کلیک کنید.">ثبت در تاپین
                </button>
                <button type="button" id="pws-tapin-ship" class="button-primary"
                        title="پس از ثبت سفارش در پنل، جهت اعلام به پست برای جمع آوری بسته اینجا کلیک کنید.">آماده ارسال
                </button>
            </div>
			<?php
		}
	}

	public function order_meta_box() {
		add_meta_box( 'tapin_order', 'تاپین', array( $this, 'order_meta_box_callback' ), 'shop_order', 'side' );
	}

	public function order_meta_box_callback( $post, $args ) {

		/** @var WC_Order $order */
		$order = wc_get_order( $post->ID );

		$order_uuid   = $order->get_meta( 'tapin_order_uuid' );
		$tapin_weight = PWS_Order::get_order_weight( $order );

		$shipping_state = $order->get_meta( '_shipping_state_id' );
		$shipping_city  = $order->get_meta( '_shipping_city_id' );

		$shipping_method = PWS_Order::get_shipping_method( $order )

		?>

		<?php if ( empty( $order_uuid ) ) { ?>

            <p class="form-field-wide">
                <label for="tapin_weight">وزن سفارش:</label>
                <input type="number" name="tapin_weight" id="tapin_weight" style="width: 100%"
                       value="<?php echo $tapin_weight; ?>">
            </p>

            <p class="form-field-wide">
                <label for="shipping_state_city">استان/شهر مقصد:</label>
                <select name="shipping_state_city" id="shipping_state_city" style="width: 100%">
					<?php

					foreach ( PWS()::states() as $state_key => $state ) {
						foreach ( PWS()::cities( $state_key ) as $city_key => $city ) {
							$key      = "{$state_key}-{$city_key}";
							$selected = selected( $key, "{$shipping_state}-{$shipping_city}", false );
							printf( "<option value='%s' %s>%s - %s</option>", $key, $selected, $state, $city );
						}
					}

					?>
                </select>
            </p>

            <p style="display: none;" id="pws-tapin-submit-tip">لطفا ابتدا روی بروزرسانی کلیک نمایید.</p>

            <button type="button" id="pws-tapin-submit" class="button-primary"
                    title="جهت ثبت سفارشات انتخاب شده در پنل تاپین و دریافت بارکد پستی، کلیک کنید.">ثبت در تاپین
            </button>
		<?php } else { ?>

            <p class="form-field-wide">
                <label>وزن سفارش:</label>
                <input type="number" style="width: 100%"
                       value="<?php echo $tapin_weight; ?>" disabled="disabled">
            </p>

            <p class="form-field-wide">
                <label>نوع پست:</label>
                <select style="width: 100%" disabled="disabled">
                    <option value="" <?php selected( null, $shipping_method ); ?>>غیرپستی</option>
                    <option value="0" <?php selected( 0, $shipping_method ); ?>>پست سفارشی</option>
                    <option value="1" <?php selected( 1, $shipping_method ); ?>>پست پیشتاز</option>
                </select>
            </p>

            <button type="button" id="pws-tapin-ship" class="button-primary"
                    title="پس از ثبت سفارش در پنل، جهت اعلام به پست برای جمع آوری بسته اینجا کلیک کنید.">آماده ارسال
            </button>
			<?php
		}

		?>
        <div class="pws-tips" style="margin-top: 15px;"></div>
		<?php
	}

	public function save_order_meta_box( $order_id, $post, $update ) {

		$order_uuid = get_post_meta( $order_id, 'tapin_order_uuid', true );

		if ( ! empty( $order_uuid ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( get_post_status( $order_id ) === 'auto-draft' ) {
			return;
		}

		if ( ! isset( $_POST['tapin_weight'], $_POST['shipping_state_city'] ) ) {
			return;
		}

		update_post_meta( $order_id, 'tapin_weight', intval( $_POST['tapin_weight'] ) );

		list( $state_id, $city_id ) = explode( '-', $_POST['shipping_state_city'] );

		$state = PWS()::get_state( $state_id );
		$city  = PWS()::get_city( $city_id );

		if ( ! is_null( $state ) && ! is_null( $city ) ) {
			update_post_meta( $order_id, '_shipping_state', $state );
			update_post_meta( $order_id, '_shipping_state_id', $state_id );
			update_post_meta( $order_id, '_shipping_city', $city );
			update_post_meta( $order_id, '_shipping_city_id', $city_id );
		}
	}

	public function change_status_callback() {

		$status = $_POST['status'] ?? null;

		if ( ! wc_is_order_status( 'wc-' . $status ) ) {

			echo json_encode( [
				'success' => false,
				'message' => 'وضعیت انتخاب شده معتبر نمی باشد.'
			] );

			die();
		}

		$order_id = $_POST['id'] ?? null;

		if ( is_null( $order_id ) || ! is_numeric( $order_id ) ) {

			echo json_encode( [
				'success' => false,
				'message' => 'سفارش انتخاب شده معتبر نمی باشد.'
			] );

			die();
		}

		/** @var WC_Order $order */
		$order = wc_get_order( $order_id );

		if ( $order == false ) {

			echo json_encode( [
				'success' => false,
				'message' => 'سفارش انتخاب شده وجود ندارد.'
			] );

			die();
		}

		$tapin_post_type = PWS_Order::get_shipping_method( $order );

		if ( is_null( $tapin_post_type ) ) {

			echo json_encode( [
				'success' => false,
				'message' => 'روش ارسال این سفارش تاپین نیست.'
			] );

			die();
		}

		$tapin_order_uuid = get_post_meta( $order_id, 'tapin_order_uuid', true );

		if ( $status == 'pws-packaged' ) { // Submit & get post barcode

			if ( ! empty( $tapin_order_uuid ) ) {

				echo json_encode( [
					'success' => false,
					'message' => 'این سفارش قبلا در پنل ثبت شده است.'
				] );

				die();
			}

			$products = [];

			foreach ( $order->get_items() as $order_item ) {

				/** @var WC_Product $product */
				$product = $order_item->get_product();

				if ( $product->is_virtual() ) {
					continue;
				}

				$price = $order_item->get_total() / $order_item->get_quantity();

				if ( get_woocommerce_currency() == 'IRT' ) {
					$price *= 10;
				}

				if ( get_woocommerce_currency() == 'IRHR' ) {
					$price *= 1000;
				}

				if ( get_woocommerce_currency() == 'IRHT' ) {
					$price *= 10000;
				}

				$title = $product->get_title();

				if ( function_exists( 'mb_substr' ) ) {
					$title = mb_substr( $title, 0, 50 );
				}

				$products[] = [
					'count'      => $order_item->get_quantity(),
					'discount'   => 0,
					'price'      => $price,
					'title'      => $title,
					'weight'     => 0,
					'product_id' => null,
				];
			}

			$order_weight = PWS_Order::get_order_weight( $order );

			$tapin_pay_type = 1;

			if ( $order->get_payment_method() == 'cod' ) {

				if ( $order->get_shipping_total() ) {
					$tapin_pay_type = 0;

					$packaging_cost = $order->get_meta( 'packaging_cost' );

					if ( $packaging_cost ) {

						if ( get_woocommerce_currency() == 'IRT' ) {
							$packaging_cost *= 10;
						}

						if ( get_woocommerce_currency() == 'IRHR' ) {
							$packaging_cost *= 1000;
						}

						if ( get_woocommerce_currency() == 'IRHT' ) {
							$packaging_cost *= 10000;
						}

						$products[] = [
							'count'      => 1,
							'discount'   => 0,
							'price'      => $packaging_cost,
							'title'      => 'بسته بندی',
							'weight'     => 0,
							'product_id' => null,
						];
					}

				} else {
					$tapin_pay_type = 3;
				}

			}

			$data = [
				'register_type'  => 1,
				'shop_id'        => PWS()->get_option( 'tapin.shop_id' ),
				'address'        => $order->get_shipping_address_1() . ' ' . $order->get_shipping_address_2(),
				'city_code'      => $order->get_meta( '_shipping_city_id' ),
				'province_code'  => $order->get_meta( '_shipping_state_id' ),
				'description'    => null,
				'email'          => null,
				'employee_code'  => '-1',
				'first_name'     => $order->get_shipping_first_name(),
				'last_name'      => $order->get_shipping_last_name(),
				'mobile'         => $order->get_billing_phone(),
				'phone'          => null,
				'postal_code'    => $order->get_shipping_postcode(),
				'pay_type'       => $tapin_pay_type,
				'order_type'     => $tapin_post_type,
				'package_weight' => $order_weight,
				'presenter_code' => 1025,
				'products'       => $products
			];

			PWS_Tapin::set_gateway( PWS()->get_option( 'tapin.gateway' ) );

			$response = PWS_Tapin::request( 'v2/public/order/post/register', $data );

			if ( is_wp_error( $response ) || $response->returns->status != 200 ) {

				PWS()->log( __METHOD__ . ' Line: ' . __LINE__ );
				PWS()->log( $data );
				PWS()->log( $response );

				$errors = [];

				foreach ( (array) $response->entries as $key => $message ) {
					if ( is_string( $message[0] ) ) {
						$errors[] = "{$key} > {$message[0]}";
					}
				}

				echo json_encode( [
					'success' => false,
					'message' => $response->returns->message . '<br>' . implode( '<br>', $errors )
				] );

				die();
			}

			update_post_meta( $order_id, 'tapin_order_uuid', $response->entries->id );
			update_post_meta( $order_id, 'tapin_order_id', $response->entries->order_id );
			update_post_meta( $order_id, 'tapin_send_price', $response->entries->send_price );
			update_post_meta( $order_id, 'tapin_send_price_tax', $response->entries->send_price_tax );
			update_post_meta( $order_id, 'tapin_send_time', time() );
			update_post_meta( $order_id, 'tapin_weight', $order_weight );
			update_post_meta( $order_id, 'post_barcode', $response->entries->barcode );

			$note = "بارکد پستی مرسوله شما: {$response->entries->barcode}
                        می توانید مرسوله خود را از طریق لینک https://newtracking.post.ir رهگیری نمایید.";

			$order->set_status( $status );
			$order->save();
			$order->add_order_note( $note, 1 );

			do_action( 'pws_save_order_post_barcode', $order, $response->entries->barcode );

			echo json_encode( [
				'success' => true,
				'message' => 'بسته بندی شده',
			] );

			die();

		} else if ( $status == 'pws-ready-to-ship' ) {

			if ( empty( $tapin_order_uuid ) ) {

				echo json_encode( [
					'success' => false,
					'message' => 'سفارش در پنل ثبت نشده است.'
				] );

				die();
			}

			$tapin_order_id = get_post_meta( $order_id, 'tapin_order_id', true );

			$data = [
				'shop_id'  => PWS()->get_option( 'tapin.shop_id' ),
				'order_id' => $tapin_order_id,
				'status'   => 2
			];

			PWS_Tapin::set_gateway( PWS()->get_option( 'tapin.gateway' ) );

			$response = PWS_Tapin::request( 'v2/public/order/post/change-status', $data );

			if ( is_wp_error( $response ) || $response->returns->status != 200 ) {

				PWS()->log( __METHOD__ . ' Line: ' . __LINE__ );
				PWS()->log( $data );
				PWS()->log( $response );

				$errors = [];

				foreach ( (array) $response->entries as $key => $message ) {
					if ( is_string( $message[0] ) ) {
						$errors[] = "{$key} > {$message[0]}";
					}
				}

				echo json_encode( [
					'success' => false,
					'message' => $response->returns->message . '<br>' . implode( '<br>', $errors )
				] );

				die();
			}

			$order->set_status( $status );
			$order->save();

			echo json_encode( [
				'success' => true,
				'message' => 'آماده به ارسال',
			] );

			die();

		} else {

			echo json_encode( [
				'success' => false,
				'message' => "ابتدا باید به 'بسته بندی شده' تغییر وضعیت دهید."
			] );

			die();

		}

	}

	public function check_status_scheduled() {
		if ( ! wp_next_scheduled( 'pws_check_status' ) ) {
			wp_schedule_event( time(), 'hourly', 'pws_check_status' );
		}
	}

	public function check_status_callback() {

		$args_query = [
			'post_type'   => [ 'shop_order' ],
			'post_status' => [
				self::$status[1],
				self::$status[2],
				self::$status[5],
				self::$status[8],
			],
			'nopaging'    => true,
			'order'       => 'DESC',
			'meta_key'    => 'tapin_order_uuid',
		];

		$query = new WP_Query( $args_query );

		$posts = array_column( $query->posts, 'ID' );

		$posts = array_map( function ( $post_id ) {
			return [
				'id'      => get_post_meta( $post_id, 'tapin_order_uuid', true ),
				'post_id' => $post_id
			];
		}, $posts );

		if ( count( $posts ) ) {

			PWS_Tapin::set_gateway( PWS()->get_option( 'tapin.gateway' ) );

			$statuses = PWS_Tapin::request( 'v2/public/order/post/get-status/bulk', [
				'shop_id' => PWS()->get_option( 'tapin.shop_id' ),
				'orders'  => $posts
			] );

			if ( is_wp_error( $statuses ) ) {
				return false;
			}

			$orders = array_column( $statuses->entries->list, null, 'id' );

			foreach ( $posts as $post ) {

				if ( isset( $orders[ $post['id'] ] ) ) {

					$status = $orders[ $post['id'] ]->status;

					$status = self::$status[ $status ] ?? null;

					if ( ! is_null( $status ) ) {

						/** @var WC_Order $order */
						$order = wc_get_order( $post['post_id'] );

						if ( $order->get_status() != $status ) {
							$order->set_status( $status, 'بروزرسانی خودکار تاپین -' );
							$order->save();
						}

					}
				}

			}

		}

		wp_reset_postdata();
	}

}

new PWS_Status();
