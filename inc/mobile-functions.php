<?php
/**
 * Mobile Api Functions
 * @package Get Halal
 */

/////////////////////////////////////////////////////////////////////////////////////////////////////
///
///
/// Mobile Apis for Gethalal App


add_filter( 'woocommerce_rest_check_permissions', 'gethalal_allow_rest_api_queries', 10, 4 );
function gethalal_allow_rest_api_queries( $permission, $context, $zero, $object ) {
    // Optionally limit permitted queries to different contexts.
    if ( 'read' != $context) {
        return $permission;
    }
    // Write the parameters to the error log (or debug.log file) to see what requests are being accessed.
    //error_log( sprintf( 'Permission: %s, Context: %s; Object: %s', var_export( $permission, true ), $context, var_export( $object, true ) ) );

    return true;  // Allow all queries.
}


add_action( 'rest_api_init', function () {
    register_rest_route( 'wc/v2', '/addresses', array(
        'methods' => 'GET',
        'callback' => 'gethalal_api_get_address',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', '/address', array(
        'methods' => 'POST',
        'callback' => 'gethalal_api_post_address',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', '/set_default_address', array(
        'methods' => 'POST',
        'callback' => 'gethalal_api_post_set_default_address',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', '/remove_address', array(
        'methods' => 'POST',
        'callback' => 'gethalal_api_post_remove_address',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', '/get_countries', array(
        'methods' => 'GET',
        'callback' => 'gethalal_api_get_countries',
        'permission_callback' => '__return_true',
    ));

    register_rest_route( 'wc/v2', '/payment_methods', array(
        'methods' => 'GET',
        'callback' => 'gethalal_api_get_payment_methods',
        'permission_callback' => '__return_true',
    ));

    register_rest_route( 'wc/v2', '/payment_method', array(
        'methods' => 'POST',
        'callback' => 'gethalal_api_post_set_payment_method',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', '/set_default_payment_method', array(
        'methods' => 'POST',
        'callback' => 'gethalal_api_post_set_default_payment_method',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', '/remove_payment_method', array(
        'methods' => 'POST',
        'callback' => 'gethalal_remove_payment_method',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', '/set_postcode', array(
        'methods' => 'POST',
        'callback' => 'gethalal_set_postcode',
        'permission_callback' => '__return_true',
    ));

    register_rest_route( 'wc/v2', '/get_postcode', array(
        'methods' => 'GET',
        'callback' => 'gethalal_get_postcode',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', 'stripe_payment', array(
        'methods'  => 'POST',
        'callback' => 'gethalal_rest_stripe_payment_endpoint_handler',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', 'paypal_payment', array(
        'methods'  => 'POST',
        'callback' => 'gethalal_rest_paypal_payment_endpoint_handler',
        'permission_callback' => '__return_true'
    ));

    register_rest_route('wc/v2', 'register', array(
        'methods' => 'POST',
        'callback' => 'gethalal_register',
        'permission_callback' => '__return_true'
    ));


    register_rest_route( 'wc/v2', '/create_order', array(
        'methods' => 'POST',
        'callback' => 'gethalal_create_order',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', '/get_reorder_items', array(
        'methods' => 'GET',
        'callback' => 'gethalal_get_reorder_items',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', '/cancel_order', array(
        'methods' => 'GET',
        'callback' => 'gethalal_cancel_order',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', '/forgot_password', array(
        'methods' => 'POST',
        'callback' => 'gethalal_forgot_password',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', '/check_otp', array(
        'methods' => 'POST',
        'callback' => 'gethalal_check_otp',
        'permission_callback' => '__return_true'
    ));

    register_rest_route( 'wc/v2', '/reset_password', array(
        'methods' => 'POST',
        'callback' => 'gethalal_reset_password',
        'permission_callback' => '__return_true'
    ));
});



