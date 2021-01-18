<?php
/**
 * Home partial layout: Slider
 *
 * @package WordPress
 * @subpackage gameplus
 * @since 1.0
*/
?>

<section id="main-slider">
        
    <div class="items">

        <div id="slider-control">
            <span id="slide-change-delay"><?php the_field('slide-change-delay', 'option'); ?></span>
            <ul>
                <?php
                if (have_rows('slides', 'option')) :
                    $index = 1;
                    while (have_rows('slides', 'option')) :
                        the_row();
                ?>
                <li :class="sliderStatus[<?php echo $index; ?>]" @click="changeSlide(<?php echo $index; ?>)"><?php the_sub_field('slide-name', 'option'); ?></li>
                <?php $index++; endwhile; endif; ?>
            </ul>
        </div>



        <?php
        if (have_rows('slides', 'option')) :
            $index = 1;
            while (have_rows('slides', 'option')) :
                the_row();
        ?>
         <!-- SLIDE -->
         <div class="slide" :class="sliderStatus[<?php echo $index; ?>]" v-show="sliderStatus[<?php echo $index; ?>] === 'show'" id="slide0<?php echo $index; ?>">

            <div class="slide-background-container">
                <img class="slide-background" src="<?php the_sub_field('slide-background', 'option'); ?>" alt="<?php the_sub_field('slide-name', 'option'); ?>">
                <div class="overly"></div>
            </div>

            <div class="slide-container">

                <?php
                $post_id = get_sub_field('slide-product', 'option');
                $product = wc_get_product( $post_id );
                $sale_price = $product->get_sale_price();
                $regualr_price = $product->get_regular_price();
                $price = $product->get_price();
                ?>
                <a href="<?php echo get_the_permalink($post_id); ?>" class="slide-product">
                    <img src="<?php echo get_the_post_thumbnail_url($post_id,'large'); ?>" alt="<?php the_sub_field('slide-name', 'option'); ?>">
                    <div class="overly">
                        <div>
                            <p class="type-of-product"><?php the_field('product-type',$post_id); ?></p>
                            <span class="product-name"><?php echo $product->get_title(); ?></span>
                        </div>
                        <div>
                            <p class="product-description"><?php the_sub_field('slide-short-description', 'option'); ?></p>
                        </div>
                        <div>
                            <div class="product-icons">
                                <!-- <a href="" class="ti-heart"></a> -->
                                <span class="ti-shopping-cart"></span>
                            </div>
                            <div class="price <?php if (!empty($sale_price)) echo 'padd'; ?>">
                                <?php if (!empty($sale_price)) { ?>
                                    <span class="sale-percentage">
                                        <?php
                                            $per = 100 - (($sale_price / $regualr_price) * 100);
                                            echo floor($per).'%';
                                        ?>
                                    </span>
                                <?php } ?>

                                <p class="manual-price"><?php echo $price.' '; echo get_woocommerce_currency_symbol(); ?></p></p>
                                <p class="sale-price">
                                    <?php 
                                    if (!empty($sale_price)) { ?>
                                        <span><?php echo $regualr_price; ?></span>
                                    <?php } ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <div class="left-side">
                    <div class="slide-photo">
                        <img src="<?php the_sub_field('slide-background', 'option'); ?>" alt="<?php the_sub_field('slide-name', 'option'); ?>">
                        <div class="overly"></div>
                    </div>
                    <div class="description">
                        <?php $variable = get_field('vizhegi1', 'option'); if (!empty($variable)) { ?>
                        <div>
                            <p><?php echo $variable; ?></p>
                        </div>
                        <?php } ?>
                        <?php $variable = get_field('vizhegi2', 'option'); if (!empty($variable)) { ?>
                        <div>
                            <p><?php echo $variable; ?></p>
                        </div>
                        <?php } ?>
                        <?php $variable = get_field('vizhegi3', 'option'); if (!empty($variable)) { ?>
                        <div>
                            <p><?php echo $variable; ?></p>
                        </div>
                        <?php } ?>
                        <?php $variable = get_field('vizhegi4', 'option'); if (!empty($variable)) { ?>
                        <div>
                            <p><?php echo $variable; ?></p>
                        </div>
                        <?php } ?>
                    </div>
                </div>

            </div>

        </div>
        <!-- SLIDE -->

        <?php $index++; endwhile; endif; ?>

    </div>

</section>