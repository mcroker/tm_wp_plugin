<?php


/* Adds a box to the main column on the Post and Page edit screens */
function tm_create_metadatabox_team() {
    add_meta_box(
        'tm_teammeta',
        'Team Metadata',
        'tm_team_inner_custom_box',
        'tm_team',
        'side',
        'high'
    );
}

/* Prints the box content */
function tm_team_inner_custom_box($post)
{
    // Use nonce for verification
    wp_nonce_field( 'tm_team_field_nonce', 'tm_team_nonce' );

    // Get saved value, if none exists, "default" is selected
    $saved_leagueurl = get_post_meta( $post->ID, 'tm_team_rfucompetition', true);
    printf(
      '<input type="text" name="tm_team_rfucompetition" value="%1$s" id="tm_team_rfucompetition"/>'.
      '<label for="tm_team_leagueurl">RFU Competition</label><br>',
      esc_attr($saved_leagueurl)
    );
}

/* When the post is saved, saves our custom data */
function tm_team_save_postdata( $post_id )
{
      // verify if this is an auto save routine.
      // If it is our form has not been submitted, so we dont want to do anything
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
          return;

      // verify this came from the our screen and with proper authorization,
      // because save_post can be triggered at other times
      if ( !wp_verify_nonce( $_POST['tm_team_nonce'], 'tm_team_field_nonce' ) )
          return;

      if ( isset($_POST['tm_team_rfucompetition']) && $_POST['tm_team_rfucompetition'] != "" ){
            update_post_meta( $post_id, 'tm_team_rfucompetition', $_POST['tm_team_rfucompetition'] );
      }
}
?>