function gethalal_api_get_address()
{
    $addresses = [];
    if (get_current_user_id()) {
        $addresses = Gethalal_Utils::get_addresses(get_current_user_id());
    }
    return $addresses;
}

function gethalal_api_post_address(WP_REST_Request $req)
{
    $params = $req->get_params();
    $account_display_name = !empty($params['account_display_name']) ? wc_clean(wp_unslash($params['account_display_name'])) : '';
    $account_email = !empty($params['account_email']) ? wc_clean(wp_unslash($params['account_email'])) : '';
    $account_phone = !empty($params['account_phone']) ? wc_clean(wp_unslash($params['account_phone'])) : '';
    $account_address_text = !empty($params['account_address_text']) ? wc_clean(wp_unslash($params['account_address_text'])) : '';
    $account_address_country = !empty($params['account_address_country']) ? wc_clean(wp_unslash($params['account_address_country'])) : '';
    $account_address_postcode = !empty($params['account_address_postcode']) ? wc_clean(wp_unslash($params['account_address_postcode'])) : '';
    $account_address_city = !empty($params['account_address_city']) ? wc_clean(wp_unslash($params['account_address_city'])) : '';
    $account_address_floor = !empty($params['account_address_floor']) ? wc_clean(wp_unslash($params['account_address_floor'])) : '';

    $error = false;
    $zone = gethalal_get_zone_by_postcode($account_address_postcode);
    if(!$zone){
        $error = "Invalid Post Code";
    }

    $new_address = array(
        'display_name' => $account_display_name,
        'email' => $account_email,
        'phone' => $account_phone,
        'address' => $account_address_text,
        'country' => $account_address_country,
        'postcode' => $account_address_postcode,
        'city' => $account_address_city,
        'floor' => $account_address_floor,
        'land_mark' => '',
        'zone' => $zone?$zone['zone_name']:''
    );

    // Handle required fields.
    $required_fields = array(
        'account_display_name' => __('Display name', 'woocommerce'),
        'account_email' => __('Email address', 'woocommerce'),
        'account_phone' => __('Phone Number', 'woocommerce'),
        'account_address_text' => __('Address', 'woocommerce'),
        'account_address_country' => __('Country', 'woocommerce'),
        'account_address_postcode' => __('Zip Code', 'woocommerce'),
        'account_address_city' => __('City', 'woocommerce'),
        'account_address_floor' => __('Floor', 'woocommerce'),
    );

    foreach ($required_fields as $field_key => $field_name) {
        if (empty($params[$field_key])) {
            /* translators: %s: Field name. */
            $error = sprintf(__('%s is a required field.', 'woocommerce'), $field_name);
        }
    }

    if($error){
        return ['success' => false, 'error' => $error, 'params'=>$params];
    }
    $user_id = get_current_user_id();
    if(!isset($params['account_address_index'])){
        $address_id = Gethalal_Utils::add_address($user_id, $new_address);
    } else {
        $address_id = intval($params['account_address_index']);
        Gethalal_Utils::update_address($user_id, $address_id, $new_address);
    }

    if (intval($params['is_default'])) {
        Gethalal_Utils::set_default_address($user_id, $address_id);
    }

    return ['success' => true, 'addresses' => Gethalal_Utils::get_addresses($user_id)];
}


function gethalal_api_post_set_default_address(WP_REST_Request $req){
    $params = $req->get_params();
    $user_id = get_current_user_id();

    if (isset($params['default_id'])) {
        Gethalal_Utils::set_default_address($user_id, $params['default_id']);
    }
    return ['success' => true, 'addresses' => Gethalal_Utils::get_addresses($user_id)];
}


function gethalal_api_post_remove_address(WP_REST_Request $req){
    $params = $req->get_params();
    $user_id = get_current_user_id();

    if (isset($params['id'])) {
        Gethalal_Utils::remove_address($user_id, $params['id']);
        return ['success' => true, 'addresses' => Gethalal_Utils::get_addresses($user_id)];
    }
    return ['success' => false, 'error' => 'invalid params'];
}

