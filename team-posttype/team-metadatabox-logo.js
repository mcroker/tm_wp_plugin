jQuery(document).ready(function($) {

	// Uploading files
	var file_frame;

	jQuery.fn.upload_tm_team_logo = function( button ) {
		var button_id = button.attr('id');
		var field_id = button_id.replace( '_button', '' );

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
		  file_frame.open();
		  return;
		}

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: jQuery( this ).data( 'uploader_title' ),
		  button: {
		    text: jQuery( this ).data( 'uploader_button_text' ),
		  },
		  multiple: false
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		  var attachment = file_frame.state().get('selection').first().toJSON();
		  jQuery("#"+field_id).val(attachment.id);
		  jQuery("#teamlogodiv img").attr('src',attachment.url);
		  jQuery( '#teamlogodiv img' ).show();
		  jQuery( '#' + button_id ).attr( 'id', 'remove_tm_team_logo_button' );
		  jQuery( '#remove_tm_team_logo_button' ).text( 'Remove listing image' );
		});

		// Finally, open the modal
		file_frame.open();
	};

	jQuery('#teamlogodiv').on( 'click', '#upload_tm_team_logo_button', function( event ) {
		event.preventDefault();
		jQuery.fn.upload_tm_team_logo( jQuery(this) );
	});

	jQuery('#teamlogodiv').on( 'click', '#remove_tm_team_logo_button', function( event ) {
		event.preventDefault();
		jQuery( '#upload_tm_team_logo' ).val( '' );
		jQuery( '#teamlogodiv img' ).attr( 'src', '' );
		jQuery( '#teamlogodiv img' ).hide();
		jQuery( this ).attr( 'id', 'upload_tm_team_logo_button' );
		jQuery( '#upload_tm_team_logo_button' ).text( 'Set listing image' );
	});

});
