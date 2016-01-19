<?php
/******************************************************************

Plugin Name: 	TCU Image Button Widget
Description: 	This plugin gives you the ability to add a special button styling. You will be able to add an image, text for the link, and the URL.
Version: 		1.1
Author: 		Website & Social Media Management
Author URI: 	http://mkc.tcu.edu/web-management.asp


 ******************************************************************/

if ( !defined( 'WPINC' ) ) {
    die;
} // don't remove bracket!

/************* INCLUDE NEEDED FILES ***************/

require_once('classes/tcu_image_button_class.php'); // Don't remove this!

/******************** Enqueue front end CSS *************/

add_action( 'after_setup_theme', 'image_button_init', 16 );

function image_button_init() {
	// enqueue base scripts and styles
	add_action( 'wp_enqueue_scripts', 'tcu_image_button_styles', 999 );

} /* end image_button_init */

// Add our own CSS
function tcu_image_button_styles() {

	// register main stylesheet
	wp_register_style( 'image-button-styles', plugins_url( '/css/image-button-styles.css' , __FILE__), array(), '', 'all' );
	// enqueue styles and scripts
	wp_enqueue_style( 'image-button-styles' );

} // don't remove this bracket!

// INITIATE PLUGIN
function tcu_image_button_initiate() {

    flush_rewrite_rules();

} // don't remove this bracket!

register_activation_hook( __FILE__, 'tcu_image_button_initiate' );

// DEACTIVATE PLUGIN
function tcu_image_button_deactivate() {

    flush_rewrite_rules();

} // don't remove this bracket!

register_deactivation_hook( __FILE__, 'tcu_image_button_deactivate' );

?>