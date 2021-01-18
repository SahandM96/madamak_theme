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
<main>

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
                        <a href="<?php the_field('tickets-page','option'); ?>" class="ti-comment"><span>تیکت ها</span></a>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['customer-logout'] ) ); ?>" class="ti-close"><span>خروج</span></a>
                    </li>
                </ul>
            </nav>

            <nav class="navigation">
                <ul>
                    <li class="active">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url(get_option( 'woocommerce_myaccount_page_id' ))); ?>"><i class="ti-home"></i> پیشخوان</a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['orders'] ) ); ?>"><i class="ti-shopping-cart-full"></i> سفارش‌ها</a>
                    </li>
                    <li>
                        <a href="<?php the_field('games-page','option'); ?>"><i class="ti-game"></i> بازی‌ها و اکانت‌ها</a>
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

            <?php if (!empty(get_field('admin-message','option'))) { ?>
            <div id="admin-message">
                <h6>پیام مدیر:</h6>
                <p><?php the_field('admin-message','option'); ?></p>
            </div>
            <?php } ?>



            <div id="main-buttons">
            
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['orders'] ) ); ?>"><i class="ti-shopping-cart-full"></i><span>سفارش ها</span></a>
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['downloads'] ) ); ?>"><i class="ti-download"></i><span>دانلود‌ها</span></a>
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoints['edit-address'] ) ); ?>"><i class="ti-map-alt"></i><span>آدرس‌ها</span></a>
                <a href="<?php the_field('tickets-page','option'); ?>"><i class="ti-comment"></i><span>تیکت های پشتیبانی</span></a>

            </div>



            <?php if (get_field('admin-offer-show','option')) : ?>
            <div id="big-offer">

                <span id="line-x-l"></span>
                <span id="line-y-t"></span>
                <span id="line-x-r"></span>
                <span id="line-y-b"></span>

                <img src="<?php echo get_template_directory_uri().'/images/svg/sale.svg'; ?>" alt="" id="sale-icon">
            
                <img src="<?php echo get_template_directory_uri().'/images/explotion.jpg'; ?>" alt="" id="offer-bg">

                <h5><?php the_field('admin-offer-title','option'); ?></h5>
                
                <div>
                    <strong><?php the_field('admin-offer-number','option'); ?>%</strong>
                    <p>تخفیف با کد :</p>
                    <div>
                        <p><?php the_field('admin-offer-code','option'); ?></p>
                    </div>
                </div>

            </div>
            <?php endif; ?>
            


            <div id="suggested-product">

                <div class="heading">

                    <h5>محصول پیشنهادی مدیر به شما</h5>

                </div>

                <a href="<?php the_permalink(); ?>" class="product">

                    <?php
                    $productId = get_field('admin-product','option');
                    $product = new WC_Product( $productId );
                    $sale_price = $product->get_sale_price();
                    $regualr_price = $product->get_regular_price();
                    $price = $product->get_price();
                    echo get_the_post_thumbnail($productId);
                    ?>
					<div class="heading">
						<p class="type-of-product"><?php the_field('product-type',$productId); ?></p>
						<h5 class="product-name"><?php echo get_the_title($productId); ?></h5>
					</div>
                    <div class="overly">
                        <div class="price <?php if (!empty($sale_price)) echo 'padd'; ?>">
                            <?php if (!empty($sale_price)) { ?>
                                <div class="sale-percentage">
                                    <p>
                                    <?php
                                        $per = 100 - (($sale_price / $regualr_price) * 100);
                                        echo floor($per);
                                    ?>
                                    </p>
                                    <span>%</span>
                                </div>
                            <?php } ?>

                            <p><?php echo $price; ?></p>
                            <p><?php echo get_woocommerce_currency_symbol(); ?></p>
                        </div>
                    </div>

                </a>


            </div>

        </div>



    
    </section>

</main>
<?php get_footer(); ?>