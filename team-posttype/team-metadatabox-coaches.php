<?php
/* Adds a box to the main column on the Post and Page edit screens */
if ( ! function_exists('tm_team_create_metadatabox_coaches')):
  function tm_team_create_metadatabox_coaches() {
    add_meta_box(
      'tm_coaches',
      'Coaches Tab',
      'tm_team_coaches_inner_custom_box',
      'tm_team',
      'normal',
      'default'
    );
  }
  add_action( 'add_meta_boxes', 'tm_team_create_metadatabox_coaches' );
endif;

/* Prints the box content */
if ( ! function_exists('tm_team_coaches_inner_custom_box')):
  function tm_team_coaches_inner_custom_box($post)
  {
    // Use nonce for verification
    wp_nonce_field( 'tm_coaches_field_nonce', 'tm_team_coaches_nonce' );

    // Get saved value, if none exists, "default" is selected
    $team = new TMTeam($post);
    wp_editor( $team->coachestext, "tm_team_coachestext");
  }
endif;

// ==================================================
/* When the post is saved, saves our custom data */
if ( ! function_exists('tm_team_coaches_save_postdata')):
  function tm_team_coaches_save_postdata( $post_id )
  {
    $post_type = get_post_type($post_id);
    if ( "tm_team" != $post_type ) return;

    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (! isset( $_POST['tm_team_coaches_nonce']) )
    return;
    if ( !wp_verify_nonce( $_POST['tm_team_coaches_nonce'], 'tm_coaches_field_nonce' ) )
    return;

    if ( isset($_POST['tm_team_coachestext']) ) {
      $team = new TMTeam($post_id);
      $team->coachestext = $_POST['tm_team_coachestext'];
    }
  }
  add_action( 'save_post', 'tm_team_coaches_save_postdata' );
endif;
?>
