<?php

static $instance = 0;
$instance++;

$defaults = array(
	'post_type' => 'event',
	'taxonomy' => 'event-category',
	'taxonomy_relation' => 'AND',
	'limit' => 3,
	'offset' => 0,
	'category' => '0', // integer category ID
	'orderby' => 'event_date', // date, title, rand, event_date
	'order' => 'DESC', // ASC
	'show' => 'upcoming',
	'display' => 'excerpt', // excerpt, none
	'more_link' => false, // true goes to post type archive, and admits custom link
	'more_text' => __( 'More &rarr;', 'themify-event-post' ),
	'style' => 'grid3', // grid4, grid2, list-post
	'image_w' => '',
	'image_h' => '',
	'image_size' => 'medium',
	'title' => 'yes', // no
	'title_tag' => 'h2',
	'unlink_title' => 'no',
	'image' => 'yes', // no
	'unlink_image' => 'no',
	'hide_event_location' => 'no',
	'hide_event_date' => 'no',
	'hide_event_organizer' => 'no',
	'hide_event_performer' => 'no',
	'hide_event_meta' => 'no',
	'hide_page_nav' => 'no',
	'id' => '', /* show a single event post */
	// templating
	'template' => 'content',
	'template_before' => '',
	'template_after' => '',
);
$args = shortcode_atts( $defaults, $atts, 'themify_event_post' );

if ( ! empty( $args['id'] ) ) {
	$args['style'] = 'list-post';
}

if ( empty( $args['template_before'] ) )
	$args['template_before'] = '<div class="themify_event_post_loop ' . esc_attr( $args['style'] ) . '">';
if ( empty( $args['template_after'] ) )
	$args['template_after'] = '</div>';

// Event Query Setup
$events = array();

if ( $args['show'] === 'upcoming' || $args['show'] === 'tabbed' ) { // show only future events
	$query = new WP_Query();
	$args['show'] = 'upcoming';
	$events[] = $query->query( apply_filters( 'themify_event_shortcode_args', themify_event_post_parse_query( $args ) ) );
}
if ( 'past' === $args['show'] || 'tabbed' === $args['show'] ) {
	$query = new WP_Query();
	$args['show'] = 'past';
	$events[] = $query->query( apply_filters( 'themify_event_shortcode_args', themify_event_post_parse_query( $args ) ) );
}
if ( 'mix' === $args['show'] ) {
	$query = new WP_Query();
	$events[] = $query->query( apply_filters( 'themify_event_shortcode_args', themify_event_post_parse_query( $args ) ) );
}

ob_start();
if ( $args['show'] === 'tabbed' ) {
	?>
	<div class="themify-events-tabs">
		<ul>
			<li><a href="#themify-events-upcoming-<?php echo $instance; ?>"><?php _e( 'Upcoming', 'themify-event-post' ); ?></a></li>
			<li><a href="#themify-events-past-<?php echo $instance; ?>"><?php _e( 'Past', 'themify-event-post' ); ?></a></li>
		</ul>
		<div id="themify-events-upcoming-<?php echo $instance; ?>">
			<?php echo $args['template_before'] . $this->get_shortcode_template( $events[0], $args['template'], $args ) . $args['template_after']; ?>
		</div>
		<div id="themify-events-past-<?php echo $instance; ?>">
			<?php echo $args['template_before'] . $this->get_shortcode_template( $events[1], $args['template'], $args ) . $args['template_after']; ?>
		</div>
	</div>
	<?php
} else {

	$output = $this->get_shortcode_template( $events[0], $args['template'], $args );
	if ( $output !== '' ) {
		echo $args['template_before'] . $output . $args['template_after'];
		if ( $args['hide_page_nav'] === 'no' ) {
			echo themify_event_post_pagenav( array(
				'total_posts' => $query->found_posts,
				'paged' => themify_event_post_get_paged_query(),
				'offset' => (int) $args['offset'],
				'posts_per_page' => (int) $args['limit'],
			) );
		}
	}

}
return ob_get_clean();
