<?php

if ( ! function_exists( 'themify_event_post_map' ) ) :
/**
 * Render a Google Maps instance
 *
 * @return string
 */
function themify_event_post_map( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'address' => '99 Blue Jays Way, Toronto, Ontario, Canada',
		'width' => '100%',
		'height' => '300px',
		'zoom' => 15,
		'type' => 'ROADMAP',
		'scroll_wheel' => 'yes',
		'draggable_disable_mobile_map' => 'yes',
		'draggable' => 'yes',
	) );

	if ( class_exists( 'Themify_Google_Maps',false ) ) {
		ob_start();
		the_widget( 'Themify_Google_Maps', array(
			'address_map' => $args['address'],
			'width' => str_replace( 'px', '', $args['width'] ),
			'height' => str_replace( 'px', '', $args['height'] ),
			'zoom_map' => $args['zoom'],
			'type_map' => $args['type'],
			'scrollwheel_map' => $args['scroll_wheel'] === 'no' ? 'disable' : 'enable',
			'draggable_map' => $args['draggable'] === 'no' ? 'disable' : 'enable',
			'draggable_disable_mobile_map' => $args['draggable_disable_mobile_map'],
		) );
		return ob_get_clean();
	}

	extract( $args );

	/* if no unit is provided for width and height, use "px" */
	if( ! preg_match( '/[px|%]$/', $width ) ) {
		$width = $width . 'px';
	}
	if( ! preg_match( '/[px|%]$/', $height ) ) {
		$height = $height . 'px';
	}

	if ( 'yes' === $draggable && 'yes' === $draggable_disable_mobile_map && wp_is_mobile() ) {
		$draggable = 'disable';
	}
	$num = rand( 0, 10000 );
	$data['address'] = $address;
	$data['num'] = $num;
	$data['zoom'] = $zoom;
	$data['type'] = $type;
	$data['scroll'] =  $scroll_wheel ==='yes';
	$data['drag'] =  $draggable==='yes';
	return '
	<div class="tep_map_container">
		<div data-map=\''.esc_attr( json_encode( $data ) ).'\' id="themify_map_canvas_' . esc_attr( $num ) . '" style="display: block;width:' . esc_attr( $width ) . ';height:' . esc_attr( $height ) . ';" class="map-container tep_map"></div>
	</div>';
}
endif;

if ( ! function_exists( 'themify_event_post_get_repeat_date' ) ) :
/**
 * Displays repeated dates
 *
 * @since 1.0.0
 */
function themify_event_post_get_repeat_date( $type, $number, $date, $time ) {
	$result = '';
	switch( $type ) {
		case 'day':
			$result = '<span class="event-day">'. sprintf( _n( 'Every day','Every %s days', $number, 'themify-event-post' ), $number ).'</span>';
		break;
		case 'week':
			$result = '<span class="event-day">'. sprintf( _n( 'Every week on %2$s', 'Every %s weeks on %s', $number, 'themify-event-post' ), $number ,  date_i18n( 'l', strtotime( $date ) ) ).'</span>';
		break;
		case 'year':
			$result = '<span class="event-day">'. sprintf( _n( 'Every year on %2$s', 'Every %s years on %s', $number, 'themify-event-post' ), $number,  date_i18n( 'M d', strtotime( $date ) ) ).'</span>';
		break;
	}
	if ( $result && $time ) {
		$result .= '<span class="event-time-at">' ._x( ' @ ', 'Connector between date and time (with spaces around itself) in event date and time.', 'themify-event-post' ). '</span> <span class="event-time">'.date_i18n( get_option( 'time_format' ), strtotime( $time ) ).'</span>' ;
	}

	return apply_filters( 'themify_event_date_repeat', $result, $type, $number, $date, $time );
}
endif;

function themify_event_is_repeated() {
    $repeat = get_post_meta( get_the_id(), 'repeat', true );
	if ( $repeat ) {
		$repeat_x = intval( get_post_meta( get_the_id(), 'repeat_x', true ) );
		if ( $repeat_x <= 0 ) {
		    $repeat = false;
		}
	}

    return $repeat;
}

if ( ! function_exists( 'themify_event_post_date' ) ) :
/**
 * Displays Start Date and End Date properly formatted
 *
 * @since 1.0.0
 */
