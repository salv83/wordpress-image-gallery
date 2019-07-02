<?php
/**
 * Plugin Name: Wordpress Photo Gallery
 * Description: This plugin retrieve pictures info from a remote endpoint and display them in a gallery using the shortcode: [wordpress-image-gallery]
 * Author: Salvatore Marino
 * Version: 1.0.0
 */


/* 
 * The plugin is divided in two files backend.php where we will manage the shortcode and
 * wpg-option-page.php where will will manage the option page with all the settings of the gallery
 */
include_once( dirname( __FILE__ ) . '/include/backend.php' );
include_once( dirname( __FILE__ ) . '/include/wpg-option-page.php' );


/*
 * Here we enqueue the css file that will style our gallery
 */
function wordpress_image_gallery_enqueue_scripts() {
    wp_enqueue_style( 'wordpress-image-gallery', plugins_url('/include/style.css', __FILE__));
    
}

add_action( 'wp_enqueue_scripts', 'wordpress_image_gallery_enqueue_scripts' );