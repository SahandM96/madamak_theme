<?php get_header(); ?>

<main>

	<div class="background-container">
        <img class="slide-background" src="<?php echo get_template_directory_uri().'/images/assassins-creed-syndicate.jpg' ?>">
        <div class="overly"></div>
    </div>

	<div class="search-container">

        <div class="page-heading ">
            <h6>نتایج جست و جو برای:</h6>
            <h6 class="per"><?php echo get_search_query(); ?></h6>
        </div>
        
		<section id="product-list">
			<?php if (have_posts()) { while(have_posts()) : the_post();
                get_template_part('inc/loops/items/productTypeThree');
            endwhile; } else { ?>
                
            <?php } ?>
		</section>

	</div>

</main>


<?php get_footer(); ?>