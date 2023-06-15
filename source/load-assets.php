<?php 

function sfb_admin_scripts() {

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-sortable');

    wp_enqueue_script('sfb-js', plugin_dir_url(__DIR__).'assets/js/sfb.js',['jquery'],SFB_ASSETS_VERSION,true);
    wp_enqueue_style('sfb-bootstrap', plugin_dir_url(__DIR__).'assets/css/bootstrap-custom.css',[],'5.2.3','all'); 
    wp_enqueue_style('sfb', plugin_dir_url(__DIR__).'assets/css/sfb.css',[],SFB_ASSETS_VERSION,'all'); 
}  

function sfb_enque_scripts()
{
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_script('jquery');
    wp_enqueue_script('sfb', plugin_dir_url(__DIR__).'assets/js/sfb-front.js',['jquery'],SFB_ASSETS_VERSION,true);
    wp_enqueue_style('sfb-bootstrap', plugin_dir_url(__DIR__).'assets/css/bootstrap-custom.css',[],'5.2.3','all'); 
    wp_enqueue_style('sfb', plugin_dir_url(__DIR__).'assets/css/sfb.css',[],SFB_ASSETS_VERSION,'all'); 
}

add_action('admin_enqueue_scripts', 'sfb_admin_scripts'); 
add_action('wp_enqueue_scripts', 'sfb_enque_scripts' );