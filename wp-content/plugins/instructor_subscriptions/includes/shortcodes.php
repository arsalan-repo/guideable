<?php

class InstructorShortcodes
{
	public function payment_details( $atts )
	{
		$saved = false;
		if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'save_settings' ) ) {
			$post    = array(
				'paypal_address' => $_POST['paypal_address']
			);
			$user_id = get_current_user_id();
			$saved   = update_user_meta( $user_id, 'paypal_address', $_POST['paypal_address'] );
		}
		?>
        <style>
            .paypal-form input {
                background: #ecfae1;
                border: 1px solid #cecece;
                padding: 15px 20px;
                border-radius: 10px;
                font-size: 16px;
            }

            .paypal-form label {
                margin: 0 0 10px 0;
                font-size: 15px;
            }

            .paypal-form small {
                font-size: 10px;
            }

            .paypal-form button.save {
                font-size: 18px;
                font-weight: 400;
                padding: 10px 20px;
                border-radius: 5px;
                background: #f17325;
                background: linear-gradient(to right, #f17325, #be308c);
                background: -webkit-linear-gradient(to right, #f17325, #be308c);
                border: none;
                color: #fff;
            }
        </style>
        <div class="">
            <div class="">
				<?php if ( $saved ) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Your settings have been saved successfully.</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
				<?php } ?>
                <form method="post" class="form#learn-press-profile-basic-information paypal-form">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Paypal Email address</label>
                        <input name="paypal_address" type="email" class="form-control" aria-describedby="emailHelp"
                               placeholder="Enter Paypal Email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                            else.
                        </small>
                    </div>
					<?php
					wp_nonce_field( 'save_settings' );
					?>
                    <button type="submit" class="save btn btn-primary" name="gg">Save</button>
                </form>
            </div>
        </div>
		<?php
	}

	public function withdrawal()
	{
		?>
        <style>
            .request label, .request input {
                font-size: 15px;
            }

            .request small {
                font-size: 10px;
            }

            .request button {
                font-size: 12px;
                background: #f37423;
                border: #f37423;
            }
        </style>
        <div class="container">
            <div class="row">
                <form method="post" class="request">
                    <div class="form-group">
                        <label>Amount</label>
                        <input name="amount" type="number" class="form-control" aria-describedby="withdraw"
                               placeholder="Enter Amount">
                        <small id="withdraw" class="form-text text-muted">Amount you wish to withdraw.</small>
                    </div>
					<?php
					wp_nonce_field( 'withdraw_amount' );
					?>
                    <button type="submit" class="save btn btn-primary">Request payment</button>
                </form>
            </div>
        </div>
		<?php
	}

	public function show_wallet_amount( $atts )
	{
		$atts = shortcode_atts( [
			                        'user_id' => get_current_user_id()
		                        ], $atts, 'wallet' );

		$current_user = $atts['user_id'];
		$role         = get_user_meta( $current_user, 'wp_capabilities', true );
		$role         = array_keys( $role );
		if ( in_array( 'lp_teacher', $role ) ) {
			$wallet = get_user_meta( $current_user, 'wallet', true );
			if ( ! empty( $wallet ) ) {
				echo "Wallet Amount: " . "$" . $wallet;
			} else {
				echo "Wallet Amount: " . "$ 0";
			}
		}

	}

	public function course_categories()
	{
		$terms = get_terms( array(
			                    'taxonomy'   => 'course_category',
			                    'hide_empty' => 0
		                    )
		);
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			?>
			<?php foreach ( $terms as $term ) { ?>
                <div class="vc_col-md-3 course-cat">
                    <a href="<?= get_term_link( $term->term_id, 'course_category' ); ?>">
                        <div class="course-category" role="alert">
							<?php
							$course_count = $term->count;
							?>
                            <ul>
                                <li><?= $term->name; ?></li>
                                <li><?= "(" . $course_count . ")"; ?></li>
                            </ul>
                        </div>
                    </a>
                </div>
			<?php }
		} else {
			$error_string = $terms->get_error_message();
			echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
		}
	}

	public function best_selling_course( $atts )
	{
		$atts = shortcode_atts(
			array(
				'count' => '4',
			), $atts, 'list_courses'
		);
//        echo 'abc';
		$args      = array(
			'post_type'      => 'lp_course',
			'orderby'        => 'comment_count',
			'order'          => 'desc',
			'posts_per_page' => $atts['count']
		);
		$the_query = new WP_Query( $args );
		?>
        <div class="row">
			<?php
			if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
				?>
				<?php
				$post_id = get_the_ID();
				$ratings = learn_press_get_course_rate( $post_id, false );
//            $users = learn_press_get_course_rate_total($post_id, false);
				$price      = get_post_meta( $post_id, '_lp_price', true );
				$course_cat = wp_get_object_terms( $post_id, 'course_category', array( 'fields' => 'names' ) );
				$cat_name   = 'Uncategorized';
				if ( isset( $course_cat[0] ) ) {
					$cat_name = $course_cat[0];
				}
				?>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-thumb-img">
                            <a href="<?= get_permalink(); ?>">
								<?= the_post_thumbnail(); ?>
                            </a>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title"><a href="<?= get_permalink(); ?>"><?= the_title(); ?></a></h2>
                            <p class="card-text">in <?= $cat_name ?></p>
                            <div class="ratings">
                                <p><?php echo do_shortcode( '[course_rating id="' . $post_id . '"]' ) . " " . number_format( (float) $ratings['rated'], 2, '.', '' ) . " " . "(" . $ratings['total'] . ")" ?></p>
                            </div>
                            <h3><?= empty( $price ) ? 'Free' : "$ " . $price; ?></h3>
                            <a href="<?= get_permalink(); ?>"><span><i class="fas fa-external-link-alt"></i></span></a>
                        </div>
                    </div>
                </div>
			<?php endwhile; else: ?>

                <p>Nothing Here.</p>

			<?php endif;
			wp_reset_postdata(); ?>
        </div>
		<?php
	}

	public function course_rating( $atts )
	{
		$atts    = shortcode_atts(
			array(
				'id' => null
			), $atts, 'course_rating'
		);
		$ratings = learn_press_get_course_rate( $atts['id'], false );
		$return  = '';
		if ( ! empty( $atts['id'] ) ) {
			$ratings = learn_press_get_course_rate( $atts['id'], false );
			$rated   = $ratings['rated'];
			if ( $ratings['rated'] == null ) {
				$rated = 0;
			}
			for ( $x = 1; $x <= 5; $x ++ ) {
				$return .= '<span class="rating"><i class="' . ( $rated >= $x ? "fa" : "far" ) . ' fa-star"></i></span>';
			}
		}

		return $return;
	}

	public function course_reviews()
	{
		$terms = get_terms( array(
			                    'taxonomy'   => 'course_category',
			                    'hide_empty' => 0
		                    )
		);

		$comments = [];

		foreach ( $terms as $term ) {
			$posts = get_posts(
				array(
					'post_type' => 'lp_course',
					'tax_query' => array(
						array(
							'taxonomy' => 'course_category',
							'field'    => 'term_id',
							'terms'    => $term->term_id,
						)
					)
				)
			);
			foreach ( $posts as $post ) {
				$comments = array_merge( $comments, get_comments(
					array(
						'post_id' => $post->ID
					)
				) );
			}
		}
		shuffle( $comments );
		?>

        <div class="owl-carousel owl-theme" id="owl-review">
            <?php
				foreach ( $comments as $key => $comment ) {
					$rating     = get_comment_meta( $comment->comment_ID, '_lpr_rating', true );
					$categories = wp_get_post_terms( $comment->comment_post_ID, 'course_category' );
					?>
            <div class="item">
                <div class="media">
                    <div class="media-left">
                        <img class="media-object" src="https://dummyimage.com/100x100/b8b6b8/030303" alt="...">
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading"><?= $comment->comment_author; ?></h4>
                        <div class="ratings">
                            <?php
                            for ( $x = 1; $x <= 5; $x ++ ) {
                                echo '<span class="rating"><i class="' . ( $rating >= $x ? "fa" : "far" ) . ' fa-star"></i></span>';
                            }
                            ?>
                        </div>
                        <?php
                        foreach ( $categories as $category ) {
                            echo '<h5> in ' . $category->name . '</h5>';
                        }
                        ?>
                        <p>
                            <?= $comment->comment_content ?>
                        </p>
                    </div>
                </div>
            </div>
                    <?php } ?>
        </div>

		<?php
	}

	public function list_course_categories()
	{
		$terms = get_terms( array(
			                    'taxonomy'   => 'course_category',
			                    'hide_empty' => 0
		                    )
		);
		ob_start();
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			?>
			<?php foreach ( $terms as $term ) { ?>
                <div class="course-cat">
                    <a href="<?= get_term_link( $term->term_id, 'course_category' ); ?>">
                        <div class="course-category" role="alert">
							<?php
							$course_count = $term->count;
							?>
                            <ul class="custom">
                                <li><?= $term->name; ?></li>
                                <li><?= "(" . $course_count . ")"; ?></li>
                            </ul>
                        </div>
                    </a>
                </div>
			<?php }
		} else {
			$error_string = $terms->get_error_message();
			echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
		}
		$return = ob_get_clean();

		return $return;
	}

	public function popular_course()
	{
		$args      = array(
			'post_type'      => 'lp_course',
			'orderby'        => 'rating',
			'order'          => 'asc',
			'posts_per_page' => 12
		);
		$the_query = new WP_Query( $args );
		$flag      = true;
		$count     = 0;
//        $posts = $the_query->post_count;
		ob_start();
		?>
        <div id="pop-courses">
            <div class="owl-carousel owl-theme">
                <?php
                $post_count = $the_query->post_count;
                if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
                    $count ++;
                    $post_id    = get_the_ID();
                    $ratings    = learn_press_get_course_rate( $post_id, false );
                    $price      = get_post_meta( $post_id, '_lp_price', true );
                    $course_cat = wp_get_object_terms( $post_id, 'course_category', array( 'fields' => 'names' ) );
                    $cat_name   = 'Uncategorized';
                    if ( isset( $course_cat[0] ) ) {
                        $cat_name = $course_cat[0];
                    }
                    $featured = get_post_meta($post_id, '_lp_featured', true);
                    if($featured == 'yes'){
                        ?>
                        <div class="item popular-courses">
                            <div class="card">
                                <div class="card-thumb-img">
                                    <a href="<?= get_permalink(); ?>">
                                        <?= the_post_thumbnail(); ?>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="heading_and_text" style="display: inline-grid;">
                                        <h2 class="card-title"><a href="<?= get_permalink(); ?>"><?= the_title(); ?></a></h2>
                                        <p class="card-text">in <?= $cat_name ?></p>
                                    </div>
                                    <div class="ratings">
                                        <p><?php echo do_shortcode( '[course_rating id="' . $post_id . '"]' ) . " " . number_format( (float) $ratings['rated'], 2, '.', '' ) . " " . "(" . $ratings['total'] . ")" ?></p>
                                    </div>
                                    <h3><?= empty( $price ) ? 'Free' : "$ " . number_format( $price, 2 ); ?></h3>
                                    <a href="<?= get_permalink(); ?>"><span><i class="fas fa-external-link-alt"></i></span></a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php endwhile; ?>
                <?php endif;
                wp_reset_postdata(); ?>
            </div>
        </div>
        <?php
		$return = ob_get_clean();

		return $return;
	}

	public function instructor_header_details()
	{
		$user_id            = get_current_user_id();
		$args               = array(
			'post_type'      => 'lp_course',
			'post_status'    => 'publish',
			'author'         => $user_id,
			'posts_per_page' => - 1
		);
		$current_user_posts = get_posts( $args );
		$total              = count( $current_user_posts );


		$total_students = 0;
		foreach ( $current_user_posts as $post_id ) {
			$course         = learn_press_get_course( $post_id->ID );
			$students       = $course->get_users_enrolled();
			$total_students += $students;
		}


		$total_reviews = 0;
		foreach ( $current_user_posts as $post_id ) {
			$ratings       = learn_press_get_course_rate( $post_id->ID, false );
			$total_reviews += $ratings['total'];
		}

		$total_ratings = 0;
		foreach ( $current_user_posts as $post_id ) {
			$ratings       = learn_press_get_course_rate( $post_id->ID, false );
			$total_ratings += (float) $ratings['rated'];
		}
		$role = get_user_meta( $user_id, 'wp_capabilities', true );
		$role = array_keys( $role );
		ob_start();
		?>
		<?php if ( in_array( 'lp_teacher', $role ) ) { ?>
        <div class="instructor-header">
            <ul>
                <li>
					<?php
					if ( $total_ratings == '0' ) {
						for ( $i = 0; $i <= 4; $i ++ ) {
							echo '<span class="rating"><i class="far fa-star"></i></span>';
						}
						echo ' 0';
					} else {
						$sum_ratings = $total_ratings / $total_reviews;
						for ( $x = 1; $x <= 5; $x ++ ) {
							echo '<span class="rating"><i class="' . ( $sum_ratings >= $x ? "fa" : "far" ) . ' fa-star"></i></span>';
						}
						echo ' ' . number_format( $sum_ratings, 2, '.', '' );
					}
					?>
                </li>
                <li><span><i class="fa fa-comment"></i></span> <?= $total_reviews; ?> Reviews</li>
                <li><span><i class="fa fa-user"></i></span> <?= $total_students; ?> Students</li>
                <li><span><i class="fa fa-play"></i></span> <?= $total ?> Courses</li>
            </ul>
        </div>
		<?php
	}
		$return = ob_get_clean();

		return $return;
	}

	public function top_rated_courses( $atts )
	{
		$atts = shortcode_atts(
			array(
				'count' => '4',
			), $atts, 'list_top_rated_courses'
		);
//        echo 'abc';
		$args      = array(
			'post_type'      => 'lp_course',
			'orderby'        => 'comment_count',
			'order'          => 'desc',
			'posts_per_page' => $atts['count']
		);
		$the_query = new WP_Query( $args );
		?>
        <div class="row">
			<?php
			if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
				?>
				<?php
				$post_id = get_the_ID();
				$ratings = learn_press_get_course_rate( $post_id, false );
//            $users = learn_press_get_course_rate_total($post_id, false);
				$price      = get_post_meta( $post_id, '_lp_price', true );
				$course_cat = wp_get_object_terms( $post_id, 'course_category', array( 'fields' => 'names' ) );
				$cat_name   = 'Uncategorized';
				if ( isset( $course_cat[0] ) ) {
					$cat_name = $course_cat[0];
				}
				?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-thumb-img">
                            <a href="<?= get_permalink(); ?>">
								<?= the_post_thumbnail(); ?>
                            </a>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title"><a href="<?= get_permalink(); ?>"><?= the_title(); ?></a></h2>
                            <p class="card-text">in <?= $cat_name ?></p>
                            <div class="ratings">
                                <p><?php echo do_shortcode( '[course_rating id="' . $post_id . '"]' ) . " " . number_format( (float) $ratings['rated'], 2, '.', '' ) . " " . "(" . $ratings['total'] . ")" ?></p>
                            </div>
                            <h3><?= empty( $price ) ? 'Free' : "$ " . number_format( $price, 2 ); ?></h3>
                            <a href="<?= get_permalink(); ?>"><span><i class="fas fa-external-link-alt"></i></span></a>
                        </div>
                    </div>
                </div>
			<?php endwhile; else: ?>

                <p>Nothing Here.</p>

			<?php endif;
			wp_reset_postdata(); ?>
        </div>
		<?php
	}

    public function pop_course()
    {
        $args      = array(
            'post_type'      => 'lp_course',
            'orderby'        => 'rating',
            'order'          => 'asc',
            'posts_per_page' => 12
        );
        $the_query = new WP_Query( $args );
        $flag      = true;
        $count     = 0;
//        $posts = $the_query->post_count;
        ob_start();
        ?>
        <div id="courseCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php for ( $i = 1; $i <= 3; $i ++ ) { ?>
                    <li data-target="#courseCarousel" data-slide-to="<?= $i - 1; ?>"
                        class="<?= $i == 1 ? 'active' : '' ?>"></li>
                <?php } ?>
            </ol>
            <div class="carousel-inner">
                <?php
                $post_count = $the_query->post_count;
                if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
                    $count ++;
                    $post_id    = get_the_ID();
                    $ratings    = learn_press_get_course_rate( $post_id, false );
                    $price      = get_post_meta( $post_id, '_lp_price', true );
                    $course_cat = wp_get_object_terms( $post_id, 'course_category', array( 'fields' => 'names' ) );
                    $cat_name   = 'Uncategorized';
                    if ( isset( $course_cat[0] ) ) {
                        $cat_name = $course_cat[0];
                    }
                    ?>
                    <?php
                    if ( $flag ) {
                        $flag = false;
                        ?>
                        <div class="carousel-item <?= ( $count == 1 ? "active" : "" ) ?>">
                        <div class="row" style="width : 100%">
                    <?php } ?>
                    <div class="col-md-3 col-xs-12">

                        <div class="card">
                            <div class="card-thumb-img">
                                <a href="<?= get_permalink(); ?>">
                                    <?= the_post_thumbnail(); ?>
                                </a>
                            </div>
                            <div class="card-body">
                                <h2 class="card-title"><a href="<?= get_permalink(); ?>"><?= the_title(); ?></a></h2>
                                <p class="card-text">in <?= $cat_name ?></p>
                                <div class="ratings">
                                    <p><?php echo do_shortcode( '[course_rating id="' . $post_id . '"]' ) . " " . number_format( (float) $ratings['rated'], 2, '.', '' ) . " " . "(" . $ratings['total'] . ")" ?></p>
                                </div>
                                <h3><?= empty( $price ) ? 'Free' : "$ " . number_format( $price, 2 ); ?></h3>
                                <a href="<?= get_permalink(); ?>"><span><i class="fas fa-external-link-alt"></i></span></a>
                            </div>
                        </div>
                    </div>
                    <?php if ( $count % 4 == 0 || $count == $post_count ) {
                        $flag = true;
                        ?>
                        </div>
                        </div>
                    <?php } endwhile; else: ?>

                    <p>Nothing Here.</p>

                <?php endif;
                wp_reset_postdata(); ?>
            </div>
        </div>
        <?php
        $return = ob_get_clean();

        return $return;
    }

}