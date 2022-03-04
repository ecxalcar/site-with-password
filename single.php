<?php do_action( 'delete_post', $post_id );  ?>
<?php get_header(); ?>

	<main role="main">
	<!-- section -->
		<section>

			<?php if (have_posts()): while (have_posts()) : the_post(); ?>

				<div class="img-post">
					<?php echo get_the_post_thumbnail();?>
				</div>

				<div class="container pt-5">
					<div class="row">
						<div class="col-md-8">
							<h1><?php the_title(); ?></h1>
							<h6><?php the_category(); ?></h6>
							<p><?php the_content(); ?></p>
						</div>

						<div class="col-md-4">
							<span><?php echo do_shortcode('[related_posts]'); ?></span>
						</div>
					</div>
					<br>
					<br>
				</div>
			<?php endwhile; ?>

			<?php else: ?>

				<!-- article -->
				<article>
					<h1>No more posts</h1>
				</article>
				<!-- /article -->

			<?php endif; ?>

		</section>
	<!-- /section -->
	</main>


<?php get_footer(); ?>
