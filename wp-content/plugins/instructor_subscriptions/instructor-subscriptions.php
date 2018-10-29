<?php
/*
Plugin Name: Instructor Subscriptions
Plugin URI:
description:
Version: 1.0
Author: HZTech
Author URI:
License:
*/

require_once('page_templater.php');


add_action( 'wp_head','my_flush_rules' );

// flush_rules() if our rules are not yet included
function my_flush_rules(){
    $rules = get_option( 'rewrite_rules' );
//    var_dump($rules);die;
    if(isset($_GET['key']))
    {
        global $wpdb;
        $order_id = get_query_var('lp-order-received');
        $results = $wpdb->get_row("SELECT order_item_id FROM {$wpdb->prefix}learnpress_order_items WHERE order_id = '$order_id'", OBJECT);
        $order_item_id = $results -> order_item_id;

        $results = $wpdb->get_row("SELECT meta_value FROM {$wpdb->prefix}learnpress_order_itemmeta WHERE learnpress_order_item_id = '$order_item_id' AND meta_key = '_course_id'");
        $course_id = $results -> meta_value;

        $course = get_post($course_id);

        $price = get_post_meta($course_id, '_lp_price' ,true);
        if(!empty($price))
        {
            $price = floatval($price);
            $instructor_price = $price;
            $comission = $price * 5 /100;

            $user_id = $course -> post_author;
            $wallet = get_user_meta($user_id, 'wallet', true);
            $pkg_name = get_user_meta($user_id, 'pkg_name', true);
            if($pkg_name == 'basic'){
                $instructor_price = $price - $comission;
            }
            $wallet = floatval($wallet) + $instructor_price;
            update_user_meta($user_id, 'wallet', $wallet);
        }

        //        var_dump($course);die;;



//        var_dump(get_query_var('lp-order-received') . "abc");
    }
}

function save_data() {
    if(isset($_GET['paypal']) && $_GET['paypal'] == 'notify') {
        global $wpdb;
        $tablename = $wpdb->prefix. 'orders';
        $data = json_encode($_POST);
        $custom = json_decode($_POST['custom'], true);
        $user_id = $custom['user_id'];
        $pkg_name = $custom['pkg_name'];

        if(isset($_POST['payment_status']))
        {
            $mydata =  $wpdb->insert($tablename, array(
                    'user_id' => $user_id,
                    'pkg_name' => $pkg_name,
                    'transaction' => $data
                )
            );
            update_user_meta($user_id, 'pkg_name', $pkg_name);
            update_user_meta($user_id, 'last_transaction', date("Y-m-d h:i:sa"));
        }


        file_put_contents("paypal_data.txt", $mydata);

    }
    elseif(isset($_GET['paypal']) && $_GET['paypal'] == 'return')
    {
        echo "Thankyou";
        exit;
    }
}

add_action( 'init', 'save_data' );

function payment_details($atts){
    $saved = false;
    if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'save_settings')){
        $post = array(
            'paypal_address' => $_POST['paypal_address']
        );
        $user_id = get_current_user_id();
        $saved = update_user_meta($user_id, 'paypal_address', $_POST['paypal_address']);
    }
    ?>
    <div class="container">
        <div class="row">
            <?php if($saved) {?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Your settings have been saved successfully.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <form method="post">
                <div class="form-group">
                    <label for="exampleInputEmail1">Paypal Email address</label>
                    <input name="paypal_address" type="email" class="form-control" aria-describedby="emailHelp" placeholder="Enter Paypal Email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <?php
                wp_nonce_field('save_settings');
                ?>
                <button type="submit" class="btn btn-primary" name="gg">Submit</button>
            </form>
        </div>
    </div>
    <?php
}
add_shortcode( 'payment-settings', 'payment_details' );