function themify_event_post_date( $date_format = null, $time_format = null ) {
    if(get_post_meta( get_the_id(), 'to_be_announced', true )==='on'){
        echo '<time class="tep_date"><span class="event-start-date">'.__('To be announced','themify-event-post').'</span></time>';
        return;
    }
    $start_date = get_post_meta( get_the_id(), 'start_date', true );
	$end_date = get_post_meta( get_the_id(), 'end_date', true );
	$repeat = themify_event_is_repeated();
	$hide_event_end = get_post_meta( get_the_id(), 'event_end_date_hide', true );

	if ( $start_date || $end_date ) {
		$date_format = $date_format ?: get_option( 'date_format' );
		$time_format = $time_format ?: get_option( 'time_format' );
		echo '<time class="tep_date">';

		if( $start_date ) {
			$stamp = date_create( $start_date, new DateTimeZone( 'UTC' ) )->format( 'U' );
			echo '<span class="event-start-date">';
			$start_date_parts = explode( ' ', $start_date );
			if( $repeat ) {
				echo themify_event_post_get_repeat_date( $repeat, intval( get_post_meta( get_the_id(), 'repeat_x', true ) ), $start_date_parts[0], $start_date_parts[1] );
			} else {
				echo '<span class="event-day"> '.date_i18n( $date_format, $stamp ) .'</span> <span class="event-time-at">'. _x( ' @ ', 'Connector between date and time (with spaces around itself) in event date and time.', 'themify-event-post' ) .'</span> <span class="event-time">' . date_i18n( $time_format, $stamp ) .'</span>';
			}
			echo '</span>';
		}
		if( !$hide_event_end && ! $repeat && $end_date ) {
			$stamp = date_create( $end_date, new DateTimeZone( 'UTC' ) )->format( 'U' );
			echo '<span class="event-end-date">';
			$end_date_parts = explode( ' ', $end_date );
			echo ! isset( $start_date_parts ) || $start_date_parts[0] != $end_date_parts[0] ?'<span class="event-date-separator">'. _x( ' - ', 'Character to provide a hint that this is the event end date and time.', 'themify-event-post' ) .'</span>
 					<span class="event-day">' . date_i18n( $date_format, $stamp ) .'</span>' :' <span class="event-date-separator"> - </span>';
			if ( isset( $start_date_parts[0] ) && $start_date_parts[0] != $end_date_parts[0] ) {
				echo '<span class="event-time-at">'. _x( ' @ ', 'Connector between date and time (with spaces around itself) in event date and time.', 'themify-event-post' ).'</span>';
			}
			echo '<span class="event-time">' . date_i18n( $time_format, $stamp ) . '</span>';
			echo '</span>';
		}

		echo '</time>';
	}
}
endif;

if ( ! function_exists( 'themify_event_type' ) ) :
    /**
     * Displays event type if set
     *
     * @since 1.1.2
     */
    function themify_event_type() {
        $post_id = get_the_ID();
        $e_type=get_post_meta( $post_id, 'event_attendance', true );
        if(empty($e_type)){
            return;
        }
        echo sprintf('<div class="tep_type">%s</div>',$e_type);
    }
endif;

if ( ! function_exists( 'themify_event_organizer' ) ) :
    /**
     * Displays organizer meta
     *
     * @since 1.1.2
     */
    function themify_event_organizer() {
        $post_id = get_the_ID();
        $name=get_post_meta( $post_id, 'organizer_name', true );
        if(empty($name)){
            return;
        }
        $url=esc_url(get_post_meta( $post_id, 'organizer_url', true ));
        ob_start();
        ?>
        <div class="tep_organizer">
            <?php if(!empty($url)): ?>
            <a href="<?php echo $url ?>" target="_blank">
            <?php endif; ?>
            <?php echo $name ?>
            <?php if(!empty($url)): ?>
            </a>
            <?php endif; ?>
        </div>
        <?php
        ob_end_flush();
    }
endif;

if ( ! function_exists( 'themify_event_performer' ) ) :
    /**
     * Displays organizer meta
     *
     * @since 1.1.2
     */
    function themify_event_performer() {
        $post_id = get_the_ID();
        $name=get_post_meta( $post_id, 'performer_name', true );
        if(empty($name)){
            return;
        }
        ob_start();
        ?>
        <div class="tep_performer">
            <?php echo $name ?>
        </div>
        <?php
        ob_end_flush();
    }
endif;

