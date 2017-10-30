<?php
/**
 * Register sidebars for MedicPress
 *
 * @package medicpress-lite
 */

function medicpress_lite_sidebars() {
	// Blog Sidebar.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Blog Sidebar', 'medicpress-lite' ),
			'id'            => 'blog-sidebar',
			'description'   => esc_html__( 'Sidebar on the blog layout.', 'medicpress-lite' ),
			'class'         => 'blog  sidebar',
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="sidebar__headings">',
			'after_title'   => '</h4>',
		)
	);

	// Regular Page Sidebar.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Regular Page Sidebar', 'medicpress-lite' ),
			'id'            => 'regular-page-sidebar',
			'description'   => esc_html__( 'Sidebar on the regular page.', 'medicpress-lite' ),
			'class'         => 'sidebar',
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="sidebar__headings">',
			'after_title'   => '</h4>',
		)
	);

	// Top Left Widgets
	register_sidebar(
		array(
			'name'          => esc_html_x( 'Top Left', 'backend', 'medicpress-lite' ),
			'id'            => 'top-left-widgets',
			'description'   => esc_html_x( 'Top left widget area for Icon Box, Social Icons, Custom Menu or Text widget.', 'backend', 'medicpress-lite' ),
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
		)
	);

	// Top Right Widgets
	register_sidebar(
		array(
			'name'          => esc_html_x( 'Top Right', 'backend', 'medicpress-lite' ),
			'id'            => 'top-right-widgets',
			'description'   => esc_html_x( 'Top right widget area for Icon Box, Social Icons, Custom Menu or Text widget.', 'backend', 'medicpress-lite' ),
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
		)
	);

	// Footer.
	$footer_widgets_num = count( MedicPressLiteHelpers::footer_widgets_layout_array() );

	// Only register if not 0.
	if ( $footer_widgets_num > 0 ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer', 'medicpress-lite' ),
				'id'            => 'footer-widgets',
				'description'   => sprintf( esc_html__( 'Footer area works best with %d widgets. This number can be changed in the Appearance &rarr; Customize &rarr; Theme Options &rarr; Footer.', 'medicpress-lite' ), $footer_widgets_num ),
				'before_widget' => '<div class="col-xs-12  col-lg-__col-num__"><div class="widget  %2$s">', // __col-num__ is replaced dynamically in filter 'dynamic_sidebar_params'
				'after_widget'  => '</div></div>',
				'before_title'  => '<h4 class="footer-top__heading">',
				'after_title'   => '</h4>',
			)
		);
	}
}
add_action( 'widgets_init', 'medicpress_lite_sidebars' );
