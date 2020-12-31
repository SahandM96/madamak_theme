<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<?php
	/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	?>
	
	<section class="header-product">
		<div class="container">
			<div class="app-details_head">
				<div class="cover-header col-md-12">
					<div class="cover-header_thumbnail col-md-3">
						<?php if ( has_post_thumbnail( $product->id ) ) {
								$attachment_ids[0] = get_post_thumbnail_id( $product->id );
								$attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
								<img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
						<?php } ?>
					</div>
					
					<?php do_action( 'woocommerce_single_product_summary' ); ?>

                </div>
			</div>
		</div>
	</section>
	 
	<?php do_action( 'woocommerce_before_single_product_summary' );	?>


	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
