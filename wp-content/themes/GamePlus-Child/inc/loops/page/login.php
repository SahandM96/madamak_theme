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
		

		<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

		<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

		<div id="customer_login">

			<div class="login-container active">

		<?php endif; ?>
				<div class="photo">

					<?php $login_bg = get_template_directory_uri().'/images/cyberpunk2.jpg';
						if (!empty(get_field('login-bg', 'option'))) { $login_bg = get_field('login-bg', 'option'); }
					?>
					<div class="bg" style="background-image: url(<?php echo $login_bg; ?>"></div>
					<div class="detail">
						<h2>ورود به <?php echo get_bloginfo('name'); ?></h2>
						<p><?php the_field('shoar','option'); ?></p>
					</div>
				
				</div>
				<div class="content">
					<img src="<?php echo get_template_directory_uri().'/images/svg/login.svg'; ?>" alt="">

					<form class="woocommerce-form woocommerce-form-login login" method="post">

						<?php do_action( 'woocommerce_login_form_start' ); ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<input type="text" name="username" id="username" autocomplete="username" placeholder="نام کاربری" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
						</p>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<input type="password" name="password" id="password" placeholder="رمز عبور" autocomplete="current-password" />
						</p>

						<?php do_action( 'woocommerce_login_form' ); ?>

						<p class="form-row">
							
							<div class="two-part">
								<input type="checkbox" name="rememberme" id="rememberme" value="forever">
								<label id="for-rememberme" class="rounded-button" for="rememberme">مرا به خاطر بسپار</label>

								<span onclick="toSign()" class="rounded-button">ثبت نام</span>
							</div>
							
							<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
							<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
						</p>
						<p class="woocommerce-LostPassword lost_password">
							<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
						</p>

						<?php do_action( 'woocommerce_login_form_end' ); ?>

					</form>
				</div>

		<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

			</div>

			<div class="signin-container">

				<div class="photo">

					<?php $signin_bg = get_template_directory_uri().'/images/cyberpunk2.jpg';
						if (!empty(get_field('signin-bg', 'option'))) { $signin_bg = get_field('signin-bg', 'option'); }
					?>
					<div class="bg" style="background-image: url(<?php echo $signin_bg; ?>"></div>
					<div class="detail">
						<h2>ثبت نام در <?php echo get_bloginfo('name'); ?></h2>
						<p><?php the_field('shoar','option'); ?></p>
					</div>
				
				</div>

				<div class="content">
					<img src="<?php echo get_template_directory_uri().'/images/svg/sign.svg'; ?>" alt="">

					<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

						<?php do_action( 'woocommerce_register_form_start' ); ?>

						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
								<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
							</p>

						<?php endif; ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
							<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
						</p>

						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
								<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
							</p>

						<?php else : ?>

							<p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

						<?php endif; ?>

						<?php do_action( 'woocommerce_register_form' ); ?>

						<span onclick="toLogin()" class="rounded-button">ورود</span>

						<p class="woocommerce-form-row form-row">
							<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
							<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
						</p>

						<?php do_action( 'woocommerce_register_form_end' ); ?>

					</form>
				</div>

			</div>

		</div>
		<?php endif; ?>

	</main>

<script src="<?php echo get_template_directory_uri().'/jsmini/jquery.min.js'; ?>"></script>
<script src="<?php echo get_template_directory_uri().'/jsmini/login.js'; ?>"></script>

</div> </body>

</html>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>