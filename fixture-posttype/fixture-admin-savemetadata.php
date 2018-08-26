<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Save Post data ==================================================
if ( is_admin() && ! function_exists( 'tm_fixture_save_postdata' ) ):
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

    $fixture = new TMFixture($post_id);

    $fixture->useautofetch = isset($_POST['tm_fixture_useautofetch']);

    if ( isset($_POST['tm_fixture_team']) ){
      $fixture->team_id = $_POST['tm_fixture_team'];
    }
    if ( isset($_POST['tm_fixture_competition']) ){
      $fixture->attachTerm(new TMCompetition($_POST['tm_fixture_competition']));
    }
    if ( isset($_POST['tm_fixture_season']) ){
      $fixture->attachTerm(new TMSeason($_POST['tm_fixture_season']));
    }
    if ( isset($_POST['tm_fixture_leagueteam_select']) ){
      $fixture->attachTerm( TMOpposition::getBySlug($_POST['tm_fixture_leagueteam_select']));
    }
    if ( isset($_POST['tm_fixture_homeaway']) ){
      $fixture->homeaway = $_POST['tm_fixture_homeaway'];
    }
    if ( isset($_POST['tm_fixture_kickofftime']) ){
      $fixture->kickofftime = $_POST['tm_fixture_kickofftime'];
    }
    if ( isset($_POST['tm_fixture_scorefor']) ){
      $fixture->scorefor = $_POST['tm_fixture_scorefor'];
    }
    if ( isset($_POST['tm_fixture_scoreagainst']) ){
      $fixture->scoreagainst = $_POST['tm_fixture_scoreagainst'];
    }
  }
  add_action( 'save_post', 'tm_fixture_save_postdata' );
endif;
?>
