<?php
/**
 * Home partial layout: Product Categories
 *
 * @package WordPress
 * @subpackage gameplus
 * @since 1.0
*/

$cat1 = get_field('h_cat_1', 'option');
$cat2 = get_field('h_cat_2', 'option');
?>

<?php if (!empty($cat1)) { ?>
<section id="lastest-products">

    <div class="section-heading">

        <div>
            <span class="point"></span>
            <h6><?php the_field('h_title_1','option'); ?></h6>
            <p><?php the_field('h_subtitle_1','option'); ?></p>
        </div>

        <div>
            <?php $category = get_term( $cat1, 'product_cat' );
                $cat_id = $category->term_id; ?>
            <a href="<?php echo get_term_link( $cat_id, 'product_cat' ); ?>">مشاهده همه</a>
        </div>

    </div>

    <div id="right-shadow"></div>
    <div id="left-shadow"></div>

    <div class="owl-carousel">

        <?php  
        $args = array(
					'post_type'      => 'product',
					'posts_per_page' => 10,
					'tax_query' => array(
						array(
								'taxonomy' => 'product_cat',
								'field' => 'term_id',
								'terms' => $cat1, /*category name*/
								'operator' => 'IN',
								)
						),
				);

        $loop = new WP_Query( $args );

				if ($loop->have_posts()) {
					while ( $loop->have_posts() ) : $loop->the_post();
							global $post;
							get_template_part('inc/loops/items/product');
					endwhile;

					wp_reset_query();
				}
        ?>

    </div>

</section>
<?php } ?>


<?php if (!empty($cat2)) { ?>
<section id="lastest-products-2">

    <div class="section-heading">

        <div>
            <span class="point"></span>
            <h6><?php the_field('h_title_2','option'); ?></h6>
            <p><?php the_field('h_subtitle_2','option'); ?></p>
        </div>

        <div>
            <?php $category2 = get_term( $cat2, 'product_cat' );
                $cat_id2 = $category2->term_id; ?>
            <a href="<?php echo get_term_link( $cat_id2, 'product_cat' ); ?>">مشاهده همه</a>
        </div>

    </div>

    <div id="right-shadow"></div>
    <div id="left-shadow"></div>

    <div class="owl-carousel">

        <?php  
        $args = array(
					'post_type'      => 'product',
					'posts_per_page' => 10,
					'tax_query' => array(
						array(
								'taxonomy' => 'product_cat',
								'field' => 'term_id',
								'terms' => $cat2, /*category name*/
								'operator' => 'IN',
								)
						),
				);

        $loop = new WP_Query( $args );

				if ($loop->have_posts()) {
					while ( $loop->have_posts() ) : $loop->the_post();
							global $post;
							get_template_part('inc/loops/items/product');
					endwhile;

					wp_reset_query();
				}
        ?>

    </div>

</section>
<?php } ?>