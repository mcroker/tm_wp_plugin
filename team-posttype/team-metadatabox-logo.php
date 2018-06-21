<?php
add_action( 'add_meta_boxes', 'tm_team_logo_add_metabox' );
function tm_team_logo_add_metabox () {
	add_meta_box( 'teamlogodiv', __( 'Team Logo', 'tm' ), 'tm_team_logo_metabox', 'tm_team', 'side', 'default');
}
function tm_team_logo_metabox ( $post ) {
	global $content_width, $_wp_additional_image_sizes;
	$image_id = get_post_meta( $post->ID, 'tm_team_logo_id', true );
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
			$content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_tm_team_logo_button" >' . esc_html__( 'Remove listing image', 'tm' ) . '</a></p>';
			$content .= '<input type="hidden" id="upload_tm_team_logo" name="tm_team_logo" value="' . esc_attr( $image_id ) . '" />';
		}
		$content_width = $old_content_width;
	} else {
		$content = '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />';
		$content .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set listing image', 'tm' )
    . '" href="javascript:;" id="upload_tm_team_logo_button" id="set-teamlogo" data-uploader_title="'
    . esc_attr__( 'Choose an team logo', 'tm' )
    . '" data-uploader_button_text="'
    . esc_attr__( 'Set team logo', 'tm' ) . '">'
    . esc_html__( 'Set team logo', 'tm' ) . '</a></p>';
		$content .= '<input type="hidden" id="upload_tm_team_logo" name="tm_team_logo" value="" />';
	}
	echo $content;
}
add_action( 'save_post', 'tm_team_logo_save', 10, 1 );
function tm_team_logo_save ( $post_id ) {
	if( isset( $_POST['tm_team_logo'] ) ) {
		$image_id = (int) $_POST['tm_team_logo'];
		update_post_meta( $post_id, 'tm_team_logo_id', $image_id );
	}
}

if ( ! function_exists( 'tm_team_logo_enqueue_adminscripts' )):
  function tm_team_logo_enqueue_adminscripts($hook) {
    $plugin_url = plugin_dir_url(__FILE__);
		wp_enqueue_media();
    wp_enqueue_script( 'team-metabox-logo-js', $plugin_url . 'team-metadatabox-logo.js', array('jquery'), 'v4.0.0', true );
  }
  add_action( 'admin_enqueue_scripts', 'tm_team_logo_enqueue_adminscripts' );
endif;

?>
