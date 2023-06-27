<?php
/**
 * Add payment method form form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-add-payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="gethalal-my-account-content">
    <div class="my-account-title"><?php echo __('Add a new card/Debit', 'gethalal') ?></div>
<?php
$available_gateways = WC()->payment_gateways->get_available_payment_gateways();

if ( $available_gateways ) : ?>
	<form id="add_payment_method" method="post" class="account-new-payment-method-form gethalal-account-form">
		<div id="payment" class="woocommerce-Payment gethalal-payment">
			<div class="woocommerce-PaymentMethods payment_methods methods">
				<?php
				// Chosen Method.
				if ( count( $available_gateways ) ) {
					current( $available_gateways )->set_current();
				}

				foreach ( $available_gateways as $gateway ) {
					?>
					<div class="woocommerce-PaymentMethod woocommerce-PaymentMethod--<?php echo esc_attr( $gateway->id ); ?> payment_method_<?php echo esc_attr( $gateway->id ); ?>">
						<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> />
						<?php
						if ( $gateway->has_fields() || $gateway->get_description() ) {
							echo '<div class="woocommerce-PaymentBox woocommerce-PaymentBox--' . esc_attr( $gateway->id ) . ' payment_box payment_method_' . esc_attr( $gateway->id ) . '" style="display: none;">';
							$gateway->payment_fields();
							echo '</div>';
						}
						?>
					</div>
					<?php
				}
				?>
			</div>

			<?php do_action( 'woocommerce_add_payment_method_form_bottom' ); ?>

            <div class="set-default-action">
                <input id="default-payment-method"
                       class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" type="hidden"
                       name="default-payment-method" value="1">
                <div class="check-btn"><?php echo gethalal_get_fontawesome_icon_svg('solid', 'check-circle', 25) ?></div>
                <div class="check-label"><?php echo __('Save your card for future purchase', 'gethalal') ?></div>
            </div>

			<div class="form-action">
				<?php wp_nonce_field( 'woocommerce-add-payment-method', 'woocommerce-add-payment-method-nonce' ); ?>
				<input type="submit" class="woocommerce-Button woocommerce-Button--alt button alt" id="place_order" value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>">
				<input type="hidden" name="woocommerce_add_payment_method" id="woocommerce_add_payment_method" value="1" />
			</div>
		</div>
	</form>
<?php else : ?>
	<p class="woocommerce-notice woocommerce-notice--info woocommerce-info"><?php esc_html_e( 'New payment methods can only be added during checkout. Please contact us if you require assistance.', 'woocommerce' ); ?></p>
<?php endif; ?>
</div>
