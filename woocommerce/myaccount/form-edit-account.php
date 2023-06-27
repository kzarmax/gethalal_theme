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

$user_id = get_current_user_id();
$user = get_userdata($user_id);
$phone_number = get_user_meta($user_id, 'phone_number', true);

?>
<div class="gethalal-my-account-content">
<?php
do_action( 'woocommerce_before_edit_account_form' ); ?>

<div class="my-account-title"><?php echo __('My Account') ?></div>
<form class="gethalal-account-form woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

    <div class="form-section-title"><?php echo __('Personal  Information', 'gethalal') ?></div>
    <div class="form-section">
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" placeholder="Ali Yousry"/>
        </p>
        <div class="clear"></div>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" placeholder="aliyousry929@gmail.com" />
        </p>

        <div class="clear"></div>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <input type="text" class="woocommerce-Input woocommerce-Input--phone input-text" name="account_phone" id="account_phone" autocomplete="phone" value="<?php echo esc_attr( $phone_number ); ?>" placeholder="phone"/>
        </p>

        <div class="clear"></div>
<!--	--><?php //do_action( 'woocommerce_edit_account_form' ); ?>

    </div>
	<p class="form-submit-container">
		<?php wp_nonce_field( 'save_account_details', 'gethalal-save-account-details-nonce' ); ?>
		<button type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>"><?php esc_html_e( 'Save', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="gethalal_save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>

</div>
