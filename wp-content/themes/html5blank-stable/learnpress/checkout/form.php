<?php
/**
 * Template for displaying checkout form.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/checkout/form.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined('ABSPATH') || exit();
?>

<?php learn_press_print_messages(); ?>

<?php $checkout = LP()->checkout(); ?>

<?php
/**
 * @deprecated
 */
do_action('learn_press_before_checkout_form', $checkout);
?>

<?php
/**
 * @since 3.0.0
 *
 * @see   learn_press_checkout_form_login()
 * @see   learn_press_checkout_form_register()
 */
do_action('learn-press/before-checkout-form');
?>
<form method="post" id="learn-press-checkout" name="learn-press-checkout"
      class="learn-press-checkout checkout<?php echo !is_user_logged_in() ? " guest-checkout" : ""; ?>"
      action="<?php echo esc_url(learn_press_get_checkout_url()); ?>" enctype="multipart/form-data">

    <?php
    /**
     * @deprecated
     */
    do_action('learn_press_checkout_before_order_review');
    ?>

    <?php
    // @since 3.0.0
    do_action('learn-press/before-checkout-order-review');
    ?>

    <div id="learn-press-order-review" class="checkout-review-order">

        <?php
        /**
         * @deprecated
         */
        do_action('learn_press_checkout_order_review');

        /**
         * @since 3.0.0
         *
         * @see   learn_press_order_review()
         * @see   learn_press_order_comment()
         * @see   learn_press_order_payment()
         */
        do_action('learn-press/checkout-order-review');
        ?>

    </div>

    <?php
    // @since 3.0.0
    do_action('learn-press/after-checkout-order-review');

    /**
     * @deprecated
     */
    do_action('learn_press_checkout_after_order_review');
    ?>
    <!--
		<?php if (!is_user_logged_in()) { ?>
            <p class="button-cancel-guest-checkout">
                <button type="button" class="lp-button lp-button"
                        id="learn-press-button-cancel-guest-checkout"><?php _e('Back', 'learnpress'); ?></label></button>
            </p>
		<?php } ?>
        -->
</form>

<?php if (!is_user_logged_in() && !LP()->checkout()->is_enable_login() && !LP()->checkout()->is_enable_register()) { ?>
    <p><?php printf(__('Please login to continue checkout. %s', 'learnpress'), sprintf('<a href="%s">%s</a>', learn_press_get_login_url(), __('Login?', 'learnpress'))); ?></p>
<?php } ?>

<?php if (!is_user_logged_in() && LP()->checkout()->is_enable_guest_checkout()) { ?>

    <p class="button-continue-guest-checkout">
        <button type="button" class="lp-button lp-button-guest-checkout"
                id="learn-press-button-guest-checkout"><?php _e('Continue checkout as Guest?', 'learnpress'); ?></label></button>
    </p>
<?php } ?>

<?php

// @since 3.0.0
do_action('learn-press/after-checkout-form');

/**
 * @deprecated
 */
do_action('learn_press_after_checkout_form', $checkout);

?>
<style>
    .below_checkout{
        margin: 50px 0;
    }
    .below_checkout h1{
        font-size: 40px!important;
    }
    .below_checkout p{
        font-size: 16px!important;
    }
    .content-checkout a{
        border: 1px solid #c3bebe;
        padding: 10px 40px;
        border-radius: 5px;
    }
    .content-checkout a:hover{
        text-decoration: none!important;
    }
    .below_checkout .heading hr{
        width: 20%;
        background: brown;
    }
    .line hr{
        margin: 50px 0!important;
    }
    .below_checkout .heading{
        margin-bottom: 20px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
</style>

<div class="line">
    <hr/>
</div>

<div class="below_checkout">
    <div class="row">
        <div class="heading text-center">
            <h1>We're here to help</h1>
            <div class="below_heading">
                <hr/>
            </div>
        </div>
    </div>
    <div class="content-checkout text-center">
        <p>
            We have instructor support team in place to guide you through your entire process. If you need assis-<br/>
            tance, use the access button below.
        </p>
        <br/>
        <br/>
        <p>
            <a href="#" class="contact-button">Contact Us</a>
        </p>
    </div>
</div>