if ( ! function_exists( 'themify_event_post_pagenav' ) ) :
/**
 * Renders page navigation for Event posts
 *
 * @return string
 */
function themify_event_post_pagenav( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'paged' => 1,
		'total_posts' => 0,
		'posts_per_page' => 0,
		'pages_to_show' => 5,
		'offset' => 0,
		'before' => '',
		'after' => '',
	) );
	extract( $args );

	// $query->found_posts does not take offset into account, we need to manually adjust that
	if ( (int) $offset) {
		$total_posts = $total_posts - (int) $offset;
	}

	$max_page = ceil( $total_posts / $posts_per_page );
	$out = '';

	if ( empty( $paged ) ) {
		$paged = 1;
	}
	$pages_to_show_minus_1 = $pages_to_show - 1;
	$half_page_start = floor($pages_to_show_minus_1 / 2);
	$half_page_end = ceil($pages_to_show_minus_1 / 2);
	$start_page = $paged - $half_page_start;
	if ($start_page <= 0) {
		$start_page = 1;
	}
	$end_page = $paged + $half_page_end;
	if (($end_page - $start_page) != $pages_to_show_minus_1) {
		$end_page = $start_page + $pages_to_show_minus_1;
	}
	if ($end_page > $max_page) {
		$start_page = $max_page - $pages_to_show_minus_1;
		$end_page = $max_page;
	}
	if ($start_page <= 0) {
		$start_page = 1;
	}

	if ($max_page > 1) {

		if ( class_exists( 'Themify_Enqueue_Assets',false ) && ! empty( Themify_Enqueue_Assets::$themeVersion ) && Themify_Enqueue_Assets::$themeVersion !== null ) {
			Themify_Enqueue_Assets::loadThemeStyleModule( 'pagenav' );
	    }

		$out .= $before . '<div class="pagenav tf_clearfix">';
		if ($start_page >= 2 && $pages_to_show < $max_page) {
			$first_page_text = "&laquo;";
			$out .= '<a href="' . esc_url(get_pagenum_link()) . '" title="' . esc_attr($first_page_text) . '" class="number">' . $first_page_text . '</a>';
		}
		if ($pages_to_show < $max_page)
			$out .= get_previous_posts_link('&lt;');
		for ($i = $start_page; $i <= $end_page; $i++) {
			if ($i == $paged) {
				$out .= ' <span class="number current">' . $i . '</span> ';
			} else {
				$out .= ' <a href="' . esc_url(get_pagenum_link($i)) . '" class="number">' . $i . '</a> ';
			}
		}
		if ($pages_to_show < $max_page)
			$out .= get_next_posts_link('&gt;');
		if ($end_page < $max_page) {
			$last_page_text = "&raquo;";
			$out .= '<a href="' . esc_url(get_pagenum_link($max_page)) . '" title="' . esc_attr($last_page_text) . '" class="number">' . $last_page_text . '</a>';
		}
		$out .= '</div>' . $after;
	}
	return $out;
}
endif;

if ( ! function_exists( 'themify_event_post_get_paged_query' ) ) :
/**
 * Gets "paged" query var
 *
 * @return int
 */
function themify_event_post_get_paged_query() {
	global $wp;
	$page = 1;
	$qpaged = get_query_var( 'paged' );
	if ( ! empty( $qpaged ) ) {
		$page = $qpaged;
	} else {
		$qpaged = wp_parse_args( $wp->matched_query );
		if ( isset( $qpaged['paged'] ) && $qpaged['paged'] > 0 ) {
			$page = $qpaged['paged'];
		}
	}
	return $page;
}
endif;

if ( ! function_exists( 'themify_event_post_get_image' ) ) :
/**
 * Display post thumbnail
 *
 * @return string
 * @since 1.0.8
 */
