<?php while (have_posts()) : the_post();?>

<div class="mobile_cart_fix"> <a href="#buy"><i class="fa fa-shopping-basket"></i>خرید آنلاین محصول</a></div>
<div class="product-license" style="display:<?php global $sigma; echo $sigma['license_productv2']; ?>">
            <?php global $sigma; if($sigma['img_license']['url']!='') { ?>
                <img src="<?php echo $sigma['img_license']['url']; ?>">
              <?php } else { ?>
                <img alt="" src="<?php bloginfo('template_directory'); ?>/assets/img/license.svg">
            <?php } ?>
            
<h3><?php global $sigma; echo $sigma['title_license']; ?></h3>
		<?php the_excerpt(); ?>
</div>

<div class="product-note for-fix-product" id="sidebar">
                <?php global $sigma; if($sigma['img_detial']['url']!='') { ?>
                <img src="<?php echo $sigma['img_detial']['url']; ?>">
              <?php } else { ?>
                <img alt="" src="<?php bloginfo('template_directory'); ?>/assets/img/info.svg">
            <?php } ?>
            
<h3><?php global $sigma; echo $sigma['title_detial']; ?></</h3>

<div class="list-notes"  style="display:<?php global $sigma; echo $sigma['license_product']; ?>" >
<?php global $sigma; echo $sigma['license_content']; ?>

