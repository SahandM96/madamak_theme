<div id="sidenav">

    <div id="shop-name"><a href="<?php echo get_site_url(); ?>"><?php bloginfo('name'); ?></a></div>

    <nav id="base-list">
        <?php wp_nav_menu( array( 'theme_location' => 'header', 'container' =>'', 'menu_class' =>'nav-items' ) ); ?>
    </nav>

    <nav id="other-list">
        <ul>
            <li><a href="<?php the_field('blog-page','option'); ?>"><i class="ti-book"></i> وبلاگ</a></li>
            <li><a href="<?php the_field('contact-page','option'); ?>"><i class="ti-headphone-alt"></i> تماس</a></li>
        </ul>
    </nav>

</div>