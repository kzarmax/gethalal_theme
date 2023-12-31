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

defined('ABSPATH') || exit;

?>

<div class="addresses alignwide">

    <?php
    foreach ($get_addresses as $index => $address) :
        gethalal_address_item($index, $address);
    endforeach;
    ?>

    <div class="add-address-action">

        <div class="add-btn"><?php echo gethalal_get_fontawesome_icon_svg('solid', 'plus', 25) ?></div>

        <a class="address-add-action"
           href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'shipping')); ?>"><?php echo __('Add a new Address', 'gethalal') ?></a>

    </div>

</div>
