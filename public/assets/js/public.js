(function($) {
  'use strict';
  $(function() {
    // Write in console log the PHP value passed in enqueue_js_vars in public/class-plugin-name.php
		// console.log(pn_js_vars.alert);

		$(document).ready(function(){

			window.cb = {};

			cb.calendarStyles = function() {

				if ($('.cb-calendar-grouped').length < 1) {
					return;
				}

				if ($('.cb-calendar-grouped').outerWidth() >= 450) {
					$('.cb-calendar-grouped').addClass('cb-calendar-grouped-large');
				} else {
					$('.cb-calendar-grouped').removeClass('cb-calendar-grouped-large');
				}

			};

			cb.calendarStyles();

			$(window).on('resize',cb.calendarStyles);

		});

  });
// Place your public-facing JavaScript here
})(jQuery);
