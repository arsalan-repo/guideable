
<div class="row">
<?php if (have_posts()): while (have_posts()) : the_post(); ?>

	

	<div class="col-md-4">
		<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<!-- post thumbnail -->
		<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php the_post_thumbnail(array(370,210)); // Declare pixel size you need inside the array ?>
			</a>
		<?php endif; ?>
		<!-- /post thumbnail -->
			<!-- post title -->
		<h2>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h2>
		<!-- /post title -->
		
		<!-- post details -->
		<span class="author"><?php _e( 'By', 'html5blank' ); ?> <?php the_author_posts_link(); ?></span>
		<span class="date"> <i class="fa fa-clock"></i> <?php the_time('F j, Y'); ?></span>
		
		<!-- /post details -->
		<?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>
		<?php edit_post_link(); ?>
		
		</article>
	<!-- /article -->
		
	</div>



<?php endwhile; ?>
</div>
<?php else: ?>

	<!-- article -->
	<article>
		<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
	</article>
	<!-- /article -->

<?php endif; ?>
