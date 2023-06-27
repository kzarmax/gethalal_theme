<?php
/**
 * Api Functions
 * @package Get Halal
 */


/**
 * Add and Update Address
 * @throws Exception
 */
function gethalal_save_account_address()
{
    $nonce_value = wc_get_var($_REQUEST['woocommerce-edit-address-nonce'], wc_get_var($_REQUEST['_wpnonce'], '')); // @codingStandardsIgnoreLine.

    if (!wp_verify_nonce($nonce_value, 'woocommerce-edit_address')) {
        return;
    }

    if (empty($_POST['action']) || 'edit_address' !== $_POST['action']) {
        return;
    }

    wc_nocache_headers();

    $user_id = get_current_user_id();

    $account_display_name = !empty($_POST['account_display_name']) ? wc_clean(wp_unslash($_POST['account_display_name'])) : '';
    $account_email = !empty($_POST['account_email']) ? wc_clean(wp_unslash($_POST['account_email'])) : '';
    $account_phone = !empty($_POST['account_phone']) ? wc_clean(wp_unslash($_POST['account_phone'])) : '';
    $account_address_text = !empty($_POST['account_address_text']) ? wc_clean(wp_unslash($_POST['account_address_text'])) : '';
    $account_address_postcode = !empty($_POST['account_address_postcode']) ? wc_clean(wp_unslash($_POST['account_address_postcode'])) : '';
    $account_address_city = !empty($_POST['account_address_city']) ? wc_clean(wp_unslash($_POST['account_address_city'])) : '';
    $account_address_floor = !empty($_POST['account_address_floor']) ? wc_clean(wp_unslash($_POST['account_address_floor'])) : '';
    $account_address_country = !empty($_POST['account_address_country']) ? wc_clean(wp_unslash($_POST['account_address_country'])) : '';

    // Handle required fields.
    $required_fields = apply_filters(
        'woocommerce_save_account_details_required_fields',
        array(
            'account_display_name' => __('Display name', 'woocommerce'),
            'account_email' => __('Email address', 'woocommerce'),
            'account_phone' => __('Phone Number', 'woocommerce'),
            'account_address_text' => __('Address', 'woocommerce'),
            'account_address_postcode' => __('Zip Code', 'woocommerce'),
            'account_address_city' => __('City', 'woocommerce'),
            'account_address_floor' => __('Floor', 'woocommerce'),
        )
    );

    foreach ($required_fields as $field_key => $field_name) {
        if (empty($_POST[$field_key])) {
            /* translators: %s: Field name. */
            wc_add_notice(sprintf(__('%s is a required field.', 'woocommerce'), '<strong>' . esc_html($field_name) . '</strong>'), 'error', array('id' => $field_key));
        }
    }

    $country      =  WC()->countries->get_base_country();
    $postcode = wc_format_postcode( $account_address_postcode, $country );

    if ( ! WC_Validation::is_postcode( $postcode, $country ) ) {
        wc_add_notice( __('Zip Code is a not valid.', 'gethalal'), 'error', array('id' => 'account_address_postcode'));
    }

    if (wc_notice_count('error') === 0) {
        $zone = gethalal_get_zone_by_postcode($account_address_postcode);
        $new_address = array(
            'display_name' => $account_display_name,
            'email' => $account_email,
            'phone' => $account_phone,
            'address' => $account_address_text,
            'postcode' => $account_address_postcode,
            'country' => $account_address_country,
            'city' => $account_address_city,
            'floor' => $account_address_floor,
            'zone' => $zone?$zone['zone_name']:''
        );

        if ($user_id <= 0) {
            wp_safe_redirect(add_query_arg('address', base64_encode(json_encode($new_address)), wc_get_checkout_url()));
            return;
        }

        $customer = new WC_Customer($user_id);

        if (!$customer) {
            return;
        }

        if (!isset($_POST['address_id'])) {
            $address_id = Gethalal_Utils::add_address($user_id, $new_address);
        } else {
            $address_id = intval($_POST['address_id']);
            Gethalal_Utils::update_address($user_id, $address_id, $new_address);
        }
        if (intval($_POST['default_address_checkbox'])) {
            Gethalal_Utils::set_default_address($user_id, $address_id);
        }

        if ($customer) {
            $customer_address = "${account_address_text} ${account_address_city} ${account_address_floor}";
            $customer->set_billing_first_name($account_display_name);
            $customer->set_billing_phone($account_phone);
            $customer->set_billing_email($account_email);
            $customer->set_billing_address($customer_address);
            $customer->set_billing_country($account_address_country);
            $customer->set_billing_city($account_address_city);
            $customer->set_shipping_first_name($account_display_name);
            $customer->set_shipping_phone($account_phone);
            $customer->set_shipping_address($customer_address);
            $customer->set_postcode($account_address_postcode);
            $customer->set_shipping_country($account_address_country);
            $customer->set_shipping_city($account_address_city);
            $customer->save();
        }

        wc_add_notice(__('Account address saved successfully.', 'gethalal'));

        if(is_checkout()){
            wp_safe_redirect(wc_get_endpoint_url('checkout'));
        } else {
            wp_safe_redirect(wc_get_endpoint_url('edit-address', '', wc_get_page_permalink('myaccount')));
        }

        exit;
    }

}

