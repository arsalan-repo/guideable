<?php

get_header();

$category = wp_get_queried_object();

?>
<div class="blog-banner">
    <div class="container">
        <h1><?= $category->name ?></h1>
    </div>
</div>


<div class="container">

    <main role="main">

        <!-- section -->
        <section>


            <div class="latest-post-section">
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