function gethalal_api_get_countries(){
    $countries = WC()->countries->get_allowed_countries();
    $result = [];
    foreach ($countries as $key => $value){
        $result[] = [ 'code' => $key, 'name' => $value ];
    }
    return $result;
}

function gethalal_api_get_payment_methods(){
    $user_id = get_current_user_id();
    $saved_methods = wc_get_customer_saved_methods_list($user_id);
    return (bool)$saved_methods?$saved_methods:[];
}

function gethalal_get_account_saved_payment_methods_list_item_cc( $item, $payment_token ) {
    if ( 'cc' !== strtolower( $payment_token->get_type() ) ) {
        return $item;
    }

    $item['id'] = $payment_token->get_id();
    return $item;
}

add_filter( 'woocommerce_payment_methods_list_item', 'gethalal_get_account_saved_payment_methods_list_item_cc', 10, 2 );


function gethalal_api_post_set_payment_method(WP_REST_Request $req){
    $user_id = get_current_user_id();
    $params = $req->get_params();
    if(isset($params['payment_method_id'])){
        $payment_method_id = $params['payment_method_id'];
        $stripe_customer = new WC_Stripe_Customer($user_id);
        $stripe_customer->clear_cache();

        try {
            $customer_data         = WC_Stripe_Customer::map_customer_data( null, new WC_Customer( $user_id ) );
            $response = WC_Stripe_API::request(['customer' => $stripe_customer->get_id()], 'payment_methods/' . $payment_method_id . "/attach", 'POST');

            if ( empty( $response->error ) ) {
                $payment_method_object = WC_Stripe_API::request(
                    [
                        'billing_details' => [
                            'name' => empty($params['name'])?$customer_data['name']:$params['name'],
                            'email' => $customer_data['email'],
                            'phone' => $customer_data['phone'],
                            'address' => $customer_data['address'],
                        ],
                    ],
                    'payment_methods/' . $payment_method_id,
                    'POST'
                );

                do_action('woocommerce_stripe_add_payment_method', $user_id, $payment_method_object);

                if (intval($params['is_default'])) {
                    $stripe_customer->set_default_payment_method($payment_method_id);
                }

                return ['success' => true, 'paymentMethods' => wc_get_customer_saved_methods_list($user_id)];

            } else {
                return ['success' => false, 'response' => $response->error->message];
            }
        } catch ( Exception $e ) {
            return ['success' => false, 'error' => $e->getMessage(), 'params' => $params];
        }
    }
    return ['success' => false, 'error' => 'Invalid Params'];
}



function gethalal_api_post_set_default_payment_method(WP_REST_Request $req){
    $user_id = get_current_user_id();
    $params = $req->get_params();
    if(isset($params['token_id'])){
        $token_id = $params['token_id'];
        $token    = WC_Payment_Tokens::get( $token_id );
        if(!is_null($token) && get_current_user_id() === $token->get_user_id()){
            WC_Payment_Tokens::set_users_default( $token->get_user_id(), intval( $token_id ) );
            return ['success' => true, 'paymentMethods' => wc_get_customer_saved_methods_list($user_id)];
        }
    }
    return ['success' => false, 'error' => "Invalid Params"];
}