add_action('template_redirect', 'gethalal_save_account_address');

/**
 * Save the password/account details and redirect back to the my account page.
 */
function gethalal_save_account_detail()
{
    $nonce_value = wc_get_var($_REQUEST['gethalal-save-account-details-nonce'], wc_get_var($_REQUEST['_wpnonce'], '')); // @codingStandardsIgnoreLine.

    if (!wp_verify_nonce($nonce_value, 'save_account_details')) {
        return;
    }

    if (empty($_POST['action']) || 'gethalal_save_account_details' !== $_POST['action']) {
        return;
    }

    wc_nocache_headers();

    $user_id = get_current_user_id();

    if ($user_id <= 0) {
        return;
    }

    $account_display_name = !empty($_POST['account_display_name']) ? wc_clean(wp_unslash($_POST['account_display_name'])) : '';
    $account_email = !empty($_POST['account_email']) ? wc_clean(wp_unslash($_POST['account_email'])) : '';
    $account_phone = !empty($_POST['account_phone']) ? wc_clean(wp_unslash($_POST['account_phone'])) : '';

    // Current user data.
    $current_user = get_user_by('id', $user_id);
    $current_display_name = $current_user->display_name;
    $current_email = $current_user->user_email;

    // New user data.
    $user = new stdClass();
    $user->ID = $user_id;
    $user->display_name = $account_display_name;
    $user->phone_number = $account_phone;

    // Prevent display name to be changed to email.
    if (is_email($account_display_name)) {
        wc_add_notice(__('Display name cannot be changed to email address due to privacy concern.', 'woocommerce'), 'error');
    }

    // Handle required fields.
    $required_fields = apply_filters(
        'woocommerce_save_account_details_required_fields',
        array(
            'account_display_name' => __('Display name', 'woocommerce'),
            'account_email' => __('Email address', 'woocommerce')
        )
    );

    foreach ($required_fields as $field_key => $field_name) {
        if (empty($_POST[$field_key])) {
            /* translators: %s: Field name. */
            wc_add_notice(sprintf(__('%s is a required field.', 'woocommerce'), '<strong>' . esc_html($field_name) . '</strong>'), 'error', array('id' => $field_key));
        }
    }

    if ( ! WC_Validation::is_phone($account_phone ) ) {
        /* translators: %s: phone number */
        wc_add_notice(sprintf( __( '%s is not a valid phone number.', 'woocommerce' ), '<strong>' . esc_html(  __('Phone Number', 'gethalal') ) . '</strong>' ), array( 'id' => 'account_phone' ) );
    }

    if ($account_email) {
        $account_email = sanitize_email($account_email);
        if (!is_email($account_email)) {
            wc_add_notice(__('Please provide a valid email address.', 'woocommerce'), 'error');
        } elseif (email_exists($account_email) && $account_email !== $current_user->user_email) {
            wc_add_notice(__('This email address is already registered.', 'woocommerce'), 'error');
        }
        $user->user_email = $account_email;
    }

    if (wc_notice_count('error') === 0) {
        wp_update_user($user);
        add_user_meta($user_id, 'phone_number', $account_phone, true);

        wc_add_notice(__('Account details changed successfully.', 'woocommerce'));

        do_action('woocommerce_save_account_details', $user->ID);

        wp_safe_redirect( wc_get_endpoint_url('edit-account', '', wc_get_page_permalink('myaccount')));
        exit;
    }
}

