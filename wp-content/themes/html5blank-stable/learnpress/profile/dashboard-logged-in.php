<?php
/**
 * Template for displaying message in profile dashboard if user is logged in.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/profile/dashboard-logged-in.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$profile = LP_Profile::instance();

if ( ! $profile->is_current_user() ) {
	return;
}

$user = $profile->get_user();
?>

