<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' ); 

if (!is_user_logged_in() && $myaccount_page_id) { wp_redirect(get_page_link($myaccount_page_id)); }

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>



<section id="way">

    <div class="level active">
        <div class="icon">
            <img src="<?php echo get_template_directory_uri().'/images/svg/basket-yellow.svg'; ?>" alt="">
        </div>
        <h6>سبد خرید</h6>
    </div>

    <span class="line fullLine"></span>

    <div class="level active">
        <div class="icon">
            <img src="<?php echo get_template_directory_uri().'/images/svg/form-yellow.svg'; ?>" alt="">
        </div>
        <h6>تکمیل اطلاعات</h6>
    </div>

    <span class="line activeLine"></span>

    <div class="level">
        <div class="icon">
            <img src="<?php echo get_template_directory_uri().'/images/svg/pay-white.svg'; ?>" alt="">
        </div>
        <h6>پرداخت</h6>
    </div>

</section>


<section id="checkout">

	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

		<?php if ( $checkout->get_checkout_fields() ) : ?>

			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<?php do_action( 'woocommerce_checkout_billing' ); ?>

			<?php do_action( 'woocommerce_checkout_shipping' ); ?>

			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		<?php endif; ?>
		
		<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
		
		<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>
		
		<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

		<div id="order_review" class="woocommerce-checkout-review-order">
			<?php do_action( 'woocommerce_checkout_order_review' ); ?>
		</div>

		<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

	</form>

	<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

</section>
