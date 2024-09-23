<?php

return array(
	array(
		'name' => 'style',
		'type' => 'listbox',
		'values' => array(
			array( 'value' => 'list-post', 'text' => __( 'List Post', 'themify-event-post' ) ),
			array( 'value' => 'grid4', 'text' => __( 'Grid 4', 'themify-event-post' ) ),
			array( 'value' => 'grid3', 'text' => __( 'Grid 3', 'themify-event-post' ) ),
			array( 'value' => 'grid2', 'text' => __( 'Grid 2', 'themify-event-post' ) ),
			array( 'value' => 'grid2-thumb', 'text' => __( 'Grid 2 Thumb', 'themify-event-post' ) ),
		),
		'label' => __( 'Layout Style:', 'themify-event-post' ),
		'tooltip' => __( 'Default = grid3', 'themify-event-post' )
	),
	array(
		'name' => 'show',
		'type' => 'listbox',
		'values' => array(
			array( 'value' => 'upcoming', 'text' => __( 'Upcoming Events', 'themify-event-post' ) ),
			array( 'value' => 'past', 'text' => __( 'Past Events', 'themify-event-post' ) ),
			array( 'value' => 'mix', 'text' => __( 'All Events', 'themify-event-post' ) ),
			// array( 'value' => 'tabbed', 'text' => __( 'Tabbed', 'themify-event-post' ) ),
		),
		'label' => __( 'Show', 'themify-event-post' ),
	),
	array(
		'name' => 'limit',
		'type' => 'textbox',
		'label' => __( 'Number of Posts to Query:', 'themify-event-post' ),
		'tooltip' => __( 'Default = 5', 'themify-event-post' )
	),
	array(
		'name' => 'category',
		'type' => 'textbox',
		'label' => __( 'Categories to include', 'themify-event-post' ),
		'tooltip' => __( 'Enter the category ID numbers (eg. 2,5,6) or leave blank for default (all categories). Use minus number to exclude category (eg. category=-1 will exclude category 1).', 'themify-event-post' )
	),
	array(
		'name' => 'order',
		'type' => 'listbox',
		'values' => array(
			array( 'value' => 'DESC', 'text' => __( 'Descending', 'themify-event-post' ) ),
			array( 'value' => 'ASC', 'text' => __( 'Ascending', 'themify-event-post' ) ),
		),
		'label' => __( 'Post Order:', 'themify-event-post' ),
		'tooltip' => __( 'Default = descending.', 'themify-event-post' )
	),
	array(
		'name' => 'orderby',
		'type' => 'listbox',
		'values' => array(
			array( 'value' => 'event_date', 'text' => __( 'Event Date', 'themify-event-post' ) ),
			array( 'value' => 'date', 'text' => __( 'Date', 'themify-event-post' ) ),
			array( 'value' => 'title', 'text' => __( 'Title', 'themify-event-post' ) ),
			array( 'value' => 'rand', 'text' => __( 'Random', 'themify-event-post' ) ),
			array( 'value' => 'author', 'text' => __( 'Author', 'themify-event-post' ) ),
			array( 'value' => 'comment_count', 'text' => __( 'Comments number', 'themify-event-post' ) ),
		),
		'label' => __( 'Sort Posts By:', 'themify-event-post' ),
		'tooltip' => __( 'Default = date.', 'themify-event-post' )
	),
	array(
		'name' => 'display',
		'type' => 'listbox',
		'values' => array(
			array( 'text' => __( 'Excerpt', 'themify-event-post' ), 'value' => 'excerpt' ),
			array( 'text' => __( 'Content', 'themify-event-post' ), 'value' => 'content' ),
			array( 'text' => __( 'None', 'themify-event-post' ), 'value' => 'none' ),
		),
		'label' => __( 'Display', 'themify-event-post' ),
	),
	array(
		'name' => 'image',
		'type' => 'listbox',
		'values' => array(
			array( 'text' => __( 'Yes', 'themify-event-post' ), 'value' => 'yes' ),
			array( 'text' => __( 'No', 'themify-event-post' ), 'value' => 'no' ),
		),
		'label' => __( 'Show Featured Image', 'themify-event-post' ),
	),
	array(
		'name' => 'image_w',
		'type' => 'textbox',
		'label' => __( 'Image Width', 'themify-event-post' ),
	),
	array(
		'name' => 'image_h',
		'type' => 'textbox',
		'label' => __( 'Image Height', 'themify-event-post' ),
	),
	array(
		'name' => 'hide_event_date',
		'type' => 'listbox',
		'values' => array(
			array( 'text' => __( 'No', 'themify-event-post' ), 'value' => 'no' ),
			array( 'text' => __( 'Yes', 'themify-event-post' ), 'value' => 'yes' ),
		),
		'label' => __( 'Hide Event Date', 'themify-event-post' ),
	),
    array(
        'name' => 'hide_event_organizer',
        'type' => 'listbox',
        'values' => array(
            array( 'text' => __( 'No', 'themify-event-post' ), 'value' => 'no' ),
            array( 'text' => __( 'Yes', 'themify-event-post' ), 'value' => 'yes' ),
        ),
        'label' => __( 'Hide Event Organizer', 'themify-event-post' ),
    ),
	array(
        'name' => 'hide_event_performer',
        'type' => 'listbox',
        'values' => array(
            array( 'text' => __( 'No', 'themify-event-post' ), 'value' => 'no' ),
            array( 'text' => __( 'Yes', 'themify-event-post' ), 'value' => 'yes' ),
        ),
        'label' => __( 'Hide Event Performer', 'themify-event-post' ),
    ),
	array(
		'name' => 'hide_event_location',
		'type' => 'listbox',
		'values' => array(
			array( 'text' => __( 'No', 'themify-event-post' ), 'value' => 'no' ),
			array( 'text' => __( 'Yes', 'themify-event-post' ), 'value' => 'yes' ),
		),
		'label' => __( 'Hide Event Location', 'themify-event-post' ),
	),
	array(
		'name' => 'hide_page_nav',
		'type' => 'listbox',
		'values' => array(
			array( 'text' => __( 'No', 'themify-event-post' ), 'value' => 'no' ),
			array( 'text' => __( 'Yes', 'themify-event-post' ), 'value' => 'yes' ),
		),
		'label' => __( 'Hide Pagination Links', 'themify-event-post' ),
	),
);