<style>
    .order_details {
        border: 1px solid #e6e2e2;
        padding: 0px;
        margin-top: 20px!important;
        border-radius: 5px;
    }

    .cart-item img{
        border-radius : 0!important;
        width: 145px;
    }
    .cart-item a{
        font-size: 20px!important;
        color: black;
    }
    .course-total p{
        font-size: 25px!important;
        font-weight: bold;
        text-align: right;
    }
    .cart-item td{
        border : none!important;
    }
    .lp-list-table{
        margin: 0!important;
    }
</style>

<?php
/**
 * Template for displaying reviewing before placing order.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/checkout/review-order.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<?php $cart = learn_press_get_checkout_cart(); ?>

<div class="heading">
    <h1><?php _e( 'Your order', 'learnpress' ) ?></h1>
    <div class="below_heading">
        <hr/>
    </div>
</div>

<div class="order_details">
    <table class="learn-press-checkout-review-order-table lp-list-table">
        <tbody>

        <?php
        /**
         * @deprecated
         */
        do_action( 'learn_press_review_order_before_cart_contents' );

        /**
         * @since 3.0.0
         */

        do_action( 'learn-press/review-order/before-cart-contents' );

        if ( $items = $cart->get_items() ) {
            foreach ( $items as $cart_item_key => $cart_item ) {
                $img_url = wp_get_attachment_url( get_post_thumbnail_id($cart_item['item_id']), 'thumbnail' );
                $cart_item = apply_filters( 'learn-press/review-order/cart-item', $cart_item );
                $item_id   = $cart_item['item_id'];
                $_course   = learn_press_get_course( $item_id );

                if ( $_course && 0 < $cart_item['quantity'] ) {
                    ?>
                    <tr class="<?php echo esc_attr( apply_filters( 'learn-press/review-order/cart-item-class', 'cart-item', $cart_item, $cart_item_key ) ); ?>">
                        <?php
                        /**
                         * @deprecated
                         */
                        do_action( 'learn_press_review_order_before_cart_item', $cart_item );

                        /**
                         * @since 3.0.0
                         */
                        do_action( 'learn-press/review-order/before-cart-item', $cart_item, $cart_item_key );
                        ?>
                        <td class="course-name">
                            <div class="media">
                                <div class="media-left">
                                    <a href="<?php the_permalink( $item_id ); ?>">
                                        <img src="<?= $img_url ?>" class="img-responsive"/>
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h1>
                                        <a href="<?php the_permalink( $item_id ); ?>">
                                            <?php echo apply_filters( 'learn-press/review-order/cart-item-name', $_course->get_title(), $cart_item, $cart_item_key ); ?>
                                        </a>
                                    </h1>
                                </div>
                            </div>
<!--                            --><?php //echo apply_filters( 'learn-press/review-order/cart-item-quantity', ' <strong class="course-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
                        </td>
                        <td class="course-total">
                            <p>
                                <?php echo apply_filters( 'learn-press/review-order/cart-item-subtotal', $cart->get_item_subtotal( $_course, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                            </p>
                        </td>
                        <?php
                        /**
                         * @since 3.0.0
                         */
                        do_action( 'learn-press/review-order/after-cart-item', $cart_item, $cart_item_key );

                        /**
                         * @deprecated
                         */
                        do_action( 'learn_press_review_order_after_cart_item', $cart_item );
                        ?>
                    </tr>
                    <?php
                }
            }
        }

        /**
         * @since 3.0.0
         */

        do_action( 'learn-press/review-order/after-cart-contents' );

        /**
         * @deprecated
         */
        do_action( 'learn_press_review_order_after_cart_contents' );

        ?>

        </tbody>
    </table>
</div>