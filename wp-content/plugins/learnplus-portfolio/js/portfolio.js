(function ($) {
    'use strict';

    $(function () {
        $('.learnplus-portfolio-shortcode').each(function () {
            var $portfolio = $(this),
                $items = $portfolio.find('.portfolio_project'),
                $filter = $portfolio.prev('.portfolio-filter'),
                layout = $portfolio.data('layout'),
                gutter = parseInt($portfolio.data('gutter')),
                options;


			if (gutter > 0) {
				$portfolio.css({marginLeft: -gutter / 2, marginRight: -gutter / 2});
				$items.css('padding', gutter / 2);
			}

			options = {
				transitionDuration: '0.8s',
				itemSelector: '.portfolio_project',
				layoutMode: layout == 'metro' ? 'masonry' : layout
			};

			if ('masonry' == layout || 'metro' == layout) {
				options.masonry = {
					columnWidth: '.portfolio-sizer'
				};
			}

			$portfolio.imagesLoaded(function () {
				if (gutter > 0 && 'metro' == layout) {
					var itemHeight = $items.first().height();

					$items.filter(function () {
						return !$(this).hasClass('portfolio-long');
					}).height(itemHeight);
					$items.filter('.portfolio-long').height(itemHeight * 2 + gutter);
					$portfolio.addClass('init-completed');
				}

				$portfolio.isotope(options);
			});

			if ($filter.length) {
				$filter.on('click', 'a', function (e) {
					e.preventDefault();

					var $this = $(this),
						filterValue = $this.data('filter');

					$filter.find('li').removeClass('active');
					$this.parent().addClass('active');
					$portfolio.isotope({filter: filterValue});
				});
			}

            $('.portfolio-showcase').find('.portfolio-pagination ').on('click', '.page-numbers.next', function (e) {
                e.preventDefault();

                $(this).addClass('loading');

                var portfolio_id = $(this).parents('.portfolio-showcase').find('.learnplus-portfolio-shortcode').attr('id'),
                    pagination_id = $(this).parents('.portfolio-showcase').find('.portfolio-pagination').attr('id');

                $.get(
                    $(this).attr('href'),
                    function (response) {
                        var content = $(response).find('.learnplus-portfolio-shortcode').html(),
                            $pagination = $(response).find('.portfolio-pagination').html();
                        var $content = $(content);

                        $(document.getElementById(pagination_id)).html($pagination);
                        var $portfolio_added = $($(document.getElementById(portfolio_id)));

                        $content.imagesLoaded(function () {
                            $portfolio_added.isotope('insert', $content);
                            $items = $portfolio_added.find('.portfolio_project');
                            if (gutter > 0) {
                                $items.css('padding', gutter / 2);
                            }

                            $('.venobox').venobox({
                                titleattr: 'data-title',
                                numeratio: true,
                                infinigall: true
                            });

                            $(document.getElementById(pagination_id)).find('.page-numbers.next').removeClass('loading');
                        });
                    }
                );
            });
        });

       if( $('.gallery-main-carousel').find( '.item').length > 1 ) {
           $('.gallery-main-carousel').owlCarousel({
               items: 1,
               navigation: true,
               navigationText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
               autoPlay: true,
           })
       }

        $('#related-works').owlCarousel({
            items: 3,
            navigation: false,
            pagination: true,
            autoPlay: false,
        });

        $('.venobox').venobox({
            titleattr: 'data-title',
            numeratio: true,
            infinigall: true
        });


    });
})(jQuery);