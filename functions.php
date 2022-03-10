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

// Add 3 Children to Custom Pos Type companies
function add_children_custom_post_type( $post_id ) {  
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( !wp_is_post_revision( $post_id ) && 'companies' == get_post_type( $post_id ) && 'publish' == get_post_status( $post_id ) ) {  
        $show = get_post( $post_id );
        if( 0 == $show->post_parent ){
            $children =& get_children(
                array(
                    'post_parent' => $post_id,
                    'post_type' => 'companies'
                )
            );
            if( empty( $children ) ){
				//Children pages
				$titles	= ['About', 'Contact', 'History'];
				foreach ($titles as $key=>$title) {
					$child = array(
						'post_type' => 'companies',
						'post_title' => $title,
						'post_content' => '',
						'post_status' => 'publish',
						'post_parent' => $post_id,
						'post_author' => get_post_field('post_author', $post_id),
						'menu_order' => $key
					);
					wp_insert_post( $child );
				}
            }
        }
    }
}
add_action( 'save_post', 'add_children_custom_post_type' );

// Move to Trash
function trash_post_children($post_id) {
	$parent_ID = $post_id;
	
	$args =  array(
		'post_type' => 'companies',
		'post_parent' => $parent_ID,
		'posts_per_page' => -1,
		'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
	);
	$children = get_posts($args);
	if($children) {
		foreach($children as $p){
			wp_trash_post($p->ID, true);
		}
	}
}
add_action('trashed_post', 'trash_post_children');

// Restore Post
function restore_post_children($post_id) {
	$parent_ID = $post_id;
	$args =  array(
		'post_type' => 'companies',
		'post_parent' => $parent_ID,
		'posts_per_page' => -1,
		'post_status'	=> 'trash'
	);
	$children = get_posts($args);
	if($children) {
		foreach($children as $p) {
			wp_untrash_post($p->ID);
		}
	}
}
add_action('untrash_post', 'restore_post_children');

// Delete all children
function remove_post_children($post_id) {
	$args = array(
		'post_parent' => $post_id,
		'post_type' => 'companies',
		'posts_per_page' => -1,
		'post_status'	=>  'trash'
	);
	$children = get_posts($args);
	if (is_array($children) && count($children) > 0) {
		foreach($children as $p){
			wp_delete_post($p->ID, true);
		}
	}
}
add_action('before_delete_post', 'remove_post_children');


//====PASSWORD PROTECTED CPT AND PAGES=======
//Register session
function register_my_session() {
    if(!session_id()) {
        session_start();
    }
}
add_action('init', 'register_my_session');

function form_password_page() {
    global $post;
    $password_page  = get_field('password_page', $post->ID);
    $redirect_to    = get_field('redirect_page_to', $post->ID);
    $page_url       = get_field('page_url', $post->ID);
    $string         = 'post_'.$post->ID.'_log';
    $form_password  = hash('sha512', $_POST['post_password']);

	//Show password form
	if (!empty($password_page) && $form_password != $password_page && $_SESSION[$string] == false) {
        add_filter('the_content', 'my_custom_password_form');
    }

	//Login pages
    if(isset($password_page) && !empty($password_page) && $form_password == $password_page) {
        $_SESSION[$string] = true;

		//Redirect to first child page
        if($redirect_to == 'childPage') {
            $children = get_children_pages($post->ID, $post->post_type);
            $url_first = get_permalink($children[0]->ID);
            ?><script>window.location.href = '<?= $url_first; ?>';</script><?php
            exit();
        }

		//Redirect to specific page
        if($redirect_to == 'urlPage') {
            ?><script>window.location.href = '<?= $page_url; ?>';</script><?php
        }
    }

	//Redirect to child page when password is right
    if($_SESSION[$string] == true && $post->post_parent == false) {
        if($redirect_to == 'childPage') {
            $children = get_children_pages($post->ID, $post->post_type);
            $url_first = get_permalink($children[0]->ID);
            ?><script>window.location.href = '<?= $url_first; ?>';</script><?php
            exit();
        }

        if($redirect_to == 'urlPage') {
            ?><script>window.location.href = '<?= $page_url; ?>';</script><?php
        }
    }

    // Validate password
    if(isset($_POST['post_password']) && $form_password != $password_page) {
        echo "the Password is not correct";
    }

	//Redirect parent page when session isn't set
    if($post->post_parent) {
        $string_child = 'post_'.$post->post_parent.'_log';
        $parent_url = get_permalink($post->post_parent);
        $pass_parent = get_field('password_page', $post->post_parent);
        if($_SESSION[$string_child] == false && !empty($pass_parent)) {
            ?><script>window.location.href = '<?= $parent_url; ?>';</script><?php
            exit();            
        }
    }

	//Custom password form
    function my_custom_password_form() {
        global $post;
        $label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
        $output = '
            <div>
                <div class="container">
                    <form class="form" method="POST" action="' . htmlspecialchars($_SERVER['SCRIPT_URI']) . '">
                        <p>' . __( 'This is my Custom Form . This content is password protected. To view it please enter your password below:' ) . '</p>
                        <label for="' . $label . '">' . __( 'Password:' ) . '
                        <input name="post_password" id="' . $label . '" type="password" size="20" class="form-control" /></label>
                        <button type="submit" name="Submit" class="button-primary">' . esc_attr_x( 'Enter', 'post password form' ) . '</button>
                    </form>
                </div>
            </div>';
        return $output;
    }
}

//Get first child page
function get_children_pages( $page_id, $post_type = 'page') {
    $custom_wp_query = new WP_Query();
    $all_wp_pages    = $custom_wp_query->query( 
		array(
			'post_type' 		=> $post_type, 
			'posts_per_page' 	=> -1, 
			'orderby' 			=> 'menu_order', 
			'order' 			=> 'ASC'
		) 
	);

    $page_children = get_page_children( $page_id, $all_wp_pages );
    return $page_children;
}

//Encrypt password protected in cf
function ns_function_encrypt_passwords($value, $post_id, $field) {
    if(!empty($value)) {
        $value = hash('sha512', $value);
        return $value;
    }
    return $value;
}
add_filter('acf/update_value/type=password', 'ns_function_encrypt_passwords', 10, 3);
?>

