jQuery(function( $ ){

	// Add class based on scroll position
	$(window).scroll(function () {
		if ($(document).scrollTop() > 125 ) {
			$('.home-search-bar, .home').addClass('sticky');
		} else {
			$('.home-search-bar, .home').removeClass('sticky');
		}
	});

});
