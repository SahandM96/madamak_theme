<?php get_header(); ?>


<main>

    <?php if (have_posts()) { while(have_posts()) : the_post(); ?>
    <div class="background-container">
        <img class="slide-background" src="<?php the_post_thumbnail_url(); ?>">
        <div class="overly"></div>
    </div>

    <section id="post-container">

        <div id="post-photo">
            <img src="<?php the_post_thumbnail_url(); ?>">
        </div>

        <div id="post-heading">
            <h2><?php the_title(); ?></h2>

            <div class="info">
                <p><?php echo persian_post_time(); ?></p> /
                <p>توسط <?php the_author(); ?></p>
            </div>
        </div>

        <div id="post-content">
            <?php the_content(); ?>
        </div>

    </section>
    <?php $currentID = get_the_ID(); ?>
    <?php endwhile; } ?>

    <div id="post-comments-section">
        <?php //echo apply_filters( 'the_content',' [rwp_box id="0"] ');
        comments_template(); ?>
    </div>



    <section id="more-posts">

        <div class="section-heading">
            <h6>بیشتر بخوانید</h6>
            <p>جدیدترین مقالات ما</p>
        </div>

        <div class="more-post-owl owl-carousel">
            <?php
            $args = array(
                'post_type'      => 'post',
                'post__not_in'   => array($currentID)
            );

            $loop = new WP_Query( $args );

            while ( $loop->have_posts() ) : $loop->the_post();
                global $post;
            ?>
            <a href="<?php the_permalink(); ?>" class="post">

                <div class="photo">
                    <?php the_post_thumbnail('large'); ?>
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
            <?php endwhile; wp_reset_query(); ?>
        </div>
    </section>
  
</main>

<?php get_footer(); ?>