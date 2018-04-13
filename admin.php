<?php

add_filter( 'wp_revisions_to_keep', 'filter_function_name', 4, 2 );
function filter_function_name( $num, $post ) {
    return (int)(get_option('wpgreen_nb_revision', 1) - 1);
}


/*
For more information on creating Dashboard Widgets, view:
http://digwp.com/2010/10/customize-wordpress-dashboard/
*/

// Calling all custom dashboard widgets
function wpgreen_custom_dashboard_widgets() {

	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );            // WordPress blog
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );   // Right Now
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
	remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'normal' ); // Recent Comments
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );  // Incoming Links
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
}
add_action('wp_dashboard_setup', 'wpgreen_custom_dashboard_widgets');


function remove_menu_items() {
 global $menu;
 $restricted = array(__('Links'), __('Comments'));
 end ($menu);
 while (prev($menu)){
 $value = explode(' ',$menu[key($menu)][0]);
 if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){
 unset($menu[key($menu)]);}
 }
 }

add_action('admin_menu', 'remove_menu_items');

function remove_submenus() {
 global $submenu;



 unset($submenu['edit.php'][16]); // Supprimer 'Tags'.
 }
add_action('admin_menu', 'remove_submenus');




/** et un intervalle entre deux sauvegardes de 360 secondes**/
if ( ! defined( 'AUTOSAVE_INTERVAL' ) ){
	define('AUTOSAVE_INTERVAL', 360);
}
// Fire all our initial functions at the start
add_action('after_setup_theme','wpgreen_start', 16);
function wpgreen_start() {
    // launching operation cleanup
    add_action('init', 'wpgreen_head_cleanup');
    // remove pesky injected css for recent comments widget
    add_filter( 'wp_head', 'wpgreen_remove_wp_widget_recent_comments_style', 1 );
    // clean up comment styles in the head
    add_action('wp_head', 'wpgreen_remove_recent_comments_style', 1);
    // clean up gallery output in wp
    add_filter('gallery_style', 'wpgreen_gallery_style');
    // adding sidebars to Wordpress
    add_action( 'widgets_init', 'wpgreen_register_sidebars' );
    // cleaning up excerpt
    add_filter('excerpt_more', 'wpgreen_excerpt_more');
}

//The default wordpress head is a mess. Let's clean it up by removing all the junk we don't need.
function wpgreen_head_cleanup() {
	// Remove category feeds
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	// Remove post and comment feeds
	remove_action( 'wp_head', 'feed_links', 2 );
	// Remove EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// Remove Windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// Remove index link
	remove_action( 'wp_head', 'index_rel_link' );
	// Remove previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// Remove start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// Remove links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// Remove WP version
	remove_action( 'wp_head', 'wp_generator' );
} /* end Joints head cleanup */

// Remove injected CSS for recent comments widget
function wpgreen_remove_wp_widget_recent_comments_style() {
   if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) {
      remove_filter('wp_head', 'wp_widget_recent_comments_style' );
   }
}

// Remove injected CSS from recent comments widget
function wpgreen_remove_recent_comments_style() {
  global $wp_widget_factory;
  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
  }
}

// Remove injected CSS from gallery
function wpgreen_gallery_style($css) {
  return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
}

// This removes the annoying […] to a Read More link
function wpgreen_excerpt_more($more) {
	global $post;
	// edit here if you like
	return '';
	//return '<a class="excerpt-read-more" href="'. get_permalink($post->ID) . '" title="'. __('Read', 'jointswp') . get_the_title($post->ID).'">'. __('... Read more &raquo;', 'jointswp') .'</a>';
}

function remove_json_api () {
    // Turn off oEmbed auto discovery.
    add_filter( 'embed_oembed_discover', '__return_false' );
    // Don't filter oEmbed results.
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    // Remove oEmbed discovery links.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    remove_action( 'wp_head','feed_links', 2 );
  	remove_action( 'wp_head','feed_links_extra', 3 );
  	remove_action( 'wp_head', 'wp_resource_hints', 2 );
}
add_action( 'after_setup_theme', 'remove_json_api' );


function disable_wp_emoji() {

// all actions related to emojis
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

// filter to remove TinyMCE emojis
add_filter( 'tiny_mce_plugins', 'disable_emoji_tinymce' );
}
add_action( 'init', 'disable_wp_emoji' );

function disable_emoji_tinymce( $plugins ) {
if ( is_array( $plugins ) ) {
return array_diff( $plugins, array( 'wpemoji' ) );
} else {
return array();
}
}


// Add to existing function.php file
// Disable support for comments and trackbacks in post types
function df_disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'df_disable_comments_post_types_support');
// Close comments on the front-end
function df_disable_comments_status() {
	return false;
}
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);
// Hide existing comments
function df_disable_comments_hide_existing_comments($comments) {
	$comments = array();
	return $comments;
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function df_disable_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'df_disable_comments_admin_menu');
// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url()); exit;
	}
}
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');
// Remove comments metabox from dashboard
function df_disable_comments_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'df_disable_comments_dashboard');
// Remove comments links from admin bar
function df_disable_comments_admin_bar() {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
}
add_action('init', 'df_disable_comments_admin_bar');


// Removes from admin menu
add_action( 'admin_menu', 'my_remove_admin_menus' );
function my_remove_admin_menus() {
    remove_menu_page( 'edit-comments.php' );
}
// Removes from post and pages
add_action('init', 'remove_comment_support', 100);

function remove_comment_support() {
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'page', 'comments' );
}
// Removes from admin bar
function mytheme_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );

function wpgreen_set_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','wpgreen_set_content_type' );
