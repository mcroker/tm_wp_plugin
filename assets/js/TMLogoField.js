jQuery(document).ready(function($) {

console.log(tmphpobj);
	// Uploading files
	var file_frame;

	jQuery.fn.upload_logo = function( button ) {

		var button_id = button.attr('id');
    var basename = button_id.replace( '_logo_remove', '' ).replace( '_logo_upload', '' );
		// var field_id = button_id.replace( '_button', '' );

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
		  jQuery( '#' + tmphpobj.classname + '_logo' ).val( attachment.id );
		  jQuery( '#' + tmphpobj.classname  + '_logo_div img' ).attr( 'src', attachment.url );
		  jQuery( '#' + tmphpobj.classname  + '_logo_div img' ).show();
		  jQuery( '#' + button_id ).attr( 'id', basename  + '_logo_remove' );
		  jQuery( '#' + tmphpobj.classname  + '_logo_remove' ).text( 'Remove listing image' );
		});

		// Finally, open the modal
		file_frame.open();
	};

	jQuery( '#' + tmphpobj.classname + '_logo_div' ).on( 'click', '#' + tmphpobj.classname  + '_logo_upload', function( event ) {
		event.preventDefault();
		jQuery.fn.upload_logo( jQuery(this) );
	});
	jQuery( '#' + tmphpobj.classname + '_logo_div' ).on( 'click', '#' + tmphpobj.classname  + '_logo_remove', function( event ) {
		event.preventDefault();
		jQuery( '#' + tmphpobj.classname + '_logo' ).val( '' );
		jQuery( '#' + tmphpobj.classname  + '_logo_div img' ).attr( 'src', '' );
		jQuery( '#' + tmphpobj.classname  + '_logo_div img' ).hide();
		jQuery( this ).attr( 'id', tmphpobj.classname  + '_logo_upload' );
		jQuery( '#' + tmphpobj.classname  + '_logo_upload' ).text( 'Set listing image' );
	});

});
