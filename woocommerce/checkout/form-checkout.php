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

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

$step = $_GET['step']??'address';

$address = $_GET['address']?(array)json_decode(base64_decode($_GET['address'])):false;
if(!$address && is_user_logged_in()){
    $address = Gethalal_Utils::get_default_address(get_current_user_id());
}

if($address){
    if(!isset($address['postcode']) || !gethalal_get_zone_by_postcode($address['postcode'])){
        wc_add_notice(__('Postcode is invalid. Please check postcode again.', 'gethalal'), 'error');
        $step = 'address';
    } else if(!isset($address['country']) || ! in_array( $address['country'], array_keys( WC()->countries->get_shipping_countries() ), true )) {
        wc_add_notice(__('Country is invalid. Please check country again.', 'gethalal'), 'error');
        $step = 'address';
    } else if(empty($address['city'])) {
        wc_add_notice(__('City is invalid. Please check city again.', 'gethalal'), 'error');
        $step = 'address';
    }
}

if($address && !is_user_logged_in()){
    include_once __DIR__ . '/form-checkout-payment.php';
} else {
    $fields = $checkout->get_checkout_fields( 'billing' );
    switch ($step){
        case 'address':
            include_once __DIR__ . '/form-checkout-address.php';
            break;
        default:
            include_once __DIR__ . '/form-checkout-payment.php';
            break;
    }
}

?>
