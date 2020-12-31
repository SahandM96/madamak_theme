<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
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
<div class="cover-header_title col-md-9">
    <div class="row mb-3">
	<div class="col-md-12 mb-3">
	    	<?php the_title( '<h1>', '</h1>' ); ?>
	</div>

 <div class="col-md-3 col-sm-6 col-6">
        <div class="info-install_content">
            <span>
				<?php 
					$box1_file = rwmb_meta( 'cafe_button1_file_upload', array( 'limit' => 1 ) );
					$box1 = reset( $box1_file );
					if(!empty($box1_file)) {
				?>
					<img src="<?php echo $box1['url']; ?>">
				<?php } ?>
			</span>
        </div>
		<div class="info-install_title">
			<span>
				<?php 
					$box1_txt = rwmb_meta( 'cafe_button1_file_upload_text'); 
					echo $box1_txt; 
				?>
			</span>
		</div>
	</div>
	
	<div class="col-md-3 col-sm-6 col-6">
						<div class="info-size_content">
							<div class="info-size_data">
								<?php 
									$box4_file = rwmb_meta( 'cafe_button4_file_upload', array( 'limit' => 1 ) );
									$box4 = reset( $box4_file );
									if(!empty($box4_file)) {
								?>
									<img src="<?php echo $box4['url']; ?>">
								<?php } ?>
							</div>
						</div>
						<div class="info-size_title">
							<span>
								<?php 
									$box4_txt = rwmb_meta( 'cafe_button4_file_upload_text'); 
									echo $box4_txt; 
								?>
							</span>
						</div>
					</div>
			