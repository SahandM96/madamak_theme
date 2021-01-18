<?php get_header(); ?>
<?php $current_user = wp_get_current_user();

$endpoints = array(
    'orders'          => get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' ),
    'downloads'       => get_option( 'woocommerce_myaccount_downloads_endpoint', 'downloads' ),
    'edit-address'    => get_option( 'woocommerce_myaccount_edit_address_endpoint', 'edit-address' ),
    'payment-methods' => get_option( 'woocommerce_myaccount_payment_methods_endpoint', 'payment-methods' ),
    'edit-account'    => get_option( 'woocommerce_myaccount_edit_account_endpoint', 'edit-account' ),
    'customer-logout' => get_option( 'woocommerce_logout_endpoint', 'customer-logout' ),
);

?>

    <section id="panel-container" class="page-panel">
    
        <div class="panel-nav">

            <div class="user-detail">

                <div class="photo">
                    <?php action_woocommerce_edit_account_form(); ?>
                </div>

                <div>
                    <p>خوش آمدید</p>
                    <p><?php echo $current_user->user_login; ?></p>
                </div>
            </div>

            <nav class="navigation-top">
                <ul>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['edit-account'] ) ); ?>" class="ti-pencil"><span>تغییر اطلاعات</span></a>
                        <a href="" class="ti-comment"><span>ارسال پیام</span></a>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['customer-logout'] ) ); ?>" class="ti-close"><span>خروج</span></a>
                    </li>
                </ul>
            </nav>

            <nav class="navigation">
                <ul>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url(get_option( 'woocommerce_myaccount_page_id' ))); ?>"><i class="ti-home"></i> پیشخوان</a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['orders'] ) ); ?>"><i class="ti-shopping-cart-full"></i> سفارش‌ها</a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'games' ) ); ?>"><i class="ti-game"></i> بازی‌ها و اکانت‌ها</a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['downloads'] ) ); ?>"><i class="ti-download"></i> دانلود‌ها</a>
                    </li>
                    <li class="active">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['edit-address'] ) ); ?>"><i class="ti-map-alt"></i> آدرس‌ها</a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['edit-account'] ) ); ?>"><i class="ti-pencil"></i> تغییر اطلاعات</a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['customer-logout'] ) ); ?>"><i class="ti-close"></i> خروج</a>
                    </li>
                </ul>
            </nav>

        </div>





        <div class="user-content">

            <div class="address-form">
			
				<?php $page_title = ( 'billing' === $load_address ) ? esc_html__( 'Billing address', 'woocommerce' ) : esc_html__( 'Shipping address', 'woocommerce' );

				do_action( 'woocommerce_before_edit_account_address_form' ); ?>

				<?php if ( ! $load_address ) : ?>
					<?php wc_get_template( 'myaccount/my-address.php' ); ?>
				<?php else : ?>

					<form method="post">

						<h3><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ); ?></h3><?php // @codingStandardsIgnoreLine ?>

						<div class="woocommerce-address-fields">
							<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

							<div class="woocommerce-address-fields__field-wrapper">
								<?php
								foreach ( $address as $key => $field ) {
									woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
								}
								?>
							</div>

							<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

							<p>
								<button type="submit" class="button" name="save_address" value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>"><?php esc_html_e( 'Save address', 'woocommerce' ); ?></button>
								<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
								<input type="hidden" name="action" value="edit_address" />
							</p>
						</div>

					</form>

				<?php endif; ?>

				<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
			
			</div>

        </div>



    
    </section>

<?php get_footer(); ?>