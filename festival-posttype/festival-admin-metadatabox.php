<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* Adds a box to the main column on the Post and Page edit screens */
if ( is_admin() && ! function_exists('tm_festival_create_metadatabox')):
  function tm_festival_create_metadatabox() {
    add_meta_box(
      'tm_players',
      'Players Tab',
      'tm_festival_inner_custom_box',
      'tm_team',
      'normal',
      'default'
    );
  }
  add_action( 'add_meta_boxes', 'tm_festival_create_metadatabox' );
endif;

/* Prints the box content */
if ( ! function_exists('tm_festival_inner_custom_box')):
  function tm_festival_inner_custom_box($post)
  {
    // Use nonce for verification
    wp_nonce_field( 'tm_team_players_field_nonce', 'tm_team_players_nonce' );

    // Get saved value, if none exists, "default" is selected
    $team = new TMTeam($post);
    wp_editor( $team->playerstext, "tm_team_playerstext");
  }
endif;

// ==================================================
/* When the post is saved, saves our custom data */
if ( ! function_exists('tm_festival_save_metadatabox')):
  function tm_festival_save_metadatabox( $post_id )
  {
    $post_type = get_post_type($post_id);
    if ( "tm_team" != $post_type ) return;

    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (! isset( $_POST['tm_team_players_nonce']) )
    return;
    if ( !wp_verify_nonce( $_POST['tm_team_players_nonce'], 'tm_team_players_field_nonce' ) )
    return;

    if ( isset($_POST['tm_team_playerstext']) ) {
      $team = new TMTeam($post_id);
      $team->playerstext = $_POST['tm_team_playerstext'];
    }

  }
  add_action( 'save_post', 'tm_festival_save_metadatabox' );
endif;
?>
