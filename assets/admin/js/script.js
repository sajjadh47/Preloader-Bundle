jQuery( document ).ready( function( $ )
{
	var close_preloader = $( '.close_preloader' );
	
	var seconds_to_close_the_preloader = $( '.seconds_to_close_the_preloader' );

	seconds_to_close_the_preloader.hide();

	if ( close_preloader.find( 'select' ).val() === '1' )
	{
		seconds_to_close_the_preloader.show();
	}

	close_preloader.find( 'select' ).change( function ( e )
	{
		if ( $( this ).val() == '1' )
		{
			seconds_to_close_the_preloader.show();
		}
		else
		{
			seconds_to_close_the_preloader.hide();
		}
	});
} );