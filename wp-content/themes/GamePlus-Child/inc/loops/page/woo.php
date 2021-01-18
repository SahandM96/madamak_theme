<?php get_header(); ?>


<main class="woo-main-page">

    <div class="background-container">
        <img class="slide-background" src="<?php echo get_template_directory_uri().'/images/assassins-creed-syndicate.jpg'; ?>">
        <div class="overly"></div>
    </div>

		<div class="content">
    <?php while(have_posts()) { the_post(); the_content(); } ?>
		</div>
  
</main>

<?php get_footer(); ?>