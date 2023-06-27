<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
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

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

$get_addresses = apply_filters(
    'woocommerce_my_account_get_addresses',
    array(
        'billing' => __( 'Billing address', 'woocommerce' ),
    ),
    $customer_id
);

$oldcol = 1;
$col    = 1;
?>
<div class="gethalal-my-account-content">
    <div class="my-account-title"><?php echo __('Terms & Policy', 'gethalal') ?></div>
    <div class="account-terms-content alignwide">
        <div class="terms-section">
            <h5>1-Use of the Website</h5>
            <p>1.1. GetHala performs deliveries in the areas designated by it where orders may be placed on the Website. Products within the relevant designated area and assigned to the relevant designated area is displayed to Customer once Customer selects a delivery address on the Website.</p>
            <p>1.1. GetHala performs deliveries in the areas designated by it where orders may be placed on the Website. Products within the relevant designated area and assigned to the relevant designated area is displayed to Customer once Customer selects a delivery address on the Website.</p>
        </div>
        <div class="terms-section">
            <h5>2-Use of the Website</h5>
            <p>2.1. GetHala performs deliveries in the areas designated by it where orders may be placed on the Website. Products within the relevant designated area and assigned to the relevant designated area is displayed to Customer once Customer selects a delivery address on the Website.</p>
            <p>2.1. GetHala performs deliveries in the areas designated by it where orders may be placed on the Website. Products within the relevant designated area and assigned to the relevant designated area is displayed to Customer once Customer selects a delivery address on the Website.</p>
        </div>
    </div>
</div>
