<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Wordpress
 * @subpackage Gameplus
 * @since 1.0.0
 */

?>


<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php the_field('favicon','option'); ?>" />

    <link rel="stylesheet" href="<?php echo get_template_directory_uri().'/css/themify-icons.css'; ?>">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri().'/css/owl.carousel.min.css'; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>> <div id="app">

<header id="main-header">
    <div id="header-container">

        <a href="<?php echo get_site_url(); ?>" id="header-logo">
            <img src="<?php the_field('shop-icon','option'); ?>" alt="">
            <p><?php the_field('shoar','option'); ?></p>
        </a>

        <div id="left-side">

            <div>
                <div id="shop-navbar">
                    <button class="ti-search shop-navbar-item" @click="showSearchPanel"></button>
                    |
                    <div id="header-cart">
                        <?php $cart_content = WC()->cart->cart_contents_count; ?>
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="ti-shopping-cart shop-navbar-item">
                            <span id="cart-count"><?php echo intval($cart_content); ?></span>
                            <div id="cart-overview"><?php woocommerce_mini_cart(); ?></div>
                        </a>
                    </div>
                    |
                    <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="ti-user shop-navbar-item"></a>

                </div>

                <nav id="social-navbar">
                    <a href="<?php the_field('social-twitter','option'); ?>" class="ti-twitter"></a>
                    <a href="<?php the_field('social-facebook','option'); ?>" class="ti-facebook"></a>
                    <a href="<?php the_field('social-linkedin','option'); ?>" class="ti-linkedin"></a>
                    <a href="<?php the_field('social-telegram','option'); ?>" class="fab fa-telegram"></a>
                    <a href="<?php the_field('social-instagram','option'); ?>" class="fab fa-instagram"></a>
                </nav>

            </div>

            <div>
                <nav id="header-navbar">
                    <a href="<?php echo get_site_url(); ?>" class="ti-home icon-btn"></a>
                    <?php wp_nav_menu( array( 'theme_location' => 'header', 'container' =>'', 'menu_class' =>'nav-items', 'depth' => 2, ) ); ?>
                    <div>
                        <a href="<?php the_field('blog-page','option'); ?>" class="ti-book icon-btn">
                            <span class="title">وبلاگ</span>
                        </a>
                        <a href="<?php the_field('contact-page','option'); ?>" class="ti-headphone-alt icon-btn">
                            <span class="title">تماس</span>
                        </a>
                    </div>
                </nav>
            </div>

        </div>

    </div>

</header>


<header id="mobile-header">
    <div class="top">
        <div>
            <button class="ti-menu button" @click="openNav"></button>
        </div>
        <div>
            <a href="<?php echo get_site_url(); ?>"><img src="<?php the_field('shop-icon','option'); ?>" alt=""></a>
        </div>
        <div>
            <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="ti-user button"></a>
        </div>
    </div>
    <div class="bottom">
        <div>
            <form method="get" action="<?php bloginfo('url');?>" autocomplete="off" id="searchForm2">
                <input type="text" placeholder="جستجو در <?php bloginfo('name'); ?>" name="s" id="keyword2">
                <span  class="ti-search"></span>
                <input type="submit" id="searchsubmit" value=""/>
            </form>
            <div id="live_search_result2">
                <div id="result_box2"></div>
            </div>
        </div>
        <div>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="ti-shopping-cart shop-navbar-item">
                <span id="cart-count"><?php echo intval($cart_content); ?></span>
            </a>
        </div>
    </div>
</header>

<div id="fixed-search">
    <button class="ti-close" @click="closeSearchPanel"></button>
    <form method="get" action="<?php bloginfo('url');?>" autocomplete="off" id="searchForm">
        <input type="text" placeholder="کلمه مورد نظر + اینتر" name="s" id="keyword">
    </form>

    <div id="live_search_result">
        <div id="result_box"></div>
    </div>
    
</div>

<div id="black-screen" @click="openNav"></div>
<?php get_template_part('sidenav'); ?>

<div id="loading-screen">
    <img src="<?php echo get_template_directory_uri().'/images/loading.jpg'; ?>" alt="">
    <div class="page-loader loader-17">
        <div class="css-square square1"></div>
        <div class="css-square square2"></div>
        <div class="css-square square3"></div>
        <div class="css-square square4"></div>
        <div class="css-square square5"></div>
        <div class="css-square square6"></div>
        <div class="css-square square7"></div>
        <div class="css-square square8"></div>
    </div>
</div>


<button class="goToUp" @click="goToUp()"><i class="ti-angle-up"></i></button>