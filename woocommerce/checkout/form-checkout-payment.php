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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="gethalal-checkout">
    <div class="checkout-header">
        <div class="header-content alignwide">
            <img class="checkout-header-logo"
                 src="<?php echo esc_url(get_template_directory_uri()) ?>/assets/images/checkout_payment.png"
                 alt="checkout-header">
            <div class="checkout-header-titles">
                <div class="checkout-header-title active"><?php echo __('My Address', 'gethalal') ?></div>
                <div class="checkout-header-title active"><?php echo __('Payment', 'gethalal') ?></div>
                <div class="checkout-header-title"><?php echo __('Order Placed', 'gethalal') ?></div>
            </div>
        </div>
    </div>

    <div class="alignwide checkout-content">

        <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

            <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <div class="gethalal-checkout-section">
                    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                    <div class="delivery-container checkout-form-section form-section">
                        <div class="form-section-title"><?php echo __('Delivery Date & Time', 'gethalal') ?></div>
                        <div class="section-content delivery-time-input delivery-time-container">
                            <input type="hidden" id="delivery_date" name="delivery_date" value="">
                            <input type="hidden" id="delivery_time" name="delivery_time" value="0">
<!--                            <input type="hidden" id="delivery_cycle" name="delivery_cycle" value="">-->
                            <input type="text" id="delivery_text" disabled name="delivery_text" class="delivery-time" value="" placeholder="<?php echo __('Schedule a time', 'gethalal') ?>">
                            <div class="input-right"><?php echo gethalal_get_fontawesome_icon_svg('solid', 'chevron-right', 18) ?></div>
                        </div>
                    </div>

                    <div class="form-separator"></div>

                    <?php if ( $checkout->get_checkout_fields('billing') ) : ?>

                        <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                        <div class="col2-set" id="customer_details">
                            <div class="col-1">
                                <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            </div>

                            <div class="col-2">
                                <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                            </div>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                    <?php endif; ?>

                    <?php woocommerce_checkout_payment(); ?>

                    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

                </div>
                <?php woocommerce_order_review(); ?>
            </div>


        </form>

        <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

    </div>
</div>
