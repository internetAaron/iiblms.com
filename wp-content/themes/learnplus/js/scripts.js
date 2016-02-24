var learnplus = learnplus || {},
	learnplusShortCode = learnplusShortCode || {};
(function ($) {
	'use strict';

	$(function () {
		// Posts gallery
		$('.format-gallery-slider .slides').bxSlider({
			mode    : 'fade',
			pager   : false,
			nextText: '<i class="fa fa-angle-right"></i>',
			prevText: '<i class="fa fa-angle-left"></i>',
			speed   : 1000,
			auto    : true
		});

		$( '.topbar').find( '.widget_currency_sel_widget').each( function() {
				if( $( this).find( 'ul').hasClass( 'curr_list_vertical' ) ) {
					$( this).addClass( 'cur-list-vertical' );
					var current = $( this).find( 'li.wcml-active-currency').html();
					$( this).find( 'ul').before( '<span class="currency-active">' + current + '</span>' );
				}
		} );

		// Reloader
		$(window).load(function () {
			$('#loader').delay(300).fadeOut('slow');
		});

		// Show menu when resize to bigger
		$(window).resize( function() {
			if ( $(window).width() > 959 ) {
				$( '#site-navigation' ).show();
			} else {
				$( '#site-navigation' ).hide();
			}
		} );

		// Scroll effect button top
		$( '#scroll-top' ).on( 'click', function( event ) {
			event.preventDefault();
			$( 'html, body' ).stop().animate ( {
					scrollTop : 0
				},
				1200
			);
		} );

		$(window).scroll( function() {
			if ( $(window).scrollTop() > $(window).height() ) {
				$( '#scroll-top' ).addClass( 'show-scroll' );
			} else {
				$( '#scroll-top' ).removeClass( 'show-scroll' );
			}
		} );

		/**
		 * Search toggle
		 */
		$( '.menu-item-search' ).on( 'click', 'i', function( e ) {
			e.preventDefault();
			$('body').toggleClass( 'display-search' );
		} );

		$( '#search-panel-close').on( 'click', function( e ) {
			e.preventDefault();
			$('body').toggleClass( 'display-search' );
		} );

		// Click the icon navbar-toggle show/hide menu mobile
		$('.site-header').on('click', '.navbar-toggle', function (e) {
			e.preventDefault();
			$('.primary-nav').slideToggle();
		});

		// Product thumbnails
		if ($('.product-details .product-content .thumbnails').find('a').length > 1) {
			$('.product-details .product-content .thumbnails').bxSlider({
				mode    : 'fade',
				pager   : false,
				nextText: '<i class="fa fa-angle-right"></i>',
				prevText: '<i class="fa fa-angle-left"></i>',
				speed   : 1000,
				auto    : true
			});
		}

		// Custom tabs
		$('.custom-tabs .vc_tta-tab').on('click', 'a', function () {

			$('.custom-tabs .vc_tta-tabs-list').find('.vc_tta-tab').removeClass('vc_active');
			$(this).parent().addClass('vc_active');
			var id = $(this).attr('href').replace('#', '');
			$('.custom-tabs .vc_tta-panels').find('.vc_tta-panel').removeClass('vc_active').hide();
			$('.custom-tabs .vc_tta-panels').find('#' + id).addClass('vc_active').show();

			return false;
		});

		// Product Reviews Tab
		$('.woocommerce .product-content #tab-reviews').hide();
		$('.woocommerce .product-content ul.tabs').on('click', 'a', function (e) {
			e.preventDefault();
			$(this).parents('.woocommerce-tabs').find('#tab-reviews').slideToggle();
		});

		// AFFIX
		if (!$('body').hasClass('header-left')) {
			$('.site-header').affix({
				offset: {
					top   : 100,
					bottom: function () {
						return (this.bottom = $('.site-footer').outerHeight(true));
					}
				}
			});
		}

		// Primary Nav Menu
		$('.primary-nav .menu-item-has-children').hover(function () {
			$(this).children('.mega-menu-container, .sub-menu').stop(true, true).delay(200).addClass('menu-item-hover');
		}, function () {
			$(this).children('.mega-menu-container, .sub-menu').stop(true, true).delay(200).removeClass('menu-item-hover');
		});

		// Fit Video
		$('.entry-header .format-video').fitVids({customSelector: 'iframe'});

		/**
		 * Join event popup
		 */
		var $modal = $( '#modal' );

		// Open product single modal
		$( 'body' ).on( 'click', '#btn-event-join, #btn-contact-teacher', function( e ) {
			e.preventDefault();

			$modal.fadeIn().addClass( 'in' );
			var modalHeight = $modal.find('.modal-content').height(),
				winHeight = $(window).height(),
				topModal = ( winHeight - modalHeight) / 2;

			$modal.find('.modal-content').css( { 'margin-top': topModal } );
			$('body').addClass( 'modal-open' );
		} );

		// Close portfolio modal
		$modal.on( 'click', 'button.close', function( e ) {
			e.preventDefault();

			$modal.fadeOut( 500, function() {
				$('body').removeClass( 'modal-open' );
				$modal.removeClass( 'in' );
			} );
		} );

		// Wow animation
		if (!$('body').hasClass('no-animation')) {
			var wow = new WOW({
				mobile: false,
				offset: 100
			});
			wow.init();
		}


		// Instance search
		if ( $().autocomplete ) {
			var searchCache = {},
				count = 0; // Cache the search results

			$.ui.autocomplete.prototype._renderItem = function( ul, item ) {
				count = item.count;
				return $( '<li class="woocommerce"></li>' )
					.append( '<a href="' + item.value + '">' + item.thumb + '<span class="product-title">' + item.label + '</span>' + '<span class="product-price">' + item.price + '</span></a>' )
					.appendTo( ul);

			};

			$( '#search-panel .search-field' ).autocomplete( {
				minLength: 1,
				source: function( request, response ) {
					var term = request.term,
						key  = term;

					var href = $( '#search-panel').find( '.instance-search').attr( 'action'),
						postType = $( '#search-panel').find( '.search-post-type').val();
					href = href + '?s=' + term + '&post_type=' +  postType;

					if ( key in searchCache ) {
						response( searchCache[key] );
						$( '.ui-widget-content').append( '<span class="search-results"><span>' + count + '</span>' + learnplus.search_results + '<strong>' + term + '</strong></span>' )
							.append( '<span class="all-results"><a href="' + href + '">' + learnplus.all_results + '<i class="fa fa-arrow-right"></i></a></span>' );
						return;
					}

					$.ajax( {
						url: learnplus.ajax_url,
						dataType: 'json',
						method: 'post',
						data: {
							action: 'search_products',
							lpnonce: learnplus.nonce,
							term: term,
							postType: postType
						},
						success: function( data ) {
							searchCache[key] = data.data;
							response( data.data );
							$( '.ui-widget-content').append( '<span class="search-results"><span>' + count + '</span>' + learnplus.search_results + '<strong>' + term + '</strong></span>' )
								.append( '<span class="all-results"><a href="' + href + '">' + learnplus.all_results + '<i class="fa fa-arrow-right"></i></a></span>' );
						}
					} );
				},
				select: function( event, ui ) {
					event.preventDefault();
					if ( ui.item.value != '#' ) {
						location.href = ui.item.value;
					}
				}
			} );
		}

		// Related products
		if ($('.related ul.products').find('li.product').length > 4) {
			$('.related ul.products').owlCarousel({
				loop      : true,
				margin    : 15,
				nav       : true,
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

		// Rate course
		$('body').on('click', '#respond span.stars a', function () {
			var $star = $(this),
					$rating = $(this).closest('#respond').find('#rating');

			$rating.val($star.text());
			$star.siblings('a').removeClass('active');
			$star.addClass('active');

			return false;
		});

		var nav = false;
		if( $('#owl-featured').find( '.owl-featured').length > 4 ) {
			nav = true;
		}
		$('#owl-featured').owlCarousel({
			loop      : true,
			margin    : 15,
			nav       : nav,
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

		nav = false;
		if( $('#owl-featured-2').find( '.owl-featured').length > 4 ) {
			nav = true;
		}
		$('#owl-featured-2').owlCarousel({
			loop      : true,
			margin    : 15,
			nav       : true,
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

		$( '#learndash_mark_complete_button' ).addClass( 'btn btn-primary btn-block' );
		$( '#sfwd-mark-complete input[type=submit]' ).addClass( 'btn btn-primary btn-block' );

		$(window).load(function () {
			$('.portfolio').isotope({
				itemSelector: '.item',
				layoutMode  : 'fitRows'
			});
			$('#filters a.selected').trigger('click');
		});

		$('#filters').on('click', 'a', function (e) {
			e.preventDefault();

			var selector = $(this).attr('data-option-value');
			$('.portfolio').isotope({filter: selector});

			$(this).parents('ul').find('a').removeClass('selected');
			$(this).addClass('selected');
		});
	});
})(jQuery);

