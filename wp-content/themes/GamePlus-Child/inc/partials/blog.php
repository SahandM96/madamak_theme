<section id="blog-posts">

    <div class="background-right">
        <img src="<?php echo get_template_directory_uri().'/images/assassins-creed-valhalla.png' ?>" alt="">
        <div class="overly-x"></div>
        <div class="overly-y"></div>
    </div>

    <div class="right">

        <?php
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => 1
        );

        $loop = new WP_Query( $args );

        while ( $loop->have_posts() ) : $loop->the_post();
            global $post;
        ?>
            <a href="<?php the_permalink(); ?>" class="big-post">

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
                            <h4><?php the_title(); ?></h4>
                        </div>

                        <div class="readmore">
                            <p>بیشتر بخوانید <i class="ti-arrow-left"></i></p>
                        </div>

                        <div class="statics">
                            <div>
                                <i class="ti-eye"></i>
                                <p>5000</p>
                            </div>
                            <div>
                                <i class="ti-eye"></i>
                                <p>5000</p>
                            </div>
                            <div>
                                <i class="ti-eye"></i>
                                <p>5000</p>
                            </div>
                        </div>
                </div>

            </a>
        <?php endwhile; wp_reset_query(); ?>

    </div>

    <div class="left">
    

        <div class="posts">

            <?php
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 4,
                'offset' => 1
            );

            $loop = new WP_Query( $args );

            while ( $loop->have_posts() ) : $loop->the_post();
                global $post;
            ?>
                <a href="<?php the_permalink(); ?>" class="side-post">
                    <div class="photo">
                        <?php the_post_thumbnail( 'medium' );  ?>
                    </div>
                    <div>
                        <h5><span id="marquee-text"><?php the_title(); ?></span></h5>
                        <p class="writer"><?php the_author(); ?></p>
                        <p><?php echo persian_post_time(); ?></p>
                    </div>
                    <span class="ti-angle-left"></span>
                </a>
            <?php endwhile; wp_reset_query(); ?>

        </div>

        <a href="<?php the_field('blog-page','option'); ?>" class="see-all">مشاهده همه پست ها<i class="ti-angle-left"></i></a>


    </div>

</section>