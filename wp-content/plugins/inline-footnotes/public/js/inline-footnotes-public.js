(function( $ ) {
	'use strict';

	 $(function() {
	 	$(".inline-footnote").click(function(evt) {
	 		$(this).children('.footnoteContent').toggle();
	 		evt.stopPropagation();
	 	});
	 	$('html').on('click', function() {
	 		$(".inline-footnote .footnoteContent").hide();
	 	});
	 	// $(".inline-footnote").click(function() {
	 	// 	console.log('CLICK', $(this).attr('title') );
	 	// 	var popup = $("<div style='position:absolute;z-index:999;background-color:white;'>" + $(this).attr('title') + "</div>");
	 	// 	$(this).append(popup);
	 	// });
	 });

})( jQuery );
