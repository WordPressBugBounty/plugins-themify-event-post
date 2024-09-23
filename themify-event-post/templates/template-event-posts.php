<?php
/**
 * Template to display Event Post widget
 *
 * To override this file copy it to <your_theme>/themify-event-post/themify-event-posts.php
 *
 * @var $mod_settings
 * @var $mod_name
 * @var $module_ID
 *
 * @package Themify Event Post
 */

$fields_args = wp_parse_args( $args['mod_settings'], array(
	'mod_title' => '',
	'show' => 'mix',
	'layout' => 'grid3',
	'category' => '0',
	'limit' => 3,
	'offset' => '',
	'order' => 'desc',
	'orderby' => 'date',
	'display' => 'excerpt',
	'image' => 'yes',
	'image_size' => '',
	'img_width' => '',
	'img_height' => '',
	'unlink_image' => 'no',
	'title' => 'yes',
	'title_tag' => 'h2',
	'unlink_title' => 'no',
	'hide_event_date' => 'no',
	'hide_event_organizer' => 'no',
	'hide_event_performer' => 'no',
	'hide_event_meta' => 'no',
	'hide_event_location' => 'no',
	'hide_page_nav' => 'no',
	'animation_effect' => '',
	'more_link' => '',
	'more_text' => __( 'More &rarr;', 'themify-event-post' ),
	'css' => ''
) );
 unset($args['mod_settings']);
$animation_effect = self::parse_animation_effect( $fields_args['animation_effect'] );
$container_class = apply_filters( 'themify_builder_module_classes', array(
		'module', 'module-' . $args['mod_name'], $args['module_ID'], $animation_effect, $fields_args['css']
	), $args['mod_name'], $args['module_ID'], $fields_args);
if(!empty($args['element_id'])){
	$container_class[] = 'tb_'.$args['element_id'];
    }
$container_props = apply_filters( 'themify_builder_module_container_props', array(
	'id' => $args['module_ID'],
	'class' => implode(' ', $container_class)
), $fields_args, $args['mod_name'], $args['module_ID'] );

$fields_args['more_link'] = $fields_args['more_link'] === 'y';
?>
<div <?php echo self::get_element_attributes( $container_props ); ?>>
	<?php 
	
	if(method_exists('Themify_Builder_Component_Base','add_inline_edit_fields')){
	    echo Themify_Builder_Component_Module::get_module_title($fields_args,'mod_title');
	}
	do_action( 'themify_builder_before_template_content_render' );

	echo Themify_Event_Post::get_instance()->shortcode( $fields_args );

	do_action( 'themify_builder_after_template_content_render' ); 
	?>
</div>
