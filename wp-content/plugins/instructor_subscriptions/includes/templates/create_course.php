<?php

/* Template Name: CreateCourse */

get_header();

?>

<?php

$course_categories = get_terms(array(
    'taxonomy' => 'course_category',
    'hide_empty' => false,
));

?>

<div class="course-featured-img">
	<div class="inner-content-featured-img">
		<h2>Create Course</h2>
		<p>Loream ispum Loream ispum Loream ispum Loream ispum Loream ispum Loream ispum </p>
	</div>
</div>
  <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
<form name="create-course" method="POST" id="create-course-submit">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
              
                <!-- =====Page 1====== -->
                <div class="step well"> <!-- Page 1 -->
                    <div class="main-content-wrapper text-center">
                        <h1>First, let's find out what type of course you're making.</h1>
                        <div class="create-course-flow ">
                            <i class="far fa-file-video"></i>
                            <span class="title"> Course</span>
                            <p>Create rich learning experiences with the help of video lectures, quizzes, coding exercises,
                                etc.</p>
                        </div>
                    </div>
                </div>
                <!-- =====Page 2====== -->
                <div class="step well">
                    <div class="main-content-wrapper text-center">
                        <h1>How about a working title?</h1>
                        <p>It's ok if you can't think of a good title now. You can change it later.</p>
                    </div>
                    <div class="search-box">
                        <input name="post_title" type="text" id="course_title" placeholder="e.g. Learn Photoshop CS6 from Scratch"/>
                    </div>
                </div>
                <!-- =====Page 3====== -->
                <div class="step well">
                    <div class="main-content-wrapper text-center">
                        <h1>What category best fits the knowledge you'll share?</h1>
                        <p>If you're not sure about the right category, you can change it later.</p>
                    </div>
                    <div class="drop-box">
                        <select name="course_category" id="course_category" required>
                            <option value="" selected disabled hidden>Choose here</option>
                            <?php foreach ($course_categories as $cv) {?>
                            <option value="<?= $cv->term_id ?>"><?= $cv->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
         <pre class="brush:html">
         <a class="action back btn btn-info">Previous</a>
            </div>
            <div class="col-md-6">
                <div class="pull-right">
                    <a class="action next btn btn-info">Continue</a>
                    <button class="action submit btn btn-success">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <?php wp_nonce_field('create_course') ?>
</form>

<?php get_footer() ?>