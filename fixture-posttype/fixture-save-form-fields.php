<?php
if ( ! function_exists( 'tm_fixture_save_postdata' ) ):
  function tm_fixture_save_postdata($post_id)
  {
    $post_type = get_post_type($post_id);
    if ( "tm_fixture" != $post_type ) return;

    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (! isset( $_POST['tm_fixture_nonce']) )
    return;

    if ( !wp_verify_nonce( $_POST['tm_fixture_nonce'], 'tm_fixture_field_nonce' ) )
    return;

    if ( isset($_POST['tm_fixture_useautofetch']) ){
      tm_fixture_update_useautofetch( true );
    } else {
      tm_fixture_update_useautofetch( false );
    };
    if ( isset($_POST['tm_fixture_team']) ){
      tm_fixture_update_team( $_POST['tm_fixture_team'] );
    }
    if ( isset($_POST['tm_fixture_competition']) ){
       tm_fixture_update_competition( $_POST['tm_fixture_competition'] );
    }
    if ( isset($_POST['tm_fixture_season']) ){
      tm_fixture_update_season( $_POST['tm_fixture_season'] );
    }
    if ( isset($_POST['tm_fixture_homeaway']) ){
      tm_fixture_update_homeaway( $_POST['tm_fixture_homeaway'] );
    }
    if ( isset($_POST['tm_fixture_date']) ){
      tm_fixture_update_date( $_POST['tm_fixture_date'] );
    }
    if ( isset($_POST['tm_fixture_scorefor']) ){
      tm_fixture_update_scorefor( $_POST['tm_fixture_scorefor'] );
    }
    if ( isset($_POST['tm_fixture_scoreagainst']) ){
      tm_fixture_update_scoreagainst( $_POST['tm_fixture_scoreagainst'] );
    }
  }
  add_action( 'save_post', 'tm_fixture_save_postdata' );
endif;
?>
