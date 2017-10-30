<?php
/**
 * The Header for MedicPress Theme
 *
 * @package medicpress-lite
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php endif; ?>

		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>
	<div class="boxed-container<?php echo ( is_single() && 'post' === get_post_type() ) ? '  h-entry' : ''; ?>">

	<?php get_template_part( 'template-parts/top-bar' ); ?>

	<header class="header__container">
		<div class="container">
			<div class="header">
				<!-- Logo -->
				<?php if ( ! has_custom_logo() ) : ?>
				<a class="header__logo  header__logo--text" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<p class="h1  header__logo-text"><?php bloginfo( 'name' ); ?></p>
				</a>
				<?php endif; ?>
				<?php the_custom_logo(); ?>
				<!-- Toggle button for Main Navigation on mobile -->
				<button class="btn  btn-primary  header__navbar-toggler  hidden-lg-up  js-sticky-mobile-option" type="button" data-toggle="collapse" data-target="#medicpress-main-navigation"><i class="fa  fa-bars  hamburger"></i> <span><?php esc_html_e( 'MENU' , 'medicpress-lite' ); ?></span></button>
				<!-- Main Navigation -->
				<nav class="header__main-navigation  collapse  navbar-toggleable-md  js-sticky-desktop-option" id="medicpress-main-navigation" aria-label="<?php esc_html_e( 'Main Menu', 'medicpress-lite' ); ?>">
					<?php
					if ( has_nav_menu( 'main-menu' ) ) {
						wp_nav_menu( array(
							'theme_location' => 'main-menu',
							'container'      => false,
							'menu_class'     => 'main-navigation  js-main-nav  js-dropdown',
							'walker'         => new Aria_Walker_Nav_Menu(),
							'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
						) );
					}
					else {
						wp_nav_menu( array(
							'container'      => false,
							'menu_class'     => 'main-navigation  js-main-nav  js-dropdown',
							'fallback_cb'    => function() {
								printf( '<ul id="menu-main-menu" class="main-navigation  js-main-nav  js-dropdown" role="menubar"><li class="menu-item"><a href="%1$s">Home</a></li><li class="menu-item"><a href="%2$s">Set main menu</a></li></ul>', esc_url( get_home_url() ), esc_url( admin_url( 'nav-menus.php' ) ) );
							}
						) );
					}
					?>
					<!-- Featured Button -->
					<?php
						$featured_page_data = MedicPressLiteHelpers::get_featured_page_data();

						if ( ! empty( $featured_page_data ) ) :
					?>
						<a class="btn  btn-secondary  btn-featured" href="<?php echo esc_url( $featured_page_data['url'] ); ?>" target="<?php echo esc_attr( $featured_page_data['target'] ); ?>"><?php echo esc_html( $featured_page_data['title'] ); ?></a>
					<?php endif; ?>
				</nav>
			</div>
		</div>
	</header>
