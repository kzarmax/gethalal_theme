<?php
/**
 * @package Get Halal
 * @since Get Halal 1.0
 * @developer Hosokawa Zen
 * @date 2021/11/15
 */

get_header(); ?>

<?php
    $category = get_the_category();
?>
<!--<div class="main-container">-->
<!--    <div class="slider-bar">-->
<!--        <div class="slider-box">-->
<!--            <img src="" alt=""/>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="main-content">-->
<!--        <div class="content-title">Categories</div>-->
<!--        <div class="top-categories">-->
<!--            --><?php
//                $categories = get_categories(
//                    array(
//                        'taxonomy'   => 'product_cat',
//                        'hide_empty' => false,
//                        'orderby' => 'slug',
//                        'order'   => 'ASC'
//                    )
//                );
//                foreach ( $categories as $category ) {
//                    if($category->parent || $category->slug == 'uncategorized') continue;
//                    $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
//                    $image = wp_get_attachment_url( $thumbnail_id );
//                    echo '<a href="/category/'. $category->slug .'" class="category-container"><div class="image-container"><img src="' . $image . '" class="top-category-images" alt=""></div><span>' . esc_html( $category->name ) . '</option></a>';
//                }
//            ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<?php
get_footer();
