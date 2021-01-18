<?php get_header(); ?>


<main>

    <div class="background-container">
        <img class="slide-background" src="<?php echo get_template_directory_uri().'/images/farcry.jpg'; ?>">
        <div class="overly"></div>
    </div>

    <div class="blog-page">

        <div class="page-heading">
            <h6>وبلاگ</h6>
        </div>

        <div class="blog-posts">

            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'post_type'      => 'post',
                'orderby'           => 'date',
                'order'             => 'DESC',
                'post_status'       => 'publish',
                'paged'             => $paged,
            );

            $loop = new WP_Query( $args );

            if ($loop->have_posts()) {
            while ( $loop->have_posts() ) : $loop->the_post();
                global $post;
            ?>
            <a href="<?php the_permalink(); ?>" class="post">

                <div class="photo">
                    <?php the_post_thumbnail(); ?>
                    <div class="overly"></div>
                </div>

                <div class="detail">

                    <div class="info">
                        <p><?php echo persian_post_time(); ?></p> /
                        <p>توسط <?php the_author(); ?></p>
                    </div>

                    <div class="title">
                        <h4><span id="marquee-text"><?php the_title(); ?></span></h4>
                    </div>

                    <div class="readmore">
                        <p>بیشتر بخوانید <i class="ti-arrow-left"></i></p>
                    </div>
                </div>

            </a>
            <?php endwhile; ?>

        </div>

        <div class="pagenation">
            <?php wp_pagenavi( array( 'query' => $loop ) ); wp_reset_postdata(); } ?>
        </div>

    </div>
  
</main>

<?php get_footer(); ?>