add_action('template_redirect', 'gethalal_save_account_detail');


function gethalal_set_default_account_address(){

    if (empty($_GET['action']) || 'set_default_account_address' !== $_GET['action'] || !isset($_GET['id'])) {
        return;
    }

    $user_id = get_current_user_id();

    $default_address_id = intval($_GET['id']);
    $addresses = Gethalal_Utils::get_addresses($user_id);

    if($default_address_id >= count($addresses)){

        wc_add_notice(__('This address is not registered.', 'woocommerce'), 'error');

    } else {

        Gethalal_Utils::set_default_address($user_id, $default_address_id);
        WC()->customer->set_postcode($addresses[$default_address_id]['postcode']);

        wp_safe_redirect(is_checkout()?wc_get_page_permalink('checkout'):wc_get_endpoint_url('edit-address', '', wc_get_page_permalink('myaccount')));
        exit;

    }
}

add_action('template_redirect', 'gethalal_set_default_account_address');


// Redirect login page from my-account page when user not logged in.
function gethalal_redirect_if_user_not_logged_in() {

    if ( !is_user_logged_in() && is_account_page() ) {

        wp_safe_redirect( wp_login_url());

        exit;
    }
}
add_action('template_redirect', 'gethalal_redirect_if_user_not_logged_in' );


// Remind me when product is not purchasable
function gethalal_redirect_remind_me_product() {
    if ( !empty( $_GET['remind_me_product'] ) ) {
        if ( wp_verify_nonce( $_GET['remind_me_product'], 'remind_me_product_nonce' ) ) {
            wc_add_notice( __( 'We will remind you whenever the product is available again.', 'gethalal' ), 'notice' );
            wp_safe_redirect( remove_query_arg(array('remind_me_product'), $_SERVER['REQUEST_URI']));
            exit;
        }
    }
}
add_action('template_redirect', 'gethalal_redirect_remind_me_product');

/////////////////////////////////////////////////////////////////////////////////////////////
/// APIs
///

add_action('wp_ajax_gethalal_get_product', 'gethalal_get_product');
add_action('wp_ajax_nopriv_gethalal_get_product', 'gethalal_get_product');

function gethalal_get_product()
{

    global $woocommerce;

    $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
    $lightbox_en = get_option('woocommerce_enable_lightbox') == 'yes' ? true : false;


    global $post;
    $product_id = $_POST['product_id'];
    if (intval($product_id)) {

        wp('p=' . $product_id . '&post_type=product');
        ob_start();


        while (have_posts()) :
            the_post();
            $product = wc_get_product(get_the_ID());

            $quantity = 0;
            // Loop through cart items
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                if (in_array(get_the_ID(), array($cart_item['product_id'], $cart_item['variation_id']))) {
                    $quantity = $cart_item['quantity'];
                    break; // stop the loop if product is found
                }
            }

            $defaults = array(
                'quantity' => $quantity,
                'class' => implode(
                    ' ',
                    array_filter(
                        array(
                            'button',
                            'product_type_' . $product->get_type(),
                            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                            $product->is_purchasable() && $product->is_in_stock() ? 'gethalal_ajax_add_to_cart' : '',
                        )
                    )
                ),
                'attributes' => array(
                    'data-product_id' => $product->get_id(),
                    'data-product_sku' => $product->get_sku(),
                    'aria-label' => $product->add_to_cart_description(),
                    'rel' => 'nofollow',
                ),
            );

            $args = apply_filters('woocommerce_loop_add_to_cart_args', wp_parse_args(array(), $defaults), $product);
            ?>
            <div class="modal-product-detail">
                <div id="summary-product-<?php the_ID(); ?>" <?php post_class('product'); ?> >
                    <div class="gethalal-summary entry-summary scrollable">
                        <div class="summary-image">
                            <?php the_post_thumbnail(array(343, 343)); ?>
                        </div>
                        <div class="summary-content">
                            <div class="product-title default-max-width"><?php the_title(); ?></div>
                            <div class="product-owner"><?php
                                $brand = $product->get_attribute('brand');
                                echo wp_kses_post( $brand ); ?>
                            </div>
                            <?php
                            if ( function_exists('wc_gzd_get_gzd_product') ) : ?>
                                <?php $gzd_product = wc_gzd_get_gzd_product( $product );
                                if( $gzd_product && $gzd_product->has_unit() ) : ?>
                                    <div class="product-unit-price"><?php echo $gzd_product->get_unit_price_html(); ?></div>
                                <?php else: ?>
                                    <div class="product-unit-price"><?php echo $product->get_price_html() ?></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="product-unit-price"><?php echo $product->get_price_html() ?></div>
                            <?php endif; ?>
                            <div class="product-cart">
                                <div class="product-price"><?php echo $product->get_price() . ' ' . html_entity_decode( get_woocommerce_currency_symbol() ) ?></div>
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
                            <div class="product-details">
                                <div class="summary-label"><?php echo esc_html__('Details', 'gethalal') ?></div>
                                <div class="summary-sub-label">Country: <span>Germany</span> Unit: <span>Kg</span></div>
                            </div>
                            <div class="product-description">
                                <div class="summary-label"><?php echo esc_html__('Description', 'gethalal') ?></div>
                                <div class="description-content"><?php echo $product->get_description() ?></div>
                            </div>
                        </div>
                        <div class="summary-close">
                            <span class="dialog-close-icon"> <?php echo gethalal_get_icon_svg('ui', 'close', 24); ?></span>
                        </div>
                    </div>
                </div>
            </div>

        <?php endwhile;
        echo ob_get_clean();

        exit();

    }
}

