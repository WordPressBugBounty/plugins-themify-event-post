<?php
/**
 * Template to display the_content for event posts
 *
 * To override this file copy it to <your_theme>/themify-event-post/single.php
 *
 * @var $content
 */
$post_id = get_the_id();
?>
<div class="themify_event_post">

	<?php
	echo themify_event_post_get_image();
	$image = get_the_post_thumbnail_url($post_id);
	?>

	<?php if ( $map_address = get_post_meta( $post_id, 'map_address', true ) ) : ?>
		<div class="tep_event_map">
			<?php echo themify_event_post_map( array(
				'address' => $map_address,
			) );
			?>
		</div><!-- / .tep_event_map -->
	<?php endif; ?>

	<div class="tep_event_info">

        <?php themify_event_type(); ?>

        <?php
        $title = themify_event_post_title( array(
			'post' => $this->post,
            'echo' => false
		) );
        echo $title;
        ?>

		<?php themify_event_post_date(); ?>

        <?php themify_event_organizer(); ?>

		<?php themify_event_performer(); ?>

		<?php if ( $location = get_post_meta( $post_id, 'location', true ) ) : ?>
				<div>
					<span class="tep_location">
						<?php echo $location; ?>
					</span>
					<?php if ( $map_address = get_post_meta( $post_id, 'map_address', true ) ) : ?>
						<span class="tep_address">
							<?php echo $map_address; ?>
						</span>
					<?php endif; ?>
				</div>
		<?php endif; ?>

        <?php themify_event_buy_ticket(); ?>

	</div><!-- / .tep_event_info -->

	<div class="tep_content">
		
		<?php echo $content; ?>

	</div><!-- /.tep_content -->

    <?php 
        $metadata = get_post_meta($post_id);
        echo themify_event_post_json_ld_generator(array(
            'name'=>get_the_title( $this->post ),
        'start_date'=>isset($metadata['start_date'])?$metadata['start_date'][0]:'',
        'end_date'=>isset($metadata['end_date'])?$metadata['end_date'][0]:'',
        'place'=>$location,
        'address'=>$map_address,
        'image'=>$image,
        'decription'=>trim(strip_tags($content)),

        'event_attendance'=>isset($metadata['event_attendance'])?$metadata['event_attendance'][0]:'',
        'buy_ticket'=> get_post_meta( $post_id, 'buy_tickets', true ),
        'ticket_price'=>get_post_meta( $post_id, 'buy_tickets_price', true ),
        'event_attendance'=>isset($metadata['event_attendance'])?$metadata['event_attendance'][0]:'',
        'ticket_currency'=>isset($metadata['buy_tickets_currency'])?$metadata['buy_tickets_currency'][0]:'',
        'ticket_purchase_start'=>isset($metadata['ticket_purchase_start_date'])?$metadata['ticket_purchase_start_date'][0]:'',
        'ticket_purchase_end'=>isset($metadata['ticket_purchase_end'])?$metadata['ticket_purchase_end'][0]:'',
        'ticket_availability'=>isset($metadata['ticket_availability'])? str_replace(array(' ', '-'), '', ucwords($metadata['ticket_availability'][0])) :'',
        'event_status'=>isset($metadata['event_status'])?$metadata['event_status'][0]:'',
        'organizer'=>isset($metadata['organizer'])?$metadata['organizer'][0]:'',
        'organizer_name'=>isset($metadata['organizer_name'])?$metadata['organizer_name'][0]:'',
        'organizer_url'=>isset($metadata['organizer_url'])?$metadata['organizer_url'][0]:'',
        'performer'=>isset($metadata['event_performer'])?$metadata['event_performer'][0]:'',
        'performer_name'=>isset($metadata['performer_name'])?$metadata['performer_name'][0]:'',
    )); ?>
</div><!-- / .themify_event_post -->
