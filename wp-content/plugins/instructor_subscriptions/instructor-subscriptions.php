<?php
/*
Plugin Name: Instructor Subscriptions
Plugin URI:
description:
Version: 1.0
Author: HZTech
Author URI:
License:
*/


include ('includes/instructor-subscriptions.php');
include ('includes/shortcodes.php');
include ('includes/manage_courses.php');
$instructor_subscriptions = new InstructorSubscriptions();
$manage_courses = new ManageCourses();
