<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Module Name: Event Posts
 * Description: Display Event Posts
 */

class TB_Event_Posts_Module extends Themify_Builder_Component_Module {

    public static function get_json_file():array{
        $instance = Themify_Event_Post::get_instance();
        return [ 'f' => $instance->url . 'json/style.json', 'v' => $instance->version ];
    }

    public static function get_module_name() : string {
        add_filter( 'themify_builder_active_vars', [ __CLASS__, 'builder_active_enqueue' ] );
        return __('Event Posts', 'themify-event-post');
    }

    public static function get_module_icon() : string {
        return 'calendar';
    }

    public static function builder_active_enqueue(array $vars ):array {
        $instance = Themify_Event_Post::get_instance();
        themify_enque_script( 'tb_builder_event_post', $instance->url . 'assets/active.js', $instance->version, [ 'themify-builder-app-js' ] );

        $i18n = include( trailingslashit( dirname( __DIR__ ) ) . 'includes/i18n.php' );
        $vars['i18n']['label'] = array_merge( $i18n, $vars['i18n']['label'] );

        return $vars;
    }

    public function __construct() {
        if(method_exists('Themify_Builder_Model', 'add_module')){
            parent::__construct('event-posts');
        }
        else{//backward
             parent::__construct(array(
                'name' => $this->get_name(),
                'slug' => 'event-posts'
            ));
        }
    }

    public function get_name(){//backward
        return self::get_module_name();
    }

    public function get_icon(){//backward
        return self::get_module_icon();
    }

    public function get_visual_type() {
        return 'ajax';
    }

    /**
     * Render plain content for static content.
     * 
     * @param array $module 
     * @return string
     */
    public function get_plain_content($module) {
        return ''; // no static content for dynamic content
    }

}

if ( ! method_exists( 'Themify_Builder_Component_Module', 'get_module_class' ) ) {
    if ( method_exists( 'Themify_Builder_Model', 'add_module' ) ) {
        new TB_Event_Posts_Module();
    } else {
        Themify_Builder_Model::register_module('TB_Event_Posts_Module');
    }
}