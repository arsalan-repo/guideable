<?php
/**
 * Template for displaying user profile cover image.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/profile/profile-cover.php.
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

$user = $profile->get_user();
?>

<div id="learn-press-profile-header" class="lp-profile-header">
<div class="container">
   <div class="lp-profile-cover">
        <div class="lp-profile-avatar">
			<?php echo $user->get_profile_picture("", 270); ?>
            <span class="profile-name"><?php echo $user->get_display_name(); ?></span>
			<div class="social-links">
			<a href="#"><i class="fa fa-facebook"></i> </a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-instagram"></i></a>
		<a href="#">	<i class="fas fa-globe-asia"></i></a>
			</div>
            <?= do_shortcode('[instructor_header_details]'); ?>	
			
        </div>
		
    </div>
</div>
 
</div>


