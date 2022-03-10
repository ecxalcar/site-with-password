<?php get_header(); ?>

	<main role="main">
	<!-- section -->
		<section>

			<?php if (have_posts()): while (have_posts()) : the_post(); ?>

				<div class="container pt-5">
					<h1><?php the_title(); ?></h1>
					<h6><?php the_category(); ?></h6>
					<p><?php the_content(); ?></p>
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
