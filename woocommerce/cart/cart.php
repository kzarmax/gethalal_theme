<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined('ABSPATH') || exit;
?>

<div class="alignwide">

    <?php do_action('woocommerce_before_cart'); ?>

    <div class="gethalal-cart-page">

        <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

            <?php do_action('woocommerce_before_cart_table'); ?>

            <div class="cart woocommerce-cart-form__contents gethalal-carts">

                <?php do_action('woocommerce_before_cart_contents'); ?>

                <?php
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    $defaults = array(
                        'quantity' => $cart_item['quantity'],
                        'class' => implode(
                            ' ',
                            array_filter(
                                array(
                                    'button',
                                    'product_type_' . $_product->get_type(),
                                    $_product->is_purchasable() && $_product->is_in_stock() ? 'add_to_cart_button' : '',
                                    $_product->is_purchasable() && $_product->is_in_stock() ? 'gethalal_ajax_add_to_cart is_cart' : '',
                                )
                            )
                        ),
                        'attributes' => array(
                            'data-product_id' => $_product->get_id(),
                            'data-product_sku' => $_product->get_sku(),
                            'aria-label' => $_product->add_to_cart_description(),
                            'rel' => 'nofollow',
                        ),
                    );

                    $args = apply_filters('woocommerce_loop_add_to_cart_args', wp_parse_args(array(), $defaults), $_product);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                        ?>
                        <div class="post-<?php echo $_product->get_id() ?> woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

                            <div class="product-thumbnail">
                                <?php
                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(array(100, 100)), $cart_item, $cart_item_key);

                                if (!$product_permalink) {
                                    echo $thumbnail; // PHPCS: XSS ok.
                                } else {
                                    printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                }
                                ?>
                            </div>

                            <div class="product-detail">
                                <div class="product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                                    <?php
                                    if (!$product_permalink) {
                                        echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                    } else {
                                        echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                    }

                                    do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                    // Meta data.
                                    echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

                                    // Backorder notification.
                                    if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                        echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                                    }
                                    ?>
                                </div>
                                <div class="product-owner"><?php
                                    $brand = $_product->get_attribute('brand');
                                    echo wp_kses_post( $brand ); ?>
                                </div>
                                <div class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
                                    <?php
                                    echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                    ?>
                                </div>
                                <div class="product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
                                    <?php
                                    echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                    ?>
                                </div>
                            </div>
                            <div class="product-actions">
                                <div class="actions-container">
                                    <?php
                                    if ($args['quantity'] > 0) {
                                        echo sprintf('<div data-quantity="%s" data-cart_item_key="%s" class="action-minus %s" %s>%s</div>',
                                                esc_attr($args['quantity'] - 1),
                                                $cart_item_key,
                                                esc_attr(isset($args['class']) ? $args['class'] : 'button'),
                                                isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
                                                gethalal_get_fontawesome_icon_svg('solid', $args['quantity'] == 1 ? 'trash-alt' : 'minus', 16));
                                            echo '<div class="action-quantity">' . $args['quantity'] . '</div>';
                                    }
                                    echo sprintf('<div data-quantity="%s" data-cart_item_key="%s" class="action-plus %s" %s>%s</div>',
                                        esc_attr(isset($args['quantity']) ? ($args['quantity'] + 1) : 1),
                                        $cart_item_key,
                                        esc_attr(isset($args['class']) ? $args['class'] : 'button'),
                                        isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
                                        gethalal_get_fontawesome_icon_svg('solid', 'plus', 16))
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>

                <?php do_action('woocommerce_cart_contents'); ?>

                <!--                <div>-->
                <!--                    <div class="actions">-->
                <!---->
                <!--                        --><?php //if ( wc_coupons_enabled() ) { ?>
                <!--                            <div class="coupon">-->
                <!--                                <label for="coupon_code">-->
                <?php //esc_html_e( 'Coupon:', 'woocommerce' ); ?><!--</label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="-->
                <?php //esc_attr_e( 'Coupon code', 'woocommerce' ); ?><!--" /> <button type="submit" class="button" name="apply_coupon" value="-->
                <?php //esc_attr_e( 'Apply coupon', 'woocommerce' ); ?><!--">-->
                <?php //esc_attr_e( 'Apply coupon', 'woocommerce' ); ?><!--</button>-->
                <!--                                --><?php //do_action( 'woocommerce_cart_coupon' ); ?>
                <!--                            </div>-->
                <!--                        --><?php //} ?>
                <!---->
                <!--                        <button type="submit" class="button" name="update_cart" value="-->
                <?php //esc_attr_e( 'Update cart', 'woocommerce' ); ?><!--">-->
                <?php //esc_html_e( 'Update cart', 'woocommerce' ); ?><!--</button>-->
                <!---->
                <!--                        --><?php //do_action( 'woocommerce_cart_actions' ); ?>
                <!---->
                <!--                        --><?php //wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                <!--                    </div>-->
                <!--                </div>-->

                <?php do_action('woocommerce_after_cart_contents'); ?>
            </div>
            <?php do_action('woocommerce_after_cart_table'); ?>
        </form>

        <?php do_action('woocommerce_before_cart_collaterals'); ?>

        <div class="cart-collaterals">
            <?php
            /**
             * Cart collaterals hook.
             *
             * @hooked woocommerce_cross_sell_display
             * @hooked woocommerce_cart_totals - 10
             */
            do_action('woocommerce_cart_collaterals');
            ?>
        </div>
    </div>

    <?php do_action('woocommerce_after_cart'); ?>

</div>
