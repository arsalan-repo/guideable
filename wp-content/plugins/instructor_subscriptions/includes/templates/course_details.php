<?php

/* Template Name: CourseDetails */

get_header();

$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$user_name = $current_user->display_name;

?>


<div class="course-featured-img">
	<div class="inner-content-featured-img">
		<h2>Course Details</h2>
		<p>Loream ispum Loream ispum Loream ispum Loream ispum Loream ispum Loream ispum </p>
	</div>
</div>
<form method="POST" name="course-details">

<div class="detail-course-profile-header">
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="media">
<!--                <img class="mr-3" src="https://media.istockphoto.com/vectors/young-man-avatar-character-male-face-portrait-cartoon-person-vector-vector-id855422168?s=170x170" alt="">-->
                <img class="mr-3" src="http://www.korcula-guide.com/wp-content/uploads/dummy/user.jpg" alt="">
                <div class="media-body">
                    <?php
                    if(isset($_GET['course_id'])){
                        $course_details = get_post($_GET['course_id']);
                        ?>
                        <h5 class="mt-0"><?= $course_details->post_title; ?></h5>
                        <?php
                    }
                    ?>
                    by <?= ucfirst($user_name) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <p>
                <button type="submit" class="btn btn-primary">Publish</button>
            </p>
        </div>
    </div>
	</div>
</div>	
 <div class="row tab-vertical">
		<div class="container">
		<div class="row">
        <div class="col-md-4">
            <div class="side-tabs">
                <ul class="nav nav-tabs nav-tabs--vertical nav-tabs--left" role="navigation">
                    <h3>Plan your course</h3>
                    <li class="nav-item">
                        <a href="#course-structure" class="nav-link" data-toggle="tab" role="tab" aria-controls="course-structure">Course Structure</a>
                    </li>
                    <li class="nav-item">
                        <a href="#m-details" class="nav-link" data-toggle="tab" role="tab" aria-controls="m-details">Material Details</a>
                    </li>
                    <h3>Create your content</h3>
                    <li class="nav-item">
                        <a href="#curriculum" class="nav-link" data-toggle="tab" role="tab" aria-controls="curriculum">Curriculum</a>
                    </li>
                    <h3>Publish your course</h3>
                    <li class="nav-item">
                        <a href="#general" class="nav-link" data-toggle="tab" role="tab" aria-controls="general">General</a>
                    </li>
                    <li class="nav-item">
                        <a href="#assessment" class="nav-link" data-toggle="tab" role="tab" aria-controls="assessment">Assessment</a>
                    </li>
                    <li class="nav-item">
                        <a href="#pricing" class="nav-link" data-toggle="tab" role="tab" aria-controls="pricing">Pricing</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-8">
		
            <div class="course-details">
			<div class="heading-course">
			 <h1>Course Structure</h1>
			 </div>                   
               <hr>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="course-structure" role="tabpanel">
                       <h3>Description of the Course</h3>
                        <?php
                        $setting = array(
                            'editor_height' => 300,
                        );
                        $content = '';
                        $editor_id = 'course-descriptions';

                        wp_editor($content, $editor_id, $setting);
                        ?>
                    </div>
                    <div class="tab-pane fade" id="m-details" role="tabpanel">
                        <h1>Material Details</h1>
                        <br/>
                        <h3>Includes</h3>
                        <small id="emailHelp" class="form-text text-muted">List down the material of the course</small>
                        <?php
                        $setting = array(
                            'editor_height' => 300,
                        );
                        $content = '';
                        $editor_id = 'course-includes';

                        wp_editor($content, $editor_id, $setting);
                        ?>
                        <br/>
                        <h3>Requirements</h3>
                        <small id="emailHelp" class="form-text text-muted">Requirements of the course</small>
                        <?php
                        $setting = array(
                            'editor_height' => 300,
                        );
                        $content = '';
                        $editor_id = 'course-requirements';

                        wp_editor($content, $editor_id, $setting);
                        ?>
                    </div>
                    <div class="tab-pane fade" id="curriculum" role="tabpanel">
                        <h1>Curriculum</h1>
                        <div class="main-curriculum-section">
                            <div class="curriculum-section">
                                <div class="form-group form-fields">
                                    <i class="fa fa-plus icon"></i>
                                    <input type="text" class="form-control section-name" placeholder="Write section name and press Enter">
                                </div>
                                <div class="curriculum-1">
                                    <div class="form-group form-fields">
                                        <i class="fa fa-book icon"></i>
                                        <input type="text" class="form-control create_lesson" placeholder="Create a new lesson and press Enter">
                                    </div>
                                    <div class="modal fade lesson" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Lesson Details</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <small id="emailHelp" class="form-text text-muted">List down the Lesson Details</small>
                                                        <?php
                                                        $setting = array(
                                                            'editor_height' => 300,
                                                        );
                                                        $content = '';
                                                        $editor_id = 'lesson_details';

                                                        wp_editor($content, $editor_id, $setting);
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary save_lesson">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-center" style="margin: 10px 0;"><strong>OR</strong></p>
                                    <div class="form-group form-fields">
                                        <i class="fa fa-clock-o icon"></i>
                                        <input type="text" class="form-control create_quiz" id="" placeholder="Create a new quiz and press Enter">
                                    </div>
                                    <div class="modal fade quiz" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Quiz Details</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <small id="emailHelp" class="form-text text-muted">List down the Quiz Details</small>
                                                        <?php
                                                        $setting = array(
                                                            'editor_height' => 300,
                                                        );
                                                        $content = '';
                                                        $editor_id = 'quiz_details';

                                                        wp_editor($content, $editor_id, $setting);
                                                        ?>
                                                    </div>
                                                    <div class="questions">
                                                        <br/>
                                                        <h3>Questions</h3>
                                                        <br/>
                                                        <div class="question_details">
                                                            <div class="form-inline">
                                                                <div class="form-group">
                                                                    <label>1.</label>
                                                                    <input type="text" id="" class="form-control mx-sm-5" placeholder="Create a new question">
                                                                    <div class="dropdown">
                                                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 13px;">
                                                                            Select Type
                                                                        </a>

                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                            <a class="dropdown-item true_false" href="#">True or False</a>
                                                                            <a class="dropdown-item multi" href="#">Multi Choice</a>
                                                                            <a class="dropdown-item single" href="#">Single Choice</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="true_or_false">
                                                                <div class="true_or_false_answers">
                                                                    <div class="row">
                                                                        <div class="col-md-5">
                                                                            <table class="table table-bordered">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th style="width: 80%"># Answer Text</th>
                                                                                    <th>Correct?</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td>1. True</td>
                                                                                    <td><input type="radio" class="form-control" name="" /></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>2. False</td>
                                                                                    <td><input type="radio" class="form-control" name="" /></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <h4>Settings</h4>
                                                                            <br/>
                                                                            <div class="settings">
                                                                                <div class="form-group">
                                                                                    <label>Question Explanation</label>
                                                                                    <textarea name="" class="form-control"></textarea>
                                                                                    <small id="emailHelp" class="form-text text-muted">Explain why an option is true and other is false. The text will be shown when user click on 'Check answer' button.</small>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Question Hint</label>
                                                                                    <textarea name="" class="form-control"></textarea>
                                                                                    <small id="emailHelp" class="form-text text-muted">Instruction for user to select the right answer. The text will be shown when users click the 'Hint' button.</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary save_quiz">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br/>
                                </div>
                            </div>
                        </div>
                        <div class="add_new_button">
                            <p class="text-right"><button class="add_new btn">Add New</button></p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="general" role="tabpanel">
                        <h1>General</h1>
                        <br/>
                        <div class="form-group">
                            <label>Duration</label>
							<div class="fields-duration">
							<div class="duration-input-field">
                            <input type="number" class="form-control" name="duration" placeholder="">
							</div>
							<div class="duration-select-field">
                            <select class="form-control">
                                <option value="">Minute(s)</option>
                                <option value="">Hour(s)</option>
                                <option value="">Day(s)</option>
                                <option value="">Week(s)</option>
                            </select>
							</div>
							
							</div>
                            <small id="emailHelp" class="form-text text-muted">The duration of the course.</small>
                        </div>
                        <div class="form-group">
                            <label>Max Students</label>
                            <input type="number" class="form-control" placeholder="" name="max_students">
                            <small id="emailHelp" class="form-text text-muted">Maximum number of students who can enroll in this course.</small>
                        </div>
