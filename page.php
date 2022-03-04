<?php get_header(); ?>


	<main role="main">
		<!-- section -->
		<section>
			<h1><?php the_title(); ?></h1>
			<!-- <h2>Hello</h2> -->

		<?php if (have_posts()): while (have_posts()) : the_post(); ?>

			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="container w-50 mx-auto">
				<?php
					the_content();

					//Hide custom fields when pages is protected
					$password_page = get_field('password_page', $post->ID);
					if( !$password_page ){

						$text = the_field('text');
						$description = the_field('description');
						echo $text;
						echo $description;
					}

				?>

				<?php 
				// if (function_exists('get_field')) { 
                //     $requirements = get_field('password_page');
                //         if($requirements){
                //             foreach($requirements as $requirement){
				// 				echo apply_filters('requirement', $requirement);
                //             } 
                //         } 
				// 	} 
				// ?>
			</div>

				<br class="clear">
				<?php edit_post_link(); ?>

			</article>
			<!-- /article -->

		<?php endwhile; ?>

		<?php else: ?>

			<!-- article -->
			<article>

				<h2><?php _e( 'Sorry, nothing to display.', 'takeOff' ); ?></h2>

			</article>
			<!-- /article -->

		<?php endif; ?>

		</section>
		<!-- /section -->
	</main>


<?php get_footer(); ?>
