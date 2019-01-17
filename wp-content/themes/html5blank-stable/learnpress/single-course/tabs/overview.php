<?php
/**
 * Template for displaying overview tab of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/tabs/overview.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<?php global $course; ?>
<div class="row">
    <div class="col-md-9">
        <div class="heading">
            <h1>Description</h1>
            <div class="below_heading">
                <hr>
            </div>
        </div>
        <div class="course-description" id="learn-press-course-description">

            <?php
            /**
             * @deprecated
             */
            do_action( 'learn_press_begin_single_course_description' );

            /**
             * @since 3.0.0
             */
            do_action( 'learn-press/before-single-course-description' );

            echo $course->get_content();

            /**
             * @since 3.0.0
             */
            do_action( 'learn-press/after-single-course-description' );

            /**
             * @deprecated
             */
            do_action( 'learn_press_end_single_course_description' );
            ?>

        </div>
    </div>
    <div class="col-md-3">
        <div class="material-head">
            <?php
            $fields = get_fields();
            if ($fields): ?>
                <?php foreach ($fields as $name => $value): ?>
                    <div class="material">
                        <h2><?= $name; ?></h2>
                        <div class="material_details">
                            <?= $value; ?>
                        </div>
                    </div>
                    <br/>
                    <br/>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>