jQuery( document ).ready( function( $ ) {
	"use strict";

	// Show/hide settings for post format when choose post format
	var $format = $( '#post-formats-select' ).find( 'input.post-format' ),
		$formatBox = $( '#post-format-settings' );

	$format.on( 'change', function() {
		var	type = $format.filter( ':checked' ).val();

		$formatBox.hide();
		if( $formatBox.find( '.rwmb-field' ).hasClass( type ) ) {
			$formatBox.show();
		}

		$formatBox.find( '.rwmb-field' ).slideUp();
		$formatBox.find( '.' + type ).slideDown();
	} );
	$format.filter( ':checked' ).trigger( 'change' );

	// Show/hide settings for custom layout settings
	$( '#custom_layout' ).on( 'change', function() {
		if( $( this ).is( ':checked' ) ) {
			$( '.rwmb-field.custom-layout' ).slideDown();
		}
		else {
			$( '.rwmb-field.custom-layout' ).slideUp();
		}
	} ).trigger( 'change' );

	// Show/hide settings for slider settings
	$( '#page_template' ).on( 'change', function() {
		if( $( this ).val() == 'template-instructors.php' ) {
			$( '#display-settings .number-teacher, #display-settings .lp-contact-form-7' ).slideDown();
		}
		else {
			$( '#display-settings .number-teacher, #display-settings .lp-contact-form-7' ).slideUp();
		}

		if( $( this ).val() == 'template-instructors.php' || $( this ).val() == 'template-full-width.php' ) {
			$( '#display-settings .heading-title, #display-settings .heading-layout' ).slideUp();
		}
		else {
			$( '#display-settings .heading-title, #display-settings .heading-layout' ).slideDown();
		}
	} ).trigger( 'change' );

} );
