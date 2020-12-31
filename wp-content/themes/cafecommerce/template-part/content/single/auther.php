<div class="autherarea" style="display:<?php global $sigma; echo $sigma['user_auther_box']; ?>">
    <div class="auther_img">
                <?php echo get_avatar( get_the_author_meta('email'), '75' ); ?>
    </div>
    <div class="author_about">
       <div class="author-name"> <?php the_author(', ') ?></a></div>
       <div class="author-date">

		<?php
		$current_user = wp_get_current_user();
		$reg_date = date_i18n( 'j F Y', strtotime( $current_user->user_registered ) );
		?>
		تاریخ عضویت  : <?php echo $reg_date; ?>	  
	</div>
                <p><?php the_author_meta('description'); ?></p>
    </div>

    </div>
    
    <a style="display:<?php global $sigma; echo $sigma['telegram_channel']; ?>" class="telegram_chanel" style="display:inherit" href="<?php global $sigma; echo $sigma['link_btn_channel']; ?>" target="_blank">
        <i class="icon-telegram"></i>
        <span><?php global $sigma; echo $sigma['text_box_telegram']; ?></span>
        <span class="pull-left"><?php global $sigma; echo $sigma['text_btn_box_telegram']; ?></span>
    </a>