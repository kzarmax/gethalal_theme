<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.6.0
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="woocommerce-customer-details">

    <div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
        <div class="form-section-title woocommerce-column__title"><?php esc_html_e( 'Delivery Location', 'woocommerce' ); ?></div>
        <div class="form-section-row">
            <?php
            $address = $order->get_address('shipping');
            printf(
            /* translators: 1: order number 2: order date 3: order status */
                esc_html__('Customer name:%1$s', 'gethalal'),
                '<span class="customer-name">' . $address['display_name'] . '</span>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            );
            ?>
        </div>
        <div class="form-section-row"><?php esc_html_e( 'Location:', 'gethalal' ); ?></div>
        <div class="form-section-row">
            <?php echo $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' )); ?>
        </div>


        <?php if ( $order->get_shipping_phone() ) : ?>
        <div class="form-section-row">
            <?php
            printf(
            /* translators: 1: order number 2: order date 3: order status */
                esc_html__('Mobile no: %1$s', 'gethalal'),
                '<span class="customer-phone">' . esc_html( $order->get_shipping_phone() ) . '</span>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            );
            ?>
        </div>
        <?php endif; ?>
    </div><!-- /.col-2 -->

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

</section>
