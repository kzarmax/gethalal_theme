<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="gethalal-checkout">
    <div class="checkout-header">
        <div class="header-content alignwide">
            <img class="checkout-header-logo"
                 src="<?php echo esc_url(get_template_directory_uri()) ?>/assets/images/checkout_order_placed.png"
                 alt="checkout-header">
            <div class="checkout-header-titles">
                <div class="checkout-header-title active"><?php echo __('My Address', 'gethalal') ?></div>
                <div class="checkout-header-title active"><?php echo __('Payment', 'gethalal') ?></div>
                <div class="checkout-header-title active"><?php echo __('Order Placed', 'gethalal') ?></div>
            </div>
        </div>
    </div>

    <div class="woocommerce-order checkout-success-form alignwide">

        <?php
        if ( isset($order) ) :

            do_action( 'woocommerce_before_thankyou', $order->get_id() );
            ?>

            <?php if ( $order->has_status( 'failed' ) ) : ?>

                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                    <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
                    <?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
                    <?php endif; ?>
                </p>

            <?php else : ?>

                <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
                    <a class="app-download-link"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/checkout_success.png" alt="checkout-success"></a>
                    <span><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Your order has been placed succuessfully', 'gethalal' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                </p>

                <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
                    <li class="woocommerce-order-overview__order order">
                        <span><?php esc_html_e( 'Order id', 'gethalal' ); ?></span>
                        <span>#<?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    </li>

                    <li class="woocommerce-order-overview__date date">
                        <span><?php esc_html_e( 'Delivery Date', 'gethalal' ); ?></span>
                        <span><?php echo get_post_meta($order->get_id(), 'delivery_date', true); ?>,<?php $delivery_time = get_post_meta($order->get_id(), 'delivery_time', true);  $times = Gethalal_Utils::delivery_times(); echo $times[intval($delivery_time)]['text']; ?></span>
                    </li>

<!--                    <li class="woocommerce-order-overview__date date">-->
<!--                        --><?php //esc_html_e( 'Date:', 'woocommerce' ); ?>
<!--                        <strong>--><?php //echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><!--</strong>-->
<!--                    </li>-->

    <!--				--><?php //if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
    <!--					<li class="woocommerce-order-overview__email email">-->
    <!--						--><?php //esc_html_e( 'Email:', 'woocommerce' ); ?>
    <!--						<strong>--><?php //echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><!--</strong>-->
    <!--					</li>-->
    <!--				--><?php //endif; ?>
    <!---->
    <!--				<li class="woocommerce-order-overview__total total">-->
    <!--					--><?php //esc_html_e( 'Total:', 'woocommerce' ); ?>
    <!--					<strong>--><?php //echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><!--</strong>-->
    <!--				</li>-->
    <!---->
    <!--				--><?php //if ( $order->get_payment_method_title() ) : ?>
    <!--					<li class="woocommerce-order-overview__payment-method method">-->
    <!--						--><?php //esc_html_e( 'Payment method:', 'woocommerce' ); ?>
    <!--						<strong>--><?php //echo wp_kses_post( $order->get_payment_method_title() ); ?><!--</strong>-->
    <!--					</li>-->
    <!--				--><?php //endif; ?>
                </ul>

                <div class="checkout-success-actions-container">
                    <a class="button-secondary checkout-success-action" href="<?php echo esc_url(gethalal_shop_url()) ?>"><?php echo __('Shop again', 'gethalal') ?></a>
                    <?php if(is_user_logged_in()): ?>
                        <a class="button button-primary checkout-success-action checkout-order-track" data-id="<?php echo $order->get_id(); ?>"><?php echo __('Track Your order', 'gethalal') ?></a>
                    <?php endif; ?>
                </div>

            <?php endif; ?>

<!--            --><?php //do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
<!--            --><?php //do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

        <?php else : ?>

            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

        <?php endif; ?>

    </div>

</div>
