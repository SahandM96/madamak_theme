<?php
/**
 * Home partial layout: Product Categories
 *
 * @package WordPress
 * @subpackage gameplus
 * @since 1.0
*/
?>

<section id="suggested-products">

    <div class="section-heading">

        <div>
            <span class="ti-medall"></span>
            <h6>محصولات پیشنهادی</h6>
            <p>محصولاتی که ما به شما پیشنهاد میکنیم</p>
        </div>

        <!-- <div>
            <a href="">مشاهده همه</a>
        </div> -->

    </div>

    <div id="right-shadow"></div>
    <div id="left-shadow"></div>

    <div class="owl-carousel">

        <?php  
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 10,
            'meta_query' => array(
                array(
                    'key' => 'product-special',
                    'value' => '1',
                    'compare' => '=',
                )
            )
        );

        $loop = new WP_Query( $args );

        while ( $loop->have_posts() ) : $loop->the_post();
            global $post;
            get_template_part('inc/loops/items/product');
        endwhile;
        ?>

    </div>

</section>