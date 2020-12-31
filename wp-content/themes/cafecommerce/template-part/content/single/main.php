<?php while (have_posts()) : the_post();?>
<div class="content text-center">
     <h1 class="entry-title my-3"><?php the_title(); ?></h1>
     <div style="display:<?php global $cafe; echo $cafe['show_thumnail']; ?>;" class="my-3"><?php the_post_thumbnail('index'); ?></div>
     <?php the_content();?>
</div>
<?php endwhile;?>
