<?php
/**
 * Footer
 *
 * @package medicpress-lite
 */

$medicpress_footer_widgets_layout = MedicPressHelpers::footer_widgets_layout_array();

// Bottom footer left text.
$medicpress_footer_bottom_left_txt   = get_theme_mod( 'footer_bottom_left_txt', '<strong><a href="https://www.proteusthemes.com/wordpress-themes/medicpress/">MedicPress</a></strong> - WordPress theme made by ProteusThemes.' );
// Bottom footer right text.
$medicpress_footer_bottom_right_txt  = get_theme_mod( 'footer_bottom_right_txt', 'Copyright &copy; ' . date( 'Y' ) . '. All rights reserved.' );

?>

	<footer class="footer">
		<?php if ( ! empty( $medicpress_footer_widgets_layout ) && is_active_sidebar( 'footer-widgets' ) ) : ?>
			<div class="footer-top">
				<div class="container">
					<div class="row">
						<?php dynamic_sidebar( 'footer-widgets' ); ?>
					</div>
				</div>
				<a class="footer-top__back-to-top  js-back-to-top" href="#"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
			</div>
		<?php endif; ?>
		<div class="footer-bottom__container">
			<div class="container">
				<div class="footer-bottom">
					<?php if ( ! empty( $medicpress_footer_bottom_left_txt ) ) : ?>
						<div class="footer-bottom__left">
							<?php echo wp_kses_post( do_shortcode( $medicpress_footer_bottom_left_txt ) ); ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $medicpress_footer_bottom_right_txt ) ) : ?>
						<div class="footer-bottom__right">
							<?php echo wp_kses_post( do_shortcode( $medicpress_footer_bottom_right_txt ) ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</footer>
	</div><!-- end of .boxed-container -->

	<?php wp_footer(); ?>
	</body>
</html>
