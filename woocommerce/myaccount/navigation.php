<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp;
$request = explode( '/', $wp->request );
$cur_endpoint = end($request);

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation">
    <?php foreach ( gethalal_get_account_menu_items() as $endpoint => $item ) : ?>
        <div class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
            <a class="<?php echo ($cur_endpoint == $endpoint?'active':'') ?>" href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
                <div class="MyAccount-nav-icon"><?php echo $item['icon'] ?></div>
                <div class="MyAccount-nav-text"><?php echo esc_html( $item['label'] ); ?></div>
            </a>
        </div>
    <?php endforeach; ?>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
