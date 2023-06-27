<?php
/**
 * Payment methods
 *
 * Shows customer payment methods on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/payment-methods.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

$saved_methods = wc_get_customer_saved_methods_list( get_current_user_id() );
$has_methods   = (bool) $saved_methods;
$types         = wc_get_account_payment_methods_types();

?>
<div class="gethalal-my-account-content">

<?php do_action( 'woocommerce_before_account_payment_methods', $has_methods ); ?>

<?php if ( $has_methods ) : ?>
    <div class="my-account-title"><?php echo __('Payment Methods') ?></div>
	<div class="woocommerce-MyAccount-paymentMethods shop_table shop_table_responsive account-payment-methods">
		<?php foreach ( $saved_methods as $type => $methods ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
			<?php foreach ( $methods as $method ) : ?>
				<div class="payment-method<?php echo ! empty( $method['is_default'] ) ? ' default-payment-method' : ''; ?>">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . strtolower($method['method']['brand']) . '_logo.png') ?>" alt="card-logo" class="payment-method-icon">
                    <?php
                        if ( ! empty( $method['method']['last4'] ) ) {
                            /* translators: 1: credit card type 2: last 4 digits */
                            $content = sprintf( esc_html__( '%1$s card ......%2$s', 'woocommerce' ), esc_html( wc_get_credit_card_type_label( $method['method']['brand'] ) ), esc_html( $method['method']['last4'] ) );
                        } else {
                            $content = esc_html( wc_get_credit_card_type_label( $method['method']['brand'] ) );
                        }
                        if($method['is_default']){
                            echo "<div class='payment-method-content'>${content}</div>";
                        } else {
                            echo "<a class='payment-method-content' href='" . esc_url( $method['actions']['default']['url'] ) . "'>${content}</a>";
                        }
                    ?>
                    <?php
                        foreach ( $method['actions'] as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                            if($key == 'delete'){
                                echo '<a href="' . esc_url( $action['url'] ) . '" class="payment-action ' . sanitize_html_class( $key ) . '">' . gethalal_get_fontawesome_icon_svg('solid', 'trash-alt', 22) . '</a>&nbsp;';
                            }
                        }
                    ?>
				</div>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
    <?php if ( WC()->payment_gateways->get_available_payment_gateways() ) : ?>
        <div class="add-payment-method-action">
            <div class="add-btn"><?php echo gethalal_get_fontawesome_icon_svg('solid', 'plus', 25)?></div>
            <a class="payment-method-add-action" href="<?php echo esc_url( wc_get_endpoint_url( 'add-payment-method' ) ); ?>"><?php echo __('Add a new Card', 'gethalal') ?></a>
        </div>
    <?php endif; ?>

<?php else : ?>

    <?php do_action('woocommerce_account_add-payment-method_endpoint'); ?>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_payment_methods', $has_methods ); ?>

</div>
