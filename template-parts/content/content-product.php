<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Get Halal
 */

global $product;

if($product){
    $remind = !($product->is_purchasable() && $product->is_in_stock());

    $quantity = 0;
    $product_cart_item_key = 0;
    // Loop through cart items
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        if( in_array( get_the_ID(), array($cart_item['product_id'], $cart_item['variation_id']) )){
            $quantity =  $cart_item['quantity'];
            $product_cart_item_key = $cart_item_key;
            break; // stop the loop if product is found
        }
    }
    
    $defaults = array(
        'quantity'   => $quantity,
        'class'      => implode(
            ' ',
            array_filter(
                array(
                    'button',
                    'gethalal_ajax_add_to_cart',
                    'product_type_' . $product->get_type(),
                    $product->is_purchasable() && $product->is_in_stock() ? '' : 'disable'
                )
            )
        ),
        'attributes' => array(
            'data-product_id'  => $product->get_id(),
            'data-product_sku' => $product->get_sku(),
            'aria-label'       => $product->add_to_cart_description(),
            'rel'              => 'nofollow',
        ),
    );

    $args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( array(), $defaults ), $product );

    $opened = $_GET['pd']??-1;

    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('product'); ?>>
        <!--    Disable Style When non-purchasable-->
        <div class="content-product <?php echo ($opened == $product->get_id()?'opened':'') ?> <?php echo ($product->is_purchasable() && $product->is_in_stock())?'':'not-purchasable' ?>" data-id="<?php the_ID(); ?>">
            <!--    <div class="content-product --><?php //echo ($opened == $product->get_id()?'opened':'') ?><!--" data-id="--><?php //the_ID(); ?><!--">-->
            <div class="product-header">
                <?php the_post_thumbnail( array(140, 140) ); ?>
            </div>
            <div class="product-content">
                <div class="product-title default-max-width"><?php the_title(); ?></div>
                <!--            <div class="product-owner">--><?php
                //                $brand = $product->get_attribute('brand');
                //                echo wp_kses_post( $brand ); ?>
                <!--            </div>-->
                <!--            --><?php
                //            if ( function_exists('wc_gzd_get_gzd_product') ) : ?>
                <!--                --><?php //$gzd_product = wc_gzd_get_gzd_product( $product );
                //                if( $gzd_product && $gzd_product->has_unit() ) : ?>
                <!--                    <div class="product-unit-price">--><?php //echo $gzd_product->get_unit_price_html(); ?><!--</div>-->
                <!--                --><?php //else: ?>
                <!--                    <div class="product-unit-price">--><?php //echo $product->get_price_html() ?><!--</div>-->
                <!--                --><?php //endif; ?>
                <!--            --><?php //else: ?>
                <!--                <div class="product-unit-price">--><?php //echo $product->get_price_html() ?><!--</div>-->
                <!--            --><?php //endif; ?>
                <div class="product-price"><?php echo $product->get_price() . ' ' . html_entity_decode( get_woocommerce_currency_symbol() ) ?></div>
            </div><!-- .entry-content -->
            <?php if($remind): ?>
                <div class="actions-container remind">
                    <a class="action-remind" href="<?php
                    echo add_query_arg(
                        array(
                            'remind_me_product' => wp_create_nonce( 'remind_me_product_nonce' ),
                        ),
                        ( function_exists( 'is_feed' ) && is_feed() ) || ( function_exists( 'is_404' ) && is_404() ) ? $product->get_permalink() : ''
                    )
                    ?>"><?php echo __('Remind Me', 'gethalal'); ?></a>
                </div>
            <?php else: ?>
                <div class="actions-container">
                    <?php
                    if($args['quantity'] > 0){
                        echo sprintf('<div data-quantity="%s" data-cart_item_key="%s" class="action-minus %s" %s>%s</div>',
                            esc_attr($args['quantity'] - 1),
                            $product_cart_item_key,
                            esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
                            isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                            gethalal_get_fontawesome_icon_svg('solid', $args['quantity'] == 1 ? 'trash-alt' : 'minus', 16));
                        echo '<div class="action-quantity">' .$args['quantity']. '</div>';
                    }
                    echo sprintf('<div data-quantity="%s" data-cart_item_key="%s" class="action-plus %s" %s>%s</div>',
                        esc_attr( isset( $args['quantity'] ) ? ($args['quantity'] + 1) : 1 ),
                        $product_cart_item_key,
                        esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
                        isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                        gethalal_get_fontawesome_icon_svg('solid', 'plus', 16))
                    ?>
                </div>
            <?php endif ?>
        </div>
    </article><!-- #post-<?php the_ID(); ?> -->
<?php
}