function themify_event_post_get_image( $args = array() ) {
	if ( ! has_post_thumbnail() ) {
		return '';
	}

	global $wp_version;

	/**
	 * List of parameters
	 * @var array
	 */
	$args = wp_parse_args( $args, array(
		'id'          => '',
		'ignore'      => '',
		'width'       => '',
		'height'      => '',
		'before'      => '<figure class="tep_image">',
		'after'       => '</figure>',
		'class'       => '',
		'alt'         => '',
		'title'       => '',
		'unlink'      => false,
		'image_meta'  => true,
		'crop'        => true,
	) );
	extract( $args );

	$id = (int) get_post_thumbnail_id(); /* Image script works with thumbnail IDs as well as URLs, use ID which is faster */

	$temp = themify_events_do_img( $id, $width, $height, (bool) $args['crop'] );
	$img_url = $temp['url'];

	// Build final image
	$out = '';
	if ( $args['image_meta'] == true ) {
		$out .= "<meta itemprop=\"width\" content=\"{$width}\">";
		$out .= "<meta itemprop=\"height\" content=\"{$height}\">";
		$out .= "<meta itemprop=\"url\" content=\"{$img_url}\">";
	}
	$out .= "<img src=\"{$img_url}\"";
	if ( $width ) {
		$out .= " width=\"{$width}\"";
	}
	if ( $height ) {
		$out .= " height=\"{$height}\"";
	}
	$args['class'] .= ' wp-post-image wp-image-' . $id; /* add attachment_id class to img tag */

	if ( ! empty( $args['class'] ) ) {
		$out .= " class=\"{$args['class']}\"";
	}

	// Add title attribute only if explicitly set in $args
	if ( ! empty( $args['title'] ) ) {
		$out .= ' title="' . esc_attr( $args['title'] ) . '"';
	}

	// If alt was passed as parameter, use it. Otherwise use alt text by attachment id if it was fetched or post title.
	$out_alt = '';
	if ( ! empty( $args['alt'] ) ) {
		$out_alt = $args['alt'] === 'false' ? '' : $args['alt'];
	} elseif ( ! empty( $img_alt ) ) {
		$out_alt = $img_alt;
	} else {
		if ( ! empty( $args['title'] ) ) {
			$out_alt = $args['title'];
		} elseif ( $id ) {
			$p = get_post( $id );
			if ( $p ) {
				$out_alt = $p->post_title;
			}
		} else {
			$out_alt = the_title_attribute( 'echo=0' );
		}
	}
	$out .= ' alt="' . esc_attr( $out_alt ) . '" />';

	if ( ! $unlink ) {
		$args['before'] .= sprintf( '<a href="%s">', get_permalink() );
		$args['after'] = '</a>' . $args['after'];
	}

	$out = $args['before'] . $out . $args['after'];

	if( version_compare( $wp_version, '4.4', '>=' ) ) {
		$out = function_exists('wp_filter_content_tags') ? wp_filter_content_tags($out) : wp_make_content_images_responsive( $out );
	}

	return $out;
}
endif;

if ( ! function_exists( 'themify_event_post_title' ) ) :
/**
 * Display title for event posts
 *
 * @return string|null
 * @since 1.0.1
 */
function themify_event_post_title( $args = array() ) {
	extract( wp_parse_args( $args, array(
		'post' => 0,
		'tag' => 'h2',
		'class' => 'tep_post_title entry-title',
		'before' => '',
		'after' => '',
		'before_title' => '',
		'after_title' => '',
		'echo' => true,
		'unlink' => false,
	), 'post_title' ) );

	$post = get_post( $post );
	$title = get_the_title( $post );
	if ( strlen( $title ) == 0 ) {
		return;
	}

	$link_before = $unlink ? '' : '<a href="' . get_permalink() .'">';
	$link_after = $unlink ? '' : '</a>';

	$before = "{$before} <{$tag} class=\"{$class}\">{$before_title}{$link_before}";
	$after = "{$link_after}{$after_title} </{$tag}>{$after}";

	$title = $before . $title . $after;

	if ( $echo ) {
		echo $title;
	} else {
		return $title;
	}
}
endif;

/**
 * Get a WP_Query parameters from the shortcode attributes
 *
 * @param $atts the array of shortcode parameters supplied by user
 * @param $shortcode the name of shortcode calling, provides "shortcode_atts_$shortcode" filter
 * @param $defaults allows overriding the default shortcode atts, before being replaced by $atts
 *
 * @return array
 */
function themify_event_post_parse_shortcode_ids( $category ) {

	if ( ! is_array( $category ) ) {
		$category = array_map( 'trim', explode( ',', $category ) );
	}

	$ids_in = $ids_not_in = $slugs_in = $slugs_not_in = array();
	foreach ( $category as $v ) {
		$v = trim( $v );
		$except = '-' !== $v[0];
		if ( is_numeric( $v ) ) {
			if( $except === true ) {
				$ids_in[] = $v;
			} else {
				$ids_not_in[] = abs( $v );
			}
		}
		else{
			if( $except === true ) {
				$slugs_in[] = $v;
			} else {
				$slugs_not_in[] = substr( $v, 1 );
			}
		}
	}
	return array( $ids_in, $ids_not_in, $slugs_in, $slugs_not_in );
}

