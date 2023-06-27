<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$customer_id = get_current_user_id();

$get_addresses = Gethalal_Utils::get_addresses($customer_id);
?>

<div class="gethalal-checkout">
    <div class="checkout-header">
        <div class="header-content alignwide">
            <img class="checkout-header-logo"
                 src="<?php echo esc_url(get_template_directory_uri()) ?>/assets/images/checkout_address.png"
                 alt="checkout-header">
            <div class="checkout-header-titles">
                <div class="checkout-header-title active"><?php echo __('My Address', 'gethalal') ?></div>
                <div class="checkout-header-title"><?php echo __('Payment', 'gethalal') ?></div>
                <div class="checkout-header-title"><?php echo __('Order Placed', 'gethalal') ?></div>
            </div>
        </div>
    </div>
    <?php if (count($get_addresses) < 1): ?>

        <div class="new-address-container alignwide">

            <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

            <div class="new-address-title"><?php echo __('Add a new address', 'gethalal') ?></div>

            <?php gethalal_new_address_form() ?>

            <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
        </div>

    <?php else: ?>
        <div class="addresses checkout-addresses alignwide">

            <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

            <?php
            foreach ($get_addresses as $index => $address) :
                gethalal_address_item($index, $address);
            endforeach;
            ?>
            <div class="add-address-action">
                <div class="add-btn"><?php echo gethalal_get_fontawesome_icon_svg('solid', 'plus', 25) ?></div>
                <a class="address-add-action"><?php echo __('Add a new Address', 'gethalal') ?></a>
            </div>
            <div class="checkout-next">
                <a class="button button-next" href="<?php echo wc_get_page_permalink('checkout') . '?step=payment' ?>"><?php echo __('Next', 'gethalal') ?></a>
            </div>

            <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
        </div>
    <?php endif; ?>
</div>


