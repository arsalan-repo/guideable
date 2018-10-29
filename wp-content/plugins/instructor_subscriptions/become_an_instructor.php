<?php
/**
 * Template Name: Become an Instructor
 * @package WordPress
 * @subpackage Twenty_Seventeen
 */

get_header(); ?>

    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Basic:</h5>
                                    <p class="card-text">$24.95 per month for</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">8 courses</li>
                                    <li class="list-group-item">5% transaction fees</li>
                                    <li class="list-group-item">Monthly payouts</li>
                                    <li class="list-group-item">Product support</li>
                                    <li class="list-group-item">Integrated affiliate marketing</li>
                                </ul>
                                <div class="card-body">
                                    <FORM action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
                                        <INPUT TYPE="hidden" name="cmd" value="_xclick-subscriptions">
                                        <INPUT TYPE="hidden" name="charset" value="utf-8">
                                        <INPUT TYPE="hidden" NAME="return" value="http://localhost/test/?paypal=return">
                                        <INPUT TYPE="hidden" NAME="currency_code" value="USD">
                                        <input type="hidden" name="business" value="guideablepaypal-facilitator@gmail.com">
                                        <input type="hidden" name="item_name" value="Basic Subscription">
                                        <input type="hidden" name="a3"  value="24.95" />
                                        <input type="hidden" name="t3"  value="D" />
                                        <input type="hidden" name="p3"  value="1" />
                                        <input type="hidden" name="notify_url"  value="http://localhost/test/?paypal=notify" />
                                        <input type="hidden" name="custom"  value='<?= base64_encode(json_encode(array('user_id' => get_current_user_id(), 'pkg_name' => 'basic'))); ?>' />
                                        <input type="hidden" name="src" value="1">
                                        <button type="submit">Subscribe Now</button>
                                    </FORM>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Entrepreneur</h5>
                                    <p class="card-text">$49.95 per month for</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Unlimited courses</li>
                                    <li class="list-group-item">No transaction fees</li>
                                    <li class="list-group-item">Instant payouts</li>
                                    <li class="list-group-item">Product support</li>
                                    <li class="list-group-item">Integrated affiliate marketing</li>
                                    <li class="list-group-item">Listed on preferred courses</li>
                                </ul>
                                <div class="card-body">
                                    <FORM action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
                                        <INPUT TYPE="hidden" name="cmd" value="_xclick-subscriptions">
                                        <INPUT TYPE="hidden" name="charset" value="utf-8">
                                        <INPUT TYPE="hidden" NAME="return" value="http://localhost/test/?paypal=return">
                                        <INPUT TYPE="hidden" NAME="currency_code" value="USD">
                                        <input type="hidden" name="business" value="guideablepaypal-facilitator@gmail.com">
                                        <input type="hidden" name="item_name" value="Entrepreneur Subscription">
                                        <input type="hidden" name="a3"  value="49.95" />
                                        <input type="hidden" name="t3"  value="D" />
                                        <input type="hidden" name="p3"  value="1" />
                                        <input type="hidden" name="notify_url"  value="http://localhost/test/?paypal=notify" />
                                        <input type="hidden" name="custom"  value='<?= json_encode(array('user_id' => get_current_user_id(), 'pkg_name' => 'entrepreneur')); ?>' />
                                        <input type="hidden" name="src" value="1">
                                        <button type="submit">Subscribe Now</button>
                                    </FORM>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- .wrap -->

<?php get_footer();