<!--                        <div class="form-group">-->
<!--                            <label>Students Enrolled</label>-->
<!--                            <input type="number" class="form-control" placeholder="" name="enrolled_students">-->
<!--                            <small id="emailHelp" class="form-text text-muted">How many students have taken this course.</small>-->
<!--                        </div>-->
                        <div class="form-group">
                            <label>Re-Take Course</label>
                            <input type="number" class="form-control" placeholder="" name="retake_courses">
                            <small id="emailHelp" class="form-text text-muted">How many times the user can re-take this course. Set to 0 to disable re-taking</small>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="assessment" role="tabpanel">
                        <h1>Assessment</h1>
                        <br/>
                        <div class="form-group">
                            <label>Course Result</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="_lp_course_result" value="evaluate_lesson">
                                <label class="form-check-label">
                                    Evaluate via lessons
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="_lp_course_result" value="evaluate_final_quiz">
                                <label class="form-check-label">
                                    Evaluate via results of the final quiz
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="_lp_course_result" value="evaluate_quizzes">
                                <label class="form-check-label">
                                    Evaluate via results of quizzes
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="_lp_course_result" value="evaluate_passed_quizzes">
                                <label class="form-check-label">
                                    Evaluate via results of quizzes passed
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="_lp_course_result" value="evaluate_quiz">
                                <label class="form-check-label">
                                    Evaluate via quizzes
                                </label>
                            </div>
                            <small id="emailHelp" class="form-text text-muted">The method to assess the result of a student for a course.</small>
                        </div>
                        <div class="form-group">
                            <label>Passing Condition Value</label>
                            <input type="number" class="form-control" name="passing" placeholder=""> %
                            <small id="emailHelp" class="form-text text-muted">The percentage of quiz result or completed lessons to finish the course.</small>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pricing" role="tabpanel">
                        <h1>Pricing</h1>
                        <br/>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" class="form-control" placeholder="" name="price">
                            <small id="emailHelp" class="form-text text-muted">This course requires enrollment and the suggested price is $0.00 Course price in USD currency.</small>
                        </div>

<!--                        <div class="form-group">-->
<!--                            <label>No Requirement Enroll</label>-->
<!--                            <div class="form-check">-->
<!--                                <input class="form-check-input" type="checkbox" name="free">-->
<!--                            </div>-->
<!--                            <small id="emailHelp" class="form-text text-muted">Require users logged in to study or public to all.</small>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
	</div>
</div>	
    <?php wp_nonce_field('course_details') ?>
</form>

<?php

get_footer();

?>