<?php

class ManageCourses
{
    public function __construct()
    {
        //Functions
            add_filter('page_template',array($this,'create_course_template'));
            add_filter('page_template',array($this,'course_detail_template'));
            add_action('wp',array($this,'publish_course'));
            add_action('wp',array($this,'publish_course_details'));
        //Shortcodes
    }

    public function create_course_template($page_template){
        if(is_page('create-course')){
            $page_template = dirname(__FILE__). '/templates/create_course.php';
        }
        return $page_template;
    }

    public function publish_course(){
        if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'create_course')){
            $current_user = get_current_user_id();
            $is_expired = get_user_meta($current_user, 'is_expired', true);
            $role = get_user_meta($current_user, 'wp_capabilities', true);
            $role = array_keys($role);
            if(in_array('lp_teacher', $role) && (empty($is_expired) || $is_expired == 'yes')){
                $publish_course = wp_insert_post(array(
                    'post_type' => 'lp_course',
                    'post_title' => $_POST['post_title'],
                    'post_status' => 'draft',
                    'tax_input' => array(
                        'course_category' => $_POST['course_category']
                    )
                ));
                wp_redirect(bloginfo('url').'/wp-admin/post.php?post='.$publish_course.'&action=edit');
            }
        }
    }

    public function course_detail_template($page_template){
        if(is_page('course-details')){
            $page_template = dirname(__FILE__). '/templates/course_details.php';
        }
        return $page_template;
    }

    public function publish_course_details(){
        if(isset($_GET['course_id'])){
            $course_id = $_GET['course_id'];
            $current_user = get_current_user_id();
            $is_expired = get_user_meta($current_user, 'is_expired', true);
            if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'course_details')){
                if((empty($is_expired) || $is_expired == 'yes')){
                    $course_detail = array(
                        'ID' => $course_id,
                        'post_content' => $_POST['course-descriptions'],
                    );
                    wp_update_post($course_detail);
                    update_post_meta($course_id, '_lp_max_students', $_POST['max_students']);
                    update_post_meta($course_id, '_lp_retake_count', $_POST['retake_courses']);
                    update_post_meta($course_id, '_lp_passing_condition', $_POST['passing']);
                    update_post_meta($course_id, '_lp_price', $_POST['price']);
                    update_post_meta($course_id, 'includes', $_POST['course-includes']);
                    update_post_meta($course_id, 'requirements', $_POST['course-requirements']);
                    update_post_meta($course_id, '_lp_course_result', $_POST['_lp_course_result']);
                }
                //update_post_meta($course_id, '_lp_students', $_POST['enrolled_students']);
                //update_post_meta($course_id, '_lp_duration', $_POST['']);
                //update_post_meta($course_id, '_lp_course_result', $_POST['']);
                //update_post_meta($course_id, '_lp_required_enroll', $_POST['free']);
                //$meta_values = get_post_meta( 587 );
                //var_dump( $meta_values );die;
                wp_redirect(bloginfo('url').'/profile/courses/owned/');
            }
        }
    }

}