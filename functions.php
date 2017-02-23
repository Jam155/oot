<?php

add_action( 'after_setup_theme', 'oot_setup' );
function oot_setup()
{
load_theme_textdomain( 'oot', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );
global $content_width;
if ( ! isset( $content_width ) ) $content_width = 640;
register_nav_menus(
array( 'main-menu' => __( 'Main Menu', 'oot' ) )
);
}
add_action( 'wp_enqueue_scripts', 'oot_load_scripts' );
function oot_load_scripts()
{
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('event-calender', get_template_directory_uri() . '/js/event-calander.js');
	wp_localize_script('event-calender', 'site_url', get_site_url());
}
add_action( 'comment_form_before', 'oot_enqueue_comment_reply_script' );
function oot_enqueue_comment_reply_script()
{
if ( get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}
add_filter( 'the_title', 'oot_title' );
function oot_title( $title ) {
if ( $title == '' ) {
return '&rarr;';
} else {
return $title;
}
}
add_filter( 'wp_title', 'oot_filter_wp_title' );
function oot_filter_wp_title( $title )
{
return $title . esc_attr( get_bloginfo( 'name' ) );
}
add_action( 'widgets_init', 'oot_widgets_init' );
function oot_widgets_init()
{
register_sidebar( array (
'name' => __( 'Sidebar Widget Area', 'oot' ),
'id' => 'primary-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => "</li>",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
}
function oot_custom_pings( $comment )
{
$GLOBALS['comment'] = $comment;
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
<?php 
}
add_filter( 'get_comments_number', 'oot_comments_number' );
function oot_comments_number( $count )
{
if ( !is_admin() ) {
global $id;
$comments_by_type = &separate_comments( get_comments( 'status=approve&post_id=' . $id ) );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}

add_filter( 'acf/rest_api/key', function( $key, $request, $type ) {
    return 'acf_fields';
}, 10, 3 );

add_action( 'wp_enqueue_scripts', function() {

	//load script
	wp_enqueue_script( 'offer-post-submitter', get_template_directory_uri() . '/js/offer-submitter.js', array( 'jquery' ) );

	//localize data for script
	wp_localize_script( 'offer-post-submitter', 'POST_SUBMITTER', array(
			'root' => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
			'success' => __( 'Thanks for your submission!', 'oot' ),
			'failure' => __( 'Your submission could not be processed.', 'oot' ),
			'current_user_id' => get_current_user_id()
		)
	);

});

function oot_login_logo() { ?>
	<style type="text/css">
		body.login div#login h1 a {
			background-image: url(http://oot.kinocreative.uk/wp-content/themes/oot/images/admin-logo.png);
			height: 153px;
			width: 100%;
			max-width: 208px;
			background-size: 100%;
			margin: 0 auto 55px;
		}
	</style>
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script>
		jQuery(document).ready(function($) {
			$('#loginform input[type="text"]').attr('placeholder', 'Username');
			$('#loginform input[type="password"]').attr('placeholder', 'Password');
			
			$('#loginform label[for="user_login"]').contents().filter(function() {
				return this.nodeType === 3;
			}).remove();
			$('#loginform label[for="user_pass"]').contents().filter(function() {
				return this.nodeType === 3;
			}).remove();
			
			$('input[type="checkbox"]').click(function() {
				$(this+':checked').parent('label').css("background-position","0px -20px");
				$(this).not(':checked').parent('label').css("background-position","0px 0px");
			});
		});
	</script>
<?php }
add_action( 'login_enqueue_scripts', 'oot_login_logo' );

function custom_login_css() { 
	echo '<link rel="stylesheet" type="text/css" media="all" href="' . get_stylesheet_directory_uri() . '/custom-login.css" />';
} 
add_action('login_head', 'custom_login_css');

function oot_login_logo_url() {
	return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'oot_login_logo_url' );

function oot_login_logo_url_title() {
	return 'Oot';
}
add_filter( 'login_headertitle', 'oot_login_logo_url_title' );

function oot_login_head() {
	remove_action('login_head', 'wp_shake_js', 12);
}
add_action('login_head', 'oot_login_head');

function change_lostpassword_text ( $text ) {
     if ($text == 'Lost your password?'){$text = 'Reset it now.';}
        return $text;
     }
add_filter( 'gettext', 'change_lostpassword_text' );

add_action('admin_menu', 'remove_built_in_roles');
 
function remove_built_in_roles() {
    global $wp_roles;
 
    $roles_to_remove = array('subscriber', 'contributor', 'author', 'editor', 'basic_contributor');
 
    foreach ($roles_to_remove as $role) {
        if (isset($wp_roles->roles[$role])) {
            $wp_roles->remove_role($role);
        }
    }
}

add_role( 'venue_owner', __( 'Venue Owner' ), array() );
add_role( 'venue_manager', __( 'Venue Manager' ), array() );

// Add Oot post types
add_action( 'init', 'create_post_type' );
function create_post_type() {
	
	// VENUES
	register_post_type( 'venue',
	array(
	  'labels' => array(
		'name' => __( 'Venues' ),
		'singular_name' => __( 'Venue' )
	  ),
	  'public' => true,
	  'menu_icon' => 'dashicons-location',
	  'show_in_rest' => true,
	  'rest_base' => 'venue',
	  'rest_controller_class' => 'Venues_Controller',
	  'taxonomies'  => array( 'category', 'post_tag' ),
	  'has_archive' => false,
	  'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields')
	)
	);
  
	// OFFERS
	register_post_type( 'offer',
	array(
	  'labels' => array(
		'name' => __( 'Offers' ),
		'singular_name' => __( 'Offer' )
	  ),
	  'public' => true,
	  'menu_icon' => 'dashicons-tag',
	  'show_in_rest' => true,
	  'rest_base' => 'offer',
	  'rest_controller_class' => 'Offers_Controller',
	  'taxonomies'  => array( 'category', 'post_tag' ),
	  'has_archive' => false,
	  'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields')
	)
	);
	
	// EVENTS
	register_post_type( 'event',
	array(
	  'labels' => array(
		'name' => __( 'Events' ),
		'singular_name' => __( 'Event' )
	  ),
	  'public' => true,
	  'menu_icon' => 'dashicons-megaphone',
	  'show_in_rest' => true,
	  'rest_base' => 'event',
	  'rest_controller_class' => 'Events_Controller',
	  'taxonomies'  => array( 'category', 'post_tag' ),
	  'has_archive' => false,
	  'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields')
	)
	);
}

function oot_dashicons() {
   echo "<style type='text/css' media='screen'>
			.menu-icon-event div.wp-menu-image:before {
				color: #be1e40 !important;
			}
			.menu-icon-offer div.wp-menu-image:before {
				color: #a1be3e !important;
			}
			.menu-icon-venue div.wp-menu-image:before {
				color: #6e508e !important;
			}
     	</style>";
 }
add_action('admin_head', 'oot_dashicons');

function oot_allow_post_types( $allowed_post_types ) {
    $allowed_post_types[] = array('venue', 'event', 'offer');
    return $allowed_post_types;
}
add_filter( 'rest_api_allowed_post_types', 'oot_allow_post_types');
require_once('includes/Venues_Controller.php');
require_once('includes/Offers_Controller.php');
require_once('includes/Events_Controller.php');
