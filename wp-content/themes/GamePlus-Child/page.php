<?php

$cart_page_id = get_option( 'woocommerce_cart_page_id' );
$shop_page_id = get_option( 'woocommerce_shop_page_id' ); 
$checkout_page_id = get_option( 'woocommerce_checkout_page_id' );
$pay_page_id = get_option( 'woocommerce_pay_page_id' ); 
$thanks_page_id = get_option( 'woocommerce_thanks_page_id' ); 
$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' ); 
$edit_address_page_id = get_option( 'woocommerce_edit_address_page_id' ); 
$view_order_page_id = get_option( 'woocommerce_view_order_page_id' ); 
$terms_page_id = get_option( 'woocommerce_terms_page_id' ); 
$contact_page_id = url_to_postid(get_field('contact-page','option'));
$about_page_id = url_to_postid(get_field('about-page','option'));
$blog_page_id = url_to_postid(get_field('blog-page','option'));
$games_page_id = url_to_postid(get_field('games-page','option'));
$tickets_page_id = url_to_postid(get_field('tickets-page','option'));
$submit_ticket = url_to_postid(get_field('submit-ticket-page','option'));

$lost_password_url = wp_lostpassword_url();
$lost_password_url = str_replace('https://','',$lost_password_url);
$lost_password_url = str_replace('http://','',$lost_password_url);

$lost_password_url_confirm = $lost_password_url.'&reset-link-sent=true';

$orders_url = wc_get_account_endpoint_url( get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' ) );
$orders_url = str_replace('https://','',$orders_url);
$orders_url = str_replace('http://','',$orders_url);

$address_url = wc_get_account_endpoint_url( get_option( 'woocommerce_myaccount_edit_address_endpoint', 'edit-address' ) );
$address_url = str_replace('https://','',$address_url);
$address_url = str_replace('http://','',$address_url);

$edit_address_url = 'edit-address/';

$downloads_url = wc_get_account_endpoint_url( get_option( 'woocommerce_myaccount_downloads_endpoint', 'downloads' ) );
$downloads_url = str_replace('https://','',$downloads_url);
$downloads_url = str_replace('http://','',$downloads_url);

$edt_account_url = wc_get_account_endpoint_url( get_option( 'woocommerce_myaccount_edit_account_endpoint', 'edit-account' ) );
$edt_account_url = str_replace('https://','',$edt_account_url);
$edt_account_url = str_replace('http://','',$edt_account_url);

$ticket_detail_url = 'ticket/';

$view_order_url = 'view-order/';

global $wp_query;
$current_page_id = strval($wp_query->queried_object_id);

$current_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if ($current_url == $lost_password_url) {
    include(TEMPLATEPATH . '/inc/loops/page/lostPassword.php');
} else if ($current_url == $lost_password_url_confirm) {
    include(TEMPLATEPATH . '/inc/loops/page/lostPasswordConfirm.php');
} else if ($current_url == $orders_url) {
    include(TEMPLATEPATH . '/inc/loops/page/orders.php');
} else if ($current_url == $games_url) {
    include(TEMPLATEPATH . '/inc/loops/page/games.php');
} else if ($current_url == $address_url) {
    include(TEMPLATEPATH . '/inc/loops/page/address.php');
} else if ($current_url == $downloads_url) {
    include(TEMPLATEPATH . '/inc/loops/page/downloads.php');
} else if ($current_url == $edt_account_url) {
    include(TEMPLATEPATH . '/inc/loops/page/woo.php');
}else if (strpos($current_url, $view_order_url) !== false) {
    include(TEMPLATEPATH . '/inc/loops/page/woo.php');
} else if (strpos($current_url, $edit_address_url) !== false) {
    include(TEMPLATEPATH . '/inc/loops/page/woo.php');
} else if (strpos($current_url, $ticket_detail_url) !== false) {
    include(TEMPLATEPATH . '/inc/loops/page/ticketDetail.php');
} else {

    if ($current_page_id == $contact_page_id) {
        include(TEMPLATEPATH . '/inc/loops/page/contact.php');
    } else if ($current_page_id == $about_page_id) {
        include(TEMPLATEPATH . '/inc/loops/page/about.php');
    } else if ($current_page_id == $submit_ticket) {
        include(TEMPLATEPATH . '/inc/loops/page/ticketDetail.php');
    } else if ($current_page_id == $games_page_id) {
        include(TEMPLATEPATH . '/inc/loops/page/games.php');
    } else if ($current_page_id == $blog_page_id) {
        include(TEMPLATEPATH . '/inc/loops/page/blog.php');
    } else if ($current_page_id == $tickets_page_id) {
        include(TEMPLATEPATH . '/inc/loops/page/tickets.php');
    }   else if ($current_page_id == $myaccount_page_id && !is_user_logged_in() || $current_page_id == $cart_page_id && !is_user_logged_in()) {
        include(TEMPLATEPATH . '/inc/loops/page/login.php');
    } else if ($current_page_id == $myaccount_page_id && is_user_logged_in()) {
        include(TEMPLATEPATH . '/inc/loops/page/user.php');
    } else {
        include(TEMPLATEPATH . '/inc/loops/page/woo.php');
    }
    
}

//include(TEMPLATEPATH . '/inc/loops/page/cart.php'); ?>