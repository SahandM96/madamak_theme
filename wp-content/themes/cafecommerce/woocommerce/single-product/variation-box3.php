<?php
/**
 * Single Product variation-developer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/variation-developer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="col-md-3 col-sm-6 col-6 text-center">
    <span class="author">

	
	<?php 
		$box3_file = rwmb_meta( 'cafe_button2_file_upload', array( 'limit' => 1 ) );
		$box3 = reset( $box3_file );
		if(!empty($box3_file)) {
	?>
		<img class="custom-img" src="<?php echo $box3['url']; ?>">
	<?php } ?>
</span>
<div class="info-install_title">
	    	<?php 
		$box3_txt = rwmb_meta( 'cafe_button2_file_upload_text'); 
		echo $box3_txt; 
	?>
	</div>
</div>
</div>
