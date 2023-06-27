<?php
/**
 * Functions and filters related to the menus.
 *
 * Makes the default WordPress navigation use an HTML structure similar
 * to the Navigation block.
 *
 * @link https://make.wordpress.org/themes/2020/07/06/printing-navigation-block-html-from-a-legacy-menu-in-themes/
 *
 * @package Get Halal
 */

/**
 * Add a button to top-level menu items that has sub-menus.
 * An icon is added using CSS depending on the value of aria-expanded.
 *
 * @since Get Halal 1.0
 *
 * @param string $output Nav menu item start element.
 * @param object $item   Nav menu item.
 * @param int    $depth  Depth.
 * @param object $args   Nav menu args.
 * @return string Nav menu item start element.
 */
function gethalal_add_sub_menu_toggle( $output, $item, $depth, $args ) {
	if ( 0 === $depth && in_array( 'menu-item-has-children', $item->classes, true ) ) {

		// Add toggle button.
		$output .= '<button class="sub-menu-toggle" aria-expanded="false" onClick="gethalalExpandSubMenu(this)">';
		$output .= '<span class="icon-plus">' . gethalal_get_icon_svg( 'ui', 'plus', 18 ) . '</span>';
		$output .= '<span class="icon-minus">' . gethalal_get_icon_svg( 'ui', 'minus', 18 ) . '</span>';
		$output .= '<span class="screen-reader-text">' . esc_html__( 'Open menu', 'gethalal' ) . '</span>';
		$output .= '</button>';
	}
	return $output;
}
add_filter( 'walker_nav_menu_start_el', 'gethalal_add_sub_menu_toggle', 10, 4 );

/**
 * Detects the social network from a URL and returns the SVG code for its icon.
 *
 * @since Get Halal 1.0
 *
 * @param string $uri  Social link.
 * @param int    $size The icon size in pixels.
 * @return string
 */
function gethalal_get_social_link_svg( $uri, $size = 24 ) {
	return Gethalal_SVG_Icons::get_social_link_svg( $uri, $size );
}

/**
 * Displays SVG icons in the footer navigation.
 *
 * @since Get Halal 1.0
 *
 * @param string   $item_output The menu item's starting HTML output.
 * @param WP_Post  $item        Menu item data object.
 * @param int      $depth       Depth of the menu. Used for padding.
 * @param stdClass $args        An object of wp_nav_menu() arguments.
 * @return string The menu item output with social icon.
 */
function gethalal_nav_menu_social_icons( $item_output, $item, $depth, $args ) {
	// Change SVG icon inside social links menu if there is supported URL.
	if ( 'footer' === $args->theme_location ) {
		$svg = gethalal_get_social_link_svg( $item->url, 24 );
		if ( ! empty( $svg ) ) {
			$item_output = str_replace( $args->link_before, $svg, $item_output );
		}
	}

	return $item_output;
}

add_filter( 'walker_nav_menu_start_el', 'gethalal_nav_menu_social_icons', 10, 4 );

/**
 * Filters the arguments for a single nav menu item.
 *
 * @since Get Halal 1.0
 *
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param WP_Post  $item  Menu item data object.
 * @param int      $depth Depth of menu item. Used for padding.
 * @return stdClass
 */
function gethalal_add_menu_description_args( $args, $item, $depth ) {
	$args->link_after = '';
	if ( 0 === $depth && isset( $item->description ) && $item->description ) {
		// The extra <span> element is here for styling purposes: Allows the description to not be underlined on hover.
		$args->link_after = '<p class="menu-item-description"><span>' . $item->description . '</span></p>';
	}
	return $args;
}
add_filter( 'nav_menu_item_args', 'gethalal_add_menu_description_args', 10, 3 );


/**
 * My Account Menus
 */

function gethalal_get_account_menu_items() {
    $endpoints = array(
        'orders'          => get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' ),
        'downloads'       => get_option( 'woocommerce_myaccount_downloads_endpoint', 'downloads' ),
        'edit-address'    => get_option( 'woocommerce_myaccount_edit_address_endpoint', 'edit-address' ),
        'payment-methods' => get_option( 'woocommerce_myaccount_payment_methods_endpoint', 'payment-methods' ),
        'edit-account'    => get_option( 'woocommerce_myaccount_edit_account_endpoint', 'edit-account' ),
//        'notifications'    => esc_url( wc_get_page_permalink( 'myaccount' ).'notifications'),
        'change-password'    => esc_url( wc_get_page_permalink( 'myaccount' ).'change-password'),
        'terms-policy'    => esc_url( wc_get_page_permalink( 'myaccount' ).'terms-policy'),
    );

    $items = array(
        'edit-account'    => ['label' => __( 'My Account', 'gethalal' ), 'icon' => gethalal_get_fontawesome_icon_svg('solid', 'user', 20)],
        'orders'          => ['label' => __( 'My Orders', 'gethalal' ), 'icon' => gethalal_get_fontawesome_icon_svg('solid', 'clipboard-list', 20)],
        'payment-methods' => ['label' => __( 'My payment options', 'gethalal' ), 'icon' => gethalal_get_fontawesome_icon_svg('solid', 'credit-card', 20)],
        'edit-address'    => ['label' => _n( 'My Address', 'Address', (int) wc_shipping_enabled(), 'gethalal' ), 'icon' => gethalal_get_fontawesome_icon_svg('solid', 'map-marker-alt', 20)],
//        'notifications'       => __( 'Notifications', 'woocommerce' ),
        'change-password' => ['label' => __('Change Password', 'gethalal'), 'icon' => gethalal_get_fontawesome_icon_svg('solid', 'lock', 20)],
        'terms-policy' => ['label' => __( 'Terms & Policy', 'gethalal' ), 'icon' => gethalal_get_fontawesome_icon_svg('solid', 'question-circle', 20)],
    );

    // Remove missing endpoints.
    foreach ( $endpoints as $endpoint_id => $endpoint ) {
        if ( empty( $endpoint ) ) {
            unset( $items[ $endpoint_id ] );
        }
    }

    // Check if payment gateways support add new payment methods.
    if ( isset( $items['payment-methods'] ) ) {
        $support_payment_methods = false;
        foreach ( WC()->payment_gateways->get_available_payment_gateways() as $gateway ) {
            if ( $gateway->supports( 'add_payment_method' ) || $gateway->supports( 'tokenization' ) ) {
                $support_payment_methods = true;
                break;
            }
        }

        if ( ! $support_payment_methods ) {
            unset( $items['payment-methods'] );
        }
    }

    return $items;
}

// Enable endpoint
add_action( 'init', 'gethalal_custom_endpoint_query_var' );
function gethalal_custom_endpoint_query_var( $query_vars ) {
    add_rewrite_endpoint('change-password', EP_ALL);
    add_rewrite_endpoint('notifications', EP_ALL);
    add_rewrite_endpoint('terms-policy', EP_ALL);
}

add_action('woocommerce_account_change-password_endpoint', function() {
    wc_get_template('myaccount/form-change-password.php');
});

add_action('woocommerce_account_notifications_endpoint', function() {
    wc_get_template('myaccount/form-notifications.php');
});

add_action('woocommerce_account_terms-policy_endpoint', function() {
    wc_get_template('myaccount/form-terms-policy.php');
});

