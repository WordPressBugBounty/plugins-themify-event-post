<?php
/**
 * Register Event post type and related taxonomies
 *
 * @package Themify Event Post
 */

if( ! function_exists( 'themify_event_post_register_post_type' ) ) :
/**
 * Register post type and taxonomy
 */
function themify_event_post_register_post_type() {
	register_post_type( 'event', array(
		'labels' => array(
			'name' => __('Events', 'themify-event-post'),
			'singular_name' => __('Event', 'themify-event-post'),
			'add_new' => __('Add New', 'themify-event-post'),
			'add_new_item' => __('Add New Event', 'themify-event-post'),
			'edit_item' => __('Edit Event', 'themify-event-post'),
			'new_item' => __('New Event', 'themify-event-post'),
			'view_item' => __('View Event', 'themify-event-post'),
			'search_items' => __('Search Events', 'themify-event-post'),
			'not_found' => __('No Events found', 'themify-event-post'),
			'not_found_in_trash' => __('No Events found in Trash', 'themify-event-post'),
			'menu_name' => __('Events', 'themify-event-post'),
		),
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'excerpt', 'comments' ),
		'hierarchical' => false,
		'public' => true,
		'exclude_from_search' => false,
		'query_var' => true,
		'can_export' => true,
		'capability_type' => 'post',
		'menu_icon' => 'dashicons-calendar',
		'has_archive' => true,
		'rewrite' => array(
			'slug' => Themify_Event_Post::get_instance()->get_option( 'single_permalink', 'event' ),
			'feeds' => true,
		),
		'show_in_rest' => true
	));

	register_taxonomy( 'event-category', array( 'event' ), array(
		'labels' => array(
			'name' => __( 'Event Categories', 'themify-event-post' ),
			'singular_name' => __( 'Event Categories', 'themify-event-post' ),
			'all_items' => __( 'All Event Categories', 'themify-event-post' ),
		),
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tagcloud' => true,
		'hierarchical' => true,
		'rewrite' => array(
			'slug' => Themify_Event_Post::get_instance()->get_option( 'category_permalink', 'event-category' ),
		),
		'query_var' => true,
		'show_in_rest' => true
	));

	register_taxonomy( 'event-tag', array( 'event' ), array(
		'labels' => array(
			'name' => __( 'Event Tags', 'themify-event-post' ),
			'singular_name' => __( 'Event Tags', 'themify-event-post' ),
		),
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tagcloud' => true,
		'hierarchical' => false,
		'rewrite' => true,
		'query_var' => true,
		'show_in_rest' => true
	));
}
endif;
add_action( 'init', 'themify_event_post_register_post_type' );