/**
 * Parses $args to return a formatted array for WP_Query object
 *
 * @return array
 * @since 1.0.2
 */
function themify_event_post_parse_query( $args = array() ) {
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
		'id' => '',
	);
	$args = shortcode_atts( $defaults, $args );
	extract( $args );

	$paged = themify_event_post_get_paged_query();
	$query_args = array(
		'post_type' => $post_type,
		'offset' => ( ( $paged - 1 ) * (int) $limit ) + (int) $offset,
		'posts_per_page' => $limit,
		'suppress_filters' => false,
		'orderby' => $orderby,
		'order' => $order,
	);

	if ( ! empty( $args['id'] ) ) {
		$query_args['p'] = $args['id'];
		return $query_args;
	}

	// handle weird way Builder saves query_cat field types
	$category = preg_replace( '/\|[multiple|single]*$/', '', $category );

	if ( '0' !== $category && ! empty( $category ) ) {

		list( $ids_in, $ids_not_in, $slugs_in, $slugs_not_in ) = themify_event_post_parse_shortcode_ids( $category );

		$query_args['tax_query'] = array(
			'relation' => $taxonomy_relation
		);
		if ( ! empty( $ids_in ) ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field' => 'id',
				'terms' => $ids_in
			);
		}
		if ( ! empty( $ids_not_in ) ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field' => 'id',
				'terms' => array_map( 'abs', $ids_not_in ),
				'operator' => 'NOT IN'
			);
		}
		if ( ! empty( $slugs_in ) ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $slugs_in
			);
		}
		if ( ! empty( $slugs_not_in ) ) {
		    $query_args['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $slugs_not_in,
				'operator' => 'NOT IN'
			);
		}
	}

	if ( $orderby == 'event_date' ) {
		$query_args['orderby'] = 'meta_value';
		$query_args['meta_key'] = 'start_date';
	}

	if ( $show === 'upcoming' ) {
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
			),
			array(
				'key' => 'repeat',
				'value' => 'none',
				'compare' => '!=')
		);
	} elseif ( $show === 'past' ) {
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
		$query_args['order'] = $order;
	}

	return $query_args;
}

if ( ! function_exists( 'themify_event_post_json_ld_generator' ) ) :
	/**
	 * Generate the JSON-LD structure data
	 *
	 * @return string|null
	 * @since 1.0.1
	 */
	function themify_event_post_json_ld_generator( $args = array() ) {
		$schema = array(
		        '@context'=>'https://schema.org',
				'@type'=>'Event',
				'name'=>$args['name'],
				'startDate'=>$args['start_date'],
				'endDate'=>$args['end_date'],
				'location'=>array(
				    '@type'=>'Place',
					'name'=>$args['place'],
					'address'=>array(
					    '@type'=>'PostalAddress',
						'streetAddress'=>$args['address']
					)
				),
				'image'=>!empty($args['image']) ? array($args['image']) : array(),
				'description'=>$args['decription'],
				'offers'=>array(
				    '@type'=>'Offer',
					'url'=>$args['buy_ticket'],
					'price'=>$args['ticket_price'],
					'priceCurrency'=>$args['ticket_currency'],
					'validFrom'=>$args['ticket_purchase_start'],
					'validThrough'=>$args['ticket_purchase_end'],
					'availability'=>''
				),
				'performer'=>array(
				    '@type'=>'',
                    'name'=>''
				),
                'organizer'=>array(
                    '@type'=>'',
                    'name'=>'',
                    'url'=>''  
                ),
                'eventAttendanceMode'=>'',
                'eventStatus'=>'',
			);

		$timezone = new DateTimeZone( wp_timezone_string() );
		if (!empty($args['event_attendance'])) $schema['eventAttendanceMode'] = 'https://schema.org/' . $args['event_attendance'] . 'EventAttendanceMode';
		else unset($schema['eventAttendanceMode']);

		if (empty($args['event_status'])) $args['event_status'] = 'Scheduled';
		$schema['eventStatus'] = 'https://schema.org/Event' . $args['event_status'];
		
		if (!empty($args['organizer_name']) || !empty($args['organizer_url'])){
		    $schema['organizer']['@type'] = $args['organizer'];
		    $schema['organizer']['name'] = $args['organizer_name'];
		    $schema['organizer']['url'] = $args['organizer_url'];
		} else unset($schema['organizer']);
		
		if (!empty($args['performer_name'])){
		    $schema['performer']['@type'] = $args['performer'];
		    $schema['performer']['name'] = $args['performer_name'];
		} else unset($schema['performer']);
		
		if (!empty($args['ticket_availability'])) $schema['offers']['availability'] = 'https://schema.org/' . $args['ticket_availability'];
		else unset($schema['offers']['availability']);
		
		if ( ! empty( $schema['startDate'] ) ) {
			$schema['startDate'] = date_create( $schema['startDate'], $timezone )->format( 'c' );
		}
		
		if ( ! empty( $schema['endDate'] ) ) {
            $schema['endDate'] = date_create( $schema['endDate'], $timezone )->format( 'c' );
		}
		
		if ( ! empty( $schema['validFrom'] ) ) {
		    $schema['validFrom'] = date_create( $schema['validFrom'], $timezone )->format( 'c' );
		}
		
		if ( ! empty( $schema['validThrough'] ) ) {
            $schema['validThrough'] = date_create( $schema['validThrough'], $timezone )->format( 'c' );
		}
		
		$output =
		'<script type="application/ld+json">'
			. json_encode( array( $schema ) )
		. '</script>';

		return $output;
	}
