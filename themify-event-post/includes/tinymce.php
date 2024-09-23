<?php
/**
 * TinyMCE Shortcode Generator
 *
 * @package Themify Event Post
 * @since 1.0.0
 */

class Themify_Events_Posts_TinyMCE {

	function __construct() {
		add_filter( 'mce_external_plugins', array( $this, 'add_plugin' ) );
		add_filter( 'mce_buttons', array( $this, 'add_button' ) );
		add_action( 'wp_enqueue_editor', array( $this, 'wp_enqueue_editor' ) );
	}

	/**
	 * Add button to WP Editor.
	 *
	 * @param array $mce_buttons
	 * @return mixed
	 */
	function add_button( $mce_buttons ) {
		$mce_buttons[] = 'separator';
		$mce_buttons[] = 'btnthemifyEventPosts';
		return $mce_buttons;
	}

	/**
	 * Add plugin JS file to list of external plugins.
	 *
	 * @param array $mce_external_plugins
	 * @return mixed
	 */
	function add_plugin( $mce_external_plugins ) {
		$mce_external_plugins['btnthemifyEventPosts'] = Themify_Event_Post::get_instance()->url . 'assets/tinymce.js';

		return $mce_external_plugins;
	}

	/**
	 * Enqueue assets required for tinymce shortcode generator
	 *
	 * @since 1.8.9
	 */
	function wp_enqueue_editor() {
		$instance = Themify_Event_Post::get_instance();
		wp_enqueue_style( $instance->pid . '-tinymce', $instance->url . 'assets/tinymce.css' );

		wp_localize_script( 'editor', 'themifyEventPostsMCE', array(
			'fields' => include $instance->locate_template( 'config-shortcode-generator' ),
			'labels' => array(
				'menuTooltip' => __( 'Themify Event Post Shortcode Generator', 'themify-event-post' ),
				'menuName' => __( 'Event Posts', 'themify-event-post' ),
			),
			'template' =>
			'[themify_event_post'
				. '<# if ( data.style !== "grid3" ) { #> style="{{data.style}}"<# } #>'
				. '<# if ( data.show !== "upcoming" ) { #> show="{{data.show}}"<# } #>'
				. '<# if ( data.limit ) { #> limit="{{data.limit}}"<# } #>'
				. '<# if ( data.category ) { #> category="{{data.category}}"<# } #>'
				. '<# if ( data.order !== "DESC" ) { #> order="{{data.order}}"<# } #>'
				. '<# if ( data.orderby !== "event_date" ) { #> orderby="{{data.orderby}}"<# } #>'
				. '<# if ( data.display !== "excerpt" ) { #> display="{{data.display}}"<# } #>'
				. '<# if ( data.image !== "yes" ) { #> image="{{data.image}}"<# } #>'
				. '<# if ( data.image_w ) { #> image_w="{{data.image_w}}"<# } #>'
				. '<# if ( data.image_h ) { #> image_h="{{data.image_h}}"<# } #>'
				. '<# if ( data.hide_event_date !== "no" ) { #> hide_event_date="{{data.hide_event_date}}"<# } #>'
				. '<# if ( data.hide_event_organizer !== "no" ) { #> hide_event_organizer="{{data.hide_event_organizer}}"<# } #>'
				. '<# if ( data.hide_event_performer !== "no" ) { #> hide_event_performer="{{data.hide_event_performer}}"<# } #>'
				. '<# if ( data.hide_event_location !== "no" ) { #> hide_event_location="{{data.hide_event_location}}"<# } #>'
				. '<# if ( data.hide_page_nav !== "no" ) { #> hide_page_nav="{{data.hide_page_nav}}"<# } #>'
			. ']'
		));
	}
}