<?php
/**
* The template part for displaying results in search pages.
*
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* @package TM
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists( 'tm_fixturelist_widget_content' ) ):
  function tm_fixturelist_widget_content( $displaystyle = 'block' , $title = '', $team_id = '', $maxrows = 6, $maxfuture = 3, $oldestfirst = false ) {
    if ( empty($maxrows) ) $maxrows = 6;
    if ( empty($maxfuture) ) $maxfuture = 3;

    add_image_size( 'team-logo', 50, 50 );

    switch ( get_post_type() ) {
      case 'tm_team':
      if ( empty($team_id) ) {
        $team = new TMTeam( get_the_id() );
      } else {
        $team = new TMTeam( $team_id );
      }
      $userargs = Array('title' => $title);
      switch ( $displaystyle ) {
        case "block":
        $team->loop_fixtures_now_and_next('tm_fixturelist_' . $displaystyle, $maxrows, $maxfuture, $userargs, $oldestfirst );
        break;

        case "table":
        $team->loop_fixtures_by_season('tm_fixturelist_' . $displaystyle, $userargs, $oldestfirst);
      }
      break;

      case 'tm_fixture':
      $fixture = new TMFixture( get_the_id() );
      $userargs = Array('title' => $title);
      tm_fixturelist_block_header($fixture->team, $userargs );
      tm_fixturelist_block_row($fixture, $userargs);
      tm_fixturelist_block_footer($fixture->team, $userargs);
      break;

      default:
      if ( ! empty($team_id) ) {
        $team = new TMTeam($team_id);
        $userargs = Array('title' => $title);
        switch ( $displaystyle ) {
          case "block":
          $team->loop_fixtures_now_and_next('tm_fixturelist_' . $displaystyle, $maxrows, $maxfuture, $userargs, $oldestfirst );
          break;

          case "table":
          $team->loop_fixtures_by_season('tm_fixturelist_' . $displaystyle, $userargs, $oldestfirst );
        }
      }
    }
  }
endif;
?>
