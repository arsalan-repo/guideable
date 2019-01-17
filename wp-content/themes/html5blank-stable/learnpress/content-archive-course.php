<?php
/**
 * Template for displaying archive course content.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-archive-course.php
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

global $post, $wp_query, $lp_tax_query, $wp_query;

/**
 * @deprecated
 */
do_action( 'learn_press_before_main_content' );

/**
 * @since 3.0.0
 */
do_action( 'learn-press/before-main-content' );

/**
 * @deprecated
 */
do_action( 'learn_press_archive_description' );

/**
 * @since 3.0.0
 */
do_action( 'learn-press/archive-description' );


?>
<div class="row">
    <div class="col-md-3">
        <div class="head">
            <h1>Course Categories</h1>
            <div class="hr">
                <hr/>
            </div>
        </div>
        <?php dynamic_sidebar('sidebar-1'); ?>
    </div>
    <div class="col-md-9">
        <?php
        if ( LP()->wp_query->have_posts() ) :

            /**
             * @deprecated
             */
            do_action( 'learn_press_before_courses_loop' );

            /**
             * @since 3.0.0
             */
            do_action( 'learn-press/before-courses-loop' );

            learn_press_begin_courses_loop();

            while ( LP()->wp_query->have_posts() ) : LP()->wp_query->the_post();

                learn_press_get_template_part( 'content', 'course' );

            endwhile;

            learn_press_end_courses_loop();

            /**
             * @since 3.0.0
             */
            do_action( 'learn_press_after_courses_loop' );

            /**
             * @deprecated
             */
            do_action( 'learn-press/after-courses-loop' );

            wp_reset_postdata();

        else:
            learn_press_display_message( __( 'No course found.', 'learnpress' ), 'error' );
        endif;

        /**
         * @since 3.0.0
         */
        do_action( 'learn-press/after-main-content' );

        /**
         * @deprecated
         */
        do_action( 'learn_press_after_main_content' );
        ?>
    </div>
</div>
<div class="course-list-bottom">
<hr>
<div class="course-list-bottom-content">
<h5>it's time to try it</h5>
<h2>Be along side our thousands of teachers using<br>
Guideable and make a difference</h2>
<a href="#">Get started for free</a>
</div>
</div>