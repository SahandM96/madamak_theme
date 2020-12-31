<div style="display:<?php global $sigma; echo $sigma['show_ads_product']; ?>" class="ads_posts">
    <a href="<?php global $sigma; echo $sigma['ads_post_link']; ?>" target="<?php global $sigma; echo $sigma['single_ads_target']; ?>">
        
        <?php global $sigma; if($sigma['single_ads_image']['url']!='') { ?>
    <img src="<?php echo $sigma['single_ads_image']['url']; ?>">
<?php } else { ?>
    <img alt="" src="<?php bloginfo('template_directory'); ?>/assets/img/ads728.png">
<?php } ?>

	</a>
    </div>