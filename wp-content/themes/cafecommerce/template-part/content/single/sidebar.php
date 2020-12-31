<?php $mid_var = get_post_meta($post->ID, 'dl_server1',true);
if(isset($mid_var) && !empty($mid_var)) : ?>

<div style="display: <?php global $sigma; echo $sigma['show_box_dl']; ?>" class="post-sidebar">
<?php global $sigma; if($sigma['box_dl_image_v1']['url']!='') { ?>
    <img src="<?php echo $sigma['box_dl_image_v1']['url']; ?>">
<?php } else { ?>
    <img alt="" src="<?php bloginfo('template_directory'); ?>/assets/img/download.png">
<?php } ?>

<span class="title-dlbox"><?php global $sigma; echo $sigma['box_dl_title']; ?></span>
<div class="license">

<?php global $sigma; echo $sigma['box_dl_content']; ?>


</div>

<div class="dl_server1">


<div class="dl_server1" ><a href="<?php echo get_post_meta($post->ID, 'dl_server1',true); ?>" target="_blank" class="dl_server1">
دانلود با لینک مستقیم (سرور یک )</a></div>


		</div>	
		
		
	<div class="dl_server2">

<?php $mid_var = get_post_meta($post->ID, 'dl_server2',true);
if(isset($mid_var) && !empty($mid_var)) : ?>
<div class="dl_server2" ><a href="<?php echo get_post_meta($post->ID, 'dl_server2',true); ?>" target="_blank" class="dl_server2">
دانلود با لینک غیرمستقیم (سرور دو )</a></div>
<?php endif; ?>
</div>
								
	<div class="siteurl">

<?php $mid_var = get_post_meta($post->ID, 'siteurl',true);
if(isset($mid_var) && !empty($mid_var)) : ?>
<div class="siteurl" ><a href="<?php echo get_post_meta($post->ID, 'siteurl',true); ?>" target="_blank" class="siteurl">
ورود به سایت سازنده </a></div>
<?php endif; ?>



</div>
	
	
	<div class="password_file" style="display:<?php global $sigma; echo $sigma['show_box_pass']; ?>" >

<?php $mid_var = get_post_meta($post->ID, 'password_file',true);
if(isset($mid_var) && !empty($mid_var)) : ?>
<div class="password_file2" ><i class="fa fa-lock"></i><span class="cpassword_file" >پسورد فایل : <?php echo get_post_meta($post->ID, 'password_file',true); ?>
</span></div>
<?php endif; ?>



</div>
	
	
	<div class="info-box-dt" style="display:<?php global $sigma; echo $sigma['show_box_meta']; ?>">

		<span class="date"><i class="fa fa-file-o"></i><p>حجم فایل:</p><strong><?php echo get_post_meta($post->ID, 'size',true); ?>

<?php $mid_var = get_post_meta($post->ID, 'size',true);
if(isset($mid_var) && !empty($mid_var)) : ?>
<?php endif; ?></strong></span>	

		<span class="date"><i class="fa fa-file"></i><p>نوع فایل:</p><strong><?php echo get_post_meta($post->ID, 'type',true); ?>

<?php $mid_var = get_post_meta($post->ID, 'type',true);
if(isset($mid_var) && !empty($mid_var)) : ?>
<?php endif; ?></strong></span>	





	</div>																							

</div>
<?php endif; ?>

<div class="short_url_post">
      <span class="short-link"><i class="fa fa-link"></i>لینک کوتاه : </span> 
    					<input type="text" class="ulink" value="<?php bloginfo('url'); ?>/?p=<?php the_ID(); ?>" id="myInput">	
					<i class="fa fa-files-o copy" onclick="myFunction()" aria-hidden="true"></i>
</div>
<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('سایدبار چپ - صفحه اصلی')) : else : ?><?php endif; ?>


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