add_action( 'template_redirect', 'rp_callback' );
function rp_callback() {
    if ( is_cart()) {
        $error = new WP_Error();
        foreach (WC()->cart->cart_contents as $cart_item_key => $values) {
            $product = $values['data'];

            // Check stock based on stock-status.
            if (!$product->is_in_stock()) {
                /* translators: %s: product name */
                $error->add('out-of-stock', sprintf(__('Sorry, "%s" is not in stock. This product was removed from cart.', 'woocommerce'), $product->get_name()));
                WC()->cart->remove_cart_item($cart_item_key);
            }
        }
        if($error->has_errors()){
          wc_add_notice($error->get_error_message(), 'notice');
        }
    }
}

add_action('wp_ajax_gethalal_get_address', 'gethalal_get_address');

function gethalal_get_address()
{
    $address = false;
    if (isset($_POST['address_id'])) {
        $address_id = intval($_POST['address_id']);
        $addresses = Gethalal_Utils::get_addresses(get_current_user_id());
        $address = $addresses[$address_id];
        $address = array_merge(['id' => $address_id], $address);
    }

    ob_start();

    gethalal_new_address_form($address);

    echo ob_get_clean();

    exit();
}


add_action('wp_ajax_gethalal_get_payment_method', 'gethalal_get_payment_method');

function gethalal_get_payment_method()
{
    ob_start();

    woocommerce_account_add_payment_method();

    echo ob_get_clean();

    exit();
}

add_action('wp_ajax_gethalal_update_zipcode', 'gethalal_update_zipcode');
add_action('wp_ajax_nopriv_gethalal_update_zipcode', 'gethalal_update_zipcode');

function gethalal_update_zipcode()
{
    if (!empty($_POST['zipcode'])) {
        $zip_code = $_POST['zipcode'];
        $zone = gethalal_get_zone_by_postcode($zip_code);

        if($zone){
            WC()->customer->set_postcode($zip_code);
            wc_setcookie('gethalal_customer_post_code',  $zip_code);
            wp_send_json_success(['post_code' => $zip_code, 'zone_name' => $zone['zone_name']]);
            wp_die();
        }
        wp_send_json_error(['error' => __('Please input valid postcode', 'gethalal')]);
        wp_die();
    }
    wp_send_json_error(['error' => __('Please input postcode', 'gethalal')]);
    wp_die();
}


add_action('wp_ajax_gethalal_get_order_detail', 'gethalal_get_order_detail');

function gethalal_get_order_detail()
{
    $order_id = $_POST['order_id'];
    if (intval($order_id)) {

        ob_start();
        woocommerce_account_view_order($order_id);
        echo ob_get_clean();

        exit();
    }
}

