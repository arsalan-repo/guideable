<?php
/**
 * Template for displaying user profile tabs.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/profile/tabs.php.
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
?>

<div id="learn-press-profile-nav">
    <div class="container">


		<?php do_action( 'learn-press/before-profile-nav', $profile ); ?>

        <ul class="nav justify-content-center">

			<?php
			foreach ( $profile->get_tabs()->tabs() as $tab_key => $tab_data ) {

				/**
				 * @var $tab_data LP_Profile_Tab
				 */
				if ( $tab_data->is_hidden() || ! $tab_data->user_can_view() ) {
					continue;
				}

				$slug        = $profile->get_slug( $tab_data, $tab_key );
				$link        = $profile->get_tab_link( $tab_key, true );
				$tab_classes = array( esc_attr( $tab_key ) );
				/**
				 * @var $tab_data LP_Profile_Tab
				 */
				$sections = $tab_data->sections();

				if ( $sections && sizeof( $sections ) > 1 ) {
					$tab_classes[] = 'has-child';
				}

				if ( $profile->is_current_tab( $tab_key ) ) {
					$tab_classes[] = 'active';
				} ?>

                <li class="nav-item <?php echo join( ' ', $tab_classes ) ?>">
                    <!--tabs-->
                    <a class="nav-link" href="<?php echo esc_url( $link ); ?>"
                       data-slug="<?php echo esc_attr( $link ); ?>">
						<?php echo apply_filters( 'learn_press_profile_' . $tab_key . '_tab_title', esc_html( $tab_data['title'] ), $tab_key ); ?>
                    </a>
                    <!--section-->

					<?php if ( $sections && sizeof( $sections ) > 1 ) { ?>

                        <div class="dropdown-menu">
							<?php foreach ( $sections as $section_key => $section_data ) {

								$classes = array( esc_attr( $section_key ) );
								if ( $profile->is_current_section( $section_key, $section_key ) ) {
									$classes[] = 'active';
								}

								$section_slug = $profile->get_slug( $section_data, $section_key );
								$section_link = $profile->get_tab_link( $tab_key, $section_slug );
								?>

                                <!--                            <li class="--><?php //echo join( ' ', $classes ); ?><!--">-->
                                <a class="dropdown-item"
                                   href="<?php echo $section_link; ?>"><?php echo $section_data['title']; ?></a>
                                <!--                            </li>-->

							<?php } ?>

                        </div>

					<?php } ?>

                </li>
			<?php } ?>
            <?php
            $instructor_email = $profile->get_user()->get_email();
            $email = wp_get_current_user();
            $current_user_email = $email->user_email;
            if($instructor_email == $current_user_email){
            ?>
            <li class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#request_payment">Withdraw Request</a>
            </li>
            <?php } ?>
        </ul>

        <?php
        $instructor_email = $profile->get_user()->get_email();
        $email = wp_get_current_user();
        $current_user_email = $email->user_email;
        if($instructor_email == $current_user_email){
        ?>
        <strong id="wallet-amount"><?= do_shortcode( '[wallet]' ) ?></strong>
        <?php } ?>

		<?php do_action( 'learn-press/after-profile-nav', $profile ); ?>
    </div>
</div>
<style>
    .modal-footer button{
        font-size: 12px;
    }
</style>
<!-- Modal -->
<div class="modal fade" id="request_payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Withdraw Request</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= do_shortcode("[amount-withdrawal]") ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>