<?php 
/**
 * Template Name: Blog

 * Blog Page Template.
 *
 * @author Hamkarwp
 * @since 1.0.0
 */
?>

<?php
get_header();
get_template_part('template-part/headers/head'); ?>
<div class="content-post">
    <div class="container">
		<div class="row">
			<div class="col-lg-9 archive-post-content">
				<div class="breadcrumb_post">
					<?php 
					$args = array(
					'delimiter' => ' <i></i> ',
					'home' => _x( 'خانه', 'breadcrumb', 'woocommerce' ));
					woocommerce_breadcrumb($args); ?>
				</div>
				<div class="archive-sigma">
					<div class="row">
<?php global $post;$myposts = get_posts(); foreach($myposts as $post) : setup_postdata($post);?>
<div class="col-lg-4 last-posts col-sm-6">
            <a href="<?php the_permalink() ?>"><?php the_post_thumbnail('index'); ?></a>
            
            <div class="post-meta">
            <div class="title-post"><h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2></div>
            <p><?php the_content_rss('', TRUE, '', 30); ?></p>
            
            <div class="row">
        <div class="cat-post col-lg-6 col-7">
    <h5><i class="fa fa-folder"></i> <?php the_category(', ') ?></h5></div>

        <div class="cat-post col-lg-6 col-5">
    <i class="fa fa-eye"></i> <?php
        echo getPostViews(get_the_ID());
?></div>                
            </div>
            <a class="more-post" href="<?php the_permalink() ?>" target="_blank"><i class="fa fa-list-ul"></i>توضیحات بیشتر</a>
        </div>
                    </div>
					 <?php endforeach; ?>	
<div class="pagenavi">
<?php sigma_numeric_posts_nav(); ?>

</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 sidebar-post nopadding-left">
				<?php get_template_part('template-part/content/archive/sidebar'); ?>
			</div>
		</div>
	</div>
</div>
<?php get_template_part('template-part/footers/footer');
get_footer();