/* global MedicPressVars */

// config
require.config( {
	paths: {
		util:            'bower_components/bootstrap/js/dist/util',
		alert:           'bower_components/bootstrap/js/dist/alert',
		button:          'bower_components/bootstrap/js/dist/button',
		carousel:        'bower_components/bootstrap/js/dist/carousel',
		collapse:        'bower_components/bootstrap/js/dist/collapse',
		dropdown:        'bower_components/bootstrap/js/dist/dropdown',
		modal:           'bower_components/bootstrap/js/dist/modal',
		scrollspy:       'bower_components/bootstrap/js/dist/scrollspy',
		tab:             'bower_components/bootstrap/js/dist/tab',
		tooltip:         'bower_components/bootstrap/js/dist/tooltip',
		popover:         'bower_components/bootstrap/js/dist/popover',
	}
} );

require.config( {
	baseUrl: MedicPressVars.pathToTheme
} );

require( [
		'assets/js/TouchDropdown',
		'util',
		'collapse',
		'tab',
], function () {
	'use strict';

	jQuery( function ( $ ) {
		/**
		 * Animate the scroll, when back to top is clicked
		 */
		( function () {
			$( '.js-back-to-top' ).click( function ( ev ) {
				ev.preventDefault();

				$( 'body, html' ).animate( {
					scrollTop: 0
				}, 700 );
			});
		} )();
	} );
});
