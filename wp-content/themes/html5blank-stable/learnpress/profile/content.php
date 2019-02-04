<style>
    .popup-modal,
    .modal-box {
        z-index: 900;
    }

    .modal-sandbox {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: transparent;
    }

    .popup-modal {
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        background: rgb(0,0,0);
        background: rgba(0,0,0,.8);
        overflow: auto;
    }

    .modal-box {
        position: relative;
        width: 80%;
        max-width: 920px;
        margin: 100px auto;
        animation-name: modalbox;
        animation-duration: .4s;
        animation-timing-function: cubic-bezier(0,0,.3,1.6);
    }

    .modal-header {
        padding: 20px 40px;
        background: #f37423;
        color: #ffffff;
    }

    .modal-body {
        width: 100%;
        background: #ECEFF1;
        padding: 40px 40px 100px 40px;
    }

    /* Close Button */
    .close-modal {
        text-align: right;
        cursor: pointer;
        background: #fff;
        padding: 5px;
    }

    .emoji{
        font-size: 20px
    }

    .half {
        width: 50%;
        float: left;
    }

    .custom-button{
        background: #908a8e;
        padding: 20px;
        border-radius: 5px;
        color: #fff;
        font-size: 15px!important;
        text-decoration: none;
        margin: auto;
        display: table;
    }

    .custom-button:hover, .custom-button:focus{
        text-decoration: none;
        color : #fff
    }

    /* Animation */
    @-webkit-keyframes modalbox {
        0% {
            top: -250px;
            opacity: 0;
        }
        100% {
            top: 0;
            opacity: 1;
        }
    }

    @keyframes modalbox {
        0% {
            top: -250px;
            opacity: 0;
        }
        100% {
            top: 0;
            opacity: 1;
        }
    }
</style>
<?php
/**
 * Template for displaying user profile content.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/profile/content.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( ! isset( $user ) ) {
	$user = learn_press_get_current_user();
}
  $current_user = get_current_user_id();
        $role = get_user_meta($current_user, 'wp_capabilities', true);
		$role = array_keys($role);
		$is_instructor = in_array('lp_teacher', $role);
$profile = learn_press_get_profile();
$tabs    = $profile->get_tabs();
$current = $profile->get_current_tab();

?>
<div id="learn-press-profile-content" class="lp-profile-content">
<div class="container">
	<?php foreach ( $tabs as $tab_key => $tab_data ) {

		if ( ! $profile->tab_is_visible_for_user( $tab_key ) ) {
			continue;
		}
		?>

        <div id="profile-content-<?php echo esc_attr( $tab_key ); ?>">
		<?php if( $is_instructor && $tab_key == 'courses'){
			?>
			<div class="cust-btn">
		<a href="#" class="create_manage_course">Create/Manage Courses</a>
	</div>
		<?php } ?>

			<?php
			// show profile sections
			do_action( 'learn-press/before-profile-content', $tab_key, $tab_data, $user ); ?>

			<?php if ( empty( $tab_data['sections'] ) ) {
				if ( is_callable( $tab_data['callback'] ) ) {
					echo call_user_func_array( $tab_data['callback'], array( $tab_key, $tab_data, $user ) );
				} else {
					do_action( 'learn-press/profile-content', $tab_key, $tab_data, $user );
				}
			} else {
				foreach ( $tab_data['sections'] as $key => $section ) {
					if ( $profile->get_current_section( '', false, false ) === $section['slug'] ) {
						if ( isset( $section['callback'] ) && is_callable( $section['callback'] ) ) {
							echo call_user_func_array( $section['callback'], array( $key, $section, $user ) );
						} else {
							do_action( 'learn-press/profile-section-content', $key, $section, $user );
						}
					}
				}
			} ?>

			<?php do_action( 'learn-press/after-profile-content' ); ?>
        </div>

	<?php } ?>
</div>
</div>
<div class="popup-modal" id="">
    <div class="modal-sandbox"></div>
    <div class="modal-box">
        <div class="modal-header">
            <h1>Create/Manage Courses</h1>
            <div class="close-modal">&#10006;</div>
        </div>
        <div class="modal-body">
            <div class="half">
                <a href="http://124.47.158.69/how-to-create-manage-courses/" class="custom-button">The How-To Guide</a>
            </div>
            <div class="half">
                <a href="http://124.47.158.69/create-course" class="custom-button">Create/Manage Courses</a>
            </div>
        </div>
    </div>
</div>
</div>