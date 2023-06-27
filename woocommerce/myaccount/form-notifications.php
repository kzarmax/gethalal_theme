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
$notifications = [];
?>
<div class="gethalal-my-account-content">
    <div class="my-account-title"><?php echo __('Notifications', 'gethalal') ?></div>
    <?php if(count($notifications) > 0): ?>
        <div class="notifications alignwide">
            <?php gethalal_notification_item() ?>
            <?php gethalal_notification_item() ?>
        </div>
    <?php else: ?>
        <div class="notifications-empty">
            <img class="notifications-empty-logo" src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/empty_notification.png" alt="notification-empty">
            <div class="notifications-empty-title"><?php echo __( 'No Notifications Yet.', 'woocommerce' ) ?></div>
            <span class="notification_empty_caption">
               <?php echo __( 'When you got noitigications ,they’ll show up here', 'gethalal' ) ?>
            </span>
        </div>
    <?php endif; ?>
</div>
