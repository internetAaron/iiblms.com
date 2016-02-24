jQuery(document).ready(function ($) {
	'use strict';

	$('#courses_display').on('change', function () {
		if ('grid' == $(this).val()) {
			$(this).closest('.field').next().slideDown();
		} else {
			$(this).closest('.field').next().slideUp();
		}
	}).trigger('change');
});
