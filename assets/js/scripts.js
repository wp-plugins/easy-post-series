jQuery( document ).ready( function( $ ) {	
	var WPEPS_Scripts = {
		init: function() {
			var series = $( '.wpeps-series-nav' );
			for ( var i = 1; i <= series.length; i++ ) {
				$( '.wpeps-series-' + i + ' .wpeps-show-posts' ).click( show_posts_handler( i ) );
				$( '.wpeps-series-' + i + ' .wpeps-hide-posts' ).click( hide_posts_handler( i ) );
			}

			function show_posts_handler( i ) {
				return function() { 
					$( '.wpeps-series-' + i + ' ul' ).show( 'fast' );
					$( '.wpeps-series-' + i + ' .wpeps-show-posts' ).hide( 'fast' );
					$( '.wpeps-series-' + i + ' .wpeps-hide-posts' ).show( 'fast' );
				};
			}

			function hide_posts_handler( i ) {
				return function() {
					$( '.wpeps-series-' + i + ' ul' ).hide( 'fast' );
					$( '.wpeps-series-' + i + ' .wpeps-hide-posts' ).hide( 'fast' );
					$( '.wpeps-series-' + i + ' .wpeps-show-posts' ).show( 'fast' );
				};
			}
		},
	};
	WPEPS_Scripts.init();
});