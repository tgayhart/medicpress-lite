<?php
/**
 * MedicPress Lite functions and definitions
 *
 * @author ProteusThemes <info@proteusthemes.com>
 * @package medicpress-lite
 */

// Display informative message if PHP version is less than 5.5.
if ( version_compare( phpversion(), '5.5', '<' ) ) {
	die( sprintf( esc_html_x( 'This theme requires %2$sPHP 5.5+%3$s to run. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.5%4$s Your current version of PHP: %2$s%1$s%3$s', '%1$s - version ie. 5.5.0. %2$s, %3$s and %4$s  - html tags, must be included around the same words as original', 'medicpress-lite' ), esc_html( phpversion() ), '<strong>', '</strong>', '<br>' ) );
}

// Composer autoloader.
require_once trailingslashit( get_template_directory() ) . 'vendor/autoload.php';


/**
 * Define the version variable to assign it to all the assets (css and js)
 */
define( 'MEDICPRESSLITE_WP_VERSION', wp_get_theme()->get( 'Version' ) );


/**
 * Define the development constant
 */
if ( ! defined( 'MEDICPRESSLITE_DEVELOPMENT' ) ) {
	define( 'MEDICPRESSLITE_DEVELOPMENT', false );
}


/**
 * Helper functions used in the theme
 */
require_once get_template_directory() . '/inc/helpers.php';


/**
 * Theme support and thumbnail sizes
 */
if ( ! function_exists( 'medicpress_lite_theme_setup' ) ) {
	function medicpress_lite_theme_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'medicpress-lite' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Add support for Theme Logo -> https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 61,
			'width'       => 242,
			'flex-height' => true,
			'flex-width'  => true,
		) );

		// Menus.
		register_nav_menu( 'main-menu', esc_html__( 'Main Menu', 'medicpress-lite' ) );

		/**
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Add excerpt support for pages.
		add_post_type_support( 'page', 'excerpt' );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'medicpress_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
	}
	add_action( 'after_setup_theme', 'medicpress_lite_theme_setup' );
}


/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @see https://codex.wordpress.org/Content_Width
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1140; /* pixels */
}


/**
 * Enqueue CSS stylesheets
 */
if ( ! function_exists( 'medicpress_lite_enqueue_styles' ) ) {
	function medicpress_lite_enqueue_styles() {
		$stylesheet_uri = get_stylesheet_uri();

		if ( 'yes' === get_theme_mod( 'use_minified_css', 'no' ) ) {
			$stylesheet_uri = get_stylesheet_directory_uri() . '/style.min.css';
		}

		wp_enqueue_style( 'medicpress-main', $stylesheet_uri, array(), MEDICPRESSLITE_WP_VERSION );
	}
	add_action( 'wp_enqueue_scripts', 'medicpress_lite_enqueue_styles' );
}


/**
 * Enqueue Google Web Fonts.
 */
if ( ! function_exists( 'medicpress_lite_enqueue_google_web_fonts' ) ) {
	function medicpress_lite_enqueue_google_web_fonts() {
		wp_enqueue_style( 'medicpress-google-fonts', MedicPressLiteHelpers::google_web_fonts_url(), array(), null );
	}
	add_action( 'wp_enqueue_scripts', 'medicpress_lite_enqueue_google_web_fonts' );
}


/**
 * Enqueue JS scripts
 */
if ( ! function_exists( 'medicpress_lite_enqueue_scripts' ) ) {
	function medicpress_lite_enqueue_scripts() {
		// Modernizr for the frontend feature detection.
		wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/js/modernizr.custom.20170807.min.js', array(), null );

		// Requirejs.
		wp_register_script( 'requirejs', get_template_directory_uri() . '/bower_components/requirejs/require.js', array(), null, true );

		// Array for main.js dependencies.
		$main_deps = array( 'jquery', 'underscore' );

		// Main JS file, conditionally.
		if ( true === MEDICPRESSLITE_DEVELOPMENT ) {
			$main_deps[] = 'requirejs';
			wp_enqueue_script( 'medicpress-main', get_template_directory_uri() . '/assets/js/main.js', $main_deps, MEDICPRESSLITE_WP_VERSION, true );
		}
		else {
			wp_enqueue_script( 'medicpress-main', get_template_directory_uri() . '/assets/js/main.min.js', $main_deps, MEDICPRESSLITE_WP_VERSION, true );
		}

		// Pass data to the main script.
		wp_localize_script( 'medicpress-main', 'MedicPressVars', array(
			'pathToTheme' => get_template_directory_uri(),
		) );

		// For nested comments.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'medicpress_lite_enqueue_scripts' );
}


/**
 * Require the files in the inc folder.
 */
MedicPressLiteHelpers::load_file( '/inc/theme-sidebars.php' );
MedicPressLiteHelpers::load_file( '/inc/filters.php' );
MedicPressLiteHelpers::load_file( '/inc/theme-customizer.php' );


/**
 * WIA-ARIA nav walker and accompanying JS file.
 */
if ( ! function_exists( 'medicpress_lite_wai_aria_js' ) ) {
	function medicpress_lite_wai_aria_js() {
		wp_enqueue_script( 'medicpress-wp-wai-aria', get_template_directory_uri() . '/vendor/proteusthemes/wai-aria-walker-nav-menu/wai-aria.js', array( 'jquery' ), null, true );
	}
	add_action( 'wp_enqueue_scripts', 'medicpress_lite_wai_aria_js' );
}
