<div class="product-type3">

    <?php 
    $post_id = $post->ID;
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
        <a href="<?php the_permalink(); ?>"><h4><?php echo get_the_title($post_id); ?></h4></a>
        <p><i class="ti-game"></i> دسته‌بندی : <span><?php the_gameplus_categories($product->get_id()); ?></span></p>
        <p><i class="ti-briefcase"></i> سازنده : <span><?php the_gameplus_term(get_the_ID(),'companies'); ?></span></p>
        <div class="t2-price">
            <p><?php echo $product->get_price(); ?> <span><?php echo get_woocommerce_currency_symbol(); ?></span></p>
        </div>
    </div>

</div>