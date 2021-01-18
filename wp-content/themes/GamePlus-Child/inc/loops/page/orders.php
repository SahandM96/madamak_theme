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


$my_orders_columns = apply_filters(
	'woocommerce_my_account_my_orders_columns',
	array(
		'order-number'  => esc_html__( 'Order', 'woocommerce' ),
		'order-date'    => esc_html__( 'Date', 'woocommerce' ),
		'order-status'  => esc_html__( 'Status', 'woocommerce' ),
		'order-total'   => esc_html__( 'Total', 'woocommerce' ),
		'order-actions' => '&nbsp;',
	)
);

$customer_orders = get_posts(
	apply_filters(
		'woocommerce_my_account_my_orders_query',
		array(
			'numberposts' => $order_count,
			'meta_key'    => '_customer_user',
			'meta_value'  => get_current_user_id(),
			'post_type'   => wc_get_order_types( 'view-orders' ),
			'post_status' => array_keys( wc_get_order_statuses() ),
		)
	)
);

?>

    <div class="background-container">
        <img class="slide-background" src="<?php echo get_template_directory_uri().'/images/assassins-creed-syndicate.jpg'; ?>">
        <div class="overly"></div>
    </div>

    <section id="panel-container">
    
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

            <?php if ( $customer_orders ) { ?>
                <div class="shop_table">

                    <div class="table-head">
                        <?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
                            <p class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></p>
                        <?php endforeach; ?>
                    </div>

                    <div class="table-body">
                        <?php
                        foreach ( $customer_orders as $customer_order ) :
                            $order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
                            $item_count = $order->get_item_count();
                            ?>
                            <div class="order">
                                <?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
                                    <div class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
                                        <?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
                                            <?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

                                        <?php elseif ( 'order-number' === $column_id ) : ?>
                                            <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
                                                <?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                            </a>

                                        <?php elseif ( 'order-date' === $column_id ) : ?>
                                            <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

                                        <?php elseif ( 'order-status' === $column_id ) : ?>
                                            <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

                                        <?php elseif ( 'order-total' === $column_id ) : ?>
                                            <?php
                                            /* translators: 1: formatted order total 2: total order items */
                                            printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            ?>

                                        <?php elseif ( 'order-actions' === $column_id ) : ?>
                                            <?php
                                            $actions = wc_get_account_orders_actions( $order );

                                            if ( ! empty( $actions ) ) {
                                                foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
                                                    echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
                                                }
                                            }
                                            ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="notfound">
                    <p>سفارشی وجود ندارد !</p>
                </div>
            <?php } ?>

        </div>



    
    </section>

<?php get_footer(); ?>