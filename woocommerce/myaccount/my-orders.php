<?php
/**
 * My Orders - Deprecated
 *
 * @deprecated 2.6.0 this template file is no longer used. My Account shortcode uses orders.php.
 * @package WooCommerce\Templates
 */

defined( 'ABSPATH' ) || exit;

$my_orders_columns = apply_filters(
	'woocommerce_my_account_my_orders_columns',
	array(
		'order-number'  => esc_html__( 'Order', 'woocommerce' ),
		'order-date'    => esc_html__( 'Date', 'woocommerce' ),
		'order-status'  => esc_html__( 'Status', 'woocommerce' ),
		'order-total'   => esc_html__( 'Total', 'woocommerce' ),
		'order-actions' => '&nbsp;',
	)
);

$customer_orders = get_posts(
	apply_filters(
		'woocommerce_my_account_my_orders_query',
		array(
			'numberposts' => $order_count,
			'meta_key'    => '_customer_user',
			'meta_value'  => get_current_user_id(),
			'post_type'   => wc_get_order_types( 'view-orders' ),
			'post_status' => array_keys( wc_get_order_statuses() ),
		)
	)
);

$filter = $_GET['filter']??'upcoming';
$is_filter_upcoming = $filter == 'upcoming';

if ( $customer_orders ) : ?>

	<h2><?php echo apply_filters( 'woocommerce_my_account_my_orders_title', esc_html__( 'Recent orders', 'woocommerce' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>

	<div class="shop_table shop_table_responsive my_account_orders">

		<div class="table-header">
			<a class="order-filter <?php echo $is_filter_upcoming?'active': '' ?>" href="#"><?php echo __('Upcoming', 'gethalal')?></a>
            <a class="order-filter <?php echo !$is_filter_upcoming?'active': '' ?>" href="#"><?php echo __('History', 'gethalal')?></a>
		</div>

		<div class="orders-content">
			<?php
			foreach ( $customer_orders as $customer_order ) :
				$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$item_count = $order->get_item_count();
				?>
				<div class="order" data-id="<?php echo $order->get_id(); ?>">
				    <div class="order-row">
                        <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
                        <?php gethalal_get_order_shipping_status($order) ?>
                    </div>
                    <div class="order-row">
                        <span><?php esc_html_e( 'Order id', 'gethalal' ); ?>
                        <?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    </div>
                    <div class="order-row">
                        <?php
                        /* translators: 1: formatted order total 2: total order items */
                        printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>


                        <?php
                        $actions = wc_get_account_orders_actions( $order );

                        if ( ! empty( $actions ) ) {
                            foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                                echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
                            }
                        }
                        ?>
                    </div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
