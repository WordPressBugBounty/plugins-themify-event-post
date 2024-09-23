<?php
/**
 * Template to display an Event post in archive lists
 *
 * To override this file copy it to <your_theme>/themify-event-post/content.php
 *
 * @var $image
 * @var $unlink_image
 * @var $image_w
 * @var $image_h
 * @var $hide_event_date
 * @var $hide_event_organizer
 * @var $hide_event_performer
 * @var $hide_event_location
 * @var $hide_event_meta
 * @var $title
 * @var $unlink_title
 * @var $display
 * @var $more_link
 * @var $more_text
 *
 * @package Themify Event Post
 */
$post_id = get_the_ID();
$thumb = '';
$map_address = '';
?>
<article id="post-<?php the_id(); ?>" <?php post_class( 'themify_event_post post tf_clearfix event-post' ); ?>>

	<?php if ( $image === 'yes' && has_post_thumbnail() ) : ?>
		<?php
		echo themify_event_post_get_image( array(
			'width' => $image_w,
			'height' => $image_h,
			'unlink' => $unlink_image === 'yes',
		) );
		$thumb = get_the_post_thumbnail_url($post_id);
		?>
	<?php endif; ?>

	<div class="tep_post_content">

        <?php themify_event_type(); ?>
        <?php if ( $hide_event_meta === 'no' ) : ?>
			<div class="tep_meta">
				<?php if ( has_term( '', 'event-category' ) ) : ?>
					<?php the_terms( $post_id, 'event-category', ' <span class="tep_post_category">', '<span class="tep_term_separator">,</span> ', '<i class="divider-small"></i></span>' ); ?>
				<?php endif; ?>
				<?php the_terms( $post_id, 'event-tag', ' <span class="tep_tags">', '<span class="tep_term_separator">,</span> ', '</span>' ); ?>
			</div>
		<?php endif; //post meta ?>

		<?php if ( $title === 'yes' ) : ?>
			<?php themify_event_post_title( array(
				'unlink' => $unlink_title !== 'no',
                'tag' => $title_tag
			) ); ?>
		<?php endif; //post title ?>

		<div class="tep_event_info">

			<?php if ( $hide_event_date === 'no' ) : ?>
				<?php themify_event_post_date() ?>
			<?php endif; ?>

            <?php if ( $hide_event_organizer === 'no' ) : ?>
                <?php themify_event_organizer(); ?>
            <?php endif; ?>

            <?php if ( $hide_event_performer === 'no' ) : ?>
                <?php themify_event_performer(); ?>
            <?php endif; ?>

			<?php if ( ( $location = get_post_meta( $post_id, 'location', true ) ) && $hide_event_location === 'no' ) : ?>
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

		</div><!-- / .event-info-wrap -->

		<div class="tep_content">
			<?php if ( 'excerpt' == $display ) : ?>

				<?php the_excerpt(); ?>

				<?php if ( $more_link ) : ?>
					<p>
						<a href="<?php the_permalink(); ?>" class="more-link"><?php echo $more_text; ?></a>
					</p>
				<?php endif; ?>

			<?php elseif( $display != 'none' ) : ?>
				<?php the_content(); ?>
			<?php endif; //display content ?>

		</div><!-- /.tep_content -->
		<?php 
			$metadata = get_post_meta($post_id);
			echo themify_event_post_json_ld_generator(array(
				'name'=>get_the_title( $this->post ),
				'start_date'=>isset($metadata['start_date'])?$metadata['start_date'][0]:'',
				'end_date'=>isset($metadata['end_date'])?$metadata['end_date'][0]:'',
				'place'=>$location,
				'address'=>$map_address,
				'image'=>$thumb,
				'decription'=>trim(strip_tags( get_the_content( $more_text ) )),
				'buy_ticket'=> get_post_meta( $post_id, 'buy_tickets', true ),
				'event_attendance'=>isset($metadata['event_attendance'])?$metadata['event_attendance'][0]:'',
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
	</div><!-- .tep_post_content -->

    <?php themify_event_post_edit_link(); ?>

</article>
