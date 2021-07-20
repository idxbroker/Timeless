jQuery(document).foundation();

jQuery(function( $ ){

	// Add inline CSS for off canvas menu position content is always at the top.
	$(document)
	.on('open.fndtn.offcanvas', '[data-offcanvas]', function() {
		$('.off-canvas-menu').css('position', 'fixed');
		var scrolltop = $(document).scrollTop();
		$('.off-canvas-menu').css('margin-top', scrolltop );
		$('.off-canvas-toggle').css('margin-top', scrolltop );
		$('.off-canvas section').css('margin-top', scrolltop );
	})
	.on('close.fndtn.offcanvas', '[data-offcanvas]', function() {
		$('.off-canvas-menu').css('position', 'relative');
		$('.off-canvas-toggle').css('margin-top', '0' );
	});
});