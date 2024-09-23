<?php
/**
 * Event Posts widget
 *
 * @package Themify Event Post
 */

class Themify_Event_Posts_Widget extends WP_Widget {

	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'event-posts', 'description' => __( 'A list of events.', 'themify-event-post' ) );

		/* Widget control settings. */
		$control_ops = array('id_base' => 'themify-event-post');
			   
		/* Create the widget. */
		parent::__construct('themify-event-post', __( 'Themify - Event Posts', 'themify-event-post' ), $widget_ops, $control_ops);
	}

	/**
	 * Initialize widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		echo Themify_Event_Post::get_instance()->get_template( 'widget-posts', array(
			'args' => $args,
			'instance' => $instance,
			'widget' => $this,
		) );
	}

	/**
	 * Save widget options
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['category'] = $new_instance['category'];
		$instance['show_count'] = $new_instance['show_count'];
		$instance['show'] = $new_instance['show'];

		return $instance;
	}

	/**
	 * Render widget form
	 * @param array $instance
	 *
	 * @return string|void
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => __( 'Events', 'themify-event-post' ),
			'category' => 0,
			'show_count' => 5,
			'show' => 'upcoming',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$categories = get_categories( array( 'taxonomy' => 'event-category' ) );
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'themify-event-post'); ?></label><br />
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" width="100%" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category:', 'themify-event-post');
		?></label>
			<select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
				<option value="0" <?php if (!$instance['category']) echo 'selected="selected"'; ?>><?php _e('All', 'themify-event-post'); ?></option>
				<?php
				foreach ( $categories as $cat ) {
					echo '<option value="' . $cat->cat_ID . '"';
					if ($cat->cat_ID == $instance['category'])
						echo ' selected="selected"';
					echo '>' . $cat->cat_name . ' (' . $cat->category_count . ')';
					echo '</option>';
				}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('show_count'); ?>"><?php _e('Limit:', 'themify-event-post'); ?></label>
			<input id="<?php echo $this->get_field_id('show_count'); ?>" name="<?php echo $this->get_field_name('show_count'); ?>" value="<?php echo $instance['show_count']; ?>" size="2" /> <?php _e('events', 'themify-event-post'); ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('show'); ?>"><?php _e('Show:', 'themify-event-post'); ?></label>
			<select id="<?php echo $this->get_field_id('show'); ?>" name="<?php echo $this->get_field_name('show'); ?>">
				<option value="upcoming" <?php selected( $instance['show'], 'upcoming' ); ?>><?php _e( 'Upcoming Events' ); ?></option>
				<option value="past" <?php selected( $instance['show'], 'past' ); ?>><?php _e( 'Past Events' ); ?></option>
				<option value="mix" <?php selected( $instance['show'], 'mix' ); ?>><?php _e( 'Both' ); ?></option>
			</select>
		</p>

		<?php
	}

	public static function register() {
		register_widget( 'Themify_Event_Posts_Widget' );
	}
}
add_action( 'widgets_init', array( 'Themify_Event_Posts_Widget', 'register' ) );

class Themify_Event_Categories_Widget extends WP_Widget {

	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'event-categories', 'description' => __( 'A list of event categories.', 'themify-event-post' ) );

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'themify-event-categories' );
			   
		/* Create the widget. */
		parent::__construct( 'themify-event-categories', __( 'Themify - Event Categories', 'themify-event-post' ), $widget_ops, $control_ops );
	}

	/**
	 * Initialize widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		echo Themify_Event_Post::get_instance()->get_template( 'widget-categories', array(
			'args' => $args,
			'instance' => $instance,
			'widget' => $this,
		) );
	}

	/**
	 * Save widget options
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['parent'] = $new_instance['parent'];
		$instance['depth'] = $new_instance['depth'];
		$instance['orderby'] = $new_instance['orderby'];
		$instance['exclude'] = $new_instance['exclude'];
		$instance['show_dropdown'] = $new_instance['show_dropdown'];
		$instance['show_counts'] = $new_instance['show_counts'];
		$instance['show_hierarchy'] = $new_instance['show_hierarchy'];

		return $instance;
	}

	/**
	 * Render widget form
	 * @param array $instance
	 *
	 * @return string|void
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array(
			'title' => __('Categories', 'themify-event-post'),
			'parent' => 0,
			'depth' => 0,
			'orderby' => 'name',
			'exclude' => '',
			'show_dropdown' => false,
			'show_counts' => false,
			'show_hierarchy' => true
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'themify-event-post'); ?></label><br />
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'title' ) ); ?>" value="<?php esc_attr_e( $instance['title'] ); ?>" width="100%" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'parent' ); ?>"><?php _e('Parent:', 'themify-event-post'); ?></label>
			<?php
			wp_dropdown_categories( array(
				'taxonomy'        => 'event-category',
				'show_option_all' => __('All', 'themify-event-post'),
				'orderby'         => 'name',
				'hierarchical'    => 1,
				'selected'        => $instance['parent'],
				'id'              => $this->get_field_id( 'parent' ),
				'name'            => $this->get_field_name( 'parent' ),
			));
			?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'depth' ); ?>"><?php _e('Depth:', 'themify-event-post'); ?></label>
			<select id="<?php echo $this->get_field_id( 'depth' ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'depth' ) ); ?>">
				<option value="0" <?php if ( 0 == $instance['depth'] ) echo 'selected="selected"'; ?>><?php _e('0 (default)', 'themify-event-post'); ?></option>
				<option value="1" <?php if ( 1 == $instance['depth'] ) echo 'selected="selected"'; ?>>1</option>
				<option value="2" <?php if ( 2 == $instance['depth'] ) echo 'selected="selected"'; ?>>2</option>
				<option value="3" <?php if ( 3 == $instance['depth'] ) echo 'selected="selected"'; ?>>3</option>
				<option value="4" <?php if ( 4 == $instance['depth'] ) echo 'selected="selected"'; ?>>4</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e('Orderby:', 'themify-event-post'); ?></label>
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'orderby' ) ); ?>">
				<option value="id" <?php if ( 'id' === $instance['orderby'] ) echo 'selected="selected"'; ?>><?php _e( 'ID', 'themify-event-post' ); ?></option>
				<option value="name" <?php if ( 'name' === $instance['orderby'] ) echo 'selected="selected"'; ?>><?php _e( 'Name', 'themify-event-post' ); ?></option>
				<option value="slug" <?php if ( 'slug' === $instance['orderby'] ) echo 'selected="selected"'; ?>><?php _e( 'Slug', 'themify-event-post' ); ?></option>
				<option value="count" <?php if ( 'count' === $instance['orderby'] ) echo 'selected="selected"'; ?>><?php _e('Count', 'themify-event-post'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e('Exclude:', 'themify-event-post'); ?></label><br />
			<input id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'exclude' ) ); ?>" value="<?php esc_attr_e( $instance['exclude'] ); ?>" /><br />
			<small><?php _e('Category IDs, separated by commas (eg. 5,8)', 'themify-event-post'); ?></small>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_dropdown'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_dropdown' ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'show_dropdown' ) ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_dropdown' ); ?>"><?php _e('Show as dropdown', 'themify-event-post'); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_counts'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_counts' ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'show_counts' ) ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_counts' ); ?>"><?php _e('Show post counts', 'themify-event-post'); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_hierarchy'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_hierarchy' ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'show_hierarchy' ) ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_hierarchy' ); ?>"><?php _e('Show hierarchy', 'themify-event-post'); ?></label>
		</p>
		<?php

	}

	public static function register() {
		register_widget( 'Themify_Event_Categories_Widget' );
	}
}
add_action( 'widgets_init', array( 'Themify_Event_Categories_Widget', 'register' ) );