<?php if (have_posts()): while (have_posts()) : the_post(); ?>

	<!-- article -->
	<!-- /article -->

<?php endwhile; ?>

<?php else: ?>

	<!-- article -->
	<article>
		<h2>No more posts</h2>
	</article>
	<!-- /article -->

<?php endif; ?>
