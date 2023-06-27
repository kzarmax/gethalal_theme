<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined('ABSPATH') || exit;
?>
<div class="gethalal-checkout-review-order  gethalal-checkout-section">
    <div class="review-order-header">
        <img class="donate-icon" src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/donate_icon.png" alt="donate-icon">
        <div class="donate-text"><?php echo sprintf(__('For every purchase over %s GetHalal will donate %s to Chairty', 'gethalal'), "<span class='donate-price'>50" . html_entity_decode( get_woocommerce_currency_symbol() ) . "</span>", "<span class='donate-price'>1" . html_entity_decode( get_woocommerce_currency_symbol() ) . "</span>"); ?></div>
    </div>
    <div class="form-separator"></div>

    <?php woocommerce_checkout_coupon_form(); ?>

    <div class="form-separator"></div>
    <div class="review-order-footer">
        <div class="form-section-title"><?php echo __('Purchase Summary', 'gethalal') ?></div>

        <div class="section-content">
            <div class="review-form-row cart-subtotal">
                <div><?php esc_html_e('Subtotal', 'woocommerce'); ?></div>
                <div><?php wc_cart_totals_subtotal_html(); ?></div>
            </div>

            <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

                <?php do_action('woocommerce_review_order_before_shipping'); ?>

                <?php wc_cart_totals_shipping_html(); ?>

                <?php do_action('woocommerce_review_order_after_shipping'); ?>

            <?php endif; ?>

            <?php foreach (WC()->cart->get_fees() as $fee) : ?>
                <div class="review-form-row fee">
                    <div><?php echo esc_html($fee->name); ?></div>
                    <div><?php wc_cart_totals_fee_html($fee); ?></div>
                </div>
            <?php endforeach; ?>

            <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
                <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                    <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                        <div class="review-form-row tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                            <div><?php echo esc_html($tax->label); ?></div>
                            <div><?php echo wp_kses_post($tax->formatted_amount); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="review-form-row tax-total">
                        <div><?php echo esc_html(WC()->countries->tax_or_vat()); ?></div>
                        <div><?php wc_cart_totals_taxes_total_html(); ?></div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php do_action('woocommerce_review_order_before_order_total'); ?>

            <div class="form-separator"></div>
            <div class="review-form-row order-total">
                <div><?php esc_html_e('Total', 'woocommerce'); ?></div>
                <div><?php wc_cart_totals_order_total_html(); ?></div>
            </div>

            <?php do_action('woocommerce_review_order_after_order_total'); ?>
        </div>
    </div>
    <div class="form-row place-order">
        <?php wc_get_template( 'checkout/terms.php' ); ?>

        <?php do_action('woocommerce_review_order_before_submit'); ?>

        <input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order"
               value="<?php echo __('Place Order', 'gethalal') ?>">

        <?php do_action('woocommerce_review_order_after_submit'); ?>

        <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
    </div>
</div>
