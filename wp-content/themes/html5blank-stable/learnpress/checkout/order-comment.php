<!--<style>-->
<!--    .learn-press-checkout-comment h4{-->
<!--        font-weight: bold;-->
<!--        margin: 0 0 10px 0;-->
<!--    }-->
<!--    .learn-press-checkout-comment textarea{-->
<!--        border: 1px solid #e8e6e6;-->
<!--        border-radius: 5px;-->
<!--        padding: 10px;-->
<!--    }-->
<!--    .order_total th,.order_total td{-->
<!--        padding: 20px;-->
<!--        border-top: none!important;-->
<!--        border-bottom: 1px solid #e6e2e2;-->
<!--        font-size: 13px;-->
<!--    }-->
<!--    .order_total{-->
<!--        border: 1px solid #e6e2e2;-->
<!--        padding: 0px;-->
<!--        margin: 0!important;-->
<!--        border-radius: 5px;-->
<!--    }-->
<!--    tr.order-total th, tr.order-total td{-->
<!--        border-bottom: 0;-->
<!--    }-->
<!--</style>-->
<?php
/**
 * Template for displaying order comment.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/checkout/order-comment.php.
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
<?php //$cart = learn_press_get_checkout_cart(); ?>
<!--<div class="row">-->
<!--    <div class="col-md-8">-->
<!--        <div class="learn-press-checkout-comment">-->
<!--            <h4>--><?php //_e( 'Additional Information', 'learnpress' ); ?><!--</h4>-->
<!--            <textarea name="order_comments" class="order-comments" placeholder="--><?php //_e( 'Note to administrator', 'learnpress' ); ?><!--"></textarea>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-md-4">-->
<!--        <div class="order_total">-->
<!--            <table class="table">-->
<!--                <tbody>-->
<!--                <tr class="cart-subtotal">-->
<!---->
<!--                    --><?php
//                    /**
//                     * @since 3.0.0
//                     */
//                    do_action( 'learn-press/review-order/before-subtotal-row' );
//                    ?>
<!---->
<!--                    <th>--><?php //_e( 'Subtotal', 'learnpress' ); ?><!--</th>-->
<!--                    <td>--><?php //echo $cart->get_subtotal(); ?><!--</td>-->
<!---->
<!--                    --><?php
//                    /**
//                     * @since 3.0.0
//                     */
//                    do_action( 'learn-press/review-order/after-subtotal-row' );
//                    ?>
<!--                </tr>-->
<!---->
<!--                --><?php
//                /**
//                 * @deprecated
//                 */
//                do_action( 'learn_press_review_order_before_order_total' );
//
//                /**
//                 * @since 3.0.0
//                 */
//                do_action( 'learn-press/review-order/before-order-total' );
//
//                ?>
<!---->
<!--                <tr class="order-total">-->
<!--                    --><?php
//                    /**
//                     * @since 3.0.0
//                     */
//                    do_action( 'learn-press/review-order/before-total-row' );
//                    ?>
<!---->
<!--                    <th>--><?php //_e( 'Total', 'learnpress' ); ?><!--</th>-->
<!--                    <td>--><?php //echo $cart->get_total(); ?><!--</td>-->
<!---->
<!--                    --><?php
//                    /**
//                     * @since 3.0.0
//                     */
//                    do_action( 'learn-press/review-order/after-total-row' );
//                    ?>
<!--                </tr>-->
<!---->
<!--                --><?php
//
//                /**
//                 * @since 3.0.0
//                 */
//                do_action( 'learn-press/review-order/after-order-total' );
//
//                /**
//                 * @deprecated
//                 */
//                do_action( 'learn_press_review_order_after_order_total' );
//
//                ?>
<!--                </tbody>-->
<!--                <tfoot>-->
<!--                </tfoot>-->
<!--            </table>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
