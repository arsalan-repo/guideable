<style>
    .learn-press-checkout-comment h4{
        font-weight: bold;
        margin: 0 0 10px 0;
    }
    .learn-press-checkout-comment textarea{
        border: 1px solid #e8e6e6;
        border-radius: 5px;
        padding: 10px;
    }
    .order_total th,.order_total td{
        padding: 20px;
        border-top: none!important;
        border-bottom: 1px solid #e6e2e2;
        font-size: 13px;
    }
    .order_total{
        border: 1px solid #e6e2e2;
        padding: 0px;
        margin: 0!important;
        border-radius: 5px;
    }
    tr.order-total th, tr.order-total td{
        border-bottom: 0;
    }
    #learn-press-payment{
        padding: 0px 20px 20px 20px;
    }
    #learn-press-payment .payment-methods .lp-payment-method.selected > label{
        background: transparent!important;
    }
    #learn-press-payment .payment-methods .lp-payment-method > label{
        padding: 0!important;
    }
    .learnpress-page .lp-button{
        background: linear-gradient(to right, #f17325, #be308c)!important;
        color: #fff;
        border: none;
        border-radius: 5px;
        width: 100%;
        font-size: 14px;
    }
    .payment-method-form .payment_method_paypal, #learn-press-payment-method-paypal img{
        display: none!important;
    }

</style>
<?php
/**
 * Template for displaying payment form for checkout page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/checkout/payment.php.
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

<?php
$order_button_text            = apply_filters( 'learn_press_order_button_text', __( 'Place order', 'learnpress' ) );
$order_button_text_processing = apply_filters( 'learn_press_order_button_text_processing', __( 'Processing', 'learnpress' ) );
$show_button                  = true;
$available_gateways           = ! empty( $available_gateways ) ? $available_gateways : false;
$count_gateways               = $available_gateways ? sizeof( $available_gateways ) : 0;
?>

<?php $cart = learn_press_get_checkout_cart(); ?>
<div class="row">
    <div class="col-md-8">
        <div class="learn-press-checkout-comment">
            <h4><?php _e( 'Additional Information', 'learnpress' ); ?></h4>
            <textarea name="order_comments" class="order-comments" placeholder="<?php _e( 'Note to administrator', 'learnpress' ); ?>"></textarea>
        </div>
    </div>
    <div class="col-md-4">
        <div class="order_total">
            <table class="table">
                <tbody>
                <tr class="cart-subtotal">

                    <?php
                    /**
                     * @since 3.0.0
                     */
                    do_action( 'learn-press/review-order/before-subtotal-row' );
                    ?>

                    <th><?php _e( 'Subtotal', 'learnpress' ); ?></th>
                    <td><?php echo $cart->get_subtotal(); ?></td>

                    <?php
                    /**
                     * @since 3.0.0
                     */
                    do_action( 'learn-press/review-order/after-subtotal-row' );
                    ?>
                </tr>

                <?php
                /**
                 * @deprecated
                 */
                do_action( 'learn_press_review_order_before_order_total' );

                /**
                 * @since 3.0.0
                 */
                do_action( 'learn-press/review-order/before-order-total' );

                ?>

                <tr class="order-total">
                    <?php
                    /**
                     * @since 3.0.0
                     */
                    do_action( 'learn-press/review-order/before-total-row' );
                    ?>

                    <th><?php _e( 'Total', 'learnpress' ); ?></th>
                    <td><?php echo $cart->get_total(); ?></td>

                    <?php
                    /**
                     * @since 3.0.0
                     */
                    do_action( 'learn-press/review-order/after-total-row' );
                    ?>
                </tr>

                <?php

                /**
                 * @since 3.0.0
                 */
                do_action( 'learn-press/review-order/after-order-total' );

                /**
                 * @deprecated
                 */
                do_action( 'learn_press_review_order_after_order_total' );

                ?>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
            <div id="learn-press-payment" class="learn-press-checkout-payment">

                <?php if ( LP()->cart->needs_payment() ) { ?>

                    <?php if ( ! $count_gateways ) { ?>

                        <?php $show_button = false; ?>

                        <?php if ( $message = apply_filters( 'learn_press_no_available_payment_methods_message', __( 'No payment method is available.', 'learnpress' ) ) ) { ?>
                            <?php learn_press_display_message( $message, 'error' ); ?>
                        <?php } ?>

                    <?php } else { ?>

                        <h4><?php _e( 'Payment Method', 'learnpress' ); ?></h4>

                        <?php do_action( 'learn-press/before-payment-methods' ); ?>

                        <ul class="payment-methods">

                            <?php
                            /**
                             * @deprecated
                             */
                            do_action( 'learn_press_before_payments' );

                            /**
                             * @since 3.0.0
                             */
                            do_action( 'learn-press/begin-payment-methods' );
                            ?>

                            <?php $order = 1;
                            foreach ( $available_gateways as $gateway ) {
                                if ( $order == 1 ) {
                                    learn_press_get_template( 'checkout/payment-method.php', array(
                                        'gateway'  => $gateway,
                                        'selected' => $gateway->id
                                    ) );
                                } else {
                                    learn_press_get_template( 'checkout/payment-method.php', array(
                                        'gateway'  => $gateway,
                                        'selected' => ''
                                    ) );
                                }
                                $order ++;
                            } ?>

                            <?php
                            /**
                             * @since 3.0.0
                             */
                            do_action( 'learn-press/end-payment-methods' );

                            /**
                             * @deprecated
                             */
                            do_action( 'learn_press_after_payments' );
                            ?>

                        </ul>

                        <?php do_action( 'learn-press/after-payment-methods' ); ?>

                    <?php } ?>

                <?php } ?>

                <?php do_action( 'learn-press/payment-form' ); ?>

                <?php if ( $show_button ) { ?>

                    <div id="checkout-order-action" class="place-order-action">

                        <?php
                        // @deprecated
                        do_action( 'learn_press_order_before_submit' );

                        /**
                         * @since 3.0.0
                         */
                        do_action( 'learn-press/before-checkout-submit-button' );
                        ?>

                        <?php echo apply_filters( 'learn_press_order_button_html',
                            sprintf(
                                '<button type="submit" class="lp-button button alt" name="learn_press_checkout_place_order" id="learn-press-checkout-place-order" data-processing-text="%s" data-value="%s">%s</button>',
                                esc_attr( $order_button_text_processing ),
                                esc_attr( $order_button_text ),
                                esc_attr( $order_button_text )
                            )
                        );
                        ?>

                        <?php
                        /**
                         * @since 3.0.0
                         */
                        do_action( 'learn-press/after-checkout-submit-button' );

                        // @deprecated
                        do_action( 'learn_press_order_after_submit' );
                        ?>

                        <?php if ( ! is_user_logged_in() ) { ?>
                            <button type="button" class="lp-button lp-button-guest-checkout" id="learn-press-button-guest-checkout-back"><?php _e( 'Back', 'learnpress' ); ?></label></button>
                        <?php } ?>

                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>
