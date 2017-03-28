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
				<a class="header__logo<?php echo ! has_custom_logo() ? '  header__logo--text' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php if ( has_custom_logo() ) : ?>

						<?php
							$custom_logo_id = get_theme_mod( 'custom_logo' );
							$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
							$medicpress_logo = $image[0];
						?>

						<img src="<?php echo esc_url( $medicpress_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="img-fluid" />
					<?php else : ?>
						<p class="h1  header__logo-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
					<?php endif; ?>
				</a>
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
					?>
					<!-- Featured Button -->
					<?php
						$featured_page_data = MedicPressHelpers::get_featured_page_data();

						if ( ! empty( $featured_page_data ) ) :
					?>
						<a class="btn  btn-secondary  btn-featured" href="<?php echo esc_url( $featured_page_data['url'] ); ?>" target="<?php echo esc_attr( $featured_page_data['target'] ); ?>"><?php echo esc_html( $featured_page_data['title'] ); ?></a>
					<?php endif; ?>
				</nav>
			</div>
		</div>
	</header>
