<main role="main">
    <section class="archive-product">
        <div class="container">
			<div class="row">
				<div class="col">
					<h2><?php the_title() ?></h2>
				</div>
			</div>
			<div class="archive-container mt-3">
            <?php
					if ( is_shop() || is_product_category() || is_product_tag() ) {
                    // Products per page
                    $per_page = 24;
                    if ( get_query_var( 'taxonomy' ) ) { // If on a product taxonomy archive (category or tag)
                        $args = array(
                            'post_type' => 'product',
                            'posts_per_page' => $per_page,
                            'paged' => get_query_var( 'paged' ),
                            'tax_query' => array(
                                array(
                                    'taxonomy' => get_query_var( 'taxonomy' ),
                                    'field'    => 'slug',
                                    'terms'    => get_query_var( 'term' ),
                                ),
                            ),
                        );
                    } else { // On main shop page
                        $args = array(
                            'post_type' => 'product',
                            'orderby' => 'date',
                            'order' => 'DESC',
                            'posts_per_page' => $per_page,
                            'paged' => get_query_var( 'paged' ),
                        );
                    }
                    $products = new WP_Query( $args );
                    if ( $products->have_posts() ) :
                        while ( $products->have_posts() ) : $products->the_post();
			?>

			<a href="<?php the_permalink() ?>" class="archive-item col-lg-3 col-md-4 col-sm-6">
				<div class="archive-image">
					<?php woocommerce_template_loop_product_thumbnail();?>
				</div>
				<div class="archive-info">
					<div class="archive-info_title"><?php the_title(); ?></div>
				</div>
			</a>
            <?php
				endwhile;
                    wp_reset_postdata();
                endif;
			?>
			</div>
            <?php
				}
				else 
				{
					woocommerce_content();
				}
			?>
		</div>
	</section>
</main>
        