<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(have_posts()) :

	the_post();

	global $product;

	$product_id = $product->get_id();

	$product_category_ids = $product->get_category_ids();

	if(count($product_category_ids) > 0){
		$category = get_term($product_category_ids[0], 'product_cat');

		wp_redirect(esc_url( home_url('/product-category/')) . $category->slug . '?pd=' . $product_id);

		exit();
	}

	wp_redirect(gethalal_shop_url() . '?pd=' . $product_id);
	exit();

endif;

wp_redirect(gethalal_shop_url());
exit();
