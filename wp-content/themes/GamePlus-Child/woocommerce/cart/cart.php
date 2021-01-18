
<section id="way">

    <div class="level active">
        <div class="icon">
            <img src="<?php echo get_template_directory_uri().'/images/svg/basket-yellow.svg'; ?>" alt="">
        </div>
        <h6>سبد خرید</h6>
    </div>

    <span class="line activeLine"></span>

    <div class="level">
        <div class="icon">
            <img src="<?php echo get_template_directory_uri().'/images/svg/form-white.svg'; ?>" alt="">
        </div>
        <h6>تکمیل اطلاعات</h6>
    </div>

    <span class="line"></span>

    <div class="level">
        <div class="icon">
            <img src="<?php echo get_template_directory_uri().'/images/svg/pay-white.svg'; ?>" alt="">
        </div>
        <h6>پرداخت</h6>
    </div>

</section>


<?php do_action( 'woocommerce_before_cart' ); ?>

<form class="cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<div class="items">

        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

        <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                ?>

                <div class="item">


                    <div class="right">

                        <div class="product-thumbnail">
                            <span class="line"></span>
                            <?php
                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                            if ( ! $product_permalink ) {
                                echo $thumbnail; // PHPCS: XSS ok.
                            } else {
                                printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                            }
                            ?>
                        </div>


                        <div class="product-detail" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                            <?php
                            if ( ! $product_permalink ) {
                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                            } else {
                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                            }

                            do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                            // Meta data.
                            echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                            // Backorder notification.
                            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                            }
                            ?>
                            <p class="product-type">نوع محصول : <span><?php the_field('product-type',$product_id); ?></span></p>
                            <p class="product-creator">سازنده : <span>
                                <?php the_gameplus_term($product_id,'companies'); ?>
                            </span></p>
                        </div>
                    </div>





                    <div class="price-row">

										<?php if ( !$_product->is_sold_individually() ) { ?>
                        <div class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
														<span>تعداد :</span>
														
														<?php
                            
															echo $cart_item['quantity'];
                            ?>
												</div>
												<?php } ?>

                        <div class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                            <span>قیمت واحد :</span>
                            <?php
                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                            ?>
                        </div>


                        <div class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                            <span>قیمت نهایی :</span>
                            <?php
                                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                            ?>
                        </div>

                    </div>



                    <div class="before-buy">

                        <p>قبل از خرید :</p>

                        <div class="items">
                            
                            <?php if ( $product_permalink ) { ?>
                                <a href="<?php echo $product_permalink; ?>">مشاهده صفحه محصول</a>
                            <?php } ?>
                            <?php if (!empty(get_field('naghd',$product_id))) { ?>
                                <a href="<?php the_field('naghd',$product_id); ?>">مشاهده نقد و بررسی ها</a>
                            <?php } ?>

                        </div>

                    </div>



                    <div class="product-remove">
                        <?php
                            echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                'woocommerce_cart_item_remove_link',
                                sprintf(
                                    '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                    esc_html__( 'Remove this item', 'woocommerce' ),
                                    esc_attr( $product_id ),
                                    esc_attr( $_product->get_sku() )
                                ),
                                $cart_item_key
                            );
                        ?>
                    </div>

                </div>


                <?php
            }
        }
        ?>

    </div>

    <?php do_action( 'woocommerce_cart_contents' ); ?>

    <?php do_action( 'woocommerce_after_cart_contents' ); ?>


	<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>


<div class="under-basket">

    <form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        <div colspan="6" class="actions">

            <?php if ( wc_coupons_enabled() ) { ?>
                <div class="coupon">
                    <img src="<?php echo get_template_directory_uri().'/images/svg/coupon.svg'; ?>" alt="">
                    <label for="coupon_code">کد تخفیف</label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                </div>
            <?php } ?>

            <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

            <?php do_action( 'woocommerce_cart_actions' ); ?>

            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
        </div>
    </form>



    <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
    <div class="cart-collaterals">
        <?php
            /**
             * Cart collaterals hook.
             *
             * @hooked woocommerce_cross_sell_display
             * @hooked woocommerce_cart_totals - 10
             */
            do_action( 'woocommerce_cart_collaterals' );
        ?>
    </div>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
