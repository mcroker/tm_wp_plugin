<?php
if ( ! function_exists( 'tm_team_save_postdata' ) ):
  function tm_team_save_postdata( $post_id )
  {

    $post_type = get_post_type($post_id);
    if ( "tm_team" != $post_type ) return;

    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;

    $team = new TMTeam($post_id);

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (! isset( $_POST['tm_team_nonce']) )
    return;
    if ( !wp_verify_nonce( $_POST['tm_team_nonce'], 'tm_team_field_nonce' ) )
    return;

    if ( isset($_POST['tm_team_leagueteam']) ){
      $team->leagueteam = $_POST['tm_team_leagueteam'];
    }

    $team->useautofetch = ( isset($_POST['tm_team_useautofetch']) );

  }
  add_action( 'save_post', 'tm_team_save_postdata' );
endif;
?>
