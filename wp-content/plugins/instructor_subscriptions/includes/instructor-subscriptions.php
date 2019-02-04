<?php

class InstructorSubscriptions
{
    public function __construct()
    {
//      Shortcodes
        $shortcode = new InstructorShortcodes();
        add_shortcode('payment-settings', array($shortcode, 'payment_details'));
        add_shortcode('amount-withdrawal', array($shortcode, 'withdrawal'));
        add_shortcode('wallet', array($shortcode, 'show_wallet_amount'));
        add_shortcode('list_categories', array($shortcode, 'course_categories'));
        add_shortcode('list_courses', array($shortcode, 'best_selling_course'));
        add_shortcode('course_rating', array($shortcode, 'course_rating'));
        add_shortcode('course_reviews', array($shortcode, 'course_reviews'));
        add_shortcode('list_course_categories', array($shortcode, 'list_course_categories'));
        add_shortcode('popular_course', array($shortcode, 'popular_course'));
        add_shortcode('pop_course', array($shortcode, 'pop_course'));
        add_shortcode('instructor_header_details', array($shortcode, 'instructor_header_details'));
        add_shortcode('list_top_rated_courses', array($shortcode, 'top_rated_courses'));

//      Fubnctions
        add_action('wp_head', array($this, 'my_flush_rules'));
        add_action('init', array($this, 'save_data'));
        add_action('admin_menu', array($this, 'wp_register_withdrawal_list_page'));
        add_action("wp_ajax_notify_transaction", array($this, "notify_transaction"));
        add_action("wp_ajax_nopriv_notify_transaction", array($this, "notify_transaction"));
//        add_action('save_post_lp_course', array($this, 'publish_to_review_course'), 10, 3);
        add_action('post_updated', array($this, 'publish_to_review_course'), 10, 3);
        add_action('admin_head', array($this, 'remove_instructor_menu_toolbar'), 10);
        add_action('admin_enqueue_scripts', array($this, 'ds_admin_theme_style'));
        add_action('login_enqueue_scripts', array($this, 'ds_admin_theme_style'));
        add_filter('screen_options_show_screen', array($this, 'remove_screen_options'));
        add_action('admin_head', array($this, 'back_to_website'));
        add_action('learn-press/after-form-register-fields', array($this, 'register_form_fields'));
        add_filter('learn-press/register-request-result', array($this, 'select_user_type_two'));
        add_action('pmpro_after_checkout', array($this, 'change_user_type'), 10, 2);
        add_action("pmpro_membership_post_membership_expiry", array($this, "membership_expiry"), 10, 2);
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_styles'));
        add_action('init', array($this, 'handle_withdrawal_request'));
        add_filter('wp_mail_content_type', array($this, 'wpse27856_set_content_type'));
        add_filter('learn-press/after-form-login-fields', array($this, 'redirect_to_manage_courses'));
        add_filter('wp', array($this, 'check_role_for_redirection'));
        add_action('admin_head', array($this, 'manage_courses'));
//        add_action('post_updated', array($this, 'postPending'), 10, 3);

    }

