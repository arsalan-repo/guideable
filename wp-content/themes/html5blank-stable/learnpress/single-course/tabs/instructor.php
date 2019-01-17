<?php
/**
 * Template for displaying instructor of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/instructor.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
?>
<?php
$user_id = $course->get_instructor()->_id;
$args = array(
    'post_type' => 'lp_course',
    'post_status' => 'publish',
    'author' => $user_id,
    'posts_per_page' => -1
);
$current_user_posts = get_posts( $args );
$total = count( $current_user_posts );

$total_students = 0;
foreach ( $current_user_posts as $post_id ) {
    $course         = learn_press_get_course( $post_id->ID );
    $students       = $course->get_users_enrolled();
    $total_students += $students;
}

$total_reviews = 0;
foreach ( $current_user_posts as $post_id ) {
    $ratings       = learn_press_get_course_rate( $post_id->ID, false );
    $total_reviews += $ratings['total'];
}

$total_ratings = 0;
foreach ( $current_user_posts as $post_id ) {
    $ratings       = learn_press_get_course_rate( $post_id->ID, false );
    $total_ratings += (float) $ratings['rated'];
}
$role = get_user_meta( $user_id, 'wp_capabilities', true );
$role = array_keys( $role );

?>

<div class="row">
    <div class="col-md-9">
        <div class="course-author">
            <div class="heading">
                <h1>About the Instructors</h1>
                <div class="below_heading">
                    <hr/>
                </div>
            </div>
            <div class="">
                <?php do_action( 'learn-press/before-single-course-instructor' ); ?>
                <div class="instructor-meta">
                    <div class="row">
                        <div class="col-md-3">
                            <?php echo $course->get_instructor()->get_profile_picture(); ?>
                        </div>
                        <div class="col-md-9">
                            <h1>
                                <?php echo $course->get_instructor_html(); ?>
                            </h1>
                            <div class="instructor-header">
                                <ul>
                                    <li>
                                        <?php
                                        if ( $total_ratings == '0' ) {
                                            for ( $i = 0; $i <= 4; $i ++ ) {
                                                echo '<span class="rating"><i class="far fa-star"></i></span>';
                                            }
                                            echo ' 0';
                                        } else {
                                            $sum_ratings = $total_ratings / $total_reviews;
                                            for ( $x = 1; $x <= 5; $x ++ ) {
                                                echo '<span class="rating"><i class="' . ( $sum_ratings >= $x ? "fa" : "far" ) . ' fa-star"></i></span>';
                                            }
                                            echo ' ' . number_format( $sum_ratings, 2, '.', '' );
                                        }
                                        ?>
                                    </li>
                                    <li><span><i class="fa fa-comment"></i></span> <?= $total_reviews; ?> Reviews</li>
                                    <li><span><i class="fa fa-user"></i></span> <?= $total_students; ?> Students</li>
                                    <li><span><i class="fa fa-play"></i></span> <?= $total ?> Courses</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="author-bio">
                    <p>
                        <?php echo $course->get_author()->get_description(); ?>
                    </p>
                </div>
                <?php do_action( 'learn-press/after-single-course-instructor' ); ?>
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
