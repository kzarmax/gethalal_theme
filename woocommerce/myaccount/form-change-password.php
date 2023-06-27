<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
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

defined( 'ABSPATH' ) || exit;
?>
<div class="gethalal-my-account-content">
    <?php do_action( 'woocommerce_before_edit_account_form' ); ?>

    <div class="my-account-title"><?php echo __('Change Password', 'gethalal') ?></div>
    <form class="gethalal-account-form woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

        <?php do_action( 'woocommerce_edit_account_form_start' ); ?>

        <div class="form-section-single">
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" placeholder="<?php echo __( 'Current password', 'gethalal' ); ?>"/>
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" placeholder="<?php echo __( 'New password', 'gethalal' ); ?>"/>
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" placeholder="<?php echo __( 'Confirm new password', 'gethalal' ); ?>"/>
            </p>
        </div>
        <div class="clear"></div>

        <p class="form-submit-container">
            <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
            <button type="submit" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Update', 'woocommerce' ); ?>"><?php esc_html_e( 'Update', 'woocommerce' ); ?></button>
            <input type="hidden" name="action" value="save_account_details" />
        </p>

        <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
    </form>

    <?php do_action( 'woocommerce_after_edit_account_form' ); ?>

</div>
