<?php get_header(); ?>


<?php
$product = wc_get_product( get_the_ID() );
$sale_price = $product->get_sale_price();
$regualr_price = $product->get_regular_price();
$price = $product->get_price();
$atributai = $product->get_attributes();
?>

<main id="product-main">

    <section id="product-photos">
            
        <div class="items">

            <!-- SLIDE -->
            <div class="slide">

                <div class="slide-background-container">
                    <img class="slide-background" src="<?php the_field('product-bg'); ?>" alt="">
                    <div class="overly"></div>
                </div>

                <div class="slide-container">

                    <div class="slide-product">
                        <?php the_post_thumbnail( array(320, 470) ); ?>
                    </div>



                    <div class="left-side">

                        <div class="slide-photo">
                            <?php $index = 0; if(!empty(get_field('product-video-link'))) { ?>
                            <div class="video-wrapper gallery-photo" :class="{ active : galleryPhotosStatus[<?php echo $index; ?>] }" v-show="galleryPhotosStatus[<?php echo $index; ?>]">
                                <video controls>
                                    <source src="<?php the_field('product-video-link'); ?>" type="video/mp4">
                                </video>
                            </div>
                            <?php $index++; } ?>
                            
                            <?php $attachment_ids = $product->get_gallery_image_ids();
                            foreach( $attachment_ids as $attachment_id ) {
                                $Original_image_url = wp_get_attachment_url( $attachment_id ); ?>
                                <img src="<?php echo $Original_image_url; ?>" alt="<?php the_title(); ?>" class="gallery-photo" :class="{ active : galleryPhotosStatus[<?php echo $index; ?>] }" v-show="galleryPhotosStatus[<?php echo $index; ?>]">
                            <?php $index++; } ?>
                        </div>

                    </div>


                    <div id="slider-control">
                        <button class="ti-angle-up" @click="galleryNav('per')"></button>
                        <div>
                            <?php $index = 0; if(!empty(get_field('product-video-link'))) { ?>
                                <div class="vid" :class="{ active : galleryPhotosStatus[<?php echo $index; ?>] }" @click="changeGalleryPhoto(<?php echo $index; ?>)">
                                    <img src= "<?php the_field('product-video-thumb') ?>">
                                    <i class="ti-control-play"></i>
                                </div>
                            <?php $index++; } ?>
                            <?php $attachment_ids = $product->get_gallery_image_ids();
                            foreach( $attachment_ids as $attachment_id ) { 
                                 $Original_image_url = wp_get_attachment_url( $attachment_id ); ?>
                            <img src= "<?php echo $Original_image_url; ?>" :class="{ active : galleryPhotosStatus[<?php echo $index; ?>] }" @click="changeGalleryPhoto(<?php echo $index; ?>)"></img>
                            <?php $index++; } ?>
                            </div>
                        <button class="ti-angle-down" @click="galleryNav('next')"></button>
                    </div>


                </div>

            </div>
            <!-- SLIDE -->

        </div>

    </section>



    <?php do_action( 'woocommerce_before_single_product' ); ?>

    <section id="product-info">


        <div class="background-right">
            <img src="<?php echo get_template_directory_uri().'/images/assassins-creed-valhalla.png' ?>" alt="">
            <div class="overly-x"></div>
            <div class="overly-y"></div>
        </div>


        <div class="title">
            <h1><?php the_title(); ?></h1>
            <div>
                <p><i class="ti-game"></i> دسته‌بندی : <span><?php the_gameplus_categories($product->get_id()); ?></span></p>
                <?php if(has_term('','companies')){ ?> 
                <p><i class="ti-briefcase"></i> سازنده : <span><?php the_gameplus_term(get_the_ID(),'companies'); ?></span></p>
                <?php } ?>
                <?php if(has_term('','platform')){ ?> 
                <p><i class="ti-harddrive"></i> پلتفرم : <span><?php the_gameplus_term(get_the_ID(),'platform'); ?></span></p>
                <?php } ?>
            </div>
        </div>


        <div id="add-to-cart">
        
            <ul>
                <li>
                    <img src="<?php the_field('vizhegi1-icon','option'); ?>" alt="">
                    <div>
                        <p class="title"><?php the_field('vizhegi1','option'); ?></p>
                        <p class="subtitle"><?php the_field('vizhegi1-des','option'); ?></p>
                    </div>
                </li>
                <li>
                    <img src="<?php the_field('vizhegi2-icon','option'); ?>" alt="">
                    <div>
                        <p class="title"><?php the_field('vizhegi2','option'); ?></p>
                        <p class="subtitle"><?php the_field('vizhegi2-des','option'); ?></p>
                    </div>
                </li>
                <li>
                    <img src="<?php the_field('vizhegi3-icon','option'); ?>" alt="">
                    <div>
                        <p class="title"><?php the_field('vizhegi3','option'); ?></p>
                        <p class="subtitle"><?php the_field('vizhegi3-des','option'); ?></p>
                    </div>
                </li>
            </ul>

            <div class="price-box">

                <div class="price-container">
                    <p>قیمت :</p>
                    <div>
                        <?php if (!empty($sale_price)) { ?>
                            <p class="regular-price"><?php echo $regualr_price; ?><span> <?php echo get_woocommerce_currency_symbol(); ?></span></p>
                            <p class="sale-price"><?php echo $sale_price; ?><span> <?php echo get_woocommerce_currency_symbol(); ?></span></p>
                        <?php } else { ?>
                            <p class="price"><?php echo $price; ?><span> <?php echo get_woocommerce_currency_symbol(); ?></span></p>
                        <?php } ?>
                    </div>
                </div>

				<?php woocommerce_template_single_add_to_cart(); ?>
			
            </div>

		</div>



        <div id="about-product">

            <h6>درباره محصول</h6>

            <?php echo $product->get_description(); ?>

        </div>



        <div id="product-properties">

            <h6>مشخصات محصول</h6>
            <table>
                <?php display_attribute(); ?>
            </table>

        </div>


        <div id="comments-section">
            <?php //echo apply_filters( 'the_content',' [rwp_box id="0"] ');
            comments_template(); ?>
        </div>

    </section>




    <section id="related-products">

        <div class="section-heading">

            <div>
                <span class="ti-layers-alt"></span>
                <h6>محصولات مرتبط</h6>
            </div>

            <div>
                <p>محصولات مشابه این محصول</p>
            </div>

        </div>
        <div class="owl-carousel">

            <?php  
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 10
            );

            $loop = new WP_Query( $args );

            while ( $loop->have_posts() ) : $loop->the_post();
                global $post;
                get_template_part('inc/loops/items/product');
            endwhile;

            wp_reset_query();
            ?>

        </div>

    </section>


</main>

<?php get_footer(); ?>