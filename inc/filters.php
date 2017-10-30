<?php
/**
 * Filters for MedicPress WP theme
 *
 * @package medicpress-lite
 */

/**
 * MedicPressLiteFilters class with filter hooks
 */
class MedicPressLiteFilters {

	/**
	 * Runs on class initialization. Adds filters and actions.
	 */
	function __construct() {
		// Custom text after excerpt.
		add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );

		// Footer widgets with dynamic layouts.
		add_filter( 'dynamic_sidebar_params', array( $this, 'footer_widgets_params' ), 9, 1 );

		// Google fonts.
		add_filter( 'medicpress_pre_google_web_fonts', array( $this, 'additional_fonts' ) );
		add_filter( 'medicpress_subsets_google_web_fonts', array( $this, 'subsets_google_web_fonts' ) );

		// Embeds.
		add_filter( 'embed_oembed_html', array( $this, 'embed_oembed_html' ), 10, 1 );

		// <body> and post class
		add_filter( 'body_class', array( $this, 'body_class' ), 10, 1 );
		add_filter( 'post_class', array( $this, 'post_class' ), 10, 1 );

		// Special dropdown menu.
		add_filter( 'wp_nav_menu_objects', array( $this, 'add_images_to_special_submenu' ) );

		// Custom logo.
		add_filter( 'get_custom_logo', array( $this, 'filter_custom_logo_html' ) );
	}


	/**
	 * Custom text after excerpt.
	 *
	 * @param array $more default more value.
	 * @return array
	 */
	function excerpt_more( $more ) {
		return ' &hellip;';
	}


	/**
	 * Filter the dynamic sidebars and alter the BS col classes for the footer widgets.
	 *
	 * @param  array $params parameters of the sidebar.
	 * @return array
	 */
	function footer_widgets_params( $params ) {
		static $counter              = 0;
		static $first_row            = true;
		$footer_widgets_layout_array = MedicPressLiteHelpers::footer_widgets_layout_array();

		if ( 'footer-widgets' === $params[0]['id'] ) {
			// 'before_widget' contains __col-num__, see inc/theme-sidebars.php.
			$params[0]['before_widget'] = str_replace( '__col-num__', $footer_widgets_layout_array[ $counter ], $params[0]['before_widget'] );

			// First widget in the any non-first row.
			if ( false === $first_row && 0 === $counter ) {
				$params[0]['before_widget'] = '</div><div class="row">' . $params[0]['before_widget'];
			}

			$counter++;
		}

		end( $footer_widgets_layout_array );
		if ( $counter > key( $footer_widgets_layout_array ) ) {
			$counter   = 0;
			$first_row = false;
		}

		return $params;
	}


	/**
	 * Return Google fonts and sizes.
	 *
	 * @see https://github.com/grappler/wp-standard-handles/blob/master/functions.php
	 * @param array $fonts google fonts used in the theme.
	 * @return array Google fonts and sizes.
	 */
	function additional_fonts( $fonts ) {

		/* translators: If there are characters in your language that are not supported by Open Sans or Roboto Slab, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== esc_html_x( 'on', 'Open Sans and Roboto Slab: on or off', 'medicpress-lite' ) ) {
			$fonts['Open Sans'] = array(
				'400' => '400',
				'700' => '700',
			);
			$fonts['Roboto Slab'] = array(
				'700' => '700',
			);
		}

		return $fonts;
	}


	/**
	 * Add subsets from customizer, if needed.
	 *
	 * @param array $subsets google font subsets used in the theme.
	 * @return array
	 */
	function subsets_google_web_fonts( $subsets ) {
		$additional_subset = get_theme_mod( 'charset_setting', 'latin' );

		array_push( $subsets, $additional_subset );

		return $subsets;
	}


	/**
	 * Embedded videos and video container around them.
	 *
	 * @param string $html html to be enclosed with responsive HTML.
	 * @return string
	 */
	function embed_oembed_html( $html ) {
		if (
			false !== strstr( $html, 'youtube.com' ) ||
			false !== strstr( $html, 'wordpress.tv' ) ||
			false !== strstr( $html, 'wordpress.com' ) ||
			false !== strstr( $html, 'vimeo.com' )
		) {
			$out = '<div class="embed-responsive  embed-responsive-16by9">' . $html . '</div>';
		} else {
			$out = $html;
		}
		return $out;
	}


	/**
	 * Append the right body classes to the <body>.
	 *
	 * @param  array $classes The default array of classes.
	 * @return array
	 */
	public static function body_class( $classes ) {
		$classes[] = 'medicpress-lite';

		if ( 'boxed' === get_theme_mod( 'layout_mode', 'wide' ) ) {
			$classes[] = 'boxed';
		}

		return $classes;
	}


	/**
	 * Append the right post classes.
	 *
	 * @param  array $classes The default array of classes.
	 * @return array
	 */
	public static function post_class( $classes ) {
		$classes[] = 'clearfix';
		$classes[] = 'article';

		// Remove the hentry class.
		$classes = array_diff( $classes , array( 'hentry' ) );

		return $classes;
	}


	/**
	 * Add the images to the special submenu -> the submenu items with the parent with 'pt-special-dropdown' class.
	 *
	 * @param array $items List of menu objects (WP_Post).
	 * @param array $args  Array of menu settings.
	 * @return array
	 */
	public function add_images_to_special_submenu( $items ) {
		$special_menu_parent_ids = array();

		foreach ( $items as $item ) {
			if ( in_array( 'pt-special-dropdown', $item->classes, true ) && isset( $item->ID ) ) {
				$special_menu_parent_ids[] = $item->ID;
			}

			if ( in_array( $item->menu_item_parent, $special_menu_parent_ids ) && has_post_thumbnail( $item->object_id ) ) {
				$item->title = sprintf(
					'%1$s %2$s',
					get_the_post_thumbnail( $item->object_id, 'thumbnail', array( 'alt' => esc_attr( $item->title ) ) ),
					$item->title
				);
			}
		}

		return $items;
	}


	/**
	 * Filter the custom logo HTML output code.
	 *
	 * @param  string $html The default HTML code.
	 * @return string       Changed HTML code.
	 */
	public function filter_custom_logo_html( $html ) {
		$html = str_replace( 'class="custom-logo-link"' , 'class="header__logo  custom-logo-link"', $html );
		$html = str_replace( 'class="custom-logo"' , 'class="img-fluid  custom-logo"', $html );

		return $html;
	}
}

// Single instance.
$medicpress_lite_filters = new MedicPressLiteFilters();
