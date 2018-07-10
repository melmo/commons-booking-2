(function($) {
  'use strict';
  $(function() {
    // Write in console log the PHP value passed in enqueue_js_vars in public/class-plugin-name.php
		// console.log(pn_js_vars.alert);

		/* Calendar Styles and Tooltips */

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

			cb2.calendarTooltips = function() {

				if ($('.cb-calendar-grouped').length < 1) {
					return;
				}

				$('.cb-slot[data-state="allow-booking"] ').parents('li.cb-date').each(function(i, elem) {
					var template = document.createElement('div');
					template.id = $(elem).attr('id');
					var html = '<div><ul>';

					$(elem).find('[data-state="allow-booking"]').each(function(j, slot) {
						html += '<li>';
						if ($(slot).attr('data-item-thumbnail')) {
							html += '<img src="' + $(slot).attr('data-item-thumbnail') + '">';
						}
						html += '<a href="' + $(slot).attr('data-item-thumbnail') + '">';
						html += $(slot).attr('data-item-title');
						html += '</a></li>';
					});

					html += '</ul></div>';

					template.innerHTML = html;
					
					tippy('#' + template.id, {
						appendTo : document.querySelector('.cb-calendar-grouped'),
						arrow : true,
						html: template,
						interactive : true,
						theme: 'cb-calendar',
						trigger: 'click'
					}); // need to polyfill MutationObserver for IE10 if planning to use dynamicTitle

				});
				
				

			};

			cb2.init = function() {
				cb2.calendarStyles();
				cb2.calendarTooltips();
			};

			cb2.resize = function() {
				cb2.calendarStyles();
			};

			cb2.init();

			$(window).on('resize',cb2.resize);


		});

		/* Maps */

		$(document).ready(function(){

			if ($('#cb_map').length > 0 && $('#cb_map_data').length > 0) {

				var locations = JSON.parse($('#cb_map_data').attr('data-locations'));

				var map = L.map('cb_map');
				
				L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
				}).addTo(map);

				var markers = L.featureGroup();

				for (var i in locations) {
					if (locations[i].geo.lat !== '' && locations[i].geo.lng !== '') {
						
						var marker = L.marker([locations[i].geo.lat, locations[i].geo.lng]);

						var popupText = '';

						var items = locations[i]['items'];

						for (var j in items) {
							popupText += '<p><a href="' + items[j]['url'] + '">' + items[j]['title'] + '</a></p>'
						}	

						marker.bindPopup(popupText);
						markers.addLayer(marker);
					}
					
				}

				var bounds = markers.getBounds();
			    map.fitBounds(bounds);
			    map.addLayer(markers);

				

				//markers.clearLayers();
			}

		});

  });
// Place your public-facing JavaScript here
})(jQuery);
