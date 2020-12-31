<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">
<meta name="author" content="hamkarwp">
<meta charset="utf-8">
<link rel="shortcut icon" type="image/png" href="<?php global $cafe; if($cafe['favicon']['url']!='') { ?><?php echo $cafe['favicon']['url']; ?>
<?php } else { ?> <?php bloginfo('template_directory'); ?>/assets/img/favicon.ico <?php } ?>">
<title><?php if (is_home () ) { bloginfo('name'); } elseif ( is_category() ) { single_cat_title(); echo ' - ' ; bloginfo('name'); }
 elseif (is_single() ) { single_post_title(); }
 elseif (is_page() ) { bloginfo('name'); echo ': '; single_post_title(); }
 else { wp_title('',true); } ?></title>
<meta name="description" content="<?php if (is_single()) {
    single_post_title('', true);
} else {
    bloginfo('name');
    echo " - ";
    bloginfo('description');
}
?>" />
<meta name="og:title" property="og:title" content="<?php wp_title( '|', true, 'right' ); ?><?php bloginfo( 'name' ); ?>">
<meta name="keywords" content="<?php global $cafe; echo $cafe['keywords']; ?>">
<?php wp_head(); ?>
<link href="<?php bloginfo('template_url')?>/assets/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css">
<link href="<?php bloginfo('template_url')?>/assets/fonts/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php bloginfo('template_url')?>/assets/css/flickity.css" rel="stylesheet" type="text/css">
<link href="<?php bloginfo('template_url')?>/assets/css/styles.min.css" rel="stylesheet" type="text/css">
<style><?php global $cafe; echo $cafe['custom_css']; ?></style>
<?php get_template_part('template-part/style'); ?>
<script><?php global $cafe; echo $cafe['custom_js']; ?></script>
</head>

<body class="rtl" <?php body_class(); ?>>