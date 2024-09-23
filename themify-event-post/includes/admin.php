<?php
/**
 * Create the options page for the Event Posts plugin
 *
 * @since 1.0.0
 */
class Themify_Event_Post_Admin {

	public $options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'setup_options_page' ), 100 );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'updated_option', array( $this, 'updated_option' ), 10, 3 );
		add_filter( 'manage_edit-event_columns', array( $this, 'type_column_header' ), 10, 2 );
		add_action( 'manage_event_posts_custom_column', array( $this, 'type_column' ), 10, 3 );
		add_action( 'restrict_manage_posts', array( $this, 'get_select' ) );
	}

	public function setup_options_page() {
		add_submenu_page( 'edit.php?post_type=event', __( 'Event Settings', 'themify-event-post' ), __( 'Event Settings', 'themify-event-post' ), 'manage_options', 'themify-event-post', array( $this, 'create_admin_page' ) );
	}

	public function create_admin_page() {
		?>
		<div class="wrap">
			<h2><?php _e( 'Themify Event Post', 'themify-event-post' ); ?></h2>           
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'themify_event_post' );   
				do_settings_sections( 'themify-event-post' );
				submit_button(); 
				?>
			</form>
		</div>
		<?php
    }
	
	/**
	 * Register and add settings
	 */
	public function page_init() {        
		register_setting(
			'themify_event_post', // Option group
			'themify_event_post' // Option name
		);

		add_settings_section(
			'themify_event_post_archive', // ID
			__( 'Event Archives', 'themify-event-post' ), // Title
			null, // Callback
			'themify-event-post' // Page
		);
		add_settings_section(
			'themify_event_post_price', // ID
			__( 'Ticket Price', 'themify-event-post' ), // Title
			null, // Callback
			'themify-event-post' // Page
		);
		add_settings_section(
			'themify_event_post_integration', // ID
			__( 'Integration', 'themify-event-post' ), // Title
			null, // Callback
			'themify-event-post' // Page
		);
		add_settings_section(
			'themify_event_post_permalink', // ID
			__( 'Permalink', 'themify-event-post' ), // Title
			null, // Callback
			'themify-event-post' // Page
		);

		add_settings_field(
			'show', // ID
			__( 'Show', 'themify-event-post' ), // Title 
			array( $this, 'show' ), // Callback
			'themify-event-post', // Page
			'themify_event_post_archive' // Section
		);

		add_settings_field(
			'orderby', // ID
			__( 'Order By', 'themify-event-post' ), // Title 
			array( $this, 'orderby' ), // Callback
			'themify-event-post', // Page
			'themify_event_post_archive' // Section
		);

		add_settings_field(
			'order', // ID
			__( 'Order', 'themify-event-post' ), // Title 
			array( $this, 'order' ), // Callback
			'themify-event-post', // Page
			'themify_event_post_archive' // Section
		);

		add_settings_field(
			'layout', // ID
			__( 'Layout', 'themify-event-post' ), // Title 
			array( $this, 'layout' ), // Callback
			'themify-event-post', // Page
			'themify_event_post_archive' // Section
		);

		add_settings_field(
			'google_maps_key', // ID
			__( 'Google Maps API Key', 'themify-event-post' ), // Title 
			array( $this, 'google_maps_key_callback' ), // Callback
			'themify-event-post', // Page
			'themify_event_post_integration' // Section
		);

		add_settings_field(
			'single_permalink', // ID
			__( 'Single Permalink', 'themify-event-post' ), // Title 
			array( $this, 'single_permalink_callback' ), // Callback
			'themify-event-post', // Page
			'themify_event_post_permalink' // Section
		);

		add_settings_field(
			'category_permalink', // ID
			__( 'Category Permalink', 'themify-event-post' ), // Title 
			array( $this, 'category_permalink_callback' ), // Callback
			'themify-event-post', // Page
			'themify_event_post_permalink' // Section
		);

		add_settings_field(
			'currency', // ID
			__( 'Currency', 'themify-event-post' ), // Title 
			array( $this, 'currency' ), // Callback
			'themify-event-post', // Page
			'themify_event_post_price' // Section
		);
		add_settings_field(
			'currency_pos', // ID
			__( 'Currency Position', 'themify-event-post' ), // Title 
			array( $this, 'currency_pos' ), // Callback
			'themify-event-post', // Page
			'themify_event_post_price' // Section
		);
    }

	public function orderby() {
		$value = Themify_Event_Post::get_instance()->get_option( 'orderby' );
		$options = [
			'date' => __( 'Date', 'themify-event-post' ),
			'event_date' => __( 'Event Date', 'themify-event-post' ),
			'id' => __( 'ID', 'themify-event-post' ),
			'author' => __( 'Author', 'themify-event-post' ),
			'title' => __( 'Title', 'themify-event-post' ),
			'name' => __( 'Name', 'themify-event-post' ),
			'modified' => __( 'Modified Date', 'themify-event-post' ),
			'rand' => __( 'Random', 'themify-event-post' ),
			'comment_count' => __( 'Comments Count', 'themify-event-post' ),
		];
		echo '<select name="themify_event_post[orderby]">';
		foreach ( $options as $key => $label ) {
			echo '<option value="' . $key . '" ' . selected( $value, $key, false ) . '>' . $label . '</option>';
		}
		echo '</select>';
	}

	public function order() {
		$value = Themify_Event_Post::get_instance()->get_option( 'order' );
		$options = [
			'desc' => __( 'Descending', 'themify-event-post' ),
			'asc' => __( 'Ascending', 'themify-event-post' ),
		];
		echo '<select name="themify_event_post[order]">';
		foreach ( $options as $key => $label ) {
			echo '<option value="' . $key . '" ' . selected( $value, $key, false ) . '>' . $label . '</option>';
		}
		echo '</select>';
	}

	public function layout() {
		$value = Themify_Event_Post::get_instance()->get_option( 'layout', 'grid2' );
		$options = [
			'list-post' => __( '1 Column Grid', 'themify-event-post' ),
			'grid2' => __( '2 Columns Grid', 'themify-event-post' ),
			'grid3' => __( '3 Columns Grid', 'themify-event-post' ),
			'grid4' => __( '4 Columns Grid', 'themify-event-post' ),
		];
		echo '<select name="themify_event_post[layout]">';
		foreach ( $options as $key => $label ) {
			echo '<option value="' . $key . '" ' . selected( $value, $key, false ) . '>' . $label . '</option>';
		}
		echo '</select>';
	}

	public function show() {
		$value = Themify_Event_Post::get_instance()->get_option( 'show' );
		$options = [
			'all' => __( 'All Events', 'themify-event-post' ),
			'upcoming' => __( 'Upcoming Events', 'themify-event-post' ),
			'past' => __( 'Past Events', 'themify-event-post' ),
		];
		echo '<select name="themify_event_post[show]">';
		foreach ( $options as $key => $label ) {
			echo '<option value="' . $key . '" ' . selected( $value, $key, false ) . '>' . $label . '</option>';
		}
		echo '</select>';
	}

	public function google_maps_key_callback() {
		$value = Themify_Event_Post::get_instance()->get_option( 'google_maps_key' );
		printf(
			'<input type="text" class="regular-text" id="google_maps_key" name="themify_event_post[google_maps_key]" value="%s" />',
			esc_attr($value)
		);
		printf( '<p class="description">' . __( '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key#key" target="_blank">Generate an API</a> key and insert it here. This is required for displaying the event location on a map.', 'themify-event-post' ) . '</p>');
	}

	public function single_permalink_callback() {
		$value = Themify_Event_Post::get_instance()->get_option( 'single_permalink', 'event' );
		printf(
			'<input type="text" class="regular-text" id="single_permalink" name="themify_event_post[single_permalink]" value="%s" />',
			esc_attr($value)
		);
	}

	public function category_permalink_callback() {
		$value = Themify_Event_Post::get_instance()->get_option( 'category_permalink', 'event-category' );
		printf(
			'<input type="text" class="regular-text" id="category_permalink" name="themify_event_post[category_permalink]" value="%s" />',
			esc_attr($value)
		);
	}

	public function currency() {
		$value = Themify_Event_Post::get_instance()->get_option( 'currency' );
		printf(
			'<input type="text" class="small-text" id="currency" name="themify_event_post[currency]" value="%s" />',
			esc_attr($value)
		);
	}

	public function currency_pos() {
		$value = Themify_Event_Post::get_instance()->get_option( 'currency_pos', 'right_space' );
		$options = [
			'left' => __( 'Left', 'themify-event-post' ),
			'right' => __( 'Right', 'themify-event-post' ),
			'left_space' => __( 'Left with space', 'themify-event-post' ),
			'right_space' => __( 'Right with space', 'themify-event-post' ),
		];
		echo '<select name="themify_event_post[currency_pos]">';
		foreach ( $options as $key => $label ) {
			echo '<option value="' . $key . '" ' . selected( $value, $key, false ) . '>' . $label . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Callback for after plugin's options are saved
	 *
	 * Resets the permalinks to save new rewrite slug
	 *
	 * @since 1.0.0
	 */
	function updated_option( $option_name, $old_value, $value ) {
		if ( $option_name === 'themify_event_post' ) {
			/* re-register the post type to set the new rewrite slug */
			themify_event_post_register_post_type();
			/* flush permalinks to save the new rewrite slug */
			flush_rewrite_rules();
		}
	}

	/**
	 * Display an additional column in list
	 * @param array
	 * @return array
	 */
	function type_column_header( $columns ) {
		unset( $columns['date'] );
		$columns['icon'] = __( 'Image', 'themify-event-post' );
		$columns['event_date'] = __( 'Event Date', 'themify-event-post' );
		$columns['shortcode'] = __( 'Shortcode', 'themify-event-post' );
		return $columns;
	}

	/**
	 * Display shortcode, type, size and color in columns in tiles list
	 * @param string $column key
	 * @param number $post_id
	 * @return string
	 */
	function type_column( $column, $post_id ) {
		switch ( $column ) {
			case 'shortcode':
				echo '<code>[themify_event_post id="' . $post_id . '"]</code>';
				break;

			case 'event_date':
				themify_event_post_date();
				break;

			case 'icon' :
				$image = '';
				if ( has_post_thumbnail( $post_id ) ) {
					$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'thumbnail' );
					if ( isset( $img[0] ) ) {
						$image = '<img src="' . $img[0] . '" width="50" height="50">';
					}
				}

				echo ! empty( $image ) ? $image : __( 'No Featured Media', 'themify-event-post' );
				break;
		}
	}

	/**
	 * Select form element to filter the post list
	 * @return string HTML
	 */
	public function get_select() {
		global $typenow;

		if ( 'event' !== $typenow ) {
			return;
		}

		$html = '';
		foreach ( ['event-category', 'event-tag'] as $tax) {
			$options = sprintf('<option value="">%s %s</option>', __('View All', 'themify-event-post'),
			get_taxonomy($tax)->label);
			$class = is_taxonomy_hierarchical($tax) ? ' class="level-0"' : '';
			foreach (get_terms( $tax ) as $taxon) {
				$options .= sprintf('<option %s%s value="%s">%s%s</option>', isset($_GET[$tax]) ? selected($taxon->slug, $_GET[$tax], false) : '', '0' !== $taxon->parent ? ' class="level-1"' : $class, $taxon->slug, '0' !== $taxon->parent ? str_repeat('&nbsp;', 3) : '', "{$taxon->name} ({$taxon->count})");
			}
			$html .= sprintf('<select name="%s" id="%s" class="postform">%s</select>', $tax, $tax, $options);
		}
		return print $html;
	}

}
