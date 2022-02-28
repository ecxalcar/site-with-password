<?php

function code_posts($atts) {

	$posts = get_field('show_posts', 'option');

	$data = shortcode_atts(array(
		'layout' => '2'
	), $atts);

	$html = '';
	$html .= '<section id="clients my-5">
				<div class="row no-gutters">';

		if ($posts):
			foreach($posts as $post):
				if($data['layout'] == '2'):

					$html .= '<div class="col-sm-6">
								<div class="container-post">
									<a href="'. get_the_permalink($post->ID).'">';
										$html .= '<img src="' . get_the_post_thumbnail_url($post->ID) . '">';
										$html .= '<div class="overlay">
											<h5>'. get_the_title($post->ID).'</h5>
											<span>'. get_the_category($post->ID)[0]->name.'</span>
										</div>
									</a>
								</div>
							</div>';

				elseif($data['layout'] == '3'):

					$html .= '<div class="col-sm-6 col-md-4">
						<div class="container-post-sm">
							<a href="'. get_the_permalink($post->ID).'">';
								$html .= '<img src="' . get_the_post_thumbnail_url($post->ID) . '">';
								$html .= '<div class="overlay">
									<h5>'. get_the_title($post->ID).'</h5>
									<span>'. get_the_category($post->ID)[0]->name.'</span>
								</div>
							</a>
						</div>
					</div>';
				endif;

			endforeach;
			wp_reset_postdata();
		endif;

	$html .= '</div>
	</section>';
	return $html;
}
add_shortcode('new_code_posts', 'code_posts');