</div>


 	<div id="buy" class="summary entry-summary sigma_price_holder" style="display:<?php global $sigma; echo $sigma['show_price']; ?>">
		<?php
			/**
			 * Hook: woocommerce_single_product_summary.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>
	</div>
	
<div class="btm_more_sigma" style="display:<?php global $sigma; echo $sigma['show_demo_en']; ?>" >
<div class="help">
<?php $mid_var = get_post_meta($post->ID, 'help',true);
if(isset($mid_var) && !empty($mid_var)) : ?>

<a href="<?php echo get_post_meta($post->ID, 'help',true); ?>" target="_blank" class="help">
راهنمای کار با محصول</a>

<?php endif; ?>

</div>

<div class="demo-fa" style="display:<?php global $sigma; echo $sigma['show_demo_en']; ?>" >
<?php $mid_var = get_post_meta($post->ID, 'demo-fa',true);
if(isset($mid_var) && !empty($mid_var)) : ?>

<a href="<?php echo get_post_meta($post->ID, 'demo-fa',true); ?>" target="_blank" class="demo-fa">پیشنمایش فارسی
</a>

<?php endif; ?>

</div>

<div class="demo-en" style="display:<?php global $sigma; echo $sigma['show_demo_en']; ?>" >
<?php $mid_var = get_post_meta($post->ID, 'demo',true);
if(isset($mid_var) && !empty($mid_var)) : ?>

<a href="<?php echo get_post_meta($post->ID, 'demo',true); ?>" target="_blank" class="demo-en">پیشنمایش انگلیسی
</a>

<?php endif; ?>

</div>

</div>	

<div class="info-box-dt" style="display:<?php global $sigma; echo $sigma['info_product']; ?>">

		<span class="date"><i class="fa fa-calendar"></i><p>تاریخ ارسال:</p><strong> <?php the_time('Y/m/d'); ?></strong></span>


<?php $mid_var = get_post_meta($post->ID, 'include',true);
if(isset($mid_var) && !empty($mid_var)) : ?>

		<span class="date"><i class="fa fa-file"></i><p>فایل های موجود: </p><strong><?php echo get_post_meta($post->ID, 'include',true); ?>

</strong></span><?php endif; ?>

		<span class="date"><i class="fa fa-eye-slash"></i><p>تعداد بازدید:</p><strong><?php echo getPostViews(get_the_ID()); ?></strong></span>	

<?php $mid_var = get_post_meta($post->ID, 'verion',true);
if(isset($mid_var) && !empty($mid_var)) : ?>


		<span class="date"><i class="fa fa-desktop"></i><p>نسخه محصول:</p><strong><?php echo get_post_meta($post->ID, 'verion',true); ?>
</strong></span><?php endif; ?>

<?php $mid_var = get_post_meta($post->ID, 'rahmnama',true);
if(isset($mid_var) && !empty($mid_var)) : ?>

		<span class="date"><i class="fa fa-info-circle"></i><p>فایل راهنما:</p><strong><?php echo get_post_meta($post->ID, 'rahmnama',true); ?>

</strong></span><?php endif; ?>

		<span  style="display:<?php global $sigma; echo $sigma['update_show']; ?>" class="date" ><i class="fa fa-upload"></i><p>تاریخ بروزرسانی:</p><strong>
    
    <?php

global $product;

echo '<span class="date_modified">' . $product->get_date_modified()->date_i18n('Y/m/d') . '</span>';

?>

    </strong></span>	

<?php $mid_var = get_post_meta($post->ID, 'size',true);
if(isset($mid_var) && !empty($mid_var)) : ?>

		<span class="date"><i class="fa fa-file-o"></i><p>حجم فایل:</p><strong><?php echo get_post_meta($post->ID, 'size',true); ?>
</strong></span><?php endif; ?>


		<span class="date" style="display:<?php global $sigma; echo $sigma['sales']; ?>" ><i class="fa fa-shopping-cart"></i><p>تعداد فروش:</p><strong>
<?php global $product;
$units_sold = get_post_meta( $product->id, 'total_sales', true );
echo ''. sprintf( __( '%s', 'woocommerce' ), $units_sold ).''; ?>

</strong></span>	


		<span class="date"><i class="fa fa-comment"></i><p>تعداد دیدگاه ها:</p><strong>

<?php comments_number( '0', ' 1 ', ' % ' ); ?>


</strong></span>	
 </div>
 
 	<div class="shoppin-card" style="display:<?php global $sigma; echo $sigma['payment_img_active']; ?>;">
		<span><?php global $sigma; echo $sigma['payment_img_title']; ?></span>

            <?php global $sigma; if($sigma['payment_img']['url']!='') { ?>
                <img src="<?php echo $sigma['payment_img']['url']; ?>">
              <?php } else { ?>
                <img alt="" src="<?php bloginfo('template_directory'); ?>/assets/img/shopping-cart.png">
            <?php } ?>		
		
	</div>
	
	
	
    </div>
    
    <?php endwhile;?>


<div class="author-product" style="display:<?php global $sigma; echo $sigma['vendor_show']; ?>">
        <div class="auther_img">
                <?php echo get_avatar( get_the_author_meta('email'), '60' ); ?>
    </div>
    <div class="about-vendor">
       <div class="name-vendor"> <?php the_author(', ') ?></a></div>
       <div class="date-vendor">		<?php
		$current_user = wp_get_current_user();
		$reg_date = date_i18n( 'j F Y', strtotime( $current_user->user_registered ) );
		?>
		تاریخ عضویت  : <?php echo $reg_date; ?>	 </div>
                <p class="des-vendor"><?php the_author_meta('description'); ?></p>
    </div>

<?php
if ( is_plugin_active( 'dokan-lite/dokan.php' ) ) { ?>
    <a style="display:<?php global $sigma; echo $sigma['vendor_show_btn']; ?>" href="<?php echo do_shortcode( ' [store_vendor_url] ' ); ?>" class="more-product" target="_blank" ><i class="fa fa-archive"></i>محصولات بیشتر فروشنده</a>
<?php } 
 ?>
          
    </div>



<div class="box-fav-sup">
    	<div  style="display:<?php global $sigma; echo $sigma['support_show']; ?>" class="support-btn"><a href="<?php global $sigma; echo $sigma['support_link']; ?>" target="_blank" class="support"><i class="fa fa-life-ring"></i>دریافت پشتیبانی</a></div>
				

	<div style="display:<?php global $sigma; echo $sigma['report_show']; ?>;" class="report" id="myBtn"><a href="#" id="reportbtn" data-toggle="modal" data-target="#reportmodal" class="report-btn" data-toggle="modal" data-target="#reportbox"><i class="fa fa-bug"></i><?php global $sigma; echo $sigma['report_title']; ?></a></div>
  

<div id="reportmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ارسال گزارش برای محصول <?php the_title(); ?></h4>
      </div>
      <div class="modal-body">
        <p>شما در حال ارسال گزارش برای محصول <strong><?php the_title(); ?></strong> هستید! </p>

<div class="notice_sigma">
          <p><strong>لطفاً به موارد زیر دقت فرمایید:</strong></p>
          <p></p><ul>
            <li>قبل از ارسال گزارش تخلف حتماً از صحت گزارش خود مطمئن شوید.</li>
            <li>از ارسال گزارش&zwnj;های پی در پی جداً خودداری کنید.</li>
            <li>اطلاعات شما در سیستم ذخیره خواهد شد.</li>
            <li>نکته مهم‌: لطفا عنوان محصول را در گزارش خود قید کنید.</li>
          </ul><p></p>
        </div>
<div class="report-form">
	<?php echo do_shortcode( '[contact-form-7 title="500"]' ); ?>
</div>

      </div>
    </div>

  </div>
</div>

<div  style="display:<?php global $sigma; echo $sigma['urlshort_show']; ?>" >
  <span class="short-link"><i class="fa fa-link"></i>لینک کوتاه : </span> 
    					<input type="text" class="ulink" value="<?php bloginfo('url'); ?>/?p=<?php the_ID(); ?>" id="myInput">	
					<i class="fa fa-files-o copy" onclick="myFunction()" aria-hidden="true"></i></div>
</div>
	
<div style="display:<?php global $sigma; echo $sigma['side_ads']; ?>" class="ads_sidebar">
    <a href="<?php global $sigma; echo $sigma['ads_side_link']; ?>" target="<?php global $sigma; echo $sigma['ads_side_target']; ?>" >
        
                    <?php global $sigma; if($sigma['ads_side_banner']['url']!='') { ?>
                <img src="<?php echo $sigma['ads_side_banner']['url']; ?>">
              <?php } else { ?>
                <img alt="" src="<?php bloginfo('template_directory'); ?>/assets/img/ads300-1.png">
            <?php } ?>
        
        </a>
    </div>
    
<div style="display:<?php global $sigma; echo $sigma['prom_code_active']; ?>" id="permissions" class="owl-carousel owl-theme enamad">

<?php global $sigma; echo $sigma['enamad_code_active']; ?>  <div><?php global $sigma; echo $sigma['enamad_code']; ?></div>-->
<?php global $sigma; echo $sigma['samandehi_code_active']; ?><div><?php global $sigma; echo $sigma['samandehi_code']; ?></div>-->
<?php global $sigma; echo $sigma['bank_code_active']; ?><div><?php global $sigma; echo $sigma['bank_code']; ?></div>-->

    </div>

<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('سایدبار سمت چپ جزییات محصول')) : else : ?> <?php endif; ?>	