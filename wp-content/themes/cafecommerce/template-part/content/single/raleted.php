
<div style="display:<?php global $sigma; echo $sigma['ralated_post']; ?>" class="ralated-post">
    
    <h4><?php global $sigma; echo $sigma['relate_post_title']; ?></h4>
     <div id="related" class="owl-carousel owl-theme">

<?php $related=get_posts(array('category__in'=>wp_get_post_categories($post->ID),
'orderby'=>'rand','numberposts'=>12,'post__not_in'=>array($post->ID)));
if($related) foreach($related as $post){ setup_postdata($post); ?>

    <div class="col-lg-12 last-posts">
            <a href="<?php the_permalink() ?>"><?php the_post_thumbnail('index'); ?></a>
            
            <div class="post-meta">
            <div class="title-post"><h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2></div>
            <p><?php the_content_rss('', TRUE, '', 30); ?></p>
            
            <div class="row">
        <div class="cat-post col-lg-6 col-8">
    <i class="fa fa-folder"></i> <?php the_category(', ') ?></div>

        <div class="cat-post col-lg-6 col-4">
    <i class="fa fa-eye"></i> <?php
        echo getPostViews(get_the_ID());
?></div>                
            </div>
            <a class="more-post" href="<?php the_permalink() ?>" target="_blank"><i class="fa fa-list-ul"></i>توضیحات بیشتر</a>
        </div>
                    </div>

<?php } wp_reset_postdata(); ?>
    </div>
    </div>