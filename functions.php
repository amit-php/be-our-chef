<?php
/**
* WeaversWeb functions and definitions
**/
$functions_path=get_template_directory().'/functions/';
$post_type_path=get_template_directory().'/inc/post-types/';
$theme_function_path=get_template_directory().'/inc/theme-functions/';
/*--------------------------------------*/
/* Optional Panel Helper Functions
/*--------------------------------------*/
require_once($functions_path.'admin-functions.php');
require_once($functions_path.'admin-interface.php');
require_once($functions_path.'theme-options.php');
require_once($functions_path.'default-values.php'); 
require_once($functions_path.'notification.php');
function weaversweb_ftn_wp_enqueue_scripts(){
    if(!is_admin()){
        wp_enqueue_script('jquery');
        if(is_singular()and get_site_option('thread_comments')){
            wp_print_scripts('comment-reply');
			}
		}
	}
add_action('wp_enqueue_scripts','weaversweb_ftn_wp_enqueue_scripts');
function weaversweb_ftn_get_option($name){
    $options=get_option('weaversweb_ftn_options');
    if(isset($options[$name]))
        return $options[$name];
	}

function weaversweb_ftn_update_option($name,$value){
    $options=get_option('weaversweb_ftn_options');
    $options[$name]=$value;
    return update_option('weaversweb_ftn_options',$options);
	}

function weaversweb_ftn_delete_option($name){
    $options=get_option('weaversweb_ftn_options');
    unset($options[$name]);
    return update_option('weaversweb_ftn_options',$options);
	}

function get_theme_value($field){	
	$field1=weaversweb_ftn_get_option($field);
	$field_default=all_default_values($field);
	if(!empty($field1)){
		$field_val=$field1;
		}else{
		$field_val=$field_default;	
		}
	return	$field_val;
	}
/*--------------------------------------*/
/* Post Type Helper Functions
/*--------------------------------------*/
require_once($post_type_path.'specializations.php');
require_once($post_type_path.'add_booking.php');
require_once($post_type_path.'rating.php');
require_once($post_type_path.'invitation.php');
require_once($post_type_path.'chef_bank.php');
require_once($post_type_path.'user_bank.php');
//require_once($post_type_path.'payment_orders.php');
//require_once($post_type_path.'notification.php');
/*--------------------------------------*/
/* Theme Functions
/*--------------------------------------*/
require_once($theme_function_path.'extra-functions.php');
/*--------------------------------------*/
/* Theme Helper Functions
/*--------------------------------------*/
if(!function_exists('weaversweb_theme_setup')): 
	function weaversweb_theme_setup(){
		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
		register_nav_menus(array('primary'=> __('Primary Menu','weaversweb'),'secondary'=> __('Secondary Menu','weaversweb')));
		add_theme_support('html5',array('search-form','comment-form','comment-list','gallery','caption'));
		}
	endif;
add_action('after_setup_theme','weaversweb_theme_setup');
function weaversweb_widgets_init(){
	register_sidebar(array(
		'name'=> __('Widget Area','weaversweb'),
		'id'  => 'sidebar-1',
		'description'=> __('Add widgets here to appear in your sidebar.','weaversweb'),
		'before_widget'=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'=> '</div>',
		'before_title'=> '<h2 class="widget-title">',
		'after_title'=> '</h2>',
		));
	}
add_action('widgets_init','weaversweb_widgets_init');
function weaversweb_scripts(){
	wp_enqueue_style('weaversweb-main',get_template_directory_uri().'/css/normalize.css',array());
	wp_enqueue_style('weaversweb-style',get_stylesheet_uri());

	wp_enqueue_script('weaversweb-script',get_template_directory_uri().'/js/functions.js',array('jquery'),'20151811',true);
	}
add_action('wp_enqueue_scripts','weaversweb_scripts');


function image_upload($image){

	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php'); 


	$uploadedfile = $image;
	$upload_overrides = array( 'test_form' => false );
	$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
   if ( $movefile && ! isset( $movefile['error'] ) ) {

	$filename = $movefile['file'];
	$attachment = array(
		'post_mime_type' => $movefile['type'],
		'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
		'post_content' => '',
		'post_status' => 'inherit',
		'guid' => $movefile['url']
	);
	$attachment_id = wp_insert_attachment( $attachment, $movefile['url'] );
	$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
	wp_update_attachment_metadata( $attachment_id, $attachment_data );

    return $attachment_id;
   } else {
	   return $movefile['error'];
   }

} 