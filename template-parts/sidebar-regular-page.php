<?php
/**
 * Template part for displaying page sidebar
 *
 * @package medicpress-lite
 */
?>

<div class="col-xs-12  col-lg-3">
	<div class="sidebar" role="complementary">
		<?php dynamic_sidebar( apply_filters( 'medicpress_regular_page_sidebar', 'regular-page-sidebar', get_the_ID() ) ); ?>
	</div>
</div>
