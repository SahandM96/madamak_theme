        <section id="SectionOne" class="mt-5" style="display:<?php global $cafe; echo $cafe['section_slider_show_04']; ?>">
            <div class="container">
                <div class="row"  style="display:<?php global $cafe; echo $cafe['slider_title_show_04']; ?>">
                    <div class="col">
                        <div class="d-flex justify-content-start align-items-center" id="Heading1">
                            <h2><?php global $cafe; echo $cafe['slider04_title']; ?></h2>
                            <a href="<?php global $cafe; echo $cafe['slider04_product_archive_cat']; ?>" class="ml-3"><?php global $cafe; echo $cafe['slider04_archive_title']; ?><i class="fa fa-chevron-left ml-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">	
					
						<div id="Carousel1" class="carousel" data-flickity='{"pageDots": false,"groupCells": true,"rightToLeft": true,"setGallerySize": false }' style="display:<?php global $cafe; echo $cafe['slider04_promo']; ?>">
							<?php  global $cafe;
								foreach($cafe['slider04_promo_images'] as $slide) {
									echo '<a href="' . $slide['url'] . '" class="promo-item">'; 
									echo '<div class="image-item_cover"><img class="img-fluid" src="' . $slide['image'] . '"></div></a>'; 
								}
							?>
						</div>
					
					
                        <!--Carousel-->
                        <div id="Carousel1" class="carousel" data-flickity='{"pageDots": false,"groupCells": true,"rightToLeft": true }' style="display:<?php global $cafe; echo $cafe['slider04_products']; ?>">
							<?php
								global $cafe;
								$arms = array(
									'post_type' => 'product',
									'posts_per_page' => $cafe['slider04_product_count'],
									'order' => 'DESC',
									'meta_query' => array(
											array(
												'key' => '_stock_status',
												'value' => 'instock'
											)
									),
									'post_status' => 'publish',
                                    'tax_query' => array(
										'relation' => 'OR',
										array(
											'taxonomy' => 'product_cat',
											'terms' => $cafe['slider04_product_cat'],
										),
										array(
											'taxonomy' => 'product_tag',
											'terms'    => $cafe['slider04_product_cat'],
										)
									)
								);
								$the_query = new WP_Query($arms);
							?>
						
							<?php if ($the_query->have_posts()): ?>
							<?php
								while ($the_query->have_posts()):
								$the_query->the_post();
							?>
							<a href="<?php the_permalink(); ?>"  class="image-item">
                                <div class="image-item_cover">
									<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->post->ID ), 'single-post-thumbnail' );?>
                                    <img src="<?php  echo $image[0]; ?>" />
                                </div>
                                <div class="image-item_text">
                                    <span class="image-item_title" style="display:<?php global $cafe; echo $cafe['slider04_products_title']; ?>"><?php the_title(); ?></span>
                                    <span class="image-item_subtitle" style="display:<?php global $cafe; echo $cafe['slider04_products_price']; ?>"><?php woocommerce_template_loop_price() ?></span>
                                </div>
                            </a>
							<?php endwhile; ?>
							<?php wp_reset_postdata();?>  
							<?php else: ?>
								<p class="nodata">
								<?php
									_e('متاسفانه هیچ محصولی جهت نمایش وجود ندارد ، لطفا از تنظیمات قالب / تنظیمات صفحه اصلی دسته بندی جهت نمایش را قرار دهید!');
								?>
								</p>
							<?php endif; ?> 	
                        </div>
                        <!--Carousel-->
                    </div>
                </div>
            </div>
        </section>