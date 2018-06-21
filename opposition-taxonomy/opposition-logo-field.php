<?php
function tm_opposition_logo_field ( $term ) {
	global $content_width, $_wp_additional_image_sizes;
	if ( ! is_null($term) ) {
	  $image_id = tm_opposition_get_logo( $term->term_id );
	} else {
		$image_id = null;
	}
	$old_content_width = $content_width;
	$content_width = 254;
	if ( $image_id && get_post( $image_id ) ) {
		if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
			$thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
		} else {
			$thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
		}
		if ( ! empty( $thumbnail_html ) ) {
			$content = $thumbnail_html;
			$content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_tm_opposition_logo_button" >' . esc_html__( 'Remove listing image', 'tm' ) . '</a></p>';
			$content .= '<input type="hidden" id="upload_tm_opposition_logo" name="tm_opposition_logo" value="' . esc_attr( $image_id ) . '" />';
		}
		$content_width = $old_content_width;
	} else {
		$content = '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />';
		$content .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set listing image', 'tm' )
    . '" href="javascript:;" id="upload_tm_opposition_logo_button" id="set-oppositionlogo"'
		. 'data-uploader_title="' . esc_attr__( 'Choose an opposition logo', 'tm' )
    . '" data-uploader_button_text="' . esc_attr__( 'Set opposition logo', 'tm' ) . '">'
    . esc_html__( 'Set opposition logo', 'tm' ) . '</a></p>';
		$content .= '<input type="hidden" id="upload_tm_opposition_logo" name="tm_opposition_logo" value="" />';
	}
	echo '<div id="oppositionlogodiv">';
	echo $content;
	echo '</div>';
}

// Enqueue admin scripts ========================================
if ( ! function_exists( 'tm_opposition_enqueue_adminscripts' )):
  function tm_opposition_enqueue_adminscripts($hook) {
    $plugin_url = plugin_dir_url(__FILE__);
		wp_enqueue_media();
    wp_enqueue_script( 'opposition-logo-field-js', $plugin_url . 'opposition-logo-field.js', array('jquery'), 'v4.0.0', false );
  }
  add_action( 'admin_enqueue_scripts', 'tm_opposition_enqueue_adminscripts' );
endif;
?>