endif;

/**
 * Resize images dynamically using wp built in functions
 *
 * @param string|int $image Image URL or an attachment ID
 * @param int $width
 * @param int $height
 * @param bool $crop
 * @return array
 */
function themify_events_do_img( $image, $width, $height, $crop = false ) {
	$attachment_id = null;
	$img_url = null;

	$width = is_numeric( $width ) ? $width : '';
	$height = is_numeric( $height ) ? $height : '';
	// if an attachment ID has been sent
	if( is_int( $image ) ) {
		$post = get_post( $image );
		if( $post ) {
			$attachment_id = $post->ID;
			$img_url = wp_get_attachment_url( $attachment_id );
		}
	} else {
		// URL has been passed to the function
		$img_url = esc_url( $image );

		// Check if the image is an attachment. If it's external return url, width and height.
		$upload_dir = wp_get_upload_dir();
		$base_url = preg_replace( '/https?:\/\/(www\.)?/', '', $upload_dir['baseurl'] ); // Removes protocol and WWW
		if( ! preg_match( '/' . str_replace( '/', '\/', $base_url ) . '/', $img_url ) ) {
			return array(
				'url' =>$img_url,
				'width' => $width,
				'height' => $height,
			);
		}

		// Finally, run a custom database query to get the attachment ID from the modified attachment URL
		$attachment_id = themify_events_get_attachment_id_from_url( $img_url, $base_url );
	}
	// Fetch attachment meta data. Up to this point we know the attachment ID is valid.
	$meta = $attachment_id ?wp_get_attachment_metadata( $attachment_id ):null;

	// missing metadata. bail.
	if ( ! is_array( $meta ) ) {
		return array(
			'url' => $img_url,
			'width' => $width,
			'height' => $height,
		);
	}

	// Perform calculations when height or width = 0
	if( empty( $width ) ) {
		$width = 0;
	}
	if ( empty( $height ) ) {
		// If width and height or original image are available as metadata
		if ( !empty( $meta['width'] ) && !empty( $meta['height'] ) ) {
			// Divide width by original image aspect ratio to obtain projected height
			// The floor function is used so it returns an int and metadata can be written
			$height = floor( $width / ( $meta['width'] / $meta['height'] ) );
		} else {
			$height = 0;
		}
	}
	// Check if resized image already exists
	if ( is_array( $meta ) && isset( $meta['sizes']["resized-{$width}x{$height}"] ) ) {
		$size = $meta['sizes']["resized-{$width}x{$height}"];
		if( isset( $size['width'],$size['height'] )) {
			setlocale( LC_CTYPE, get_locale() . '.UTF-8' );
			$split_url = explode( '/', $img_url );
			
			if( ! isset( $size['mime-type'] ) || $size['mime-type'] !== 'image/gif' ) {
				$split_url[ count( $split_url ) - 1 ] = $size['file'];
			}

			return array(
				'url' => implode( '/', $split_url ),
				'width' => $width,
				'height' => $height,
				'attachment_id' => $attachment_id,
			);
		}
	}

	// Requested image size doesn't exists, so let's create one
	if ( true == $crop ) {
		add_filter( 'image_resize_dimensions', 'themify_events_img_resize_dimensions', 10, 5 );
	}
	// Patch meta because if we're here, there's a valid attachment ID for sure, but maybe the meta data is not ok.
	if ( empty( $meta ) ) {
		$meta['sizes'] = array( 'large' => array() );
	}
	// Generate image returning an array with image url, width and height. If image can't generated, original url, width and height are used.
	$image = themify_events_make_image_size( $attachment_id, $width, $height, $meta, $img_url );
	
	if ( true == $crop ) {
		remove_filter( 'image_resize_dimensions', 'themify_events_img_resize_dimensions', 10 );
	}
	$image['attachment_id'] = $attachment_id;
	return $image;
}

