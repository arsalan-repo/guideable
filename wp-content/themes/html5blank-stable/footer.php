<?php global $fdata; ?>

<?php
if (!is_post_type_archive('lp_course') && !is_singular('lp_course') && !is_page('profile')) {
    echo '</div>';
}
?>
<!-- footer -->
<footer class="footer" role="contentinfo">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-1')) ?>
            </div>
            <div class="col-md-3">
                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-2')) ?>
            </div>
            <div class="col-md-3">
                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-3')) ?>
            </div>
            <div class="col-md-3">
                <h3>Get in touch</h3>
                <hr>
                <p class="get-in-touch-content">We're here if you need us:
                    Contact us</p>
                <ul class="footer-social">
                    <?php if ($fdata['facebook']) { ?>
                        <li>
                            <a href="<?php echo $fdata['facebook'] ?>" target="_blank"><i class="fa fa-facebook"
                                                                                          aria-hidden="true"></i></a>
                        </li>
                    <?php } if ($fdata['twitter']) { ?>
                        <li>
                            <a href="<?php echo $fdata['twitter'] ?>" target="_blank"><i class="fa fa-twitter"
                                                                                         aria-hidden="true"></i></a>
                        </li>
                    <?php } if ($fdata['instagram']) { ?>
                        <li>
                            <a href="<?php echo $fdata['instagram'] ?>" target="_blank"><i class="fa fa-instagram"
                                                                                           aria-hidden="true"></i></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="row row-bg-color">
            <div class="col-md-2">
                <div class="footer-logo">
                    <a href="<?php echo home_url(); ?>">
                        <img src="<?php echo $fdata['footer-logo']['url'] ?>" alt="Logo" class="logo-img">
                    </a>
                </div>
            </div>
            <div class="col-md-10">
                <?php html5blank_navfooter(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p class="copyright">
                    &copy; Copyright <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All Rights Reserved
                </p>
            </div>
        </div>
    </div>

</footer>
<!-- /footer -->

<?php wp_footer(); ?>

<!-- analytics -->
<script>
    (function (f, i, r, e, s, h, l) {
        i['GoogleAnalyticsObject'] = s;
        f[s] = f[s] || function () {
                (f[s].q = f[s].q || []).push(arguments)
            }, f[s].l = 1 * new Date();
        h = i.createElement(r),
            l = i.getElementsByTagName(r)[0];
        h.async = 1;
        h.src = e;
        l.parentNode.insertBefore(h, l)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
    ga('create', 'UA-XXXXXXXX-XX', 'yourdomain.com');
    ga('send', 'pageview');
</script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-3.1.1.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/owl.carousel.js"></script>
<script>
    jQuery(function ($) {
        $('body').find('.create_course').click(function () {
            if ($('body').hasClass('logged-in')) {
                window.location.replace("http://124.47.158.69/wp-admin/edit.php?post_type=lp_course");
            } else {
                $('html, body').animate({
                    scrollTop: $(".packages-details").offset().top
                }, 1000)
            }
        })
    });
</script>
<script>
    $('.profile .lp-profile-content').find(".create_manage_course").click(function (e) {
        e.preventDefault();
        $('.profile .lp-user-profile').find(".popup-modal").css({"display": "block"});
        // $("body").css({"overflow-y": "hidden"}); //Prevent double scrollbar.
    });

    $('.profile .lp-user-profile .popup-modal').find(".close-modal").click(function () {
        $(".popup-modal").css({"display": "none"});
        // $("body").css({"overflow-y": "auto"}); //Prevent double scrollbar.
    });
</script>

<script>
    $(document).ready(function () {
        var current = 1;

        widget = $(".step");
        btnnext = $(".next");
        btnback = $(".back");
        btnsubmit = $(".submit");

        // Init buttons and UI
        widget.not(':eq(0)').hide();
        hideButtons(current);
        setProgress(current);

        // Next button click action
        btnnext.click(function () {
            if (current < widget.length) {
                widget.show();
                widget.not(':eq(' + (current++) + ')').hide();
                setProgress(current);
                //alert("I was called from btnNext");
            }
            hideButtons(current);
        });

        // Back button click action
        btnback.click(function () {
            if (current > 1) {
                current = current - 2;
                btnnext.trigger('click');
            }
            hideButtons(current);
        });
    });

    // Change progress bar action
    setProgress = function (currstep) {
        var percent = parseFloat(100 / widget.length) * currstep;
        percent = percent.toFixed();
        $(".progress-bar")
            .css("width", percent + "%")
            .html(percent + "%");
    }

    // Hide buttons according to the current step
    hideButtons = function (current) {
        var limit = parseInt(widget.length);

        $(".action").hide();

        if (current < limit) btnnext.show();
        if (current > 1) btnback.show();
        if (current == limit) {
            btnnext.hide();
            btnsubmit.show();
        }
    }
</script>
<script>
    jQuery('#create-course-submit').submit(function () {
        var title = $('#course_title').val();
        var category = $('#course_category').val();
        if(title == ''){
            alert('Course Title is Required');
            return false;
        }else if(category == ''){
            alert('Course Category is Required');
            return false;
        }else{
            return true;
        }
    })
</script>
<!--Curriculum Section-->
<script>
    jQuery(document).ready(function () {
        $('.curriculum-section').find('.curriculum-1').hide();
    })
    jQuery(document).on('keypress', '.curriculum-section .section-name', function (e) {
        if(e.which == 13){
            $('.curriculum-1').show();
            return false;
        }
    })
    jQuery(document).on('keypress', '.curriculum-1 .create_lesson', function (e) {
        if(e.which == 13){
            if(!$('.curriculum-1 .lesson').hasClass('show')){
                $('.curriculum-1 .lesson').addClass('show');
                $('.curriculum-1 .lesson').css({'display':'block','background':'rgba(0,0,0,0.5)'});
            }
            return false;
        }
    })
    jQuery(document).on('click', '.save_lesson', function (e) {
        e.preventDefault();
        $('.curriculum-1 .lesson').removeClass('show');
        $('.curriculum-1 .lesson').css('display','none')
    })
    jQuery(document).on('click', '.curriculum-1 .close', function (e) {
        e.preventDefault();
        $('.curriculum-1 .lesson').removeClass('show');
        $('.curriculum-1 .lesson').css('display','none')
    })
    jQuery(document).on('keypress', '.curriculum-1 .create_quiz', function (e) {
        if(e.which == 13){
            if(!$('.curriculum-1 .quiz').hasClass('show')){
                $('.curriculum-1 .quiz').addClass('show');
                $('.curriculum-1 .quiz').css({'display':'block','background':'rgba(0,0,0,0.5)', 'overflow': 'auto'});
                $('body').css({'overflow': 'hidden'});
            }
            return false;
        }
    })
    jQuery(document).on('click', '.save_quiz', function (e) {
        e.preventDefault();
        $('.curriculum-1 .quiz').removeClass('show');
        $('.curriculum-1 .quiz').css({'display':'none', 'overflow': 'hidden'});
        $('body').css({'overflow': 'auto'});
    })
    jQuery(document).on('click', '.curriculum-1 .close', function (e) {
        e.preventDefault();
        $('.curriculum-1 .quiz').removeClass('show');
        $('.curriculum-1 .quiz').css({'display':'none', 'overflow': 'hidden'});
        $('body').css({'overflow': 'auto'});
    })
    jQuery(document).on('click', '.add_new_button .add_new', function (e) {
        e.preventDefault();
        $('.curriculum-section').last().clone().appendTo('.main-curriculum-section');
        $('.curriculum-section .section-name').last().val("");
        $('.curriculum-section .create_lesson').last().val("");
        $('.curriculum-section .create_quiz').last().val("");
        $('.curriculum-section #lesson_details').last().val("");
    })
</script>

</body>
</html>
