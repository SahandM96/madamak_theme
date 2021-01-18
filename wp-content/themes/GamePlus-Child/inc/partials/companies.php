<section id="companies">

    <img class="controller" src="<?php echo get_template_directory_uri().'/images/controller.png'; ?>" alt="">

    <div class="section-heading">

        <h6>کمپانی ها</h6>

        <p>آرشیو محصولات کمپانی های معروف</p>
    </div>

    <div class="items">

        <?php
        $terms = get_field('companies','option');
        $i = 1;
        foreach( $terms as $term ):
        ?>
            <a href="<?php echo get_term_link( $term ); ?>" class="company">
                <div class="logo">
                    <img src="<?php the_field('company-logo',$term); ?>" alt="">
                </div>
                <div class="name">
                    <h6><?php echo $term->name; ?></h6>
                </div>
            </a>
        <?php if ($i++ == 6) break; endforeach; ?>

    </div>

</section>