/**
 * Creates new image size.
 *
 * @uses get_attached_file()
 * @uses image_make_intermediate_size()
 * @uses wp_update_attachment_metadata()
 * @uses get_post_meta()
 * @uses update_post_meta()
 *
 * @param int $attachment_id
 * @param int $width
 * @param int $height
 * @param array $meta
 * @param string $img_url
 *
 * @return array
 */
function themify_events_make_image_size( $attachment_id, $width, $height, $meta, $img_url ) {
	if($width!==0 || $height!==0){
		setlocale( LC_CTYPE, get_locale() . '.UTF-8' );
		$attached_file = get_attached_file( $attachment_id );

		$default_size = function_exists( 'themify_get' )
						? themify_get( 'setting-img_php_base_size', 'large', true )
						: 'large';
		$source_size = apply_filters( 'themify_image_script_source_size', $default_size );
		if ( $source_size !== 'full' && isset( $meta['sizes'][ $source_size ]['file'] ) )
			$attached_file = str_replace( $meta['file'], trailingslashit( dirname( $meta['file'] ) ) . $meta['sizes'][ $source_size ]['file'], $attached_file );

		$resized = image_make_intermediate_size( $attached_file, $width, $height, true );
		if ( $resized && ! is_wp_error( $resized ) ) {

			// Save the new size in meta data
			$key = sprintf( 'resized-%dx%d', $width, $height );
			$meta['sizes'][$key] = $resized;
			$img_url = str_replace( basename( $img_url ), $resized['file'], $img_url );

			wp_update_attachment_metadata( $attachment_id, $meta );

			// Save size in backup sizes so it's deleted when original attachment is deleted.
			$backup_sizes = get_post_meta( $attachment_id, '_wp_attachment_backup_sizes', true );
			if ( ! is_array( $backup_sizes ) ) $backup_sizes = array();
			$backup_sizes[$key] = $resized;
			update_post_meta( $attachment_id, '_wp_attachment_backup_sizes', $backup_sizes );
			$img_url=esc_url($img_url);
		}
	}
	// Return original image url, width and height.
	return array(
		'url' => $img_url,
		'width' => $width,
		'height' => $height
	);
}

/**
* Disable the min commands to choose the minimum dimension, thus enabling image enlarging.
*
* @param $default
* @param $orig_w
* @param $orig_h
* @param $dest_w
* @param $dest_h
* @return array
*/
function themify_events_img_resize_dimensions( $default, $orig_w, $orig_h, $dest_w, $dest_h ) {
	// set portion of the original image that we can size to $dest_w x $dest_h
	$aspect_ratio = $orig_w / $orig_h;
	$new_w = $dest_w;
	$new_h = $dest_h;

	if ( !$new_w ) {
		$new_w = (int)( $new_h * $aspect_ratio );
	}

	if ( !$new_h ) {
		$new_h = (int)( $new_w / $aspect_ratio );
	}

	$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

	$crop_w = round( $new_w / $size_ratio );
	$crop_h = round( $new_h / $size_ratio );

	$s_x = floor( ( $orig_w - $crop_w ) / 2 );
	$s_y = floor( ( $orig_h - $crop_h ) / 2 );

	// the return array matches the parameters to imagecopyresampled()
	// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
	return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}

/**
 * Get attachment ID for image from its url.
 *
 * @param string $url
 * @param string $base_url
 * @return bool|null|string
 */
