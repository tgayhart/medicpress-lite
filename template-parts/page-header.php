<?php
/**
 * The page title part of the header
 *
 * @package medicpress-lite
 */

$medicpress_blog_id      = absint( get_option( 'page_for_posts' ) );

?>

<div class="page-header">
	<div class="container">
		<?php
		$medicpress_main_tag = 'h1';

		if ( is_home() || ( is_single() && 'post' === get_post_type() ) ) {
			$medicpress_title    = 0 === $medicpress_blog_id ? esc_html__( 'Blog', 'medicpress-lite' ) : get_the_title( $medicpress_blog_id );

			if ( is_single() ) {
				$medicpress_main_tag = 'h2';
			}
		}
		elseif ( is_category() || is_tag() || is_author() || is_post_type_archive() || is_tax() || is_day() || is_month() || is_year() ) {
			$medicpress_title = get_the_archive_title();
		}
		elseif ( is_search() ) {
			$medicpress_title = esc_html__( 'Search Results For' , 'medicpress-lite' ) . ' &quot;' . get_search_query() . '&quot;';
		}
		elseif ( is_404() ) {
			$medicpress_title = esc_html__( 'Error 404' , 'medicpress-lite' );
		}
		else {
			$medicpress_title    = get_the_title();
		}

		?>

		<?php printf( '<%1$s class="page-header__title">%2$s</%1$s>', tag_escape( $medicpress_main_tag ), wp_kses_post( $medicpress_title ) ); ?>

	</div>
</div>
