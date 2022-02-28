<?php

function takeOff_setup() {
	// Ready for i18n
	load_theme_textdomain( "takeOff", get_template_directory(). '/languages');

	// Use thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for custom logo.
	add_theme_support( 'custom-logo', array(
		'height' => 240,
		'width' => 240,
		'flex-height' => true,
	) );

	// Register Navigation Menus
	register_nav_menus(array(
		'header-menu' => 'Header Menu',
		'footer-menu' => 'Footer Menu',
	));

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
}
add_action( 'after_setup_theme', 'takeOff_setup' );

function takeOff_js()
{
	if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

		wp_register_script('jquery', get_template_directory_uri() . '/assets/js/jquery-3.5.0.min.js', array(), '');
		wp_enqueue_script('jquery'); // Enqueue it!

		wp_register_script('slick-slider', get_template_directory_uri() . '/assets/js/slick.min.js', array(), '');
		wp_enqueue_script('slick-slider'); // Enqueue it!

		wp_register_script('my-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array(), ''); 
		wp_enqueue_script('my-scripts'); // Enqueue it!
	}
}
add_action('init', 'takeOff_js'); // Add Custom Scripts to wp_head

function takeOff_css() {
	wp_enqueue_style( 'slick-slider', get_template_directory_uri() . '/assets/css/slick.css' );
	wp_enqueue_style( 'slick-slider-theme', get_template_directory_uri() . '/assets/css/slick-theme.css' );
	wp_enqueue_style( 'styles', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'my-styles', get_template_directory_uri() . '/assets/css/styles.css' );
}
add_action( 'wp_enqueue_scripts', 'takeOff_css' );

// Removes some links from the header
function takeOff_remove_headlinks() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'start_post_rel_link' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link' );
	remove_action( 'wp_head', 'parent_post_rel_link' );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'takeOff_remove_headlinks' );

//-----------------------FUNCTIONS FOR LOAD MORE POSTS BUTTON-----------------------
function my_load_more_scripts() {
	if ( is_home() ) {
		global $wp_query; 

	   // In most cases it is already included on the page and this line can be removed
		wp_enqueue_script('jquery');

	   // register our main script but do not enqueue it yet
		wp_register_script( 'my_loadmore', get_stylesheet_directory_uri() . '/loadmore.js', array('jquery') );

	   // now the most interesting part
	   // we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
	   // you can define variables directly in your HTML but I decided that the most proper way is wp_localize_script()
		wp_localize_script( 'my_loadmore', 'loadmore_params', array(
			'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
			'posts' => serialize( $wp_query->query_vars ), // everything about your loop is here
			'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
			'max_page' => $wp_query->max_num_pages
		) );

		wp_enqueue_script( 'my_loadmore' );
	}
}

function loadmore_ajax_handler(){

	// prepare our arguments for the query
	$args = json_decode( stripslashes( $_POST['query'] ), true );
	$args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
	$args['post_status'] = 'publish';

	// it is always better to use WP_Query but not here
	query_posts( $args );

	if( have_posts() ) :
		// run the loop
		while( have_posts() ): the_post();

		$feature_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
?>
			<a href="<?php the_permalink();?>"  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if($feature_image){
				?>
					<div class="blog-preview cover-img" style="background-image: url(<?php echo $feature_image ?>)"> 
				<?php 
				} else {
				?>
					<div class="blog-preview cover-img" style="background-image: url(<?php echo get_template_directory_uri() ?>/assets/img/thumbnail.png)">
				<?php
				} ?>
					<h2><?php the_title(); ?></h2>
				</div><!--blog-preview-->
			</a>
<?php
		endwhile;
	endif;
	die; // here we exit the script and even no wp_reset_query() required!
}
add_action('wp_ajax_loadmore', 'loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'loadmore_ajax_handler'); // wp_ajax_nopriv_{action}

require get_template_directory() . '/shortcodes/code-shortcodes.php';

//Page options 'RESOURCES POSTS SECTION'
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title'    	=> 'Theme Options',
		'icon_url'			=> 'dashicons-feedback'
	));
}

function form_password_page() {
	global $post;
	// Check if is sub-page: 
	if (is_page() && $post->post_parent ) {
		$ancestors = $post->ancestors;
		foreach ( $ancestors as $ancestor ) {
			// Check if parent page has password
			if ( post_password_required( $ancestor ) ) {

				//Redirect to parent page
				$parent_url = get_permalink($post->post_parent);
				header('Location:' . $parent_url);
				exit();
			}
		}
	} 

	// Parent page:
	else {
		// var_dump($post->post_password);
		// var_dump(post_password_required($post->ID));
		if(	$post->post_password && !post_password_required($post->ID) && $post->post_parent !== 0 ) {
			//Redirect to first child page when correct password is entered
			$pagekids = get_pages("child_of=".$post->ID."&sort_column=menu_order");
			if ($pagekids) {
				if (!get_post_meta($post->ID, 'dont_redirect', true)) {
					$firstchild = $pagekids[0];
					wp_redirect(get_permalink($firstchild->ID));
					exit;
				}
			}
		}
	}
}

// Remove “Protected:” prefix for password protected page
add_filter( 'protected_title_format', 'remove_protected_text' );
	function remove_protected_text() {
	return __('%s');
}
?>