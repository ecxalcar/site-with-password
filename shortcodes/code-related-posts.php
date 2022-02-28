<?php
/*related posts by tag shortcode */

function dtwd_related_posts_shortcode($atts){ 
	$related_posts_number = get_field('related_posts_number', 'option');
	extract(shortcode_atts(
		array( 
			'count' => $related_posts_number,
			'title' => 'Related Posts', 
		), $atts)); 

		global $post; 
		$current_cat = get_the_category($post->ID); 
		$current_cat = $current_cat[0]->cat_ID; $this_cat = ''; 
		$tag_ids = array();
		$tags = get_the_tags($post->ID);
		if ($tags) {
			foreach($tags as $tag) {
				$tag_ids[] = $tag->term_id;
				}
			}
		else {
			$this_cat = $current_cat;
		}
		$args = array(
			'post_type' => 'post',
			'numberposts' => $count,
			'order' => 'DESC',
			'tag__in' => $tag_ids,
			'cat' => $this_cat,
			'exclude' => $post->ID
		);

		$dtwd_related_posts = get_posts($args);
		if ( empty($dtwd_related_posts) ) {
			$args['tag__in'] = '';
			$args['cat'] = $current_cat;
			$dtwd_related_posts = get_posts($args);
		}

		if ( empty($dtwd_related_posts) ) {
			return;
		}

		$post_list = '';

		foreach($dtwd_related_posts as $dtwd_related) {
			$post_list .= '<li>'. get_the_post_thumbnail($dtwd_related->ID) . ' <a href="' . get_permalink($dtwd_related->ID) . '">' . $dtwd_related->post_title . '</a></li>'; 
			// $post_list .= '<li>' . var_dump($dtwd_related) . '</li>';
		}

		return sprintf(' <div class="dtwd_related-posts"><h4>'. $title .'</h4><ul>'. $post_list .'</ul></div><!-- .dtwd_related-posts --> '); }
		add_shortcode('related_posts', 'dtwd_related_posts_shortcode');?>