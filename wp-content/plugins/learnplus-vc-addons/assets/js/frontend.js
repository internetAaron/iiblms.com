var learnplusShortCode = learnplusShortCode || {};

jQuery( document ).ready( function( $ ) {
	'use strict';

	/**
	 * Init images carousel
	 */
	function leanplusCarousel(learnplusType) {
		if (learnplusShortCode.length === 0 || typeof learnplusType === 'undefined') {
			return;
		}
		$.each(learnplusType, function (id, typeCarousel) {
			if ($(document.getElementById(id)).find('.lp-owl-carousel').length > typeCarousel.number) {
				$(document.getElementById(id)).find('.lp-owl-list').owlCarousel({
					items: typeCarousel.number,
					nav  : false,
					dots : true
				});
			}

		});
	}

	/**
	 * Init images carousel
	 */
	function productsCarousel(learnplusType) {
		if (learnplusShortCode.length === 0 || typeof learnplusType === 'undefined') {
			return;
		}
		$.each(learnplusType, function (id, typeCarousel) {
			if ($(document.getElementById(id)).find('li.product').length > typeCarousel.number) {
				$(document.getElementById(id)).find('ul.products').owlCarousel({
					items     : typeCarousel.number,
					loop      : true,
					margin    : 15,
					nav       : typeCarousel.navigation,
					autoplay  : typeCarousel.autoplay,
					dots      : false,
					responsive: {
						0   : {
							items: 1
						},
						600 : {
							items: 2
						},
						1000: {
							items: 4
						}
					}
				});
			}

		});
	}

	/**
	 * Init images carousel
	 */
	function carousesCarousel(learnplusType) {
		if (learnplusShortCode.length === 0 || typeof learnplusType === 'undefined') {
			return;
		}
		$.each(learnplusType, function (id, typeCarousel) {
			if( $(document.getElementById(id)).find('.ld_course_grid').length <= typeCarousel.number ) {
				typeCarousel.navigation = false;
			}
			$(document.getElementById(id)).find('.course-list').owlCarousel({
				items     : typeCarousel.number,
				loop      : true,
				margin    : 15,
				nav       : typeCarousel.navigation,
				autoplay  : typeCarousel.autoplay,
				dots      : false,
				responsive: {
					0   : {
						items: 1
					},
					600 : {
						items: 2
					},
					1000: {
						items: 4
					}
				}
			});
		});
	}

	/**
	 * Init Google maps
	 */
	function learnplusMaps() {
		if (learnplusShortCode.length === 0 || typeof learnplusShortCode.map === 'undefined') {
			return;
		}

		var styles = [
					{
						stylers: [
							{saturation: -100}

						]
					}, {
						featureType: 'road',
						elementType: 'geometry',
						stylers    : [
							{hue: '#74b7b0'},
							{visibility: 'simplified'}
						]
					}, {
						featureType: 'road',
						elementType: 'labels',
						stylers    : [
							{visibility: 'on'}
						]
					}
				],
				customMap = new google.maps.StyledMapType(styles,
						{name: 'Styled Map'});

		var mapOptions = {
			scrollwheel       : false,
			draggable         : true,
			zoom              : 13,
			mapTypeId         : google.maps.MapTypeId.ROADMAP,
			panControl        : false,
			zoomControl       : true,
			zoomControlOptions: {
				style: google.maps.ZoomControlStyle.SMALL
			},
			scaleControl      : false,
			streetViewControl : false

		};

		$.each(learnplusShortCode.map, function (id, mapData) {
			var map,
					marker,
					location = new google.maps.LatLng(mapData.lat, mapData.lng);


			// Update map options
			mapOptions.zoom = parseInt(mapData.zoom, 10);
			mapOptions.center = location;
			mapOptions.mapTypeControlOptions = {
				mapTypeIds: [google.maps.MapTypeId.ROADMAP]
			};

			// Init map
			map = new google.maps.Map(document.getElementById(id), mapOptions);

			// Create marker options
			var markerOptions = {
				map     : map,
				position: location
			};
			if (mapData.marker) {
				markerOptions.icon = {
					url: mapData.marker
				};
			}

			map.mapTypes.set('map_style', customMap);
			map.setMapTypeId('map_style');

			// Init marker
			marker = new google.maps.Marker(markerOptions);

			if (mapData.info) {
				var infoWindow = new google.maps.InfoWindow({
					content : '<div class="infobox">' + mapData.info + '</div>',
					maxWidth: 600
				});

				google.maps.event.addListener(marker, 'click', function () {
					infoWindow.open(map, marker);
				});
			}

		});
	}

	/**
	 * Init slider
	 */
	function initBxSlider() {
		$('.bxslider').bxSlider({
			mode       : 'vertical',
			minSlides  : 1,
			maxSlides  : 1,
			slideMargin: 0,
			pager      : false,
			nextText   : '<i class="fa fa-arrow-down"></i>',
			prevText   : '<i class="fa fa-arrow-up"></i>',
			speed      : 1000,
			auto       : true
		});
	}

	/**
	 * Counter
	 */
	function count($this) {
		var current = parseInt($this.html(), 10);
		current = current + 10;
		$this.html(++current);

		if (current > $this.data('count')) {
			$this.html($this.data('count'));
		} else {
			setTimeout(function () {
				count($this);
			}, 10);
		}
	}

	$('.stat-count').each(function () {
		$(this).data('count', parseInt($(this).html(), 10));
		$(this).html('0');
		count($(this));
	});

	leanplusCarousel(learnplusShortCode.teamCarousel);

	leanplusCarousel(learnplusShortCode.postsCarousel);

	leanplusCarousel(learnplusShortCode.imagesCarousel);

	productsCarousel(learnplusShortCode.productsCarousel);

	carousesCarousel(learnplusShortCode.carousesCarousel);

	learnplusMaps();

	initBxSlider();
} );