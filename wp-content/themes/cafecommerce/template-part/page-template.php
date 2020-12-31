<?php while (have_posts()) : the_post();?>
<main class="content-single" role="main">
	<div class="container">
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
	</div>
<?php endwhile;?>
</main>
