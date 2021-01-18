<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
} ?>

<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php bloginfo('name'); ?>
    </title>
    <?php wp_head(); ?>

</head>

<body <?php body_class() ?>><div id="app">


	<main id="login-main">

		<div class="background-l-container">
			<div class="slide-background"></div>
			<div class="overly"></div>
		</div>
	

		<div id="customer_login">

			<div class="login-container active">

				<div class="photo">

					<div class="bg"></div>
					<div class="detail">
						<h2>ورود به <?php echo get_bloginfo('name'); ?></h2>
						<p><?php the_field('shoar','option'); ?></p>
					</div>
				
				</div>
				<div class="content">
					<img src="<?php echo get_template_directory_uri().'/images/svg/password.svg'; ?>" alt="">

					<?php do_action( 'woocommerce_before_lost_password_form' ); ?>
					<form method="post" class="woocommerce-ResetPassword lost_reset_password">

						<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
							<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" placeholder="نام کاربری یا ایمیل" />
						</p>

						<div class="clear"></div>

						<?php do_action( 'woocommerce_lostpassword_form' ); ?>

						<p class="woocommerce-form-row form-row">
							<input type="hidden" name="wc_reset_password" value="true" />
							<button type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></button>
						</p>

						<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

						</form>
						<?php do_action( 'woocommerce_after_lost_password_form' ); ?>
				</div>

			</div>

		</div>

	</main>

<script src="<?php echo get_template_directory_uri().'/jsmini/jquery.min.js'; ?>"></script>
<script src="<?php echo get_template_directory_uri().'/jsmini/login.js'; ?>"></script>

</div> </body>

</html>