<?php

class Themify_Event_Post {

	public $dir;
	public $url;
	public $version;
	public $pid = 'themify-event-post';
	private $options = null;
	public $date_time_format = 'Y-m-d H:i';

	/* a copy of original $wp_query object before it's modified by the plugin */
	public $query;
	public $post;

	/* flag to indicate rendering shortcode or the main loop */
	private static $is_shortcode = false;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public static function get_instance( $args = array() ) {
		static $instance = null;
		if ( $instance === null ) {
			$instance = new self( $args );
		}
		return $instance;
	}

	function __construct( $args = array() ) {
		$this->dir = $args['dir'];
		$this->url = $args['url'];
		$this->version = $args['version'];

		include( $this->dir . 'includes/post-type.php' );
		include( $this->dir . 'includes/widgets.php' );
		include( $this->dir . 'includes/functions.php' );
		if ( is_admin() ) {
		    add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_script') );
			include( $this->dir . 'includes/admin.php' );
			new Themify_Event_Post_Admin();
		} else {
			add_action( 'pre_get_posts', [ $this, 'pre_get_posts' ] );
		}

		add_action( 'after_setup_theme', array( $this, 'load_themify_library' ), 15 );
		add_action( 'init', array( $this, 'i18n' ) );
		add_shortcode( 'themify_event_post', array( $this, 'shortcode' ) );
		add_filter( 'themify_metabox/fields/themify-meta-boxes', array( $this, 'themify_do_metaboxes' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_filter( 'template_include', array( $this, 'template_include' ) );

		if ( current_user_can( 'publish_posts' ) && get_user_option( 'rich_editing' ) === 'true' ) {
			include( $this->dir . 'includes/tinymce.php' );
			new Themify_Events_Posts_TinyMCE();
		}

		add_action( 'themify_builder_setup_modules', array( $this, 'register_module' ) );
		if (function_exists('themify_builder_get') && empty(themify_builder_get('setting-search_exclude_event'))) {
			add_filter( 'themify_search_args', array( $this, 'add_event_to_search_result' ) );
		}
	}

	function i18n() {
		load_plugin_textdomain( 'themify-event-post', false, 'themify-event-post/languages' );
	}

	function template_include( $template ) {
		global $wp_query, $themify;

		if ( isset( $wp_query->post ) &&
			( is_singular( 'event' ) || is_tax( [ 'event-category', 'event-tag' ] ) )
		) {

			if ( class_exists( 'Tbp_Public' ,false) ) {
				$location = is_singular( 'event' ) ? 'single' : 'archive';
				/* a Builder Pro template is active, don't change the template */
				if ( ! empty( Tbp_Public::get_location( $location ) ) ) {
					return $template;
				}
			}

			$template = get_page_template();

			$this->query = clone $wp_query;
			$this->post = clone $wp_query->post;

		    if ( is_singular( 'event' ) ) {
			    $wp_query->post->post_title = '';
			    add_filter( 'the_content', array( $this, 'single_template' ), 999 );

				/* in Themify themes, disable the thumbnail */
				if ( isset( $themify ) ) {
					$themify->hide_page_image = 'yes';
				}
		    } else{

			    remove_filter( 'the_content', 'wpautop' );

			    $wp_query->posts_per_page     = 1;
			    $wp_query->nopaging           = true;
			    $wp_query->post_count         = 1;
			    $wp_query->post               = new WP_Post( new stdClass() );
			    $wp_query->post->ID           = 0;
			    $wp_query->post->filter       = 'raw';
			    $wp_query->post->post_title   = '';
			    $wp_query->post->post_content = $this->get_template( 'archive', array() );
			    $wp_query->posts              = array( $wp_query->post );
			    $wp_query->is_page            = false;
			    $wp_query->is_archive         = true;
			    $wp_query->is_category        = $this->query->is_tax( 'event-category' );
			    $wp_query->is_tax             = $this->query->is_tax( array( 'event-category', 'event-tag' ) );
			    $wp_query->is_single          = false;
		    }
		}
		return $template;
	}

	function single_template( $content ) {
		if ( get_post_type() === 'event' && self::$is_shortcode === false ) {
			$content = $this->get_template( 'single', array(
				'content' => $content
			) );
		}

		return $content;
	}

	function archive_template( $content ) {
		if ( get_post_type() === 'event' ) {
			$content = $this->get_template( 'archive', array() );
		}

		return $content;
	}

	public function load_themify_library() {
		defined( 'THEMIFY_METABOX_DIR' ) || define( 'THEMIFY_METABOX_DIR', $this->dir . 'includes/themify-metabox/' );
		defined( 'THEMIFY_METABOX_URI' ) || define( 'THEMIFY_METABOX_URI', $this->url . 'includes/themify-metabox/' );
		include_once( $this->dir . 'includes/themify-metabox/themify-metabox.php' );
	}

	public function wp_enqueue_scripts() {
		wp_enqueue_style( $this->pid, $this->url . 'assets/style.css' );

		/* the scripts.js file solely handles the map, on Themify themes this is not needed */
		if ( ! $this->is_using_themify_theme()) {
			$mapKey=$this->get_option( 'google_maps_key' );
			wp_enqueue_script( $this->pid, $this->url . 'assets/scripts.js', array( 'jquery' ), $this->version, true );
			if ( !empty($mapKey) ) {
				wp_localize_script( $this->pid, 'themifyEventPosts', array(
					'map_key' => $mapKey
				) );
			}
		}
	}

	public function enqueue_admin_script( $hook ) {
        $screen = get_current_screen();
		if ( $screen->post_type !== 'event' ) {
            return;
        }
        wp_enqueue_script( $this->pid . '-admin-script', $this->url . 'assets/admin_script.js', array('jquery'), $this->version );
    }

	/**
	 * Main shortcode rendering
	 * @param array $atts
	 * @param $post_type
	 * @return string|void
	 */
	function shortcode( $atts = array(), $content = '', $code = '' ) {
		self::$is_shortcode = true;
		$output = include $this->locate_template( 'shortcode' );
		self::$is_shortcode = false;
		return $output;
	}
	
	/**
	 * Return all options
	 *
	 * @return mixed
	 * @since 1.0
	 */
	public function get_options() {
		if( null === $this->options ) {
			$this->options = get_option( 'themify_event_post', array() );
		}

		return $this->options;
	}

	/**
	 * Return an option by its name
	 *
	 * @return mixed
	 * @since 1.0
	 */
	public function get_option( $name, $default = null ) {
		$options = $this->get_options();
		if(isset( $options[$name] )){
			return !is_array($options[$name])?sanitize_text_field($options[$name]):$options[$name];
		}
		return $default;
	}

	function themify_do_metaboxes( $meta_boxes, $post_type ) {
		if( 'event' !== $post_type ) {
			return $meta_boxes;
		}

		$event_options = array(
			'name'    => __( 'Event Settings', 'themify-event-post' ),
			'id'      => 'event-options',
			'options' => include( $this->locate_template( 'config-post-meta' ) ),
			'pages'   => 'event',
			'default_active' => true,
		);

		return array_merge( array( $event_options ), $meta_boxes );
	}

	function register_module() {
		if ( class_exists( 'Themify_Builder_Component_Module' ,false) ) {
            if(method_exists('Themify_Builder_Model', 'add_module')){
                Themify_Builder_Model::add_module($this->dir . 'modules/module-event-posts.php' );
            }
            else{
                Themify_Builder_Model::register_directory('templates',$this->dir  . 'templates');
                Themify_Builder_Model::register_directory('modules',$this->dir . 'modules');
            }
		}
	}

	public function get_template( $name, $args = array() ) {
		extract( $args );
		if( $path = $this->locate_template( $name ) ) {
			ob_start();
			include $path;
			return ob_get_clean();
		}

		return false;
	}

	public function get_shortcode_template( $posts, $slug, $args ) {
		global $post;
		if ( is_object( $post ) )
			$saved_post = clone $post;

		$html = '';

		foreach ( $posts as $post ) {
			setup_postdata( $post );
			$html .= $this->get_template( $slug, $args );
		}

		if ( isset( $saved_post ) && is_object( $saved_post ) ) {
			$post = $saved_post;
			setup_postdata( $saved_post );
		}

		return $html;
	}

	public function locate_template( $name ) {
		if( is_child_theme() && is_file( trailingslashit( get_stylesheet_directory() ) . trailingslashit( $this->pid ) . "{$name}.php" ) ) {
			return trailingslashit( get_stylesheet_directory() ) . trailingslashit( $this->pid ) . "{$name}.php";
		} else if( is_file( trailingslashit( get_template_directory() ) . trailingslashit( $this->pid ) . "{$name}.php" ) ) {
			return trailingslashit( get_template_directory() ) . trailingslashit( $this->pid ) . "{$name}.php";
		} else if( is_file( $this->get_template_dir() . "/{$name}.php" ) ) {
			return $this->get_template_dir() . "/{$name}.php";
		} else {
			return false;
		}
	}

	public function get_template_dir() {
		return $this->dir . 'templates';
	}

	function add_event_to_search_result( $args ) {
		$args['post_type'] = array_merge( array( 'event' ), (array) $args['post_type'] );
		return $args;
	}

	/**
	 * Returns true if the active theme is using Themify framework
	 *
	 * @return bool
	 */
	public function is_using_themify_theme() {
		return is_file( get_template_directory() . '/themify/themify-utils.php' );
	}

	/**
	 * Modifies the query on archive pages
	 *
	 * @since 1.2.1
	 */
	function pre_get_posts( $query ) {
		if ( $query->is_main_query() && (
			$query->is_tax( [ 'event-category', 'event-tag' ] )
			|| $query->is_post_type_archive( 'event' )
		) ) {
			$order = $this->get_option( 'order', 'desc' );
			$orderby = $this->get_option( 'orderby', 'date' );
			$show = $this->get_option( 'show', 'all' );

			$query->set( 'orderby', $orderby );
			$query->set( 'order', $order );
			if ( $orderby === 'event_date' ) {
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'meta_key', 'start_date' );
			}
			if ( $show === 'upcoming' ) {
				$query->set( 'meta_query', array(
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
					),
					array(
						'key' => 'repeat',
						'value' => 'none',
						'compare' => '!='
					)
				) );
			} elseif ( $show === 'past' ) {
				$query->set( 'meta_query', array(
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
				) );
			}
		}
	}
}