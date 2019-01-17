<?php
/**
 * Template for displaying Dashboard of user profile.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/profile/dashboard.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$error = isset($_GET['e']) ? $_GET['e'] : false;
$success = isset($_GET['success']) ? $_GET['success'] : false;
?>
<style>
    .alert{
        width: 100%;
        font-size: 13px;
    }
</style>
<div class="learn-press-profile-dashboard">
<div class="container">
    <div class="row">
        <?php if ( $error ) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?= $error; ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
        <?php if ( $success ) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?= $success ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
    </div>
    <br/>
    <br/>
    <div class="row">
        <div class="col-md-7">
            <?php
            /**
             * Before dashboard
             */
            do_action( 'learn-press/profile/before-dashboard' );

            /**
             * Dashboard summary
             */
            do_action( 'learn-press/profile/dashboard-summary' );

            /**
             * After dashboard
             */
            do_action( 'learn-press/profile/after-dashboard' );

            ?>
        </div>
        <div class="col-md-5">
            <h2>Top Rated Courses</h2>
            <div class="bottom-line">
                <hr/>
            </div>
            <?= do_shortcode('[list_top_rated_courses count=2]'); ?>
        </div>
    </div>
	<br /><br />
	<hr />
			<div class="profile-bottom">
			
				<h2>We're here to help</h2>
				<hr />
				<p>We have an instructor support team in place to guide you through your entire process. If you need assis-<br>tance, use the access button below!</p>
			
				<a href="#" class="button btn">Contact Us </a>
			
			</div>
	
	
	</div>
</div>