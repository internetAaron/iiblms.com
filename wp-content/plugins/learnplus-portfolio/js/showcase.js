( function ( $ ) {
    'use strict';

    $( function() {
        $( '#portfolio-layout').on( 'change', function() {
            if( $( this).val() == 'metro' ) {
                $( '#ta-portfolio-view' ).slideDown();
            }
            else {
                $( '#ta-portfolio-view' ).slideUp();
            }

            if( $( this).val() == 'carousel' ) {
                $( '#ta-portfolio-pagination' ).slideUp();
            }
            else {
                $( '#ta-portfolio-pagination' ).slideDown();
            }
        } ).trigger( 'change' );
    } );
} )( jQuery );