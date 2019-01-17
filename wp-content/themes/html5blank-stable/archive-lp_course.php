
<?php get_header(); ?>

<div class="course-banner">
	<div class="container">
		<h1>
			COURSES
		</h1>
		<P>
			how may we halp you?
		</P>	
	</div>
</div>

<div class="pop-course">
	<div class="container">
		<h2>
			Popular Courses
		</h2>
		<hr />		
		<div class="courses-shrt">
			<p>
				<?php echo do_shortcode( '[list_courses]' ); ?>
			</p>
		</div>		
	</div>	
</div>


<div class="courses-categories">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</div>
			<div class="col-md-9">
				<?php if (have_posts()): while (have_posts()) : the_post(); ?>

	<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php the_title(); ?>
				</article>
				<?php endwhile; endif; ?>
			</div>
		</div>
		
	</div>
	
</div>

<div class="container">
	
	
	
	<div class="row">
		<div class="col-md-4">
			<?php get_sidebar( 'Main Sidebar' ); ?>
		</div>
		<div class="col-md-8">
			
		</div>
		
	</div>
	
	
	
</div>






<?php get_footer(); ?>