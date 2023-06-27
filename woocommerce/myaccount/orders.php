<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$filter = $_GET['filter']??'upcoming';
$is_filter_upcoming = $filter == 'upcoming';
$filter_order_statuses = $is_filter_upcoming?Gethalal_Utils::UPCOMING_ORDER_STATUSES:Gethalal_Utils::HISTORY_ORDER_STATUSES;

?>
<div class="gethalal-my-account-content">

<?php do_action( 'woocommerce_before_account_orders', $has_orders ); ?>


<div class="my-account-title"><?php echo __('My Orders') ?></div>

<?php if ( $has_orders ) : ?>


    <div class="shop_table shop_table_responsive my_account_orders">

        <div class="table-header">
            <?php if($is_filter_upcoming): ?>
                <span class="order-filter button inactive"><?php echo __('Upcoming', 'gethalal')?></span>
                <a class="order-filter" href="<?php echo add_query_arg('filter', 'history')?>"><?php echo __('History', 'gethalal')?></a>
            <?php else: ?>
                <a class="order-filter" href="<?php echo add_query_arg('filter', 'upcoming')?>"><?php echo __('Upcoming', 'gethalal')?></a>
                <span class="order-filter button inactive"><?php echo __('History', 'gethalal')?></span>
            <?php endif; ?>
        </div>

        <div class="orders-content">
            <?php
            foreach ( $customer_orders->orders as $customer_order ) :
                $order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                if(!in_array($order->get_status(), $filter_order_statuses)){
                    continue;
                }
                $item_count = $order->get_item_count();
                ?>
                <div class="order" data-id="<?php echo $order->get_id(); ?>">
                    <div class="order-row">
                        <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
                        <?php gethalal_get_order_shipping_status($order) ?>
                    </div>
                    <div class="order-row" style="margin-top: 8px">
                        <span class="order-number"><?php esc_html_e( 'Order id', 'gethalal' ); ?>
                            <?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    </div>
                    <div class="order-row" style="margin-top: 16px">
                        <span class="order-price">
                            <?php
                            /* translators: 1: formatted order total 2: total order items */
                            printf( '%1$s', $order->get_formatted_order_total()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                        </span>
                        <?php
                        if (  $order->has_status(apply_filters( 'woocommerce_valid_order_statuses_for_order_again', Gethalal_Utils::HISTORY_ORDER_STATUSES)) ) {
                                echo '<a href="' . wp_nonce_url( add_query_arg( 'order_again', $order->get_id(), wc_get_cart_url() ), 'woocommerce-order_again' ) . '" class="order-action button order-again">' . __('R-Order', 'gethalal') . '</a>';
                        }
                        ?>

<!--                        --><?php
//                        $actions = wc_get_account_orders_actions( $order );
//
//                        if ( ! empty( $actions ) ) {
//                            foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
//                                echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
//                            }
//                        }
//                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
		<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php
do_action( 'woocommerce_after_account_orders', $has_orders );
?>
</div>
