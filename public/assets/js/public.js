(function($) {
  'use strict';
  $(function() {
    // Write in console log the PHP value passed in enqueue_js_vars in public/class-plugin-name.php
		// console.log(pn_js_vars.alert);

		$(document).ready(function(){

			window.cb2 = {}; // global commons booking object

			cb2.calendarStyles = function() { // manage style of calendar by calendar size, not window width

				if ($('.cb-calendar-grouped').length < 1) {
					return;
				}

				if ($('.cb-calendar-grouped').outerWidth() >= 450) {
					$('.cb-calendar-grouped').addClass('cb-calendar-grouped-large');
				} else {
					$('.cb-calendar-grouped').removeClass('cb-calendar-grouped-large');
				}

			};

			cb2.calendarStyles();

			$(window).on('resize',cb2.calendarStyles);

			tippy('.cb-date'); // need to polyfill MutationObserver for IE10 if planning to use dynamicTitle

		});

  });
// Place your public-facing JavaScript here
})(jQuery);
