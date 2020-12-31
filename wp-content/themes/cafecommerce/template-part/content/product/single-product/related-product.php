<section id="SectionOne" class="mt-5">
    <div class="container">
        <div class="row">
			<div class="col">
                <div class="d-flex justify-content-start align-items-center" id="Heading1">
                    <h2>محصولات مرتبط</h2>
                </div>
            </div>
        </div>
       <div class="row">
            <div class="col">
                <!--Carousel-->
                <div id="Carousel1" class="carousel" data-flickity='{"pageDots": false,"groupCells": true,"rightToLeft": true,"setGallerySize": false }'>
					<?php
						$trmids = wp_get_post_terms($post->ID,'product_cat');
						$trmarray = array();
						foreach ($trmids as $v)
						{
							$trmarray[] = $v->term_id;
						}
	
						$arms = array(
							'post_type' => 'product',
							'posts_per_page' => '9',
							'order' => 'DESC',
							'post_status' => 'publish',
							'post__not_in' => array($post->ID),
							'tax_query' => array(
									array(
										'taxonomy'      => 'product_cat',
										'terms'         => $trmarray,
									),
							)
						);
						$the_query = new WP_Query($arms); 
					?>
					<?php if ($the_query->have_posts()) : ?>
						<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>							
							<a href="<?php the_permalink(); ?>" class="image-item">
								 <div class="image-item_cover">
                                   <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->post->ID ), 'single-post-thumbnail' );?>
                                    <img src="<?php  echo $image[0]; ?>" />
                                </div>
								<div class="image-item_text">
                                    <span class="image-item_title"><?php the_title(); ?></span>
                                    <span class="image-item_subtitle"><?php woocommerce_template_loop_price();?></span>
                                </div>
							</a>
				        <?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
					<?php else : ?>
						<p class="nopostdata"><?php _e('هیچ محصول مرتبط با این محصول یافت نشد!'); ?></p>
					<?php endif; ?>
				</div>
                <!--Carousel-->
             </div>
        </div>
    </div>
</section>
