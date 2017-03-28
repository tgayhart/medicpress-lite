<?php

require_once dirname( __FILE__ ) . '/../inc/helpers.php';

class MedicPressHelpersTest extends WP_UnitTestCase {

	function test_helper_footer_widgets_layout_array() {
		$this->assertEquals( array( 4, 2, 2, 4 ), MedicPressHelpers::footer_widgets_layout_array(), 'default is [4,6,8]' );

		set_theme_mod( 'footer_widgets_layout', '[3,5,6]' );

		$this->assertEquals( array( 3, 2, 1, 6 ), MedicPressHelpers::footer_widgets_layout_array() );

		set_theme_mod( 'footer_widgets_layout', '[3]' );
		$this->assertEquals( array( 3, 9 ), MedicPressHelpers::footer_widgets_layout_array() );

		set_theme_mod( 'footer_widgets_layout', '[1,2,3,4,5,6]' );
		$this->assertEquals( array( 1, 1, 1, 1, 1, 1, 6 ), MedicPressHelpers::footer_widgets_layout_array() );

		set_theme_mod( 'footer_widgets_layout', 'bla' );
		$this->assertEmpty( MedicPressHelpers::footer_widgets_layout_array() );
	}

}