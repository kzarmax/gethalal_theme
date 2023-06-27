<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Get Halal
 */

?>

<div class="page-search alignwide">
	<div class="search-page-header">
		<?php if ( is_search() ) : ?>

            <div class="search-empty-text"><?php echo __( 'Sorry , no result about', 'woocommerce' ) ?> "<span><?php echo get_search_query()?></span>"</div>
            <p class="return-to-shop">
                <a class="button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', gethalal_shop_url() ) ); ?>">
                    <?php
                    /**
                     * Filter "Return To Shop" text.
                     *
                     * @since 4.6.0
                     * @param string $default_text Default text.
                     */
                    echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Continue shopping', 'woocommerce' ) ) );
                    ?>
                </a>
            </p>

		<?php else : ?>

			<h1 class="page-title" style="margin: 120px 0;"><?php esc_html_e( 'Nothing here', 'gethalal' ); ?></h1>

		<?php endif; ?>
	</div><!-- .page-header -->

	<div class="page-content">
        <h1 class="page-title"><?php esc_html_e( 'Most sold Products', 'gethalal' ); ?></h1>
        <div class="products-container">
            <?php
            $args = array(
                'post_type' => 'product',
                'meta_key' => 'total_sales',
                'orderby' => 'meta_value_num',
                'posts_per_page' => 8,
            );
            $loop = new WP_Query( $args );
            // Start the Loop.
            while ( $loop->have_posts() ) {
                $loop->the_post();

                /*
                 * Include the Post-Format-specific template for the content.
                 * If you want to override this in a child theme, then include a file
                 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                 */
                get_template_part( 'template-parts/content/content-product', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) );
            } // End the loop.
            wp_reset_query();
            ?>
        </div>
	</div><!-- .page-content -->
</div><!-- .no-results -->
