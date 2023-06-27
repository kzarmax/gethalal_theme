<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */

 ?>
<div class="cart-empty">
    <div class="cart-empty-header alignwide">
        <img class="cart-empty-logo" src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/cart_empty.png" alt="cart-empty">
        <div class="cart-empty-text"><?php echo __( 'Your cart is empty.', 'woocommerce' ) ?></div>
        <p class="return-to-shop">
            <a class="button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                <?php
                    /**
                     * Filter "Return To Shop" text.
                     *
                     * @since 4.6.0
                     * @param string $default_text Default text.
                     */
                    echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Shop Now', 'woocommerce' ) ) );
                ?>
            </a>
        </p>
    </div>
    <div class="most-sold-products alignwide">
        <div class="entry-title" style="margin-bottom: 24px;"><?php echo __( 'Most sold Products', 'woocommerce' ) ?></div>
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
</div>

