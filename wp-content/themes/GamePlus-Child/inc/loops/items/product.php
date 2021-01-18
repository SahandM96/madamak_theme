<a href="<?php the_permalink(); ?>" class="product">

    <?php
    $product = new WC_Product( $post->ID );
    $sale_price = $product->get_sale_price();
    $regualr_price = $product->get_regular_price();
    $price = $product->get_price();
    the_post_thumbnail( array(320, 470) );
    ?>
    <div class="anim-obj"></div>
    <div class="overly">
		<div class="heading">
            <p class="type-of-product"><?php the_field('product-type'); ?></p>
            <h5 class="product-name"><?php the_title(); ?></h5>
        </div>
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