<style>
 
</style>

<?php
/**
 * Template for displaying curriculum tab of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/tabs/curriculum.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();

?>
<div class="row">
    <div class="col-md-9">
        <div class="heading">
            <h1>Curriculum</h1>
            <div class="below_heading">
                <hr>
            </div>
        </div>
        <div class="course-curriculum" id="learn-press-course-curriculum">
            <div class="curriculum-scrollable">

                <?php
                /**
                 * @deprecated
                 */
                do_action( 'learn_press_before_single_course_curriculum' );

                /**
                 * @since 3.0.0
                 */
                do_action( 'learn-press/before-single-course-curriculum' );
                ?>

                <?php if ( $curriculum = $course->get_curriculum() ) { ?>

                    <ul class="curriculum-sections">
                        <?php foreach ( $curriculum as $section ) {
                            learn_press_get_template( 'single-course/loop-section.php', array( 'section' => $section ) );
                        } ?>
                    </ul>

                <?php } else { ?>

                    <?php echo apply_filters( 'learn_press_course_curriculum_empty', __( 'Curriculum is empty', 'learnpress' ) ); ?>

                <?php } ?>

                <?php
                /**
                 * @since 3.0.0
                 */
                do_action( 'learn-press/after-single-course-curriculum' );

                /**
                 * @deprecated
                 */
                do_action( 'learn_press_after_single_course_curriculum' );
                ?>

            </div>

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