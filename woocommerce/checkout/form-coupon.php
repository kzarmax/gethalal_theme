<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>

<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
    <div class="review-form-row cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
        <div><?php wc_cart_totals_coupon_label($coupon); ?></div>
        <div><?php wc_cart_totals_coupon_html($coupon); ?></div>
    </div>
<?php endforeach; ?>

<div class="coupon-container add-coupon-action">
    <div class="add-btn"><?php echo gethalal_get_fontawesome_icon_svg('solid', 'plus', 25) ?></div>
	<?php echo '<div class="showcoupon coupon-add-action">' . __('Add Coupon', 'gethalal') . '</div>'; ?>
</div>

<script type="text/javascript">
    function dialogCoupon() {
        var headerCouponIcon = document.getElementsByClassName( 'coupon-container' ),
            dialogCouponForm = document.querySelector( '.site-dialog-coupon' )
        couponField      = document.querySelector( '.site-dialog-coupon .coupon-field' ),
            closeBtn         = document.querySelector( '.site-dialog-coupon .dialog-coupon-close-icon' ),
            errorText        = document.querySelector( '.site-dialog-coupon .dialog-error' );

        if ( ! headerCouponIcon.length || ! dialogCouponForm || ! couponField || ! closeBtn ) {
            return;
        }

        // Disabled field suggestions.
        couponField.setAttribute( 'autocomplete', 'off' );

        // Field must not empty.
        couponField.setAttribute( 'required', 'required' );

        var dialogOpen = function() {
            document.documentElement.classList.add( 'dialog-coupon-open' );
            document.documentElement.classList.remove( 'dialog-coupon-close' );

            if ( window.matchMedia( '( min-width: 992px )' ).matches ) {
                couponField.focus();
            }
        };

        var dialogClose = function() {
            document.documentElement.classList.add( 'dialog-coupon-close' );
            document.documentElement.classList.remove( 'dialog-coupon-open' );
        };

        for ( var i = 0, j = headerCouponIcon.length; i < j; i++ ) {
            headerCouponIcon[i].addEventListener(
                'click',
                function() {
                    dialogOpen();
                    errorText.innerHTML = '';

                    // Use ESC key.
                    document.body.addEventListener(
                        'keyup',
                        function( e ) {
                            if ( 27 === e.keyCode ) {
                                dialogCouponForm.submit();
                                dialogClose();
                            }
                        }
                    );

                    // Use dialog overlay.
                    dialogCouponForm.addEventListener(
                        'click',
                        function( e ) {
                            if ( this !== e.target ) {
                                return;
                            }

                            dialogClose();
                        }
                    );

                    dialogCouponForm.addEventListener(
                        'submit',
                        function(e){
                            dialogClose();
                        }
                    );

                    // Use closr button.
                    closeBtn.addEventListener(
                        'click',
                        function() {
                            dialogClose();
                        }
                    );
                }
            );
        }
    }

    (function () {
        dialogCoupon();
    }());
</script>
