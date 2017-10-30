<?php
/**
 * Load the Customizer with some custom extended addons
 *
 * @package medicpress-lite
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

/**
 * This funtion is only called when the user is actually on the customizer page
 *
 * @param  WP_Customize_Manager $wp_customize
 */
if ( ! function_exists( 'medicpress_lite_customizer' ) ) {
	function medicpress_lite_customizer( $wp_customize ) {
		// Add required files.
		MedicPressLiteHelpers::load_file( '/inc/customizer/class-customize-base.php' );

		new MedicPress_Lite_Customizer_Base( $wp_customize );
	}
	add_action( 'customize_register', 'medicpress_lite_customizer' );
}


/**
 * Takes care for the frontend output from the customizer and nothing else
 */
if ( ! function_exists( 'medicpress_lite_customizer_frontend' ) && ! class_exists( 'MedicPress_Lite_Customize_Frontent' ) ) {
	function medicpress_lite_customizer_frontend() {
		MedicPressLiteHelpers::load_file( '/inc/customizer/class-customize-frontend.php' );
		$MedicPress_Lite_Customize_Frontent = new MedicPress_Lite_Customize_Frontent();
	}
	add_action( 'init', 'medicpress_lite_customizer_frontend' );
}
