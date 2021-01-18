<?php
get_header();
do_action( 'woocommerce_before_main_content' );
?>

<main>

	<div class="background-container">
        <img class="slide-background" src="<?php echo get_template_directory_uri().'/images/assassins-creed-syndicate.jpg' ?>" alt="">
        <div class="overly"></div>
    </div>

	<div class="archive-container">


		<?php if (is_shop() && !is_product_category() && !is_product_tag()) { ?>
			<!-- <h3 class="archive-title">فروشگاه</h3> -->
		<?php } else { ?>
			<?php if ($_SERVER['REQUEST_METHOD'] != 'GET') {
				$cate = get_queried_object(); ?>
				<h3 class="archive-title"><?php echo $cate->name; ?></h3>
			<?php } ?>
		<?php } ?>


		<?php if (is_shop() && !is_product_category() && !is_product_tag()) { ?>

			<?php if (get_field('shop-items','option')) { ?>

			<section id="net">

				<div id="net-blocks">
					
					<div id="left-block">

						<a href="<?php the_field('shop-item-link-1','option'); ?>" id="left-all-block" class="flip-card block">
							<div class="flip-card-inner">
								<img class="flip-card-front" src="<?php the_field('shop-item-photo-1','option'); ?>" alt="">
								<div class="flip-card-back">
									<h5><?php the_field('shop-item-title-1','option'); ?></h5>
									<p><?php the_field('shop-item-des-1','option'); ?></p>
								</div>
							</div>
						</a>

					</div>

					<div id="right-block">

						<a href="<?php the_field('shop-item-link-2','option'); ?>" id="right-top-block" class="flip-card">
							<div class="flip-card-inner">
								<img class="flip-card-front" src="<?php the_field('shop-item-photo-2','option'); ?>" alt="">
								<div class="flip-card-back">
									<h5><?php the_field('shop-item-title-2','option'); ?></h5>
									<p><?php the_field('shop-item-des-2','option'); ?></p>
								</div>
							</div>
						</a>

						<div id="right-bottom">

							<a href="<?php the_field('shop-item-link-3','option'); ?>" class="flip-card block">
								<div class="flip-card-inner">
									<img class="flip-card-front" src="<?php the_field('shop-item-photo-3','option'); ?>" alt="">
									<div class="flip-card-back">
										<h5><?php the_field('shop-item-title-3','option'); ?></h5>
										<p><?php the_field('shop-item-des-3','option'); ?></p>
									</div>
								</div>
							</a>

							<a href="<?php the_field('shop-item-link-4','option'); ?>" class="flip-card block">
								<div class="flip-card-inner">
									<img class="flip-card-front" src="<?php the_field('shop-item-photo-4','option'); ?>" alt="">
									<div class="flip-card-back">
										<h5><?php the_field('shop-item-title-4','option'); ?></h5>
										<p><?php the_field('shop-item-des-4','option'); ?></p>
									</div>
								</div>
							</a>

							<a href="<?php the_field('shop-item-link-5','option'); ?>" class="flip-card block">
								<div class="flip-card-inner">
									<img class="flip-card-front" src="<?php the_field('shop-item-photo-5','option'); ?>" alt="">
									<div class="flip-card-back">
										<h5><?php the_field('shop-item-title-5','option'); ?></h5>
										<p><?php the_field('shop-item-des-5','option'); ?></p>
									</div>
								</div>
							</a>
							
						</div>
					</div>
				</div>

			</section>

			<?php } ?>




			<section id="suggested-products-shop-page">

				<div class="section-heading-p">

					<div>
						<h6>محصولات پیشنهادی</h6>
						<p>محصولاتی که ما به شما پیشنهاد میکنیم</p>
					</div>

				</div>

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

					wp_reset_query();
					?>

				</div>

			</section>


			<div class="section-heading-p">

				<div>
					<h6>محصولات</h6>
					<p>محصولات منتشر شده در فروشگاه</p>
				</div>

			</div>

		<?php } ?>

		<?php get_template_part('myFilters'); ?>

		<section id="product-list">
			<?php
			if ( woocommerce_product_loop() ) {

				//do_action( 'woocommerce_before_shop_loop' );

				woocommerce_product_loop_start();

				if ( wc_get_loop_prop( 'total' ) ) {
					while ( have_posts() ) {
						the_post();
						do_action( 'woocommerce_shop_loop' );

						wc_get_template_part( 'content', 'product' );
					}
				}

				woocommerce_product_loop_end();

				do_action( 'woocommerce_after_shop_loop' );
			} else {

				do_action( 'woocommerce_no_products_found' );
			}

			//do_action( 'woocommerce_after_main_content' ); ?>
		</section>

	</div>

</main>


<?php get_footer(); ?>