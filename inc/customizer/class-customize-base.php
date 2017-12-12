<?php
/**
 * Customizer
 *
 * @package medicpress-lite
 */

use ProteusThemes\CustomizerUtils\Setting;
use ProteusThemes\CustomizerUtils\Control;
use ProteusThemes\CustomizerUtils\CacheManager;
use ProteusThemes\CustomizerUtils\Helpers as WpUtilsHelpers;

/**
 * Contains methods for customizing the theme customization screen.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 */
class MedicPress_Lite_Customizer_Base {
	/**
	 * The singleton manager instance
	 *
	 * @see wp-includes/class-wp-customize-manager.php
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	/**
	 * Instance of the DynamicCSS cache manager
	 *
	 * @var ProteusThemes\CustomizerUtils\CacheManager
	 */
	private $dynamic_css_cache_manager;

	/**
	 * Holds the array for the DynamiCSS.
	 *
	 * @var array
	 */
	private $dynamic_css = array();

	/**
	 * Constructor method for this class.
	 *
	 * @param WP_Customize_Manager $wp_customize The WP customizer manager instance.
	 */
	public function __construct( WP_Customize_Manager $wp_customize ) {
		// Set the private propery to instance of wp_customize.
		$this->wp_customize = $wp_customize;

		// Set the private propery to instance of DynamicCSS CacheManager.
		$this->dynamic_css_cache_manager = new CacheManager( $this->wp_customize );

		// Init the dynamic_css property.
		$this->dynamic_css = $this->dynamic_css_init();

		// Register the settings/panels/sections/controls.
		$this->register_settings();
		$this->register_sections();
		$this->register_partials();
		$this->register_controls();

		/**
		 * Action and filters
		 */

		// Render the CSS and cache it to the theme_mod when the setting is saved.
		add_action( 'wp_head', array( 'ProteusThemes\CustomizerUtils\Helpers', 'add_dynamic_css_style_tag' ), 50, 0 );
		add_action( 'customize_save_after', function() {
			$this->dynamic_css_cache_manager->cache_rendered_css();
		}, 10, 0 );

		// Save logo width/height dimensions.
		add_action( 'customize_save_logo_img', array( 'ProteusThemes\CustomizerUtils\Helpers', 'save_logo_dimensions' ), 10, 1 );
	}


