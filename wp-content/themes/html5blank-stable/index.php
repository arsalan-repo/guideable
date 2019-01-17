<?php get_header(); ?>
<div class="blog-banner">
    <div class="container">
        <h1>Guideable Blog</h1>
        <p>Guideable allows you to create a beautiful world-class learning experience at the <br/>tip of your finger.
        </p>
        <div class="categories-blog">
			<?php
			$categories = get_categories();
			if ( ! empty( $categories ) ) {
				?>
                <ul>
					<?php foreach ( $categories as $category ) { ?>
                        <li class="active">
                            <a href="<?= get_category_link( $category->term_id ) ?>"><?= $category->name ?></a>
                        </li>
					<?php } ?>
                </ul>
				<?php
			}
			?>
        </div>
    </div>
</div>


<div class="container">

    <div class="Feature-blog-section" style="display: none;">
        <h1><?php _e( 'Featured Guides', 'html5blank' ); ?></h1>
        <hr/>

		<?php
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => 2,
			'meta_key'       => 'feature_post',
			'meta_value'     => true
		);

		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) : $loop->the_post();
			echo '<br /> <a href="' . get_permalink() . '"> ' . get_the_title() . '</a>';
		endwhile;
		wp_reset_query(); ?>


    </div>


    <main role="main">

        <!-- section -->
        <section>


            <div class="latest-post-section">


                <h1><?php _e( 'Latest Guides', 'html5blank' ); ?></h1>
                <hr/>
				<?php get_template_part( 'loop' ); ?>
				<?php get_template_part( 'pagination' ); ?>
            </div>
        </section>
        <!-- /section -->
        <hr/>
        <div class="blog-bottom">
            <div class="content-blg">
                <p>
                    It’s time to try it
                </p>
                <h1>
                    Be along side our thousands of teachers using<br/>
                    Guideable and make a difference
                </h1>
                <a href="#">Get started for free</a>
            </div>

        </div>

    </main>
</div>
<?php get_sidebar(); ?>

<script>
    jQuery(function ($) {
        $(".blog-banner").insertBefore($('.blog-banner').parent());
    });
</script>

<?php get_footer(); ?>
