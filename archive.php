<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Get Halal
 */

get_header();

$archive = get_queried_object();
$title  = single_cat_title( '', false );
$description = get_the_archive_description();

//var_dump('Archive Php: ' . $title);
?>

<div class="archive-container">
    <div class="sidebar-box">
        <div class="sidebar-items">
            <?php
            $categories = get_categories(
                array(
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => false,
                    'order'   => 'ASC',
                    'orderby' => 'menu_order'
                )
            );
            $subCategories = [];
            foreach ( $categories as $category ) {
                if($category->parent || $category->slug == 'uncategorized') {
                    if($category->parent == $archive->term_id){
                        $subCategories[] = $category;
                    }
                    continue;
                }
                $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                $image = wp_get_attachment_url( $thumbnail_id );
                $is_active = $category->term_id == $archive->term_id;
                echo '<a href="' . esc_url( home_url( '/' )) . '/product-category/'. $category->slug .'"><div class="category-container ' . ($is_active?'active':'') . '"><div class="image-container"><img src="' . $image . '" class="top-category-images" alt=""></div><span>' . esc_html( $category->name ) . '</option></div></a>';
            }
            ?>
        </div>
    </div>
    <div class="archive-right">
        <div class="archive-content">
            <div class="category-header">
                <span class="content-title"><?php echo ('Categories') ?></span>
            </div>

            <div class="sub-categories">
                <?php
                foreach ( $subCategories as $category ) {
                    echo '<a href=""><div class="sub-category-container"><span>' . $category->name . '</span></div></a>';
                }
                ?>
            </div>

            <div class="products-container">
                <?php if ( have_posts() ) : ?>
                    <?php while ( have_posts() ) : ?>
                        <?php the_post(); ?>
                        <?php get_template_part( 'template-parts/content/content-product', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) ); ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php get_template_part( 'template-parts/content/content-none' ); ?>
                <?php endif; ?>
            </div>
            <?php if(have_posts()) { gethalal_the_posts_navigation(); }?>
        </div>
    </div>

</div>

<?php get_footer(); ?>
