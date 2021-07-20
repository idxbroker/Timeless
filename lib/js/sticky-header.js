jQuery(function( $ ){

	// Add class based on scroll position
	$(window).scroll(function () {
		if ($(document).scrollTop() > 0 ) {
			$('.sticky-header, .home, .off-canvas-toggle').addClass('sticky');
			$('.site-inner').css({"padding-top": $('.sticky-header').height()});
		} else {
			$('.sticky-header, .home, .off-canvas-toggle').removeClass('sticky');
			$('.site-inner').css({"padding-top": ""});
		}
	});

});