function withdrawal()
{
    $user_id = get_current_user_id();
    $wallet = get_user_meta($user_id, 'wallet', true);
    $wallet = floatval($wallet);
    $request = false;
    $error = false;
    if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'withdraw_amount'))
    {
        $req_amount = $_POST['amount'];
        if($wallet >= $req_amount) {
            $post = array(
                'user_id' => get_current_user_id(),
                'amount' => $_POST['amount']
            );
            global $wpdb;
            $table_name = $wpdb->prefix . "withdrawal";
            $wpdb->insert($table_name, $post);
            $request = true;
            $wallet = $wallet - $req_amount;
            update_user_meta($user_id, 'wallet', $wallet);
        }else{
            $error = "Insufficient Balance.";
        }
    }
    ?>
    <div class="container">
        <div class="row">
            <?php if($error) {?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?= $error; ?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <?php if($request) {?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Withdrawal request has been sent. You'll be updated shortly.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <form method="post">
                <div class="form-group">
                    <label>Amount</label>
                    <input name="amount" type="number" class="form-control" aria-describedby="withdraw" placeholder="Enter Amount">
                    <small id="withdraw" class="form-text text-muted">Amount you wish to withdraw.</small>
                </div>
                <?php
                wp_nonce_field('withdraw_amount');
                ?>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <?php
}
add_shortcode( 'amount-withdrawal', 'withdrawal' );


function wp_register_withdrawal_list_page(){
    add_menu_page('Withdrawal List', 'Withdrawal', 'manage_options', 'withdrawal-list', 'create_withdrawal_list','dashicons-list-view',110);
}
add_action('admin_menu', 'wp_register_withdrawal_list_page');
function create_withdrawal_list()
{
    global $wpdb;
    if(isset($_GET['status']) && isset($_GET['request_id'])){
        if($_GET['status'] == 'approve'){
            $wpdb->update("{$wpdb->prefix}withdrawal", array('status' => 'approved'), array('id' => $_GET['request_id']));
        }elseif ($_GET['status'] == 'decline'){
            $wpdb->update("{$wpdb->prefix}withdrawal", array('status' => 'declined'), array('id' => $_GET['request_id']));
        }
    }
//    var_dump($update);die;
    wp_register_style('bootstrap.min', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
    wp_enqueue_style('bootstrap.min');
    $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}withdrawal", OBJECT);
//    var_dump($results);
    ?>
    <div class="container">
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
                    <th scope="col">Withdrawal time</th>
                    <th scope="col">Transaction time</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($results as $k => $v) {
                    $user_name = get_user_by('id', $v->user_id);
                    ?>
                    <tr>
                        <td><?= $v->id; ?></td>
                        <td><?= $v->user_id; ?></td>
                        <td><?= $user_name->user_nicename; ?></td>
                        <td><?= $v->amount; ?></td>
                        <td><?= $v->withdraw_time; ?></td>
                        <td><?= $v->transaction_time; ?></td>
                        <td><?= $v->status; ?></td>
                        <?php
                        if($v->status == 'approved' || $v->status == 'declined'){
                            ?>
                            <script>
                                jQuery(function ($) {
                                    $(".approve").addClass("disabled");
                                    $(".decline").addClass("disabled");
                                });
                            </script>
                            <?php
                        }
                        ?>
                        <td>
                            <a href="<?php echo add_query_arg(array('status' => 'approve', 'request_id' => $v->id)) ?>"
                               class="approve btn btn-success" data-toggle="modal" data-target="#request">Approve</a>
                            /
                            <a href="<?php echo add_query_arg(array('status' => 'decline', 'request_id' => $v->id)) ?>"
                               class="decline btn btn-danger" data-toggle="modal" data-target="#request">Decline</a>
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
                        <button type="button" class="yes btn btn-success">Yes</button>
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
            $( ".approve" ).click(function() {
                href = $(this).attr('href');
            });
            $( ".decline" ).click(function() {
                href = $(this).attr('href');
            });
            $(".modal-footer .yes").click(function () {
                document.location.href = href;
            });
        });
    </script>
    <?php

}