	/**
	 * Initialization of the dynamic CSS settings with config arrays
	 *
	 * @return array
	 */
	private function dynamic_css_init() {
		$darken5   = new Setting\DynamicCSS\ModDarken( 5 );
		$darken6   = new Setting\DynamicCSS\ModDarken( 6 );
		$darken12  = new Setting\DynamicCSS\ModDarken( 12 );
		$lighten5  = new Setting\DynamicCSS\ModLighten( 5 );
		$lighten7  = new Setting\DynamicCSS\ModLighten( 7 );

		return array(
			'primary_color' => array(
				'default' => '#079bbb',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'a',
								'a:focus',
								'.article__content .more-link',
								'.article__content .more-link:focus',
								'.widget_tag_cloud a',
								'.widget_archive a:focus',
								'.widget_archive a:hover',
								'.widget_archive a:hover:active',
								'.widget_pages a:focus',
								'.widget_pages a:hover',
								'.widget_pages a:hover:active',
								'.widget_categories a:focus',
								'.widget_categories a:hover',
								'.widget_categories a:hover:active',
								'.widget_meta a:focus',
								'.widget_meta a:hover',
								'.widget_meta a:hover:active',
								'.widget_recent_comments a:focus',
								'.widget_recent_comments a:hover',
								'.widget_recent_comments a:hover:active',
								'.widget_recent_entries a:focus',
								'.widget_recent_entries a:hover',
								'.widget_recent_entries a:hover:active',
								'.widget_rss a:focus',
								'.widget_rss a:hover',
								'.widget_rss a:hover:active',
								'.article__tags a',
								'.footer-top .widget_tag_cloud a',
								'.footer-bottom .icon-container:hover',
								'.footer-top__heading',
							),
						),
					),
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'a:hover',
								'.article__content .more-link:hover',
							),
						),
						'modifier'  => $darken6,
					),
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'a:active:hover',
								'.article__content .more-link:active:hover',
							),
						),
						'modifier'  => $darken12,
					),
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation > .current-menu-item > a',
								'.main-navigation > .current-menu-ancestor > a',
								'.main-navigation a::after',
								'.main-navigation > .current-menu-item:focus > a',
								'.main-navigation > .current-menu-item:hover > a',
								'.main-navigation > .current-menu-ancestor:focus > a',
								'.main-navigation > .current-menu-ancestor:hover > a',
							),
						),
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.btn-primary',
								'.widget_calendar caption',
								'.widget_tag_cloud a:focus',
								'.widget_tag_cloud a:hover',
								'.article__tags a:focus',
								'.article__tags a:hover',
								'.footer-top__back-to-top',
								'.footer-top__back-to-top:focus',
							),
						),
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.btn-primary:focus',
								'.btn-primary:hover',
								'.widget_tag_cloud a:active:hover',
								'.article__tags a:active:hover',
								'.footer-top__back-to-top:hover',
							),
						),
						'modifier'  => $darken6,
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.btn-primary:active:hover',
								'.footer-top__back-to-top:active:hover',
							),
						),
						'modifier'  => $darken12,
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation .sub-menu .menu-item > a:hover',
							),
						),
						'modifier'  => $lighten7,
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'@media (max-width: 991px)' => array(
								'.main-navigation',
							),
						),
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation .sub-menu a',
								'.main-navigation .pt-special-dropdown .sub-menu',
							),
						),
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.btn-primary',
								'.widget_tag_cloud a',
								'.widget_tag_cloud a:focus',
								'.widget_tag_cloud a:hover',
								'.article__tags a',
								'.article__tags a:focus',
								'.article__tags a:hover',
							),
						),
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.btn-primary:focus',
								'.btn-primary:hover',
								'.widget_tag_cloud a:active:hover',
								'.article__tags a:active:hover',
							),
						),
						'modifier'  => $darken6,
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.btn-primary:active:hover',
							),
						),
						'modifier'  => $darken12,
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'@media (max-width: 991px)' => array(
								'.main-navigation a',
							),
						),
						'modifier'  => $lighten5,
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation .sub-menu a',
								'.main-navigation .sub-menu .sub-menu a',
								'.main-navigation .sub-menu .menu-item:hover > a',
								'.main-navigation .pt-special-dropdown .sub-menu .menu-item:not(:last-of-type)',
							),
						),
						'modifier'  => $lighten7,
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation > .current-menu-item > a',
								'.main-navigation > .current-menu-ancestor > a',
								'.main-navigation .menu-item:focus > a',
								'.main-navigation .menu-item:hover > a',
								'.main-navigation .menu-item.is-hover > a',
							),
						),
					),
				),
			),

			'secondary_color' => array(
				'default' => '#66d0cc',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.btn-secondary',
							),
						),
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.btn-secondary:focus',
								'.btn-secondary:hover',
							),
						),
						'modifier'  => $darken6,
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.btn-secondary:active:hover',
							),
						),
						'modifier'  => $darken12,
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.btn-secondary',
							),
						),
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.btn-secondary:focus',
								'.btn-secondary:hover',
							),
						),
						'modifier'  => $darken6,
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.btn-secondary:active:hover',
							),
						),
						'modifier'  => $darken12,
					),
				),
			),
		);
	}

	/**
	 * Register customizer settings
	 *
	 * @return void
	 */
	public function register_settings() {
		// Header.
		$this->wp_customize->add_setting( 'top_bar_visibility', array( 'default' => 'yes' ) );

		// Featured page.
		$this->wp_customize->add_setting( 'featured_page_select', array( 'default' => 'none' ) );
		$this->wp_customize->add_setting( 'featured_page_custom_text' );
		$this->wp_customize->add_setting( 'featured_page_custom_url' );
		$this->wp_customize->add_setting( 'featured_page_open_in_new_window' );

		// Theme layout & color.
		$this->wp_customize->add_setting( 'layout_mode', array( 'default' => 'wide' ) );

		// Footer.
		$this->wp_customize->add_setting( 'footer_bottom_left_txt', array( 'default' => '<strong><a href="https://www.proteusthemes.com/wordpress-themes/medicpress/">MedicPress</a></strong> - WordPress theme made by ProteusThemes.' ) );
		$this->wp_customize->add_setting( 'footer_bottom_right_txt', array( 'default' => 'Copyright &copy; ' . date_i18n( 'Y' ) . '. All rights reserved.' ) );

		// Theme Info.
		$this->wp_customize->add_setting( 'theme_info_text' );

		// All the DynamicCSS settings.
		foreach ( $this->dynamic_css as $setting_id => $args ) {
			$this->wp_customize->add_setting(
				new Setting\DynamicCSS( $this->wp_customize, $setting_id, $args )
			);
		}
	}

	/**
	 * Sections
	 *
	 * @return void
	 */
	public function register_sections() {
		$this->wp_customize->add_section( 'medicpress_theme_options', array(
			'title'       => esc_html__( 'Theme Options', 'medicpress-lite' ),
			'description' => esc_html__( 'All MedicPress Lite theme specific settings.', 'medicpress-lite' ),
			'priority'    => 5,
		) );

		$this->wp_customize->add_section( 'medicpress_theme_info', array(
			'title'       => esc_html__( 'Theme Info', 'medicpress-lite' ),
			'description' => esc_html__( 'More information about MedicPress.', 'medicpress-lite' ),
			'priority'    => 10,
		) );
	}


	/**
	 * Partials for selective refresh
	 *
	 * @return void
	 */
	public function register_partials() {
		$this->wp_customize->selective_refresh->add_partial( 'dynamic_css', array(
			'selector' => 'head > #wp-utils-dynamic-css-style-tag',
			'settings' => array_keys( $this->dynamic_css ),
			'render_callback' => function() {
				return $this->dynamic_css_cache_manager->render_css();
			},
		) );
	}


	/**
	 * Controls
	 *
	 * @return void
	 */
	public function register_controls() {
		// Section: Theme Options.

		// Primary Color
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'primary_color',
			array(
				'priority' => 5,
				'label'    => esc_html__( 'Primary color', 'medicpress-lite' ),
				'section'  => 'medicpress_theme_options',
			)
		) );

		// Layout Mode
		$this->wp_customize->add_control( 'layout_mode', array(
			'type'     => 'select',
			'priority' => 10,
			'label'    => esc_html__( 'Layout', 'medicpress-lite' ),
			'section'  => 'medicpress_theme_options',
			'choices'  => array(
				'wide'  => esc_html__( 'Wide', 'medicpress-lite' ),
				'boxed' => esc_html__( 'Boxed', 'medicpress-lite' ),
			),
		) );

		// Top Bar Visibility
		$this->wp_customize->add_control( 'top_bar_visibility', array(
			'type'        => 'select',
			'priority'    => 15,
			'label'       => esc_html__( 'Top bar visibility', 'medicpress-lite' ),
			'description' => esc_html__( 'Show or hide?', 'medicpress-lite' ),
			'section'     => 'medicpress_theme_options',
			'choices'     => array(
				'yes'         => esc_html__( 'Show', 'medicpress-lite' ),
				'no'          => esc_html__( 'Hide', 'medicpress-lite' ),
				'hide_mobile' => esc_html__( 'Hide on Mobile', 'medicpress-lite' ),
			),
		) );

		$this->wp_customize->add_control( 'featured_page_select', array(
			'type'        => 'select',
			'priority'    => 20,
			'label'       => esc_html__( 'Featured page', 'medicpress-lite' ),
			'description' => esc_html__( 'To which page should the Featured Page button link to?', 'medicpress-lite' ),
			'section'     => 'medicpress_theme_options',
			'choices'     => WpUtilsHelpers::get_all_pages_id_title(),
		) );
		$this->wp_customize->add_control( 'featured_page_custom_text', array(
			'priority'        => 25,
			'label'           => esc_html__( 'Custom Button Text', 'medicpress-lite' ),
			'section'         => 'medicpress_theme_options',
			'active_callback' => function() {
				return WpUtilsHelpers::is_theme_mod_specific_value( 'featured_page_select', 'custom-url' );
			},
		) );

		$this->wp_customize->add_control( 'featured_page_custom_url', array(
			'priority'        => 30,
			'label'           => esc_html__( 'Custom URL', 'medicpress-lite' ),
			'section'         => 'medicpress_theme_options',
			'active_callback' => function() {
				return WpUtilsHelpers::is_theme_mod_specific_value( 'featured_page_select', 'custom-url' );
			},
		) );

		$this->wp_customize->add_control( 'featured_page_open_in_new_window', array(
			'type'            => 'checkbox',
			'priority'        => 35,
			'label'           => esc_html__( 'Open link in a new window/tab.', 'medicpress-lite' ),
			'section'         => 'medicpress_theme_options',
			'active_callback' => function() {
				return ( ! WpUtilsHelpers::is_theme_mod_specific_value( 'featured_page_select', 'none' ) );
			},
		) );

		$this->wp_customize->add_control( 'footer_bottom_left_txt', array(
			'type'        => 'text',
			'priority'    => 40,
			'label'       => esc_html__( 'Footer bottom left text', 'medicpress-lite' ),
			'description' => esc_html__( 'You can use HTML: a, span, i, em, strong, img.', 'medicpress-lite' ),
			'section'     => 'medicpress_theme_options',
		) );

		$this->wp_customize->add_control( 'footer_bottom_right_txt', array(
			'type'        => 'text',
			'priority'    => 45,
			'label'       => esc_html__( 'Footer bottom right text', 'medicpress-lite' ),
			'description' => esc_html__( 'You can use HTML: a, span, i, em, strong, img.', 'medicpress-lite' ),
			'section'     => 'medicpress_theme_options',
		) );

		// Section: medicpress_theme_info.
		$this->wp_customize->add_control( 'theme_info_text', array(
			'type'        => 'hidden',
			'priority'    => 5,
			'description' => sprintf( esc_html__( '%1$sView Documentation%2$s %3$s %4$sView Demo%2$s %3$s %5$sView MedicPress Pro%2$s' , 'medicpress-lite' ),
			'<b><a style="display: block; font-style: normal; height: 45px; line-height: 45px;" class="button" href="https://demo.proteusthemes.com/medicpress-lite/documentation/" target="_blank">',
			'</a></b>',
			'<hr>',
			'<b><a style="display: block; font-style: normal; height: 45px; line-height: 45px;" class="button" href="https://demo.proteusthemes.com/medicpress-lite/" target="_blank">',
			'<b><a style="display: block; font-style: normal; height: 45px; line-height: 45px;" class="button" href="https://www.proteusthemes.com/wordpress-themes/medicpress/" target="_blank">'
			),
			'section'     => 'medicpress_theme_info',
		) );
	}
}
