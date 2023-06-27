<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Get Halal
 */

$search_text = get_search_query();
$is_searching = strlen($search_text) > 0;
?>

<div class="page-search alignwide">

    <div class="search-container-app">
        <form role="search" method="get" id="searchform" class="searchform search-box" action="<?php echo esc_url( home_url() ); ?>">
            <img class="desktop-search-icon" src="<?php echo get_template_directory_uri() . '/assets/images/search.png' ?>" alt="search"/>
            <input type="text" name="s" class="search-text-input" value="<?php echo $search_text ?>" placeholder="<?php echo __('Search for products you want...', 'gethalal') ?>">
            <a href="<?php echo apply_filters('gethalal_shop_url', array()) ?>" class="search-clear <?php echo $is_searching?'is-searching':'' ?>">
                <?php echo gethalal_get_icon_svg( 'ui', 'close', 16 ) ?>
            </a>
            <input type="hidden" name="post_type" value="product" />
        </form>
    </div>

<?php
if ( have_posts() ) {
    if($is_searching){
	?>
        <div class="search-page-title">
            <?php
            printf(
                esc_html(
                /* translators: %d: The number of search results. */
                    _n(
                        "We found %d result for '${search_text}'",
                        "We found %d results for '${search_text}'",
                        (int) $wp_query->found_posts,
                        'gethalal'
                    )
                ),
                (int) $wp_query->found_posts
            );
            ?>
        </div>
    <?php } ?>
    <div class="products-container">
        <?php
        // Start the Loop.
        while ( have_posts() ) {
            the_post();

            /*
             * Include the Post-Format-specific template for the content.
             * If you want to override this in a child theme, then include a file
             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
             */
            get_template_part( 'template-parts/content/content-product', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) );
        } // End the loop.
        ?>
    </div>
    <?php
	// Previous/next page navigation.
	gethalal_the_posts_navigation();

	// If no content, include the "No posts found" template.
} else {
	get_template_part( 'template-parts/content/content-none' );
}

get_footer();

?>
</div>
