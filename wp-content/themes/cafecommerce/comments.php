<div id="commentsarea" class="mt-5">
    
<h4><i class="fa fa-comment-o"></i> دیدگاه کاربران</h4>

<?php if ( post_password_required() ) : ?>
<p class="nopassword"> این مطلب محافظت شده است. برای نمایش نظرات رمز عبور را وارد نمائید.</p>
</div>
<?php return; endif; ?>
<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
<ol class="commentlist posts"><?php wp_list_comments( array( 'callback' => 'comment_loop' ) ); ?></ol>
<p class="nopassword posts">متاسفیم! برای ثبت دیدگاه باید <a id="show_login" href="#">وارد شوید!</a> </p>
</div>
<?php return; endif; ?>
<?php if ( have_comments() ) : ?>

<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
<p class="nocomments">مجوز ارسال دیدگاه داده نشده است!</p>
<?php endif; ?>

<ol class="commentlist posts"><?php wp_list_comments( array( 'callback' => 'comment_loop' ) ); ?></ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
<nav id="comment-nav-above">
<div class="nav-previous"><?php previous_comments_link( 'دیدگاه‌های قبلی' ); ?></div>
<div class="nav-next"><?php next_comments_link( 'دیدگاه‌های جدید' ); ?></div>
</nav>
<?php endif; ?>

<?php comment_form(); ?>