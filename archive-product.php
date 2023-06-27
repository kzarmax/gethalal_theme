<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Get Halal
 */

get_header();

if(is_search()){
    require_once('search.php');
    exit();
}

$archive = get_queried_object();
$title  = single_cat_title( '', false );
$description = get_the_archive_description();

$selectedCat = $archive;
if($archive->parent){
    $archive = get_term_by('term_taxonomy_id', $archive->parent);
}

$thumbnail_id = get_term_meta( $archive->term_id, 'thumbnail_id', true );
$archive_image = wp_get_attachment_url( $thumbnail_id );

?>

<div class="archive-container">
    <div class="responsive active-category">
        <?php
            echo '<div class="category-container ' . ($archive?'active':'') . '"><div class="image-container"><img src="' . $archive_image . '" class="top-category-images" alt=""></div><span>' . esc_html( $archive->name ) . '</option></div>';
            echo '<a href="javascript:void(0);" onclick="clickOpenCategory()" >'.gethalal_get_fontawesome_icon_svg('solid', 'bars', 24).'</a>';
        ?>
        <script type="text/javascript">
            function clickOpenCategory() {
                var topCategory = document.getElementById("topCategories");
                if (topCategory.className === "sidebar-box") {
                    topCategory.className += " navigate";
                } else {
                    topCategory.className = "sidebar-box";
                }
            }
        </script>
    </div>
    <div class="sidebar-box" id="topCategories">
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
                    if($category->parent == $archive->term_id && $category->slug != 'uncategorized'){
                        $subCategories[] = $category;
                    }
                    continue;
                }
                $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                $image = wp_get_attachment_url( $thumbnail_id );
                $is_active = $category->term_id == $archive->term_id;
                echo '<a href="' . esc_url( home_url( '/product-category/' )) . $category->slug .'/"><div class="category-container ' . ($is_active?'active':'') . '"><div class="image-container"><img src="' . $image . '" class="top-category-images" alt=""></div><span>' . esc_html( $category->name ) . '</option></div></a>';
            }
            ?>
        </div>
    </div>
    <div class="archive-right">
        <div class="archive-content">

            <?php

            wc_print_notices();

            if(count($subCategories) > 0):
                ?>
                <div class="category-header">
                    <span class="content-title"><?php echo esc_html($archive->name) ?></span>
                </div>
                <div class="sub-categories">
                    <?php
                    $is_all = $selectedCat->term_id === $archive->term_id;
                    echo '<a href="' . esc_url( home_url('/product-category/')) . $archive->slug .'/"><div class="sub-category-container ' . ($is_all?'active':'') . '"><span>' . __('All', 'gethalal') . '</span></div></a>';
                    foreach ( $subCategories as $category ) {
                        $is_active = $category->term_id == $selectedCat->term_id;
                        echo '<a href="' . esc_url( home_url('/product-category/')) . $category->slug .'/"><div class="sub-category-container ' . ($is_active?'active':'') . '"><span>' . $category->name . '</span></div></a>';
                    }
                    ?>
                </div>
            <?php else: ?>
                <div class="category-header">
                    <span class="content-title"><?php echo esc_html( $archive->name ) ?></span>
                </div>
            <?php endif; ?>

            <?php if ( have_posts() ) : ?>
                <div class="products-container">
                    <?php while ( have_posts() ) : ?>
                        <?php the_post(); ?>
                        <?php get_template_part( 'template-parts/content/content-product', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) ); ?>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <?php get_template_part( 'template-parts/content/content-none' ); ?>
            <?php endif; ?>

            <?php if(have_posts()) { gethalal_the_posts_navigation(); }?>

        </div>
    </div>

</div>

<?php get_footer(); ?>
