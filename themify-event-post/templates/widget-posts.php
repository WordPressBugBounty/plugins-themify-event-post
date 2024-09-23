<?php
/**
 * Template to display Event Post widget
 *
 * To override this file copy it to <your_theme>/themify-event-post/widget-posts.php
 *
 * @var $args
 * @var $instance
 * @var $widget
 *
 * @package Themify Event Post
 */
global $post;

$query_args = array(
	'post_type' => 'event',
	'posts_per_page' => $instance['show_count'],
	'suppress_filters' => false,
);
if ( ! empty( $instance['category'] ) ) {
	$query_args['tax_query'][] = array(
		'taxonomy' => 'event-category',
		'field' => 'id',
		'terms' => $instance['category']
	);
}
if ( $instance['show'] === 'upcoming' ) {
	$query_args['meta_query'] = array(
		'relation' => 'OR',
		array(
			'key' => 'end_date',
			'value' => date_i18n( 'Y-m-d H:i' ),
			'compare' => '>='
		),
		array(
			'key' => 'start_date',
			'value' => date_i18n( 'Y-m-d H:i' ),
			'compare' => '>='
		)
	);
} else if ( $instance['show'] === 'past' ) {
	$query_args['meta_query'] = array(
		'relation' => 'AND',
		array(
			'key' => 'end_date',
			'value' => date_i18n( 'Y-m-d H:i' ),
			'compare' => '<'
		),
		array(
			'key' => 'end_date',
			'value' => '',
			'compare' => '!='
		),
	);
}

$posts = get_posts( apply_filters( 'themify_event_post_widget_query_args', $query_args, $instance ) );
if ( ! empty( $posts ) ) {
	// cache $post object
	if ( is_object( $post ) )
		$saved_post = clone $post;

	echo $args['before_widget'];

	$title = apply_filters( 'widget_title', $instance['title'], $instance, $widget->id_base );
	if ( $title ) {
		echo $args['before_title'] . $title . $args['after_title'];
	}

	?>

	<ul>
		<?php foreach ( $posts as $post ) : setup_postdata( $post ); ?>
			<li>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php
	if ( isset( $saved_post ) && is_object( $saved_post ) ) {
		$post = $saved_post;
		setup_postdata( $saved_post );
	}

	echo $args['after_widget'];
}