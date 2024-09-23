<?php
/*
Plugin Name:  Themify Event Post
Plugin URI:   https://themify.me/event-post
Version:      1.3.2 
Author:       Themify
Author URI:   https://themify.me
Description:  This plugin will add an Event post type. A simple way to display events on your site.
Text Domain:  themify-event-post
Domain Path:  /languages
Requires PHP: 7.2
Compatibility: 7.0.0
/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 */

defined( 'ABSPATH' ) or die;

function themify_event_post_setup() {
	// Check if Themify theme has registered event post type
	if(function_exists('themify_post_types')){
		if(in_array('event',themify_post_types())){
			add_action( 'admin_init', 'themify_event_deactivate' );
			add_action( 'admin_notices', 'themify_event_admin_notice' );
			return false;
		}
	}
	include plugin_dir_path( __FILE__ ) . 'includes/system.php';

	Themify_Event_Post::get_instance( array(
		'url' => trailingslashit( plugin_dir_url( __FILE__ ) ),
		'dir' => trailingslashit( plugin_dir_path( __FILE__ ) ),
		'version' => '1.3.2'
	) );
}
add_action( 'after_setup_theme', 'themify_event_post_setup' );
add_filter( 'plugin_row_meta', 'themify_event_post_plugin_meta', 10, 2 );
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'themify_event_post_action_links' );

function themify_event_post_plugin_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) === $file ) {
		$row_meta = array(
		  'changelogs'    => '<a href="' . esc_url( 'https://themify.org/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'themify-event-post' ) . '">' . esc_html__( 'View Changelogs', 'themify-event-post' ) . '</a>'
		);
 
		return array_merge( $links, $row_meta );
	}
	return (array) $links;
}
function themify_event_post_action_links( $links ) {
	if ( is_plugin_active( 'themify-updater/themify-updater.php' ) ) {
		$tlinks = array(
		 '<a href="' . admin_url( 'index.php?page=themify-license' ) . '">'.__('Themify License', 'themify-event-post') .'</a>',
		 );
	} else {
		$tlinks = array(
		 '<a href="' . esc_url('https://themify.me/docs/themify-updater-documentation') . '">'. __('Themify Updater', 'themify-event-post') .'</a>',
		 );
	}
	return array_merge( $links, $tlinks );
}

/**
 * Plugin activation hook
 * Flush rewrite rules after custom post type has been registered
 */
function themify_event_post_activation() {
	if(false === themify_event_post_setup()){
		return false;
	}
	include trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/post-type.php';
	themify_event_post_register_post_type();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'themify_event_post_activation' );


function themify_event_deactivate() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

function themify_event_admin_notice() {
	echo sprintf("<div class='updated'><p>%s</p></div>",__('Your Themify theme already has Event post type, don\'t need to activate the Themify Event Post plugin.','themify-event-post'));
	if ( isset( $_GET['activate'] ) ){
		unset( $_GET['activate'] );
	}
}