/**
 * Order Cancel Dialog
 */

add_action('wp_ajax_gethalal_get_cancel_dialog', 'gethalal_get_cancel_dialog');

if(!function_exists('gethalal_get_cancel_dialog')){
    function gethalal_get_cancel_dialog()
    {
        $order_id = $_POST['order_id'];
        $order = wc_get_order( $order_id );
        $cancel_link = $order->get_cancel_order_url_raw( wc_get_endpoint_url( 'orders', '', wc_get_page_permalink( 'myaccount' ) ));

        ob_start();
        ?>
            <div class="dialog-order-cancel-content">
                <div class="cancel-header">
                    <span class="dialog-title"><?php echo __('Why you need to cancel?', 'gethalal') ?></span>
                    <span class="dialog-close-icon"> <?php echo gethalal_get_icon_svg('ui', 'close', 24); ?></span>
                </div>
                <div class="cancel-content">
                    <select id="cancel_reason_type" name="cancel_reason_type" class="cancel_reason_type">
                        <option value="Other" selected><?php echo __('Other', 'gethalal') ?></option>
                    </select>
                    <textarea id="cancel_reason_text" name="cancel_reason_text" class="cancel-reason-text"></textarea>
                </div>
                <div class="cancel-actions">
                    <a class="woocommerce-Button button button-primary" href="<?php echo $cancel_link ?>" ><?php echo __( 'Yes, Cancel the Order', 'woocommerce' );?></a>
                    <a class="woocommerce-Button button button-secondary" ><?php echo __( 'No, i need the order', 'woocommerce' );?></a>
                </div>
            </div>
        <?php
        echo ob_get_clean();
        exit();
    }
}

//////////////////////////////////////////////////////////////////////
/// Ajax Call
///
/// Add Event Handler To update QTY

function getalal_update_qty()
{
    ob_start();
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    $quantity = $_POST['quantity'];
    $product_cart_item_key = null;

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item):

        if ($cart_item['product_id'] == $product_id) {
            WC()->cart->set_quantity($cart_item_key, $quantity, true);
            $product_cart_item_key = $cart_item_key;
        }

    endforeach;

    $all = WC()->cart->get_cart_contents_count();

    wp_send_json(['product_id' => $product_id, 'quantity' => $quantity, 'all' => $all, 'cart_item_key' => $product_cart_item_key]);
}

add_action('woocommerce_ajax_added_to_cart', 'getalal_update_qty');
add_filter( 'woocommerce_cart_redirect_after_error', 'wc_get_cart_url', 10, 2 );

// Remove product in the cart using ajax
function gethalal_ajax_product_remove_from_cart()
{

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
    {
        if($cart_item['product_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key'] )
        {
            WC()->cart->remove_cart_item($cart_item_key);
        }
    }

    WC()->cart->calculate_totals();
    WC()->cart->maybe_set_cart_cookies();

    $data = array(
        'product_id' => $_POST['product_id'],
        'quantity' => 0,
        'all' => WC()->cart->get_cart_contents_count(),
        'cart_item_key' => $_POST['cart_item_key']
    );

    wp_send_json( $data );
    wp_die();
}

add_action( 'wp_ajax_remove_from_cart', 'gethalal_ajax_product_remove_from_cart' );
add_action('wp_ajax_nopriv_remove_from_cart', 'gethalal_ajax_product_remove_from_cart');


// hook into the fragments in AJAX and get fresh order review
add_filter('woocommerce_update_order_review_fragments', 'gethalal_order_fragments_split_shipping', 10, 1);

function gethalal_order_fragments_split_shipping($order_fragments) {

    ob_start();
    gethalal_woocommerce_order_review_shipping_split();
    $gethalal_woocommerce_order_review_shipping_split = ob_get_clean();

    $order_fragments['.gethalal-checkout-review-order.gethalal-checkout-section'] = $gethalal_woocommerce_order_review_shipping_split;

    return $order_fragments;

}

// get the template that just has the shipping options that we need for the fresh order review
function gethalal_woocommerce_order_review_shipping_split( $deprecated = false ) {
    wc_get_template( 'checkout/review-order.php', array( 'checkout' => WC()->checkout() ) );
}