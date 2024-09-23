<?php

return array(
	// Start Date
	array(
		'name' => 'start_date',
		'title' => __('Event Starts On', 'themify-event-post'),
		'description' => __('Enter event start date and time.', 'themify-event-post'),
		'type' => 'date',
		'meta' => array(
			'required' => true,
			'default' => '',
			'pick' => __('Pick Date', 'themify-event-post'),
			'close' => __('Done', 'themify-event-post'),
			'clear' => __('Clear Date', 'themify-event-post'),
			'date_format' => '',
			'time_format' => 'HH:mm',
			'timeseparator' => ' '
		),
		'force_save' => true,
	),
    // To be announced
    array(
        'name' 		=> 'to_be_announced',
        'title' 	=> '',
        'after' => __('To be announced', 'themify-event-post'),
        'type' 		=> 'checkbox',
        'meta'		=> array(),
    ),
	array(
		'name' => 'end_date',
		'title' => __('Event Ends On', 'themify-event-post'),
		'description' => __('Enter event end date and time.', 'themify-event-post'),
		'type' => 'date',
		'meta' => array(
			'required' => true,
			'default' => '',
			'pick' => __('Pick Date', 'themify-event-post'),
			'close' => __('Done', 'themify-event-post'),
			'clear' => __('Clear Date', 'themify-event-post'),
			'date_format' => '',
			'time_format' => 'HH:mm',
			'timeseparator' => ' '
		),
		'force_save' => true,
	),
	// Repeat date
	array(
		'title' => __('Repeat', 'themify-event-post'),
		'description' => '',
		'type' => 'multi',
		'meta' => array(
			'fields' => array(
				array(
					'name' => 'repeat',
					'label' => '',
					'type' => 'dropdown',
					'meta' =>array( 
						array(
							'value' => '',
							'selected' => true,
							'name' => __('None', 'themify-event-post')
						),
						array(
							'value' => 'day',
							'name' => __('Daily', 'themify-event-post')
						),
						array(
							'value' => 'week',
							'name' => __('Weekly', 'themify-event-post')
						),
						array(
							'value' => 'year',
							'name' => __('Yearly', 'themify-event-post')
						)
					),
					'default' => ''
				),
				array(
					'name' => 'repeat_x',
					'label' => '',
					'description' => '',
					'type' => 'textbox',
					'meta' => array('size' => 'small'),
					'before' => sprintf('<span style="margin:0 5px 0 15px;">%s</span>',__('Every', 'themify-event-post')),
					'after' => sprintf('<span style="margin-left:5px;">%s</span>',__('week', 'themify-event-post')),
				),
			),
			'description' => '',
			'before' => '',
			'after' => '',
			'separator' => ''
		)
	),
	// Hide end event date in the loop
	array(
		'name' 		=> 'event_end_date_hide',
		'title' 	=> __('Hide End Date', 'themify-event-post'),
		'after' => __('Hide event end date on single view.', 'themify-event-post'),
		'type' 		=> 'checkbox',
		'meta'		=> array(),
	),
	// Event Status
	array(
		'name' 		=> 'event_status',
		'title' 	=> __('Event Status', 'themify-event-post'),
		'description'	=> '',
		'type'		=> 'dropdown',
		'meta'		=> array(
			array( 'name' => __('Scheduled', 'themify-event-post'), 'value' => 'Scheduled', 'selected' => true ),
			array( 'name' => __('Rescheduled', 'themify-event-post'), 'value' => 'Rescheduled'),
			array( 'name' => __('Cancelled', 'themify-event-post'), 'value' => 'Cancelled')
		),
		'default' => ''
	),
	// Location
	array(
		'name' => 'location',
		'title' => __('Location', 'themify-event-post'),
		'description' => __('Enter city or venue name.', 'themify-event-post'),
		'type' => 'textbox',
		'meta' => array(),
	),
	// Map Address
	array(
		'name' => 'map_address',
		'title' => __('Map Address', 'themify-event-post'),
		'description' => __('Enter full address for Google Map.', 'themify-event-post'),
		'type' => 'textarea',
		'meta' => array(),
	),
	// Organizer
	array(
		'name' 		=> 'organizer',
		'title'		=> __('Organizer is', 'themify-event-post'),
		'description'	=> '',
		'type'		=> 'dropdown',
		'meta'		=> array(
			array( 'name' => __('Person', 'themify-event-post'), 'value' => 'Person', 'selected' => true),
			array( 'name' => __('Organization', 'themify-event-post'), 'value' => 'Organization')
		),
		'default' => ''
	),
	array(
		'name' => 'organizer_name',
		'title' => __('Organizer Name', 'themify-event-post'),
		'description' => __('Organizer name.', 'themify-event-post'),
		'type' => 'textbox',
		'meta' => array(),
	),
	array(
		'name' => 'organizer_url',
		'title' => __('Organizer Link', 'themify-event-post'),
		'description' => __('Organizer link.', 'themify-event-post'),
		'type' => 'textbox',
		'meta' => array(),
	),
	// Performer
	array(
		'name' 		=> 'event_performer',
		'title'		=> __('Performer is', 'themify-event-post'),
		'description'	=> '',
		'type'		=> 'dropdown',
		'meta'		=> array(
			array( 'name' => '', 'value' => '', 'selected' => true ),
			array( 'name' => __('Person', 'themify-event-post'), 'value' => 'Person' ),
			array( 'name' => __('Organization', 'themify-event-post'), 'value' => 'Organization')
		),
		'default' => ''
	),
	array(
		'name' => 'performer_name',
		'title' => __('Performer Name', 'themify-event-post'),
		'description' => __('Performer name.', 'themify-event-post'),
		'type' => 'textbox',
		'meta' => array(),
	),
	// Event Attendance.
	array(
		'name' 		=> 'event_attendance',
		'title'		=> __('Event Attendance Type', 'themify-event-post'),
		'description'	=> '',
		'type'		=> 'dropdown',
		'meta'		=> array(
			array( 'name' => '', 'value' => '', 'selected' => true ),
			array( 'name' => __('Online', 'themify-event-post'), 'value' => 'Online'),
			array( 'name' => __('Offline', 'themify-event-post'), 'value' => 'Offline'),
			array( 'name' => __('Mixed', 'themify-event-post'), 'value' => 'Mixed' )
		),
		'default' => '',
	),
	// Buy Tickets
	array(
		'title' => __('Buy Ticket', 'themify-event-post'),
		'description' => '',
		'type' => 'multi',
		'meta' => array(
			'fields' => array(
				array(
					'name' => 'buy_tickets_label',
					'label' => '',
					'before' => sprintf( '<span style="margin:0 5px 0 15px;">%s</span>', __('Text:', 'themify-event-post') ),
					'type' => 'textbox',
					'meta' => array('size' => 'medium'),
				),
				array(
					'before' => sprintf( '<span style="margin:0 5px 0 15px;">%s</span>', __('Link:', 'themify-event-post') ),
					'name' => 'buy_tickets',
					'title' => __('Buy Ticket Link', 'themify-event-post'),
					'type' => 'textbox',
					'meta' => array( 'size' => 'medium' ),
					'class'	=> 'themify-event-ticket-link'
				),
			),
		)
	),
	// Ticket Price
	array(
		'title' => __('Ticket Price', 'themify-event-post'),
		'description' => '',
		'type' => 'multi',
		'meta' => array(
			'fields' => array(
				array(
					'name' => 'buy_tickets_price',
					'label' => '',
					'type' => 'textbox',
					'default' => '',
					'meta' => array('size' => 'small'),
					'before' => sprintf( '<span style="margin:0 5px 0 15px;">%s</span>', __('Amount:', 'themify-event-post') ),
				),
				array(
					'name' => 'buy_tickets_currency',
					'type' => 'textbox',
					'meta' => array( 'size' => 'small' ),
					'before' => sprintf( '<span style="margin:0 5px 0 15px;">%s</span>', __('Currency Code:', 'themify-event-post') ),
					'after' => sprintf( '<small style="margin:0 5px 0 15px;">%s</small>', __('For Example: USD', 'themify-event-post') ),
				),
			),
		),
		'class'	=> 'themify-event-ticket'
	),
	// Ticket purchase start and end date
	array(
		'name' => 'ticket_purchase_start_date',
		'title' => __('Ticket Purchase Starts On', 'themify-event-post'),
		'description' => __('Enter ticket purchase start date and time.', 'themify-event-post'),
		'type' => 'date',
		'meta' => array(
			'default' => '',
			'pick' => __('Pick Date', 'themify-event-post'),
			'close' => __('Done', 'themify-event-post'),
			'clear' => __('Clear Date', 'themify-event-post'),
			'date_format' => '',
			'time_format' => 'HH:mm',
			'timeseparator' => ' '
		),
		'class'	=> 'themify-event-ticket'
	),
	array(
		'name' => 'ticket_purchase_end_date',
		'title' => __('Ticket Purchase Ends On', 'themify-event-post'),
		'description' => __('Enter ticket purchase end date and time.', 'themify-event-post'),
		'type' => 'date',
		'meta' => array(
			'default' => '',
			'pick' => __('Pick Date', 'themify-event-post'),
			'close' => __('Done', 'themify-event-post'),
			'clear' => __('Clear Date', 'themify-event-post'),
			'date_format' => '',
			'time_format' => 'HH:mm',
			'timeseparator' => ' '
		),
		'class'	=> 'themify-event-ticket'
	),
	// Ticket Availability.
	array(
		'name' 		=> 'ticket_availability',
		'title'		=> __('Ticket Availability', 'themify-event-post'),
		'description'	=> '',
		'type'		=> 'dropdown',
		'meta'		=> array(
			array( 'name' => __('In stock', 'themify-event-post'), 'value' => 'In stock', 'selected' => true ),
			array( 'name' => __('Out of stock', 'themify-event-post'), 'value' => 'Out of stock'),
			array( 'name' => __('Pre-order', 'themify-event-post'), 'value' => 'Pre-order' )
		),
		'default' => '',
		'class'	=> 'themify-event-ticket'
	)
);