    function handle_withdrawal_request()
    {
        if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'withdraw_amount')) {
            $user_id = get_current_user_id();
            $wallet = get_user_meta($user_id, 'wallet', true);
            $wallet = floatval($wallet);
            $error = $success = false;
            $paypal_address = get_user_meta($user_id, 'paypal_address', true);
            $req_amount = $_POST['amount'];

            if (empty($paypal_address)) {
                $error = "Kindly enter your Paypal email address in Payment Settings.";
            } elseif ($wallet >= $req_amount) {

                $post = array(
                    'user_id' => get_current_user_id(),
                    'amount' => $_POST['amount'],
                    'paypal_email' => $paypal_address
                );

                global $wpdb;

                $table_name = $wpdb->prefix . "withdrawal";
                $wpdb->insert($table_name, $post);
                $wallet = $wallet - $req_amount;

                update_user_meta($user_id, 'wallet', $wallet);

                $success = "Withdrawal request has been sent. You'll be updated shortly.";
            } else {
                $error = "Insufficient Balance.";
            }

            if ($error) {
                wp_redirect(add_query_arg(['e' => urlencode($error)], get_bloginfo('url') . '/profile'));
                die;
            }

            if ($success) {
                wp_redirect(add_query_arg(['success' => urlencode($success)], get_bloginfo('url') . '/profile'));
                die;
            }
        }
    }

    function filter_media_comment_status($open, $post_id)
    {
        $post = get_post($post_id);
        if ($post->post_type == 'lp_course') {
            return false;
        }
        return $open;
    }

    public function my_flush_rules()
    {
        $rules = get_option('rewrite_rules');
        if (isset($_GET['key'])) {
            global $wpdb;
            $order_id = get_query_var('lp-order-received');
            $results = $wpdb->get_row("SELECT order_item_id FROM {$wpdb->prefix}learnpress_order_items WHERE order_id = '$order_id'", OBJECT);
            $order_item_id = $results->order_item_id;

            $results = $wpdb->get_row("SELECT meta_value FROM {$wpdb->prefix}learnpress_order_itemmeta WHERE learnpress_order_item_id = '$order_item_id' AND meta_key = '_course_id'");
            $course_id = $results->meta_value;

            $course = get_post($course_id);

            $price = get_post_meta($course_id, '_lp_price', true);
            if (!empty($price)) {
                $price = floatval($price);
                $instructor_price = $price;
                $comission = $price * 5 / 100;
                $free_commission = $price * 35 / 100;

                $user_id = $course->post_author;
                $wallet = get_user_meta($user_id, 'wallet', true);
                $pkg_id = get_user_meta($user_id, 'pkg_id', true);
                if ($pkg_id == '1') {
                    $instructor_price = $price - $comission;
                } elseif ($pkg_id == '3') {
                    $instructor_price = $price - $free_commission;
                }
                $wallet = floatval($wallet) + $instructor_price;
                update_user_meta($user_id, 'wallet', $wallet);
            }
        }
    }

    public function save_data()
    {
        if (isset($_GET['paypal']) && $_GET['paypal'] == 'notify') {
            if (isset($_POST['payment_status'])) {
                global $wpdb;
                $tablename = $wpdb->prefix . 'orders';
                $data = json_encode($_POST);
                $custom = json_decode(base64_decode($_POST['custom']), true);
                $user_id = $custom['user_id'];
                $pkg_name = $custom['pkg_name'];
                $my_values = array(
                    'user_id' => $user_id,
                    'pkg_name' => $pkg_name,
                    'transaction' => $data
                );
                $mydata = $wpdb->insert($tablename, $my_values);
                update_user_meta($user_id, 'pkg_name', $pkg_name);
                update_user_meta($user_id, 'last_transaction', date("Y-m-d h:i:sa"));
                file_put_contents("paypal_data1.txt", json_encode($mydata) . PHP_EOL . json_encode($custom) . PHP_EOL);
            }


        } elseif (isset($_GET['paypal']) && $_GET['paypal'] == 'return') {
            echo "Thankyou";
            exit;
        }
    }

    public function wp_register_withdrawal_list_page()
    {
        add_menu_page('Withdrawal List', 'Withdrawal', 'manage_options', 'withdrawal-list', array($this, 'create_withdrawal_list'), 'dashicons-list-view', 110);
    }


    public function create_withdrawal_list()
    {
        global $wpdb;
        if (isset($_GET['status']) && isset($_GET['request_id'])) {
            if ($_GET['status'] == 'approve') {
                $wpdb->update("{$wpdb->prefix}withdrawal", array('status' => 'approved'), array('id' => $_GET['request_id']));
            } elseif ($_GET['status'] == 'decline') {
                $wpdb->update("{$wpdb->prefix}withdrawal", array('status' => 'declined'), array('id' => $_GET['request_id']));
            }
        }
        wp_register_style('bootstrap.min', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
        wp_enqueue_style('bootstrap.min');
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}withdrawal", OBJECT);
        ?>
        <div class="container">
            <style>
                .container {
                    max-width: 1370px !important;
                }
            </style>
            <div class="row">
                <h2>Withdrawal List</h2>
                <Br/>
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">User ID</th>
                        <th scope="col">User Name</th>
                        <th scope="col">Withdrawal amount</th>
                        <th scope="col">Paypal Email</th>
                        <th scope="col">Withdrawal time</th>
                        <th scope="col">Transaction time</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($results as $k => $v) {
                        $user_name = get_user_by('id', $v->user_id);
                        $paypal_email = get_user_meta($v->user_id, 'paypal_address', true);
                        ?>
                        <tr>
                            <td><?= $v->id; ?></td>
                            <td><?= $v->user_id; ?></td>
                            <td><?= $user_name->user_nicename; ?></td>
                            <td><?= $v->amount; ?></td>
                            <td><?= $paypal_email; ?></td>
                            <td><?= $v->withdraw_time; ?></td>
                            <td><?= $v->transaction_time; ?></td>
                            <td><?= $v->status; ?></td>
                            <td>
                                <?php $disabled = $v->status != 'pending'; ?>
                                <a href="<?php echo add_query_arg(array('status' => 'approve', 'request_id' => $v->id)) ?>"
                                   class="approve btn btn-success <?= $disabled ? "disabled" : '' ?>"
                                   data-toggle="modal" data-target="#request" data-requestid="<?= $v->id; ?>"
                                   data-email="<?= $paypal_email; ?>" data-amount="<?= $v->amount; ?>">Approve</a>
                                /
                                <a href="<?php echo add_query_arg(array('status' => 'decline', 'request_id' => $v->id)) ?>"
                                   class="decline btn btn-danger <?= $disabled ? "disabled" : '' ?>" data-toggle="modal"
                                   data-target="#request">Decline</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal fade" id="request" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Approve</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to approve the request?
                        </div>
                        <div class="modal-footer">
                            <FORM action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
                                <INPUT TYPE="hidden" name="cmd" value="_xclick">
                                <INPUT TYPE="hidden" name="charset" value="utf-8">
                                <INPUT TYPE="hidden" NAME="return"
                                       value="http://124.47.158.69/wp-admin/admin.php?page=withdrawal-list">
                                <input type="hidden" name="notify_url"
                                       value="http://124.47.158.69/wp-admin/admin-ajax.php?action=notify_transaction"/>
                                <INPUT TYPE="hidden" NAME="currency_code" value="USD">
                                <input type="hidden" name="item_name" value="Withdrawal Amount">
                                <input type="hidden" id="email" name="business" value="">
                                <input type="hidden" id="amount" name="amount" value="">
                                <input type="hidden" id="request_id" name="custom" value="">
                                <button type="submit" class="yes btn btn-success">Yes</button>
                            </FORM>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        //Google Jquery
        wp_register_script('jquery.min', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js');
        wp_enqueue_script('jquery.min');
        //Bootstrap Scripts
        wp_register_script('bootstrap.min', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js');
        wp_enqueue_script('bootstrap.min');
        ?>
        <script type="text/javascript">
            var href = false;
            jQuery(function ($) {
                $(".approve").click(function () {
                    var email = $(this).attr('data-email');
                    var amount = $(this).attr('data-amount');
                    var request_id = $(this).attr('data-requestid');
                    $("#email").val(email);
                    $("#amount").val(amount);
                    $("#request_id").val(request_id);
                });

                $(".decline").click(function () {
                    href = $(this).attr('href');
                    document.location.href = href;
                });
            });
        </script>
        <?php

    }

    public function notify_transaction()
    {
        if (isset($_POST['payment_status']) && $_POST['payment_status'] == 'Completed') {
            $req_id = $_POST['custom'];
            $time = $_POST['payment_date'];
            if ($req_id) {
                global $wpdb;
                $table_name = $wpdb->prefix . "withdrawal";
                $wpdb->update("$table_name", array('status' => 'approved', 'transaction_time' => $time), array('id' => $req_id));
            }

        }
    }

    public function publish_to_review_course($post_id, $post_before, $post_after)
    {
        global $wpdb;
        if (empty($post_id) || empty($post_before)) {
            return;
        }
        $post_type = $post_before->post_type;
        if ($post_type !== "lp_course") {
            return;
        }
        $current_user = get_current_user_id();
        $is_expired = get_user_meta($current_user, 'is_expired', true);
        $role = get_user_meta($current_user, 'wp_capabilities', true);
        $role = array_keys($role);
        if ($post_type == "lp_course" && in_array('lp_teacher', $role) && (empty($is_expired) || $is_expired == 'yes')) {
            $my_post = array(
                'ID' => $post_id,
                'post_status' => 'pending'
            );

            remove_action('post_updated', array($this, 'publish_to_review_course'), 10, 3);

            $wpdb->update($wpdb->prefix . 'posts', array('post_status' => 'pending'), array('ID' => $post_id));

            add_action('post_updated', array($this, 'publish_to_review_course'), 10, 3);

        }
    }


    public function remove_instructor_menu_toolbar()
    {
        $current_user = get_current_user_id();
        $role = get_user_meta($current_user, 'wp_capabilities', true);
        $role = array_keys($role);
        if (in_array('lp_teacher', $role)) {
            echo "<style>";
            echo "#wpadminbar,#adminmenumain, #wpfooter{display: none;}";
            echo "#wpadminbar, #wpfooter, #menu-dashboard, #toplevel_page_learn_press, #menu-posts, #menu-media, #menu-comments, #menu-posts-html5-blank, #toplevel_page_wpcf7, #menu-users, #menu-tools, #toplevel_page_vc-welcome{display: none;}";
//            echo "#wpcontent, #wpfooter{margin-left : 0!important}";
//            echo "#wpcontent{height : 0!important}";
            echo "</style>";
        }
    }

    public function ds_admin_theme_style()
    {
        if (!current_user_can('manage_options')) {
            echo '<style>.update-nag, .updated, .error, .is-dismissible { display: none; }</style>';
        }
    }

    public function remove_screen_options()
    {
        $current_user = get_current_user_id();
        $role = get_user_meta($current_user, 'wp_capabilities', true);
        $role = array_keys($role);
        if (in_array('lp_teacher', $role)) {
            return false;
        }
    }

    public function back_to_website()
    {
        add_thickbox();
        $user_id = get_current_user_id();
        $user_type = get_user_meta($user_id, 'wp_capabilities', true);
        $user_type = array_keys($user_type);
        if (in_array('lp_teacher', $user_type)) {
            echo "<style>";
            echo "#mybutton, #back_course{text-decoration : none;margin: 0 0 0 20px;padding: 4px 8px;background: #f7f7f7;border: 1px solid #ccc;border-radius: 2px;color: #0073aa;}";
            echo "#mybutton:hover, #back_course:hover{    border-color: #008EC2;background: #00a0d2;color: #fff;}";
            echo ".myalert{    margin: 30px 20px 10px 20px;background: #fff;padding: 20px;font-size: 16px;border-left: 5px solid;border-left-color: #ffb900!important;}";
            echo "</style>";
            echo "<a href='" . get_bloginfo('url') . "/courses" . "' id='mybutton'>";
            echo "Back to Guideable";
            echo "</a>";
            echo "<a href='" . get_bloginfo('url') . "/wp-admin/edit.php?post_type=lp_course" . "' id='back_course'>";
            echo "Back to Courses";
            echo "</a>";
            // Jquery
            ?>
            <style>
                #insert-media-button {
                    background: #008ec2;
                    color: #fff;
                    border: none;
                    padding: 0px 15px;
                }

                #remove-post-thumbnail, #set-post-thumbnail {
                    background: #0073aa;
                    padding: 10px;
                    color: #fff;
                    font-size: 15px;
                    text-decoration: none;
                }

                a.quiz, a.lesson {
                    font-size: 15px;
                    text-decoration: none;
                }

                a.quiz:before {
                    font-family: Dashicons;
                    font-size: 20px;
                    position: relative;
                    top: 8px;
                    content: '\f469';
                    padding: 8px;
                    background: #0073aa;
                    color: #fff;
                    border-radius: 5px;
                    margin: 10px;
                }

                a.lesson:before {
                    font-family: Dashicons;
                    font-size: 20px;
                    position: relative;
                    top: 8px;
                    content: '\f330';
                    padding: 8px;
                    background: #0073aa;
                    color: #fff;
                    border-radius: 5px;
                    margin: 10px;
                }

                #TB_window {
                    height: 140px !important;
                }

                #acf_after_title-sortables {
                    padding: 0 0 6px 0;
                    font-size: 23px;
                    font-weight: 400;
                    color: #000;
                }

                /*#mybutton, #back_course, .page-title-action, #search-submit,#doaction,#post-query-submit, #doaction2, input#save-post, #post-preview, input.button.tagadd,#insert-media-button, #publish, #set-post-thumbnail, button.button.insert-media.add_media {*/
                /*background: linear-gradient(to right, #f17325, #be308c)!important;*/
                /*color: #fff!important;*/
                /*border-radius: 7px!important;*/
                /*padding: 7px 20px!important;*/
                /*border: none!important;*/
                /*box-shadow: none!important;*/
                /*text-shadow: none!important;*/
                /*}*/
                /*#search-submit, #doaction, #doaction2, #post-query-submit, input#save-post, #post-preview, #insert-media-button, #publish, input.button.tagadd, #set-post-thumbnail, button.button.insert-media.add_media {*/
                /*padding: 0px 20px !important;*/
                /*}*/
                /*body.wp-admin.wp-core-ui.js.learnpress.post-new-php.auto-fold.admin-bar.post-type-lp_course.branch-4-9.version-4-9-8.admin-color-fresh.locale-en-us.no-customize-support.sticky-menu.svg, body.wp-admin.wp-core-ui.js.learnpress.edit-php.auto-fold.admin-bar.post-type-lp_course.branch-4-9.version-4-9-8.admin-color-fresh.locale-en-us.no-customize-support.sticky-menu.svg{*/
                /*background: #fff!important;*/
                /*}*/
                span.lp-label-counter, span.comment-count-approved {
                    background: #f17325 !important;
                }

                a.post-com-count.post-com-count-approved:after {
                    border-top: 5px solid #f17325 !important;
                }

                .learn-press-advertisement-slider {
                    display: none;
                }

                span.wp-media-buttons-icon:before {
                    color: #fff !important;
                }
            </style>
            <script>
                jQuery(function ($) {
                    $("body").append('<div id="my-content-id" style="display:none;">\n' +
                        '     <p>\n' +
                        '<div class="section-type" style="width: 100%">' +
                        '          <div style="width : 50%;float: left" \n>' +
                        '          <a href="#" class="quiz">Create a Quiz</a> \n' +
                        '          </div>' +
                        '          <div style="width : 50%; float: left" \n>' +
                        '          <a href="#" class="lesson">Create a Lesson</a> \n' +
                        '          </div>' +
                        '</div>' +
                        '</p>\n' +
                        '</div><a href="#TB_inline?&width=600&height=550&inlineId=my-content-id" id="tooltip" class="thickbox" style="display : none">View my inline content!</a>');

                    $(document).on('click', '.section-type a.quiz', function (e) {
                        e.preventDefault();
                        var id = $("#tooltip").attr('data-id');
                        $("input[data-id='" + id + "']").parents('.new-section-item').find('label.lp_quiz').trigger('click');
                        $("#TB_overlay").trigger('click');
                    });

                    $(document).on('click', '.section-type a.lesson', function (e) {
                        e.preventDefault();
                        var id = $("#tooltip").attr('data-id');
                        $("input[data-id='" + id + "']").parents('.new-section-item').find('label.lp_lesson').trigger('click');
                        $("#TB_overlay").trigger('click');
                    });

                    $(document).on('focus', '.new-section-item .title input[type="text"]', function () {
                        var id = 'popup-' + Math.random();

                        if (!$(this).hasClass("triggered")) {
                            $("#tooltip").trigger('click');
                            $(this).addClass("triggered");
                            $(this).attr('data-id', id);
                            $("#tooltip").attr("data-id", id);
                            //$(this).parents('.new-section-item').find('label.lp_quiz').trigger('click');
                        } else if ($(this).hasClass("triggered")) {
                            $("#tooltip").trigger('click');
                            $(this).addClass("triggered");
                            $(this).attr('data-id', id);
                            $("#tooltip").attr("data-id", id);
                        }
                    });
                    $("#title-prompt-text").text("Enter course title");
                    $("#acf_after_title-sortables").append("Course Description");
                    $("#tagsdiv-course_tag .hndle span").text("What people will Search");
                    $("#title-prompt-text").prop("required", true);
                    $("#_lp_price").attr("placeholder", "Enter course price");
                    $("#_lp_price").prop("required", true);
                    $("#_lp_duration").prop("required", true);
                    $("#publish").click(function () {
                        if ($("#_lp_price").val() == "") {
                            alert('Course price is required');
                        }
                        if ($("#_thumbnail_id").val() == "") {
                            alert('Course image is required');
                        }
                    });
                    $(window).load(function () {
                        $(".title-input").attr("placeholder", "Add Curriculum/Lessons and press Enter");
                        $("#insert-media-button").text("Add Videos/Photos/Music/Documents");
                    })
                });
            </script>
            <?php
        }
        $current_user = get_current_user_id();
        $role = get_user_meta($current_user, 'wp_capabilities', true);
        $role = array_keys($role);
        $membership = get_user_meta($current_user, 'is_expired', true);
        if (in_array('lp_teacher', $role) && empty($membership) || $membership == "yes") {
            ?>
            <div class="myalert alert-warning notice-warning subscription_notify">
                <strong>To publish a course</strong>, Kindly subscribe to a <a
                        href="<?= site_url() . "/membership-account/membership-levels/" ?>">Membership plan</a>
            </div>
        <?php } ?>
        <style>
            #toplevel_page_learn_press .wp-menu-name {
                display: none;
            }
        </style>
        <script>
            jQuery(function ($) {
                var menu_name = $("#toplevel_page_learn_press .wp-menu-name").html();
                menu_name = menu_name.replace('LearnPress', 'Guideable');
                $("#toplevel_page_learn_press .wp-menu-name").html(menu_name);
                $("#toplevel_page_learn_press .wp-menu-name").show();
            });
        </script>
        <?php
    }

    public function register_form_fields()
    {
        ?>
        <div class="regis-choose">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="user_type" id="inlineRadio2" value="subscriber"
                       checked>
                <label class="form-check-label" for="inlineRadio2">Register as Student</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="user_type" id="inlineRadio1" value="lp_teacher">
                <label class="form-check-label" for="inlineRadio1">Register as Instructor</label>
            </div>
        </div>
        <?php
    }

    public function select_user_type_two($result)
    {
        if (isset($result['result']) && $result['result'] == "success") {
            $email = $_POST['reg_email'];
            $user_details = get_user_by('email', $email);
            $user_type = $_POST['user_type'];
            $user_id = $user_details->ID;
            $username = $user_details->display_name;
            $to = $user_details->user_email;
            $subject = "Welcome to Guideable";
            ob_start();
            include('templates/registration_email_template.php');
            $message = ob_get_clean();
            $headers = "";
            wp_mail($to, $subject, $message, $headers);
        }
        return $result;
    }

    public function wpse27856_set_content_type()
    {
        return "text/html";
    }

    public function change_user_type($user_id, $order_detail)
    {
        $user = new \WP_User($user_id);
        $user->set_role("lp_teacher");
        if ($order_detail->gateway == 'free') {
            update_user_meta($user_id, 'pkg_id', $order_detail->membership_id);
            update_user_meta($user_id, 'is_expired', "no");
        } else {
            update_user_meta($user_id, 'pkg_id', $order_detail->membership_level->id);
            update_user_meta($user_id, 'is_expired', "no");
        }
    }

    public function membership_expiry($user_id, $membership_id)
    {
        update_user_meta($user_id, 'is_expired', "yes");
    }

    public function register_plugin_styles()
    {
        wp_register_style('instructor_subscriptions', plugins_url('instructor_subscriptions/includes/assets/css/shortcodes.css'));
        wp_enqueue_style('instructor_subscriptions');
    }

    public function postPending($post_ID)
    {
        if (get_role('author')) {
            //Unhook this function
            remove_action('post_updated', 'postPending', 10, 3);

            return wp_update_post(array('ID' => $post_ID, 'post_status' => 'pending'));

            // re-hook this function
            add_action('post_updated', 'postPending', 10, 3);
        }
    }

    public function redirect_to_manage_courses()
    {
        echo '<input type="hidden" name="redirect_to" value="' . get_bloginfo('wpurl') . '/?check_redirection"/>';
    }

    public function check_role_for_redirection()
    {
        if (isset($_GET['check_redirection'])) {
            $user_details = wp_get_current_user();
//            var_dump($user_details);die;
            $user_id = get_current_user_id();
            $role = get_user_meta($user_id, 'wp_capabilities', true);
            $role = array_keys($role);
            if (in_array('lp_teacher', $role)) {

                wp_redirect(get_bloginfo('wpurl') . '/profile/' . $user_details->user_login . '/courses/owned/');
            } else {
                wp_redirect(get_bloginfo('wpurl') . '/courses/');
            }
        }
    }

    public function manage_courses()
    {
        $user_id = get_current_user_id();
        $role = get_user_meta($user_id, 'wp_capabilities', true);
        $role = array_keys($role);
        if (in_array('lp_teacher', $role)) {
            if (isset($_GET['post'])) {
                $current_user = wp_get_current_user();
                $user_name = $current_user->display_name;
                $course_details = get_post($_GET['post']);
                ?>
                <style>
                    #adminmenu .heading, #adminmenu .heading:hover {
                        margin: 20px 4px;
                        cursor: auto;
                        color: #555d66;
                        font-weight: bold;
                        font-size: 15px;
                    }

                    #adminmenu .child {
                        font-size: 17px !important;
                        margin: 0 10px;
                        padding: 10px 0;
                    }

                    #adminmenu a {
                        color: #555d66 !important;
                    }

                    #adminmenu .child:hover {
                        background: rgba(128, 128, 128, 0.1);
                        padding: 10px 0;
                        color: black;
                    }

                    #adminmenuwrap, #adminmenuback, #adminmenu {
                        background: #f1f1f1 !important;
                    }

                    #adminmenu .active {
                        border-left: 4px solid #555d66;
                        background: rgba(128, 128, 128, 0.1);
                    }

                    #adminmenumain {
                        display: block !important;
                    }

                    .page-title-action {
                        display: none;
                    }

                    #acf_after_title-sortables {
                        padding-top: 15px !important;
                    }

                    /*#wp-content-wrap{*/
                    /*padding-top: 12px!important;*/
                    /*}*/
                    .wp-heading-inline {
                        margin: 0 0 40px 0 !important;
                    }

                    .actionable {
                        padding: 0 0 25px 0;
                        margin: 0;
                    }

                    .actionable a {
                        text-decoration: none;
                        margin: 0 10px 0 0px;
                        padding: 4px 8px;
                        background: #f17325;
                        color: #ffffff;
                        padding: 10px;
                    }

                    #mybutton, #back_course, .subscription_notify {
                        display: none;
                    }

                    #postimagediv .hide-if-no-js {
                        text-align: center;
                    }

                    .top-bar-info {
                        width: 100%;
                        background: #fff;
                        padding: 20px 0 65px 0;
                        margin-bottom: 20px;
                    }

                    .one-half {
                        width: 45%;
                        float: left;
                        padding: 0 30px;
                    }

                    .one-half p {
                        text-align: right;
                    }

                    .one-half button {
                        text-decoration: none;
                        background: #f17325;
                        border-radius: 2px;
                        color: #fff;
                        padding: 15px 30px;
                        margin: -32px -15px;
                        border: none !important;
                    }

                    .one-half h2 {
                        padding: 0 !important;
                        font-size: 20px !important;
                    }

                    #set-post-thumbnail, .insert-media {
                        background: #f17325 !important;
                        border: none !important;
                        color: #fff !important;
                        margin-bottom: 10px !important;
                    }

                    .membership-alert a, .membership-alert a:hover {
                        color: #f17325;
                    }

                    #field-_lp_featured {
                        display: none
                    }

                    #wpwrap {
                        display: none;
                    }
                </style>
                <script>
                    jQuery(document).ready(function () {
                        $('<li class="course_creation_tabs heading"><a>Plan your course</a></li>').appendTo('#adminmenu');
                        $('<li class="course_creation_tabs child structure"><a data-id="#titlediv,#postdivrich,#postimagediv,#course_categorydiv" id="course_structure">Course Structure</a></li>').appendTo('#adminmenu');
                        $('<li class="course_creation_tabs child"><a data-id="#postbox-container-2" id="material_details">Material Details</a></li>').appendTo('#adminmenu');
                        $('<li class="course_creation_tabs heading"><a>Create your content</a></li>').appendTo('#adminmenu');
                        $('<li class="course_creation_tabs child"><a data-id="#admin-editor-lp_course" id="curriculum">Curriculum</a></li>').appendTo('#adminmenu');
                        $('<li class="course_creation_tabs heading"><a>Publish your course</a></li>').appendTo('#adminmenu');
                        $('<li class="course_creation_tabs child"><a data-id="#learn-press-admin-editor-metabox-settings" id="course_general">Course Settings</a></li>').appendTo('#adminmenu');

                        $('#adminmenu li:not(.course_creation_tabs)').remove();

                        $('#adminmenu li:not(.heading) a').click(function (e) {
                            e.preventDefault();
                            $('#adminmenu li:not(.heading)').removeClass('active');
                            $(this).parent().addClass('active');
                            $('.structure').removeClass('active');
                            hideAllTabs();
                            showTabs($(this).data('id'))
                        })

                        $('.structure').addClass('active');

                        function showTabs(id) {
                            $(id).fadeIn(1000);
                        }

                        function hideAllTabs() {
                            $('#titlediv').hide();
                            $('#postdivrich').hide();
                            $('#admin-editor-lp_course').hide();
                            $('#learn-press-admin-editor-metabox-settings').hide();
                            $('#postbox-container-2').hide();
                            $('#submitdiv').hide();
                            $('#course_categorydiv').hide();
                            $('#tagsdiv-course_tag').hide();
                            $('#postimagediv').hide();
                            $('.wp-heading-inline').hide();
                            $('.heading_title').hide();
                            $('#acf_after_title-sortables').hide();
                        }


                        hideAllTabs();
                        showTabs('#titlediv,#postdivrich,#postimagediv,#course_categorydiv');
                        $('<div class="myalert alert-warning notice-warning membership-alert" style="margin: 0 0 20px 0;"><strong>To publish a course</strong>, Kindly subscribe to a <a href="<?= site_url() . "/membership-account/membership-levels/" ?>">Membership plan</a></div>').insertBefore('#post-body');
                        $('<div class="actionable"><a href="<?= site_url() . "/courses" ?>">Back to Guideable</a><a href="<?= site_url() . "/wp-admin/edit.php?post_type=lp_course" ?>">Back to Courses</a></div>').insertBefore('.membership-alert');
                        $('.insert-media').text('Add Videos/Photos/Music/Documents');
                        $('<div class="top-bar-info"><div class="one-half"><h2><?= (empty($course_details->post_title) ? 'Course Name' : ucfirst($course_details->post_title)) ?></h2> <span>by <?= ucfirst($user_name) ?></span></div><div class="one-half"><p><button>Publish Course</button></p></div></div>').insertAfter('.membership-alert');
                        $('#wpwrap').fadeIn(2000);
                        $('.one-half button').click(function () {
                            $('#publish').click();
                        })
                    })
                </script>
                <?php
            }
        }
    }

}

?>