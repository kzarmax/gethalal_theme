<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

$customer_id = get_current_user_id();

$get_addresses = Gethalal_Utils::get_addresses($customer_id);

?>
<div class="gethalal-my-account-content">

    <div class="my-account-title"><?php echo __('My Address', 'gethalal') ?></div>

    <?php do_action('woocommerce_before_edit_account_address_form'); ?>

    <?php if (count($get_addresses) > 0) : ?>

        <?php wc_get_template('myaccount/my-address.php', ['get_addresses' => $get_addresses]); ?>

    <?php else : ?>

        <?php gethalal_new_address_form([], 'gethalal-account-form account-new-address-form') ?>

    <?php endif; ?>

    <?php do_action('woocommerce_after_edit_account_address_form'); ?>
</div>
