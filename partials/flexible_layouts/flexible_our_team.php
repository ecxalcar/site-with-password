<!--OUR TEAM
===========================================-->
<?php
	$section_title = get_sub_field('section_title');
?>
<section id="our-team">
	<div class="container-fluid">
		<h1><?php echo $section_title; ?></h1>
		<div class="wrapper">
			<?php 
				$args= array(
					'post_type'			=> 'our_team',
					'posts_per_page'	=> 3
				);
				
				$ourTeamQuery = new WP_Query($args);
				
				if ($ourTeamQuery->have_posts()) {
					while ($ourTeamQuery->have_posts()) {
						$ourTeamQuery->the_post();

						$worker_avatar 		= get_field('worker_avatar');
						$worker_occupation 	= get_field('worker_occupation');
						$worker_description = get_field('worker_description');
				?>
					<div class="card">
						<div class="header">
							<div class="avatar">
								<?php if($worker_avatar): ?>
									<img src="<?php echo $worker_avatar['url']?>" alt="">
								<?php else: ?>
									<img src="<?php echo get_template_directory_uri()?>/assets/img/user.png" alt="">
									<?php endif; ?>
								</div><!--avatar-->
							<div class="name-occupation">
								<p><?php the_title(); ?></p>
								<p><b><?php echo $worker_occupation; ?></b></p>
							</div><!--name-occupation-->
						</div><!--header-->
						<div class="content">
							<p><?php echo $worker_description; ?></p>
						</div><!--content-->
					</div><!--card-->
				<?php

					}//endwhile
					wp_reset_query();
				}//endif
			?>
		</div><!--wrapper-->
	</div><!--container-fluid-->
</section><!--#our-team-->