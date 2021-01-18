<?php
/**
 * Home partial layout: Product Categories
 *
 * @package WordPress
 * @subpackage gameplus
 * @since 1.0
*/
?>

<section id="product-categories">

    <div class="items">

        <?php
        $terms = get_field('products-cat','option');
        $i = 1;
        foreach( $terms as $term ):
        ?>
            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="category">
                <h5><?php echo $term->name; ?></h5>
                <p><?php the_field('product-category-name',$term); ?></p>
            </a>

        <?php if ($i++ == 5) break; endforeach; ?>

    </div>

</section>