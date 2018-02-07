(function($) {
  'use strict';
  $(function() {
		$('#tabs').tabs({
			beforeActivate: function (event, ui) {
				var hash = ui.newTab.children("li a").attr("href");
				window.location.hash = hash;
			}
		});
  });
// Place your administration-specific JavaScript here
})(jQuery);
