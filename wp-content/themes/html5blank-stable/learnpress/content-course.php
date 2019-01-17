<?php
/**
 * Template for displaying course content within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-course.php
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$user = LP_Global::user();
?>

<?php
$post_id = get_the_ID();
$ratings = learn_press_get_course_rate($post_id, false);
$price = get_post_meta($post_id, '_lp_price', true);
$sale = get_post_meta($post_id, '_lp_sale_price', true);
$course_cat = wp_get_object_terms($post_id, 'course_category', array('fields' => 'names'));
$cat_name = 'Uncategorized';
if (isset($course_cat[0])) {
    $cat_name = $course_cat[0];
}
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('col-md-3'); ?>>
    <div class="card">
	<div class="card-thumb-img">
        <a href="<?= get_permalink(); ?>"><?= the_post_thumbnail(); ?></a>
		</div>
        <div class="card-body">
            <h2 class="card-title"><a href="<?= get_permalink(); ?>" style="font-size: 2rem!important;"><?= the_title(); ?></a></h2>
            <p class="card-text">in <?= $cat_name ?></p>
            <div class="ratings">
                <p><?php echo do_shortcode('[course_rating id="' . $post_id . '"]') . " " . number_format((float)$ratings['rated'], 2,'.','') . " " . "(" . $ratings['total'] . ")" ?></p>
            </div>
            <h3>
                <?php
                if(empty($price)){
                    echo "Free";
                }else{
                    $is_sale = !empty($sale);
                    $regular_price = "$". number_format((float)$price, 2,'.','');
                    echo ($is_sale ? "<del>" : "").$regular_price.($is_sale ? "</del>" : "");
                    if($is_sale){
                        echo " $". number_format((float)$sale, 2,'.','');
                    }
                }
                ?>
            </h3>
            <a href="<?= get_permalink(); ?>"><span><i class="fas fa-external-link-alt"></i></span></a>
        </div>
    </div>
</div>