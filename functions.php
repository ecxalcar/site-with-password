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

// function form_password_page() {
// 	global $post;
// 	// Check if is sub-page:
// 	if (is_page() && $post->post_parent > 0 ) { // verifica si es padre o hijo $post->post_parent retorna id de padre
		
// 		if ( post_password_required( $post->post_parent ) ) { //verifica si padre requiere contraseña y se ha proporcionado la contraseña correcta - true
// 			//Redirect to parent page
// 			$parent_url = get_permalink($post->post_parent); // obtiene url por id del padre
// 			header('Location:' . $parent_url);
// 			exit();
// 		}
// 		// else {
// 			// 	//falso si no se requiere una contraseña o está presente la cookie de contraseña correcta. CUANDO YA SE INGRESO LA CONTRASEñA CORRECTA
// 			// }
// 	} else {
// 		// if(	$post->post_password && !post_password_required($post->ID) && $post->post_parent !== 0 ) {

// 			$redirect_page = get_field('redirect_page_to', $post->ID);
// 			$page_url = get_field('page_url', $post->ID);

// 			// if ($redirect_page == 'childPage' ) {
// 			// 	echo 'redirect to child page';
// 			// } elseif($redirect_page == 'urlPage' ) {
// 			// 	echo 'redirect to specific page';
// 			// 	echo $page_url;
// 			// }

// 			// var_dump($post->post_password); // retorna la contraseña de la pagina
// 			// var_dump(!post_password_required($post->ID)); // retorna true cuando ya esta desbloqueado.
// 			// var_dump($post->post_parent !== 0); // retorna falso siempre bloqueado/desbloqueado
// 			// var_dump($post->post_parent); // retorna 0 siempre - retorna si la pagina es de nivel superior

// 			if(	$post->post_password && !post_password_required($post->ID) ) {
// 				if ($redirect_page == 'childPage' ) {
// 					$pagekids = get_pages("child_of=".$post->ID."&sort_column=menu_order"); // muestra todas las paginas hijo con su informacion
// 					if ($pagekids) {
// 						$firstchild = $pagekids[0];
// 						wp_redirect(get_permalink($firstchild->ID));
// 						exit;
// 					}

// 				} else {
// 					wp_redirect($page_url);
// 					exit;
// 				}
// 			}
// 	}
// }

// // Remove “Protected:” prefix for password protected page
// add_filter( 'protected_title_format', 'remove_protected_text' );
// 	function remove_protected_text() {
// 	return __('%s');
// }

//Add 3 Children to Custom Pos Type
function add_children_custom_post_type( $post_id ) {  
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( !wp_is_post_revision( $post_id ) && 'companies' == get_post_type( $post_id ) && 'publish' != get_post_status( $post_id ) ) {  
        $show = get_post( $post_id );
        if( 0 == $show->post_parent ){
            $children =& get_children(
                array(
                    'post_parent' => $post_id,
                    'post_type' => 'companies'
                )
            );
            if( empty( $children ) ){

				//Child page 
				$titles	= ['History', 'About', 'Contact'];
				foreach ($titles as $title) {
					$child = array(
						'post_type' => 'companies',
						'post_title' => $title,
						'post_content' => '',
						'post_status' => 'publish',
						'post_parent' => $post_id,
						'post_author' => 1
					);
					wp_insert_post( $child );
				}
            }
        }
    }
}
add_action( 'save_post', 'add_children_custom_post_type' );


function form_password_page() {
	global $post;
	$password_page = get_field('password_page', $post->ID);

	if (!empty($password_page)) {
		add_filter('the_content', 'my_custom_password_form');
		echo 'tiene contraseña ';
	}

	if(is_page() && $post->post_parent) {
		// echo 'es subpage';
		$parent_has_password = get_field('password_page', $post->post_parent );
		if ($parent_has_password) {
			// echo 'padre tiene pass';
			$parent_url = get_permalink($post->post_parent); // obtiene url por id del padre
			header('Location:' . $parent_url);
			exit();

		} else  {
			echo 'padre no tiene contraseña';
		}
	}

	function my_custom_password_form() {
		global $post;
		$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
		$output = '
		<div class="boldgrid-section">
			<div class="container">
				<form class="form" method="post" action="">
					<p>' . __( 'This is my Custom Form . This content is password protected. To view it please enter your password below:' ) . '</p>
					<label for="' . $label . '">' . __( 'Password:' ) . ' <input name="post_password" id="' . $label . '" type="password" size="20" class="form-control" /></label><button type="submit" name="Submit" class="button-primary">' . esc_attr_x( 'Enter', 'post password form' ) . '</button>
				</form>
			</div>
		</div>';
		return $output;
	}

}

// $password = $_POST['post_password'];
// Find a post with the ACF Custom value that matches our username.
// $user_query = new WP_Query(array( 
//     'posts_per_page' => 1, 
//     'post_type' => 'page', 
//     'meta_query' => array( 
// 		'relation' => 'or', 
// 		array( 
// 			'key' => 'password', 
// 			'value' => $password, 
// 			'compare' => '=' 
// 		) 
//     ) 
// ));

// if ($user_query->have_posts()) { 
//     while($user_query->have_posts()){
//         // Load the current post.
//         $user_query->the_post(); 
//         // Get the current post.
//         $user = get_post();
//         // Get the hashed password from the post.
//         $hashed_password = get_post_meta($user->ID, 'password', true); 
//         // Compare the hashed passwords.
//         if (wp_check_password(wp_hash_password($password), $hashed_password)) { 
//             echo "logged in successfull"; 
//         } else { 
//             echo "user found, password incorrect"; 
//         }
//     }
// }

if ( $_POST['submit'] ) {
		if ($password == 'Parent 1 Home') {
			header('Location: http://sitewithpassword.local/about/history/');
		}

	// if ( $_POST['post_password'] == $password_page ) {
	
	// 	// setcookie( 'wp-postpass_' . COOKIEHASH, $hasher->HashPassword( wp_unslash( $_POST['post_password'] ) ), $expire, COOKIEPATH, COOKIE_DOMAIN, $secure );
	// 	// setcookie( "show_page", get_the_ID(), time() + 1 * 24 * 60 * 60);
	// 	echo 'Cookie creada';
	// } else {
	// 	echo 'Cookie no creada';
	// }
}



// Delete Custom Post Type Children

// function clear_all_childs($post_id){
// 	$args = array( 
// 		'post_parent' => $parent_id,
// 		'post_type' => 'companies'
// 	);
	
// 	$posts = get_posts( $args );
	
// 	if (is_array($posts) && count($posts) > 0) {
		
// 		// Delete all the Children of the Parent Page
// 		foreach($posts as $post){
// 			wp_delete_post($post->ID, true);
// 		}
// 	}
	
// 	// Delete the Parent Page
// 	wp_delete_post($parent_id, true);
// }
// add_filter('delete_post', 'clear_all_childs');

// add_action('trashed_post', 'clear_all_children');

// function clear_all_children($post_id){
//     $childs = get_post(
// 		array(
// 			'post_parent' => $post_id,
// 			'post_type' => 'companies' 
// 		)
//     );

//     if(empty($childs)) {
//         return;
// 	}

//     foreach($childs as $post){
//         wp_delete_post($post->ID, true); // true => bypass trash and permanently delete
//     }
// }

?>