function themify_events_get_attachment_id_from_url( $url = '', $base_url = '' ) {
	// If this is the URL of an auto-generated thumbnail, get the URL of the original image
	$url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif|webp)$)/i', '', $url );

	$id = attachment_url_to_postid( $url );
	return $id;
}

/**
 * Template function to display the ticket price for an event.
 * Must be used inside the loop, or provide a post_id|WP_Post object for $post param.
 *
 * @since 1.2.5
 */
function themify_event_post_get_price( $post = null, $before = '', $after = '' ) {
	$post = get_post( $post );
	$price = get_post_meta( $post->ID, 'buy_tickets_price', true );
	if ( empty( $price ) ) {
		return '';
	}

	$currency = get_post_meta( $post->ID, 'buy_tickets_currency', true );
	if ( empty( $currency ) ) {
		$currency = Themify_Event_Post::get_instance()->get_option( 'currency' );
	}
	$currency_pos = Themify_Event_Post::get_instance()->get_option( 'currency_pos', 'right_space' );
	switch ( $currency_pos ) {
		case 'left':
			$format = '%2$s%1$s';
			break;
		case 'right':
			$format = '%1$s%2$s';
			break;
		case 'left_space':
			$format = '%2$s %1$s';
			break;
		default:
			$format = '%1$s %2$s';
	}

	return $before . sprintf( $format, $price, $currency ) . $after;
}

if ( ! function_exists( 'themify_event_post_edit_link' ) ) :
function themify_event_post_edit_link() {
    if ( function_exists( 'themify_post_edit_link' ) ) {
        themify_post_edit_link();
    } else {
        edit_post_link();
    }
}
endif;

if ( ! function_exists( 'themify_event_get_buy_ticket' ) ) :
function themify_event_get_buy_ticket( $post = null ) : string {
    $post = get_post( $post );
    $output = '';
    if ( $buy_ticket = get_post_meta( $post->ID, 'buy_tickets', true ) ) {
        $buy_tickets_label = get_post_meta( $post->ID, 'buy_tickets_label', true );
        if ( ! $buy_tickets_label ) {
            $buy_tickets_label = __( 'Buy Tickets', 'themify-event-post' );
        }

        $is_visible = true;
        $start_date = get_post_meta( $post->ID, 'ticket_purchase_start_date', true );
        $end_date = get_post_meta( $post->ID, 'ticket_purchase_end_date', true );
        $time = time();
        if (
            ( $start_date && $time < strtotime( $start_date ) )
            || ( $end_date && $time > strtotime( $end_date ) )
        ) {
            $is_visible = false;
        }

        $attr = [
            'target' => '_blank',
            'class' => 'tep_ticket_link'
        ];
        if ( $is_visible ) {
            $attr['href'] = $buy_ticket;
        } else {
            $attr['href'] = '#';
            $attr['class'] .= ' tep_ticket_disabled';
            $attr['data-tep_tooltip'] = __( 'Purchase is not available at the moment.', 'themify-event-post' );
        }

        $buy_tickets_label .= themify_event_post_get_price( null, ' - ' );
        $output = sprintf( '<a %s>%s</a>', themify_event_build_atts( $attr ), esc_html( $buy_tickets_label ) );
    }

    return $output;
}
endif;

if ( ! function_exists( 'themify_event_buy_ticket' ) ) :
function themify_event_buy_ticket( $before = '<p class="tep_ticket">', $after = '</p>' ) {
    $buy_button = themify_event_get_buy_ticket();
    if ( $buy_button ) {
        echo $before, $buy_button, $after;
    }
}
endif;

function themify_event_is_visible( $post = null ) : bool {
    $post = get_post( $post );

    $start_date = get_post_meta( $post->ID, 'start_date', true );
	$end_date = get_post_meta( $post->ID, 'end_date', true );
    $time = time();
    if (
        ( ! $start_date || $time > strtotime( $start_date ) )
        && ( ! $end_date || $time < strtotime( $end_date ) )
    ) {
        return true;
    }

    return false;
}

function themify_event_build_atts( $atts = array() ) : string {
    $attribute_string = '';
    foreach ( $atts as $attr => $value ) {
        if ( false !== $value && '' !== $value && is_scalar( $value ) ) {
            $value             = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
            $attribute_string .= ' ' . $attr . '="' . $value . '"';
        }
    }
    return $attribute_string;
}