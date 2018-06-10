<?php


/* Adds a box to the main column on the Post and Page edit screens */
function tm_create_metadatabox_matchreport() {
  add_meta_box(
    'tm_matchreport',
    'Match Report',
    'tm_matchreport_inner_custom_box',
    'fixture',
    'normal',
    'default'
  );
}

/* Prints the box content */
function tm_matchreport_inner_custom_box($post)
{
  // Use nonce for verification
  wp_nonce_field( 'tm_matchreport_field_nonce', 'tm_matchreport_nonce' );

  // Get saved value, if none exists, "default" is selected
  $saved_matchreport = get_post_meta( $post->ID, 'tm_fixture_matchreport', true);
  wp_editor( $saved, "tm_fixture_matchreport");

}

/* When the post is saved, saves our custom data */
function tm_matchreport_save_postdata( $post_id )
{
  // verify if this is an auto save routine.
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
  return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times
  if ( !wp_verify_nonce( $_POST['tm_matchreport_nonce'], 'tm_matchreport_field_nonce' ) )
  return;

  if ( isset($_POST['tm_fixture_matchreport']) && $_POST['tm_fixture_matchreport'] != "" ){
    update_post_meta( $post_id, 'tm_fixture_matchreport', $_POST['tm_fixture_matchreport'] );
  }

}

?>
