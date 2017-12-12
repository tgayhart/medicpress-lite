<?php
/**
 * Helper functions
 *
 * @package medicpress-lite
 */

/**
 * MedicPressLiteHelpers class with static methods
 */
class MedicPressLiteHelpers {
	/**
	 * The comments_number() does not use _n function, here we are to fix that
	 */
	static function pretty_comments_number() {
		global $post;
		printf(
			/* translators: %s represents a number */
			_n( '%s Comment', '%s Comments', get_comments_number(), 'medicpress-lite' ), number_format_i18n( get_comments_number() )
		);
	}


	/**
	 * Return array of the number which represent the layout of the footer.
	 *
	 * @return array
	 */
	static function footer_widgets_layout_array() {
		$layout = get_theme_mod( 'footer_widgets_layout', '[4,6,8]' );
		$layout = json_decode( $layout );

		if ( is_array( $layout ) && ! empty( $layout ) ) {
			$spans = array( (int) $layout[0] );

			for ( $i = 0; $i < ( count( $layout ) - 1 ); $i++ ) {
				$spans[] = $layout[ $i + 1 ] - $layout[ $i ];
			}

			$spans[] = 12 - $layout[ $i ];

			return $spans;
		}
		elseif ( 1 === $layout ) { // Single column.
			return array( '12' );
		}

		// Default: disable footer.
		return array();
	}

	/**
	 * Return url with Google Fonts.
	 *
	 * @see https://github.com/grappler/wp-standard-handles/blob/master/functions.php
	 * @return string Google fonts URL for the theme.
	 */
	static function google_web_fonts_url() {
		$fonts_url = '';
		$fonts     = array();
		$subsets   = array( 'latin' );

		$fonts = apply_filters( 'medicpress_pre_google_web_fonts', $fonts );

		foreach ( $fonts as $key => $value ) {
			$fonts[ $key ] = $key . ':' . implode( ',', $value );
		}

		/* translators: To add an additional character subset specific to your language, translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language. */
		$subset = esc_html_x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'medicpress-lite' );
		if ( 'cyrillic' == $subset ) {
			array_push( $subsets, 'cyrillic', 'cyrillic-ext' );
		} elseif ( 'greek' == $subset ) {
			array_push( $subsets, 'greek', 'greek-ext' );
		} elseif ( 'devanagari' == $subset ) {
			array_push( $subsets, 'devanagari' );
		} elseif ( 'vietnamese' == $subset ) {
			array_push( $subsets, 'vietnamese' );
		}

		$subsets = apply_filters( 'medicpress_subsets_google_web_fonts', $subsets );

		if ( $fonts ) {
			$fonts_url = add_query_arg(
				array(
					'family' => urlencode( implode( '|', $fonts ) ),
					'subset' => urlencode( implode( ',', array_unique( $subsets ) ) ),
				),
				'//fonts.googleapis.com/css'
			);
		}

		return apply_filters( 'medicpress_google_web_fonts_url', $fonts_url );
	}


	/**
	 * Custom wp_list_comments callback (template)
	 *
	 * @param obj   $comment WP comment object.
	 * @param array $args comment arguments.
	 * @param int   $depth WP comment depth.
	 */
	static function custom_comment( $comment, $args, $depth ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	?>

		<<?php echo tag_escape( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( array( 'clearfix', empty( $args['has_children'] ) ? '' : 'parent' ) ); ?>>
			<div class="comment__content">
				<div class="comment__inner">
					<div class="comment__avatar">
						<?php if ( 0 != $args['avatar_size'] ) { echo get_avatar( $comment, $args['avatar_size'] ); } ?>
					</div>
					<div class="comment__metadata">
						<?php comment_reply_link( array_merge( $args, array(
							'depth' => $depth,
							'before' => '',
						) ) ); ?>
						<?php edit_comment_link( esc_html__( 'Edit', 'medicpress-lite' ), '' ); ?>
					</div>
					<cite class="comment__author  vcard">
						<?php echo get_comment_author_link(); ?>
					</cite>
					<time class="comment__date" datetime="<?php comment_time( 'c' ); ?>">
						<i class="fa fa-calendar-o" aria-hidden="true"></i>  <?php comment_date( 'F j, Y' ); ?>
					</time>
					<div class="comment__text">
						<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment__awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.' , 'medicpress-lite' ); ?></p>
						<?php endif; ?>

						<?php comment_text(); ?>
					</div>
				</div>

		<?php
	}


	/**
	 * Return correct path to the file (check child theme first)
	 *
	 * @param string $relative_file_path relative path to the file.
	 * @return string, absolute path of the correct file.
	 */
	public static function get_correct_file_path( $relative_file_path ) {
		if ( file_exists( get_stylesheet_directory() . $relative_file_path ) ) {
			return get_stylesheet_directory() . $relative_file_path;
		}
		elseif ( file_exists( get_template_directory() . $relative_file_path ) ) {
			return get_template_directory() . $relative_file_path;
		}
		else {
			return false;
		}
	}


	/**
	 * Require the correct file with require_once (checks child theme first)
	 *
	 * @param string $relative_file_path relative path to the file.
	 */
	public static function load_file( $relative_file_path ) {
		require_once self::get_correct_file_path( $relative_file_path );
	}


	/**
	 * Return the correct page ID.
	 * WPML support.
	 *
	 * @param mixed [string|int] $original_id Original ID.
	 */
	public static function get_page_id( $original_id ) {
		if ( function_exists( 'icl_object_id' ) ) {
			return icl_object_id( $original_id, 'page', true );
		}

		return $original_id;
	}


	/**
	 * Get the author posts link with author display name (also works outside the WP loop).
	 *
	 * @param string $class String of classes that will be added to the link.
	 */
	public static function get_author_posts_link( $class = '' ) {
		global $post;

		$author_id   = $post->post_author;
		$author_url  = get_author_posts_url( $author_id );
		$author_name = get_the_author_meta( 'display_name', $author_id );

		ob_start();
		?>
			<a class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $author_url ); ?>" title="<?php echo esc_html__( 'Posted by', 'medicpress-lite' ) . ' ' . esc_html( $author_name ); ?>" rel="author"><?php echo esc_html( $author_name ); ?></a>
		<?php
		$output = ob_get_clean();

		return $output;
	}


	/**
	 * Get the featured page customizer settings data or return false if it's set to 'none'.
	 *
	 * @return mixed Boolean if set to 'none' or array with the data.
	 */
	public static function get_featured_page_data() {
		$data = array(
			'url'    => '',
			'target' => '',
			'title'  => '',
		);

		$selected_page = self::get_page_id( get_theme_mod( 'featured_page_select', 'none' ) );

		// If the featured page is not set in customizer return false.
		if ( 'none' === $selected_page ) {
			return false;
		}

		// Get the target data.
		$data['target'] = get_theme_mod( 'featured_page_open_in_new_window', '' ) ? '_blank' : '_self';

		// Get the url and the title, depending on what settings are selected.
		if ( 'custom-url' === $selected_page ) {
			$data['title'] = get_theme_mod( 'featured_page_custom_text', 'Featured Page' );
			$data['url']   = get_theme_mod( 'featured_page_custom_url', '#' );
		}
		else {
			$data['title'] = get_the_title( absint( $selected_page ) );
			$data['url']   = get_permalink( absint( $selected_page ) );
		}

		return $data;
	}
}
