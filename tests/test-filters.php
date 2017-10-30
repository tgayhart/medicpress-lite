<?php

require_once dirname( __FILE__ ) . '/../inc/filters.php';

class MedicPressLiteFiltersTest extends WP_UnitTestCase {

	function test_filter_body_class_empty() {
		$expected = [ 'medicpress-lite' ];
		$actual = MedicPressLiteFilters::body_class( [] );

		$this->assertEquals( $expected, $actual );
	}

	function test_filter_body_class_boxed() {
		set_theme_mod( 'layout_mode', 'boxed' );

		$expected = [ 'medicpress-lite', 'boxed' ];
		$actual = MedicPressLiteFilters::body_class( [] );

		$this->assertEquals( $expected, $actual );
	}

	function test_filter_post_class() {
		$expected = [ 'clearfix', 'article' ];
		$actual = MedicPressLiteFilters::post_class( [] );

		$this->assertEquals( $expected, $actual );
	}
}
