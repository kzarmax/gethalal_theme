<?php
/**
 * Order tracking
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/tracking.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.2.0
 */

defined('ABSPATH') || exit;

$notes = $order->get_customer_order_notes();
?>
<div class="gethalal-order-traking">

    <div class="order-status-container form-row-between">
        <span class="form-title"><?php echo __('Order status', 'gethalal') ?></span>
        <?php gethalal_get_order_shipping_status($order) ?>
    </div>
    <div class="form-separator-2"></div>
    <div class="order-information-container">
        <div class="form-section-title"><?php echo __('Order Information', 'gethalal') ?></div>
        <div class="form-section-row">
            <?php
            printf(
            /* translators: 1: order number 2: order date 3: order status */
                esc_html__('Order id:#%1$s', 'gethalal'),
                '<span class="order-number">' . $order->get_order_number() . '</span>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            );
            ?>
        </div>
        <div class="form-section-row">
            <?php
            printf(
            /* translators: 1: order number 2: order date 3: order status */
                esc_html__('Date: %1$s', 'gethalal'),
                '<span class="order-date">' . wc_format_datetime($order->get_date_created()) . '</span>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            );
            ?>
        </div>
        <div class="form-section-row">
            <?php
            printf(
            /* translators: 1: order number 2: order date 3: order status */
                esc_html__('Payment method:#%1$s', 'gethalal'),
                '<span class="order-number">' . $order->get_payment_method_title() . '</span>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            );
            ?>
        </div>
        <!--    <div class="form-section-row">-->
        <!--        --><?php
        //        printf(
        //        /* translators: 1: order number 2: order date 3: order status */
        //            esc_html__('Coupon:#%1$s', 'gethalal'),
        //            '<mark class="order-number">' . $order->get_coupons() . '</mark>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        //        );
        //        ?>
        <!--    </div>-->
    </div>

    <div class="form-separator-2"></div>

    <?php if ($notes) : ?>
        <h2><?php esc_html_e('Order updates', 'woocommerce'); ?></h2>
        <ol class="woocommerce-OrderUpdates commentlist notes">
            <?php foreach ($notes as $note) : ?>
                <li class="woocommerce-OrderUpdate comment note">
                    <div class="woocommerce-OrderUpdate-inner comment_container">
                        <div class="woocommerce-OrderUpdate-text comment-text">
                            <p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n(esc_html__('l jS \o\f F Y, h:ia', 'woocommerce'), strtotime($note->comment_date)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                            <div class="woocommerce-OrderUpdate-description description">
                                <?php echo wpautop(wptexturize($note->comment_content)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
        <div class="form-separator-2"></div>
    <?php endif; ?>

    <?php
    do_action('woocommerce_view_order', $order_id);
   ?>
</div>
