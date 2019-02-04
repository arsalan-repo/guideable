<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
		<?php global $fdata; ?>
		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <link href="<?php echo $fdata['favicon-logo']['url'] ?>" rel="shortcut icon">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">
		<link href="<?php echo get_template_directory_uri(); ?>/css/primarystyle.css" rel="stylesheet">
		<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.css" rel="stylesheet">
		<link href="<?php echo get_template_directory_uri(); ?>/css/fontawesome/css/all.css" rel="stylesheet">
		<link href="<?php echo get_template_directory_uri(); ?>/css/animate.css" rel="stylesheet">
		<link href="<?php echo get_template_directory_uri(); ?>/css/owl.carousel.min.css" rel="stylesheet">
		<link href="<?php echo get_template_directory_uri(); ?>/css/owl.theme.default.min.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>
		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/rwd.css">
		<script>
        // conditionizr.com
        // configure environment tests
        conditionizr.config({
            assets: '<?php echo get_template_directory_uri(); ?>',
            tests: {}
        });
        jQuery(document).ready(function () {
            $('#select_options').click(function() {
                $('html, body').animate({
                    scrollTop: $(".packages-section").offset().top
                }, 1000)
            });
            $('#pop-courses .owl-carousel').owlCarousel({
                loop:false,
                margin:10,
                nav:true,
                autoplay:true,
                autoplayTimeout:2000,
                autoplayHoverPause:true,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:2
                    },
                    1000:{
                        items:4
                    }
                }
            });
            $('#owl-review').owlCarousel({
                loop:true,
                margin:10,
                nav:true,
                autoplay:true,
                autoplayTimeout:2000,
                autoplayHoverPause:true,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:2
                    },
                    1000:{
                        items:3
                    }
                }
            });
            $(".create-course-instrutor-sign-in").click(function (event) {
                event.preventDefault();
                    window.location.href = '<?= home_url() ?>/profile/courses/owned/';
            });
            $('.regis-choose #inlineRadio2').removeAttr('checked');
            $('.regis-choose #inlineRadio1').attr("checked", "checked");
        });
        </script>
		
		<script>
function openNav() {
    document.getElementById("mySidenav").style.width = "240px";
	$('.closebtn').css('display','block');
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
	$('.closebtn').css('display','none');
}
</script>

	</head>
    <?php
    global $post;
    $post_slug = $post->post_name;
    ?>
	<body <?php body_class(); ?>>

			<!-- header -->
			<header class="header clear" role="banner">
				<div class="container">
					<div class="row">
						<div class="col-md-2">
							<div class="logo">
								<a href="<?php echo home_url(); ?>">
									<img src="<?php echo $fdata['site-logo']['url'] ?>" alt="Logo" class="logo-img">
								</a>
							</div>
						</div>
						<div class="col-md-4">
							<?php echo get_search_form(); ?>
						</div>
                        <div class="col-md-1 single-menu-item">
                            <a href="<?php echo home_url(); ?>/courses">
                                <span>
                                    Courses
                                </span>
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                        <!--div class="col-md-1"></div-->
						<div class="col-md-5">
							<nav class="nav main-navigation" role="navigation">
								<?php html5blank_nav(); ?>
							</nav>
							
							
							<nav class="navbar navbar-light light-blue lighten-4">

							<button class="navbar-toggler toggler-example" type="button" data-toggle="collapse" data-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1"
									aria-expanded="false" aria-label="Toggle navigation" onclick="openNav()"><span class="dark-blue-text"><i class="fa fa-bars fa-1x"></i></span></button>
							</nav>
							<!-- Verticale Nav bar -->
									<div id="mySidenav" class="sidenav">
			
							<div class="logo">
								<a href="<?php echo home_url(); ?>">
									<img src="<?php echo get_bloginfo('url') ?>/wp-content/uploads/2018/11/ver-logo.png" alt="Logo" class="logo-img">
								</a>
								 <a href="javascript:void(0)" class="closebtn" onclick="closeNav()" sytle="display:none;">&times;</a>
							</div>						
 
								<div class="scrollerbar-menu">
 <?php html5blank_navsecondary(); ?>
								
							<div class="ver-menu-bottom">
								<h3>
									We're here<br/> to help
								</h3>

								<p>
									Open a Support Ticket
								</p>
								<a href="http://124.47.158.69/contact-us/" class="suprt-btn">Contact Us</a>
								</div>	
												
										</div>		
								</div>
					
							<!-- Verticale Nav bar -->
							
						</div>
					</div>
				</div>


			</header>
			<!-- /header -->
		<?php
		if (!is_post_type_archive('lp_course') && !is_singular('lp_course') && !is_page('profile') && !is_page('create-course') && !is_page('course-details')) {
			echo '<div class="container">';
		}
		
		?>
			
				
