<?php
/**
 * Partial layout: Banner
 *
 * @package WordPress
 * @subpackage gameplus
 * @since 1.0
*/
?>

<?php

$photoType = get_field('banner-how','option');
$productId = get_field('banner-product','option');

?>

<!-- Banner -->
<section class="banner">

<?php $class=''; if ($photoType == 'choose') $class = 'padd'; ?>

    <div class="background <?php echo $class; ?>">
        <img class="bg" src="<?php the_field('banner-bg','option'); ?>" alt="<?php echo get_the_title($productId); ?>">
        <div class="overly"></div>
    </div>

    <div class="items">
        <div class="texts">
            <h4 class="title-per"><?php echo get_the_title($productId); ?></h4>
            <p class="description"><?php the_field('banner-description','option'); ?></p>
            <a href="<?php the_permalink($productId); ?>">خرید</a>
        </div>

        <?php if ($photoType == 'choose') { ?>
            <img class="png" src="<?php the_field('banner-photo','option'); ?>" alt="<?php echo get_the_title($productId); ?>">
        <?php } else { ?>
            
            <a href="<?php the_permalink(); ?>" class="product">

                <?php
                $product = new WC_Product( $productId );
                $sale_price = $product->get_sale_price();
                $regualr_price = $product->get_regular_price();
                $price = $product->get_price();
                echo get_the_post_thumbnail($productId,array(320, 470));
                ?>
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
                    <div class="heading">
                        <p class="type-of-product"><?php the_field('product-type',$productId); ?></p>
                        <h5 class="product-name"><?php echo get_the_title($productId); ?></h5>
                    </div>
                </div>

            </a>    


        <?php } ?>
    </div>
    <div class="overly <?php echo $class; ?>"></div>
</section>
<!-- Banner -->