function gethalal_remove_payment_method(WP_REST_Request $req){
    $user_id = get_current_user_id();
    $params = $req->get_params();
    if(isset($params['token_id'])) {
        try {
            $token_id = $params['token_id'];
            WC_Payment_Tokens::delete( $token_id );
            return ['success' => true, 'paymentMethods' => wc_get_customer_saved_methods_list($user_id)];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    return ['success' => false, 'error' => 'Invalid Params'];
}

function stripe_request( $path, $params = null, $order = null, $method = 'POST' ) {
    if ( is_null( $params ) ) {
        return WC_Stripe_API::retrieve( $path );
    }

    return WC_Stripe_API::request( $params, $path, $method );
}

function gethalal_set_postcode(WP_REST_Request $req){
    $params = $req->get_params();
    if(isset($params['postcode'])){
        try{
            $postcode = $params['postcode'];
            $zone = gethalal_get_zone_by_postcode($postcode);
            if($zone){
                if(get_current_user_id()){
                    $customer = new WC_Customer(get_current_user_id());
                    $customer->set_postcode($postcode);
                    $customer->save();
                }
                return ['success' => true, 'data' => ['postcode' => $params['postcode'], 'zone' => $zone['zone_name'], 'zone_id' => $zone['id']]];
            }
        } catch (Exception $e) {
        }
    }
    return ['success' => false];
}

function gethalal_get_postcode(){
    try{
        $customer = new WC_Customer(get_current_user_id());
        $postcode = $customer->get_postcode();
        $target_zone = gethalal_get_zone_by_postcode($postcode);
        return ['postcode'=>$postcode, 'zone' => $target_zone['zone_name'], 'zone_id' => $target_zone['id']];
    } catch (Exception $exception) {
        return ['postcode' => '', 'zone' => ''];
    }
}

function gethalal_rest_stripe_payment_endpoint_handler(WP_REST_Request $request = null){

    $response       = array();
    $parameters 	= $request->get_params();
    $payment_method = sanitize_text_field( $parameters['payment_method'] );
    $order_id       = sanitize_text_field( $parameters['order_id'] );
    $token_id  = sanitize_text_field( $parameters['payment_token'] );
    $token    = WC_Payment_Tokens::get( $token_id );
    $payment_token = $token->get_token( 'edit' );

    $error          = new WP_Error();

    if ( empty( $payment_method ) ) {
        $error->add( 400, __( "Payment Method 'payment_method' is required.", 'wc-rest-payment' ), array( 'status' => 400 ) );
        return $error;
    }
    if ( empty( $order_id ) ) {
        $error->add( 401, __( "Order ID 'order_id' is required.", 'wc-rest-payment' ), array( 'status' => 400 ) );
        return $error;
    } else if ( wc_get_order($order_id) == false ) {
        $error->add( 402, __( "Order ID 'order_id' is invalid. Order does not exist.", 'wc-rest-payment' ), array( 'status' => 400 ) );
        return $error;
    } else if ( wc_get_order($order_id)->get_status() !== 'pending' ) {
        $error->add( 403, __( "Order status is NOT 'pending', meaning order had already received payment. Multiple payment to the same order is not allowed. ", 'wc-rest-payment' ), array( 'status' => 400 ) );
        return $error;
    }
    if ( empty( $payment_token ) ) {
        $error->add( 404, __( "Payment Token 'payment_token' is required.", 'wc-rest-payment' ), array( 'status' => 400 ) );
        return $error;
    }

    if ( $payment_method === "stripe" ) {
        $wc_gateway_stripe                = new WC_Gateway_Stripe();
        $_POST['payment_method'] = $payment_method;
        $_POST['wc-stripe-payment-token']            = $token_id;
        $_POST['wc_stripe_selected_upe_payment_type'] = 'card';
        $payment_result               = $wc_gateway_stripe->process_payment( $order_id );
        if ( $payment_result['result'] === "success" ) {
            $response['code']    = 200;
            $response['message'] = __( "Your Payment was Successful", "wc-rest-payment" );
        } else {
            return new WP_REST_Response( array("c"), 200 );
            $response['code']    = 401;
            $response['message'] = __( "Please enter valid card details", "wc-rest-payment" );
        }
    }  else {
        $response['code'] = 405;
        $response['message'] = __( "Please select an available payment method. Supported payment method can be found at https://wordpress.org/plugins/wc-rest-payment/#description", "wc-rest-payment" );
    }

    return new WP_REST_Response( $response, 200 );
}

function gethalal_rest_paypal_payment_endpoint_handler(WP_REST_Request $request = null){

    $response       = array();
    $parameters 	= $request->get_params();
    $order_id       = sanitize_text_field( $parameters['order_id'] );
    $transaction_id = sanitize_text_field( $parameters['transaction_id'] );

    $error          = new WP_Error();

    if ( empty( $transaction_id ) ) {
        $error->add( 400, __( "Paypal Payment ID 'payment_id' is required.", 'wc-rest-payment' ), array( 'status' => 400 ) );
        return $error;
    }
    if ( empty( $order_id ) ) {
        $error->add( 401, __( "Order ID 'order_id' is required.", 'wc-rest-payment' ), array( 'status' => 400 ) );
        return $error;
    } else if ( wc_get_order($order_id) == false ) {
        $error->add( 402, __( "Order ID 'order_id' is invalid. Order does not exist.", 'wc-rest-payment' ), array( 'status' => 400 ) );
        return $error;
    } else if ( wc_get_order($order_id)->get_status() !== 'pending' ) {
        $error->add( 403, __( "Order status is NOT 'pending', meaning order had already received payment. Multiple payment to the same order is not allowed. ", 'wc-rest-payment' ), array( 'status' => 400 ) );
        return $error;
    }

    $wc_order = wc_get_order( $order_id );
    $wc_order->set_transaction_id($transaction_id);
    $wc_order->update_status( 'processing' );

    $response['code']    = 200;
    $response['message'] = __( "Your Payment was Successful", "wc-rest-payment" );


    return new WP_REST_Response( $response, 200 );
}

// REST API Apply Coupon
add_filter( 'woocommerce_rest_prepare_shop_order_object', 'gethalal_change_shop_order_response', 10, 3 );
function gethalal_change_shop_order_response( $response, $object, $request ) {
    $order = wc_get_order( $response->data['id'] );
    $used_coupons = $request->get_param( 'coupon_lines' );
    $coupon_amount = 0;
    if( !empty( $used_coupons ) ):
        foreach ($used_coupons as $coupon ){
            $coupon_amount = $coupon['discount'];
        }
    endif;

    $order_coupons = $response->data['coupon_lines'];
    if( !empty( $order_coupons ) ) :
        foreach ( $order_coupons as $coupon ) {
            wc_update_order_item_meta( $coupon['id'], 'discount_amount', $coupon['discount'] );
        }
    endif;
    $order_total = get_post_meta( $response->data['id'], '_order_total', true );
    $order_total = $order_total - $coupon_amount;
    update_post_meta( $order->ID, '_order_total', $order_total );
    $response->data['total']  = $order_total;

    return $response;
}


function gethalal_register(WP_REST_Request $request){
    $params = $request->get_params();
    if(isset($params['email']) && isset($params['password'])){
        $errors = gethalal_register_new_user( $params['email'], $params['password'] );

        if ( ! is_wp_error( $errors ) ) {
            return ['success' => true];
        }
        return ['success' => false, 'error' => $errors->get_error_message()];
    }
    return ['success' => false, 'error' => 'invalid params'];
}

function gethalal_create_order(WP_REST_Request $request){
    return (new WC_REST_Orders_Controller())->create_item($request);
}

function gethalal_get_reorder_items(WP_REST_Request $request){
    $params = $request->get_params();
    if(isset($params['order_id'])) {
        $order_id = $params['order_id'];
        $order = wc_get_order($order_id);
        if($order && $line_items = $order->get_items()){
            $reorder_item = [];
            foreach ($line_items as $line_item){
                $item_product = $line_item->get_product();
                if($item_product){
                    $id = $item_product->get_id();

                    $image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
                    $data = $item_product->get_data();
                    $data['images'] = [
                        [
                            'id' => $id,
                            'src' => $image_src[0]
                        ]
                    ];
                    $data['quantity'] = $line_item->get_quantity();

                    $reorder_item[$item_product->get_id()] = $data;
                }
            }
            if(count($reorder_item) > 0){
                return ['success' => true, 'items' => $reorder_item];
            }
            return ['success' => false, 'error' => 'No Products'];
        }
    }
    return ['success' => false, 'error' => 'Invalid params'];
}


function gethalal_cancel_order(WP_REST_Request $request){
    $params = $request->get_params();
    if(isset($params['order_id'])) {
        $order_id = $params['order_id'];
        $order            = wc_get_order( $order_id );
        $user_can_cancel  = current_user_can( 'cancel_order', $order_id );
        $order_can_cancel = $order->has_status( apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) );
        if ( $user_can_cancel && $order_can_cancel && $order->get_id() === $order_id){
            $order->update_status( 'cancelled', __( 'Order cancelled by customer.', 'woocommerce' ) );
            do_action( 'woocommerce_cancelled_order', $order->get_id() );
            return ['success' => true];
        }
        return ['success' => false, 'error' => 'Invalid order', 'user_can' => $user_can_cancel, 'order_can' => $order_can_cancel];
    }
    return ['success' => false, 'error' => 'Invalid params'];
}


add_filter( 'retrieve_password_message', 'gethalal_retrieve_password_message', 10, 4 );
function gethalal_retrieve_password_message( $retrieve_password_message, $key, $user_login, $user_data ) {

    $optCode = randOTPCode();
    $timestamp = time() + 60 * 5;               // expire 5 min
    $optData = ['time' => $timestamp, 'opt' => $optCode];
    $reset_opt_code = add_user_meta($user_data->ID, 'RESET_OPT_CODE', $optData, true);
    if(!$reset_opt_code) {
        update_user_meta($user_data->ID, 'RESET_OPT_CODE', $optData);
    }

    $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

    $message = __( 'You has requested a password reset for the following account:' ) . "\r\n\r\n";
    /* translators: %s: Site name. */
    $message .= sprintf( __( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
    /* translators: %s: User login. */
    $message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
    $message .= __( 'To reset your password, please use this authentication code:' ) . "\r\n\r\n";
    $message .= $optCode;

    return $message;
}

if (!function_exists('randOTPCode')) {

    function randOTPCode(): string
    {
        $length = 4;
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[wp_rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}

function gethalal_forgot_password(WP_REST_Request $request){
    $params = $request->get_params();
    if(isset($params['user_login'])){
        $errors = retrieve_password($params['user_login']);

        if ( ! is_wp_error( $errors ) ) {
            return ['success' => true];
        }
        return ['success' => false, 'error' => $errors->get_error_message()];
    }
    return ['success' => false, 'error' => 'Invalid params'];
}

function gethalal_check_otp(WP_REST_Request $request){
    $params = $request->get_params();
    if(isset($params['user_login']) && isset($params['code'])){
        $user = get_user_by( 'email', trim($params['user_login']));
        $reset_opcode = get_user_meta($user->ID, 'RESET_OPT_CODE', true);
        if($reset_opcode){
            if($reset_opcode['time'] > time()){
                if($reset_opcode['opt'] == $params['code']){
                    $reset_nonce =  wp_create_nonce('gethalal_reset_nonce');
                    return ['success' => true, 'nonce' => $reset_nonce];
                }
            } else {
                return ['success' => false, 'error' => 'This auth code was expired'];
            }
        } else {
            return ['success' => false, 'error' => 'Invalid Auth Code'];
        }
    }
    return ['success' => false, 'error' => 'Invalid params'];
}

function gethalal_reset_password(WP_REST_Request $request){
    $params = $request->get_params();
    if(isset($params['user_login']) && isset($params['pass'])){
        $user = get_user_by( 'email', trim($params['user_login']));
        if($user){
            reset_password( $user, $params['pass']);
            return ['success' => true];
        }
    }
    return ['success' => false, 'error' => 'Invalid params'];
}
