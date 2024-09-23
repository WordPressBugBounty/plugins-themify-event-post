<?php
/**
 * Template to display Event Categories widget
 *
 * To override this file copy it to <your_theme>/themify-event-post/widget-categories.php
 *
 * @var $args
 * @var $instance
 * @var $widget
 *
 * @package Themify Event Post
 */
$themify_this_widget_id_pre = isset( $args['widget_id'] ) ? $args['widget_id'] : '';
$themify_widget_id = $themify_this_widget_id_pre . '-cats';
/* User-selected settings. */
$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $widget->id_base );
$parent = isset( $instance['parent'] ) ? $instance['parent'] : null;
$depth = isset( $instance['depth'] ) ? $instance['depth'] : null;
$orderby = isset( $instance['orderby'] ) ? $instance['orderby'] : null;
$exclude = isset( $instance['exclude'] ) ? $instance['exclude'] : null;
$show_dropdown = isset( $instance['show_dropdown'] ) ? $instance['show_dropdown'] : false;
$show_counts = isset( $instance['show_counts'] ) ? $instance['show_counts'] : false;
$show_hierarchy = isset( $instance['show_hierarchy'] ) ? $instance['show_hierarchy'] : false;

/* Before widget (defined by themes). */
echo $args['before_widget'];

/* Title of widget (before and after defined by themes). */
if ( $title ) {
	echo $args['before_title'] , $title , $args['after_title'];
}

$_args = array(
	'taxonomy'      => 'event-category',
	'orderby'       => $orderby,
	'show_count'    => $show_counts,
	'child_of'      => $parent,
	'exclude'       => $exclude,
	'hierarchical'  => $show_hierarchy,
	'depth'         => $depth,
	'title_li'      => '',
	'id'			=> $themify_widget_id,
);

if ( $show_dropdown ) {
	$_args['show_option_none'] = __( 'Select Category', 'themify-event-post' );
	wp_dropdown_categories( $_args );
?>

	<script type='text/javascript'>
	/* <![CDATA[ */
		function onCatChange() {
			var dropdown = document.getElementById('<?php echo esc_js( $themify_widget_id ); ?>'),
				catSelected = dropdown.options[dropdown.selectedIndex].value;
			if ( catSelected > 0 ) {
				location.href = "<?php echo home_url(); ?>/?cat="+catSelected;
			}
		}
		document.getElementById('<?php echo esc_js( $themify_widget_id ); ?>').onchange = onCatChange;
	/* ]]> */
	</script>

<?php
} else {
	echo '<ul>';

	wp_list_categories( $_args );

	echo '</ul>';
}

/* After widget (defined by themes). */
echo $args['after_widget'];
