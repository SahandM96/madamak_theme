<div class="comments">
	<h5 class="section-title"><i class="ti-comments"></i> دیدگاه ها</h5>
	<?php 
		if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
			die ('Please do not load this page directly. Thanks!');

		if ( post_password_required() ) { ?>
			<p class="nocomments">برای مشاهده‌ی دیدگاه‌ها باید رمز نوشته را وارد کنید.</p>
		<?php
			return;
		}
	?>


	<?php if ( have_comments() ) : ?>

	<ul class="commentlist">
		<?php wp_list_comments(); ?>
	</ul>


	<div class="comments-navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
		</div>

		<?php else : ?>

		<?php if ('open' == $post->comment_status) : ?>

		<?php else : ?>
			<p>موقتا امکان ارسال دیدگاه وجود ندارد</p>

		<?php endif; ?>
		<?php endif; ?>

		<?php if ('open' == $post->comment_status) : ?>

		<div id="respond">

		<h5 class="postcomment"><?php comment_form_title( '', 'دیدگاه شما در مورد %s' ); ?></h5>

		<div class="cancel-comment-reply">
			<small><?php cancel_comment_reply_link(); ?></small>
		</div>

		<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
		<p>ابتدا <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">وارد شوید</a> تا بتوانید دیدگاهی ارسال کنید</p>

		<?php else : ?>

		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="<?php if ( $user_ID ) : echo 'logged'; endif;?>">

			<?php if ( $user_ID ) : ?>

		<p id="user">کاربر: <a id="userlink" href=""><?php echo $user_identity; ?></a>. <a id="logout" href="<?php echo wp_logout_url(get_permalink()); ?>" title="خروج از حساب کاربری">خروج از حساب کاربری</a></p>

		<p id="psubmit-logged">
			<input name="submit" id="submit" type="submit" tabindex="5" value="انتشار دیدگاه" class="Cbutton" />
			</p>

			<?php else : ?>
			<p id="pauthor">
			<input type="text" name="author" id="author" class="textarea" placeholder="نام" size="28" tabindex="1" />
			</p>

			<p id="pemail">
			<input type="text" name="email" id="email" placeholder="ایمیل" size="28" tabindex="2" class="textarea" />

			</p>

			<p id="purl">
			<input type="text" name="url" id="url" placeholder="وبسایت خود را وارد کنید..."  size="28" tabindex="3" class="textarea" />

			</p>

			<p id="psubmit">
			<input name="submit" id="submit" type="submit" tabindex="5" value="انتشار دیدگاه" class="Cbutton" />

			<?php endif; ?>

			<p id="pcomment">
			<textarea name="comment" id="comment" cols="60" rows="10" tabindex="4" class="textarea" placeholder="دیدگاه"></textarea>
			</p>
			
			<?php comment_id_fields(); ?>
			</p>
			<?php do_action('comment_form', $post->ID); ?>
		</form>
		<?php endif; ?>

	</div>
	<?php else : ?>
	<p>موقتا امکان ارسال دیدگاه وجود ندارد</p>
	<?php endif; ?>
</div>