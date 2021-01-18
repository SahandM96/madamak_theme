<?php
/**
 * Home partial layout: Products By Category
 *
 * @package WordPress
 * @subpackage gameplus
 * @since 1.0
*/
?>

<section id="all-products">

    <div class="background-right">
        <img src="<?php echo get_template_directory_uri().'/images/call-of-duty-2.png'; ?>" style="background-position: center" alt="">
        <div class="overly-x"></div>
        <div class="overly-y"></div>
    </div>

    <div class="background-left">
        <img src="<?php echo get_template_directory_uri().'/images/call-of-duty.png'; ?>" alt="">
        <div class="overly-x"></div>
        <div class="overly-y"></div>
    </div>

    <!-- RIGHT SECTION -->
    <div class="right">

        <div class="section-heading">

            <div>
                <h6>محصولات</h6>
            </div>

            <div>
                <nav>
                    <ul>
                        <li><button :class="{ active: archiveCategoriesStatus[0] }" @click="changeArchiveCategory(0)">همه</button></li>
                        <?php
                        $terms = get_field('archive-cat','option');
                        $i = 1;
                        foreach( $terms as $term ):
                        ?>
                            <li><button :class="{ active: archiveCategoriesStatus[<?php echo $i; ?>] }" @click="changeArchiveCategory(<?php echo $i; ?>)"><?php echo $term->name; ?></button></li>
                        <?php if ($i++ == 3) break; endforeach; ?>
                    </ul>
                </nav>
            </div>

        </div>




        <div class="items" v-if="archiveCategoriesStatus[0]">
            <?php  
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 6
            );
            $loop = new WP_Query( $args );
            while ( $loop->have_posts() ) : $loop->the_post();
                global $post;
                get_template_part('inc/loops/items/product');
            endwhile;
            wp_reset_query();
            ?>
        </div>

        <?php $i = 1; foreach( $terms as $term ): ?>
        <div class="items" v-if="archiveCategoriesStatus[<?php echo $i; ?>]">

            <?php  
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 6,
                'product_cat' => $term->slug
            );

            $loop = new WP_Query( $args );

            while ( $loop->have_posts() ) : $loop->the_post();
                global $post;
                get_template_part('inc/loops/items/product');
            endwhile;

            wp_reset_query();
            ?>

        </div>
        <?php if ($i++ == 3) break; endforeach; ?>

    </div>
    <!-- RIGHT SECTION -->






    <!-- LEFT SECTION -->
        <div class="left">
        
            <div class="section-heading-l">
                <h6>پرفروش ها</h6>
            </div>

            <div class="items">
                <?php  
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 6,
                    'meta_key' => 'total_sales',
                    'orderby' => 'meta_value_num',
                );

                $loop = new WP_Query( $args );

                while ( $loop->have_posts() ) : $loop->the_post();
                    global $post; ?>
                    <a href="<?php the_permalink(); ?>" class="game">
                        <img class="cover" src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                        <div>
                            <h5><?php the_title(); ?></h5>
                            <p><?php the_field('product-type'); ?></p>
                        </div>
                        <span class="ti-angle-left"></span>
                    </a>
                <?php endwhile; wp_reset_query(); ?>
            </div>

        </div>
    <!-- LEFT SECTION -->

</section>