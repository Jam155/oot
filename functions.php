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

function oot_load_scripts(){
	wp_enqueue_script( 'wp-util' );
	wp_enqueue_script('oot-jquery', 'https://code.jquery.com/jquery-1.12.4.js');
	wp_enqueue_script('event-calendar', get_template_directory_uri() . '/js/event-calendar.js');
	wp_localize_script('event-calendar', 'site_url', get_site_url());

	//load script
	wp_enqueue_script( 'offer-post-submitter', get_template_directory_uri() . '/js/offer-submitter.js', array() );	
	wp_enqueue_script( 'jquery-ui-script', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array());
	wp_enqueue_script( 'wickedpicker-script', get_template_directory_uri() . '/js/wickedpicker.min.js', array());
	wp_enqueue_script( 'glDatePicker-script', get_template_directory_uri() . '/js/glDatePicker.min.js', array());
	wp_enqueue_script( 'he-script', get_template_directory_uri() . '/js/he.js', array());
	wp_enqueue_script( 'moment-script', get_template_directory_uri() . '/js/moment.js', array());
	wp_enqueue_script( 'monthly-script', get_template_directory_uri() . '/js/monthly.js', array());

	//load styles
	wp_enqueue_style('oot-style', get_template_directory_uri() . '/css/oot-style.css');
	wp_enqueue_style('jquery-ui-style', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
	wp_enqueue_style('wickedpicker-style', get_template_directory_uri() . '/css/wickedpicker.min.css');
	wp_enqueue_style('glDatePicker-style', get_template_directory_uri() . '/css/glDatePicker.flatwhite.css');
	wp_enqueue_style('monthly-style', get_template_directory_uri() . '/css/monthly.css');

	//Oot Scripts
	wp_enqueue_script( 'oot-header-script', get_template_directory_uri() . '/js/header.js', array(), null, '');	
	wp_localize_script('oot-header-script', 'template_url', get_template_directory_uri());

	wp_enqueue_script( 'add-fontawesome', 'https://use.fontawesome.com/70c98e5b29.js', array(), null, true);	

	//localize data for script
	wp_localize_script( 'offer-post-submitter', 'POST_SUBMITTER', array(
		'root' => esc_url_raw( rest_url() ),
		'nonce' => wp_create_nonce( 'wp_rest' ),
		'success' => __( 'Thanks for your submission!', 'oot' ),
		'failure' => __( 'Your submission could not be processed.', 'oot' ),
		'current_user_id' => get_current_user_id()
	));
};
add_action( 'wp_enqueue_scripts', 'oot_load_scripts' );


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

function oot_mime_types($mime_types){
    //Creating a new array will reset the allowed filetypes
    $mime_types = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif' => 'image/gif',
        'png' => 'image/png',
        'bmp' => 'image/bmp',
        'tif|tiff' => 'image/tiff'
    );
    return $mime_types;
}
add_filter('upload_mimes', 'oot_mime_types', 1, 1);

add_action( 'wp_footer', 'oot_underscore_offer', 25 );
function oot_underscore_offer() { ?>
    <script type="text/html" id="tmpl-oot-offer">
		<div class="offer-item">
			<div class="left">
				<img width="140" height="110" src="{{{ data.offer_thumbnail }}}" class="attachment-full size-full wp-post-image" alt="">
			</div>
			<div class="right">
				<h2>
					<label for="offer-title" class="control-label no-icon">
						<p class="text-info">{{{ data.offer_title }}}</p>
						<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
					</label>
					<i class="editoffer fa fa-pencil" aria-hidden="true" data-edit-type="expand"></i>
				</h2>
				<div class="offer-details">
					<label for="offer-date" class="control-label">
						<p class="text-info">{{{ data.offer_date }}}</p>
						<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="date"></i>
					</label>
					<label for="offer-time" class="control-label">
						<p class="text-info"><span class="starttime">{{{ data.offer_starttime }}}</span> - <span class="endtime">{{{ data.offer_endtime }}}</span></p>
						<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="time"></i>
					</label>
					<label for="offer-quantity" class="control-label">
						<p class="text-info">{{{ data.offer_quantity }}}</p>
						<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="number"></i>
					</label>
				</div>
				
			</div>
			<div class="offer-description">
				<label for="venue-description" class="control-label venue-description">
					<p class="venue-desc-title">Full Description</p><i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="textarea"></i>
					<div class="text-info-wrapper">
						<p class="text-info">{{{ data.offer_description }}}</p>
					</div>
				</label>
				<span class="remove">Remove Offer<i class="fa fa-trash" aria-hidden="true"></i></span>
				<span class="save">Save Offer<i class="fa fa-check" aria-hidden="true"></i></span>
			</div>
		</div>
    </script>
<?php }

add_action( 'wp_footer', 'oot_underscore_event', 25 );
function oot_underscore_event() { ?>
    <script type="text/html" id="tmpl-oot-event">
		<div class="event-item" id="event-{{{ data.event_random_id }}}">
			<div class="left">
				<img width="140" height="110" src="{{{ data.event_thumbnail }}}" class="attachment-full size-full wp-post-image" alt="">
			</div>
			<div class="right">
				<h2>
					<label for="event-title" class="control-label no-icon">
						<p class="text-info">{{{ data.event_title }}}</p>
						<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
					</label>
					<i class="editevent fa fa-pencil" aria-hidden="true" data-edit-type="expand"></i>
				</h2>
				<div class="event-details">
					<label for="event-date" class="control-label">
						<p class="text-info">{{{ data.event_date }}}</p>
						<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="date"></i>
					</label>
					<label for="event-time" class="control-label">
						<p class="text-info"><span class="starttime">{{{ data.event_starttime }}}</span> - <span class="endtime">{{{ data.event_endtime }}}</span></p>
						<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="time"></i>
					</label>
					<label for="event-quantity" class="control-label">
						<p class="text-info">{{{ data.event_price }}}</p>
						<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="currency"></i>
					</label>	
				</div>
			</div>
			<div class="event-description">
				<label for="venue-description" class="control-label venue-description">
					<p class="venue-desc-title">Full Description</p><i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="textarea"></i>
					<div class="text-info-wrapper">
						<p class="text-info">{{{ data.event_description }}}</p>
					</div>
				</label>
				<div class="repeat-event-wrapper">
						<p class="venue-desc-title">Repeat Event?</p>
						<?php
							$key = 'field_58ac60bee9d5c';
							$field = get_field_object($key);
							if ($field) {
								foreach ($field['choices'] as $key => $value) {
							?>
									<input type="radio" data-value="<?php echo $value; ?>" id="{{{ data.event_random_id }}}<?php echo $value; ?>" name="event" />
									<label for="{{{ data.event_random_id }}}<?php echo $value; ?>"><span></span><?php echo $value; ?></label>
							<?php
								}
							}
						?>
					</div>
				<span class="remove">Remove Event<i class="fa fa-trash" aria-hidden="true"></i></span>
				<span class="save">Save Event<i class="fa fa-check" aria-hidden="true"></i></span>
			</div>
		</div>
    </script>
<?php }

function my_page_columns($columns)
{
    $columns = array(
        'cb'         => '<input type="checkbox" />',
        'title'     => 'Last Name',
        'first'     => 'First Name',
        'date'        =>    'Date',
    );
    return $columns;
}

function my_custom_columns($column)
{
    global $post;
    
    if ($column == 'first') {
        echo 'test';
    }
    else {
         echo '';
    }
}

add_action("manage_offer_posts_custom_column", "my_custom_columns");
add_filter("manage_offer_posts_columns", "my_page_columns");