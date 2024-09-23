<?php
/**
 * Template to display the_content for event posts
 *
 * To override this file copy it to <your_theme>/themify-event-post/archive.php
 *
 */
?>

<?php if ( is_category() || is_tag() || is_tax() ) : ?>
	<h1 class="page-title"><?php single_term_title( '' ) ?></h1>
<?php endif; ?>

<?php if ( $this->query->have_posts() ) : ?>
	<div class="themify_event_post_loop <?php echo esc_attr( Themify_Event_Post::get_instance()->get_option( 'layout', 'grid2' ) ); ?>">

		<?php while ( $this->query->have_posts() ) : $this->query->the_post(); ?>

				<?php
				echo $this->get_template( 'content', array(
					'display' => 'excerpt', // excerpt, none
					'more_link' => false, // true goes to post type archive, and admits custom link
					'more_text' => __( 'More &rarr;', 'themify-event-post' ),
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
					'image_w' => '',
					'image_h' => '',
				) );
				?>

		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>

	</div><!-- .themify_event_post_archive -->
<?php endif; ?>
