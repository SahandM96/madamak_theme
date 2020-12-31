<div class="row">
	<div class="col">
        <h1><?php single_cat_title(); ?></h1>
    </div>
</div>
<div class="archive-container mt-3">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<a href="<?php the_permalink() ?>" class="archive-item col-lg-3 col-md-4 col-sm-6">
        <div class="archive-image">
            <?php the_post_thumbnail('index'); ?>
        </div>
        <div class="archive-info">
            <div class="archive-info_title"><?php the_title(); ?></div>
        </div>
    </a>
<?php endwhile; else: ?>
<?php endif; ?>
</div>

