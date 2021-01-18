<?php get_header(); ?>


<main>

    <div class="background-container">
        <img class="slide-background" src="<?php echo get_template_directory_uri().'/images/assassins-creed-syndicate.jpg'; ?>">
        <div class="overly"></div>
    </div>

    <?php while(have_posts()) { the_post(); the_content(); } ?>
  
</main>

<?php get_footer(); ?>