<li class="product-type2">

    <?php
    $product = new WC_Product( $post->ID );
    $sale_price = $product->get_sale_price();
    $regualr_price = $product->get_regular_price();
    $price = $product->get_price();
    ?>
    <a href="<?php the_permalink(); ?>" class="img"><?php
    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $product->get_image(), $cart_item, $cart_item_key );
    echo $thumbnail;
    ?></a>
    <div class="detail">
        <a href="<?php the_permalink(); ?>"><h5 class="product-name"><?php the_title(); ?></h5></a>
        <div>
            <p class="type-of-product"><?php the_field('product-type'); ?></p>
            <p class="product-price"><span><?php echo get_woocommerce_currency_symbol(); ?></span> <?php echo $price; ?></p>
        </div>
        <a href="<?php the_permalink(); ?>" class="buy-btn">مشاهده محصول</a>
    </div>

</li>