<?php
/**
 * Template for displaying course meta start.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/global/course-meta-start.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
$post_id = get_the_ID();
$cat = wp_get_object_terms($post_id, 'course_category', array('fields' => 'names'));
?>
<div class="course-header">
<div class="container">

    <div class="row">
        <div class="col-md-8">
            <div class="title">
                <h1><?php the_title(); ?></h1>
                
            </div>
			
		<div class="review-button-price">
			<div class="row">
						<div class="col-md-8">
							<div class="inner-col-8"> 
						<div class="instructor-desti">
						<h3><?= implode(', ',$cat) ?></h3>
						</div>
                    <?php
                        $course = learn_press_get_course($post_id);
                        $students = $course->get_users_enrolled();

                        $ratings = learn_press_get_course_rate( $post_id, false );
                    ?>
                    <div class="course-meta-details">
                        <ul>
                            <li>
                                <?php
                                $total_ratings = $ratings['rated'];
                                if ( $total_ratings == '0' ) {
                                    for ( $i = 0; $i <= 4; $i ++ ) {
                                        echo '<span class="rating"><i class="far fa-star"></i></span>';
                                    }
                                    echo ' 0';
                                } else {
                                    for ( $x = 1; $x <= 5; $x ++ ) {
                                        echo '<span class="rating"><i class="' . ( $total_ratings >= $x ? "fa" : "far" ) . ' fa-star"></i></span>';
                                    }
                                    echo ' ' . number_format( $total_ratings, 2, '.', '' );
                                }
                                ?>
                            </li>
                            <li><span><i class="fa fa-comment"></i></span> <?= $ratings['total'] ?> Reviews</li>
                            <li><span><i class="fa fa-user"></i></span> <?= $students; ?> Students</li>
                        </ul>
                    </div>
                    <div class="meta">
                        <h4>30-Day Money-Back Guarantee</h4>
                    </div>
						</div>
                </div>
				
				<div class="col-md-4">
                    <?php
                    $user   = LP_Global::user();
                    $course = LP_Global::course();

                    if ( ! $price = $course->get_price_html() ) {
                        return;
                    }
                    ?>
					<div class="price-btn">
                    <div class="course-price">
                        <?php if ( $course->has_sale_price() ) { ?>
                            <span class="origin-price"> <?php echo $course->get_origin_price_html(); ?></span>
                        <?php } ?>
                        <span class="price"><?php echo $price; ?></span>
                    </div>
                    <div class="lp-course-buttons">

                        <?php do_action( 'learn-press/before-course-buttons' ); ?>
                        <?php
                        /**
                         * @see learn_press_course_purchase_button - 10
                         * @see learn_press_course_enroll_button - 10
                         * @see learn_press_course_retake_button - 10
                         */
                        do_action( 'learn-press/course-buttons' );
                        ?>
                        <?php do_action( 'learn-press/after-course-buttons' ); ?>

                    </div>
					</div>
                </div>
				
			</div>
		</div>
			
            
        </div>
        <div class="col-md-4">
            <?php
            global $post;
            $course      = learn_press_get_course();
            $video_embed = $course->get_video_embed();

            if ( $video_embed ) {
            ?>
            <div class="course-video"><?php echo $video_embed; ?></div>
            <?php
            }

                if ( ! has_post_thumbnail() || $video_embed ) {
                    return;
                }
                ?>

            <div class="course-thumbnail">
                <?php
                $image_title   = get_the_title( get_post_thumbnail_id() ) ? esc_attr( get_the_title( get_post_thumbnail_id() ) ) : '';
                $image_caption = get_post( get_post_thumbnail_id() ) ? esc_attr( get_post( get_post_thumbnail_id() )->post_excerpt ) : '""';
                $image_link    = wp_get_attachment_url( get_post_thumbnail_id() );
                $image         = get_the_post_thumbnail( $post->ID, apply_filters( 'single_course_image_size', 'single_course' ), array(
                    'title' => $image_title,
                    'alt'   => $image_title
                ) );

                echo apply_filters(
                    'learn_press_single_course_image_html',
                    sprintf( '<a href="%s" itemprop="image" class="learn-press-single-thumbnail" title="%s">%s</a>', $image_link, $image_caption, $image ),
                    $post->ID
                );
                ?>
            </div>

        </div>
    </div>
	</div>
	
</div>

