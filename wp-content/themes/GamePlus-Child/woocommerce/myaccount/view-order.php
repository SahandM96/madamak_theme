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
                        <a href="<?php the_field('tickets-page','option'); ?>" class="ti-comment"><span>تیکت‌ها</span></a>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['customer-logout'] ) ); ?>" class="ti-close"><span>خروج</span></a>
                    </li>
                </ul>
            </nav>

            <nav class="navigation">
                <ul>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url(get_option( 'woocommerce_myaccount_page_id' ))); ?>"><i class="ti-home"></i> پیشخوان</a>
                    </li>
                    <li class="active">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['orders'] ) ); ?>"><i class="ti-shopping-cart-full"></i> سفارش‌ها</a>
                    </li>
                    <li>
                        <a href="<?php the_field('games-page','option') ?>"><i class="ti-game"></i> بازی‌ها و اکانت‌ها</a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['downloads'] ) ); ?>"><i class="ti-download"></i> دانلود‌ها</a>
                    </li>
                    <li>
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

            <div id="view-order-container">

                <div class="view-order-heading">
                    <div>
                        <h6>جزئیات سفارش: </h6>
                        <p><?php echo $order->get_order_number(); ?></p>
                        <p><?php echo wc_format_datetime( $order->get_date_created() ); ?></p>
                    </div>
                    <div>
                        <span><?php echo wc_get_order_status_name( $order->get_status() ); ?></span>
                    </div>
                </div>

                <?php $notes = $order->get_customer_order_notes(); ?>

                <?php if ( $notes ) : ?>
                    <h2><?php esc_html_e( 'Order updates', 'woocommerce' ); ?></h2>
                    <ol class="woocommerce-OrderUpdates commentlist notes">
                        <?php foreach ( $notes as $note ) : ?>
                        <li class="woocommerce-OrderUpdate comment note">
                            <div class="woocommerce-OrderUpdate-inner comment_container">
                                <div class="woocommerce-OrderUpdate-text comment-text">
                                    <p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                                    <div class="woocommerce-OrderUpdate-description description">
                                        <?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>

                <?php do_action( 'woocommerce_view_order', $order_id ); ?>

            </div>


        </div>



    
    </section>

<?php get_footer(); ?>