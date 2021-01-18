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

    <div class="background-container">
        <img class="slide-background" src="<?php echo get_template_directory_uri().'/images/assassins-creed-syndicate.jpg'; ?>">
        <div class="overly"></div>
    </div>

    <section id="panel-container" class="no-max-height">
    
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
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['orders'] ) ); ?>"><i class="ti-shopping-cart-full"></i> سفارش‌ها</a>
                    </li>
                    <li class="active">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'games' ) ); ?>"><i class="ti-game"></i> بازی‌ها و اکانت‌ها</a>
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





        <div class="user-content my-games">

            <?php if (have_rows('user-accounts', 'user_'.$current_user->ID)) {
                    while(have_rows('user-accounts', 'user_'.$current_user->ID)) : the_row(); ?>
                        

                        <div class="purchased-game">

                            <?php 
                            $post_id = get_sub_field('user-product');
                            $product = new WC_Product( $post_id );
                            ?>

                            <div class="product-thumbnail">
                                <span class="line"></span>
                                <?php
                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $product->get_image(), $cart_item, $cart_item_key );
                                echo $thumbnail;
                                ?>
                            </div>


                            <div class="product-detail" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                                <h4><?php echo get_the_title($post_id); ?></h4>
                                <p class="product-username"><i class="ti-user"></i> Username: <span><?php the_sub_field('user-product-username'); ?></span></p>
                                <p class="product-password"><i class="ti-key"></i> Password: <span><?php the_sub_field('user-product-pass'); ?></span></p>
                            </div>

                        </div>
            <?php endwhile; } ?>

            <?php if (have_rows('user-cdkeys', 'user_'.$current_user->ID)) {
                    while(have_rows('user-cdkeys', 'user_'.$current_user->ID)) : the_row(); ?>
                        

                        <div class="purchased-game">

                            <?php 
                            $post_id = get_sub_field('user-product-c');
                            $product = new WC_Product( $post_id );
                            ?>

                            <div class="product-thumbnail">
                                <span class="line"></span>
                                <?php
                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $product->get_image(), $cart_item, $cart_item_key );
                                echo $thumbnail;
                                ?>
                            </div>


                            <div class="product-detail" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                                <h4><?php echo get_the_title($post_id); ?></h4>
                                <p class="product-password"><i class="ti-key"></i> Password: <span><?php the_sub_field('cdkey-code'); ?></span></p>
                            </div>

                        </div>
            <?php endwhile; } ?>

        </div>



    
    </section>

<?php get_footer(); ?>