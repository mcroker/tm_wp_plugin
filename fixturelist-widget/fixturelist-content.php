<?php
/**
* The template part for displaying results in search pages.
*
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* @package TM
*/

if ( ! function_exists( 'tm_fixturelist_cmp_fixtures_by_date_desc' ) ):
  function tm_fixturelist_cmp_fixtures_by_date_desc($a, $b)
  {
    return ($a->fixturedate < $b->fixturedate);
  }
endif;

if ( ! function_exists( 'tm_fixturelist_cmp_fixtures_by_date_asc' ) ):
  function tm_fixturelist_cmp_fixtures_by_date_asc($a, $b)
  {
    return ($a->fixturedate > $b->fixturedate);
  }
endif;

// Main ==================================================
if ( ! function_exists( 'tm_fixturelist_widget_content' ) ):
  function tm_fixturelist_widget_content( $displaystyle = 'block' , $title = '', $team_id = '', $maxrows = 6, $maxfuture = 3 ) {
    if ( empty($maxrows) ) $maxrows = 6;
    if ( empty($maxfuture) ) $maxfuture = 3;

    add_image_size( 'team-logo', 50, 50 );

    if ( empty($team_id) ) {
      switch ( get_post_type() ) {
        case 'tm_team':
        $fixtures = tm_team_get_fixtures_objs( get_the_id() );
        $team_id = get_the_id();
        break;

        case 'tm_fixture':
        $fixtures = Array ( tm_fixture_getobj( get_the_id() ) );
        $team_id = tm_fixture_get_team( $fixtures->ID )->ID;
        break;

        default:
        $fixtures = Array();
      }
    } else {
      $fixtures = tm_team_get_fixtures_objs( $team_id );
    }

    if (sizeof($fixtures) > 0) {
      echo $title;

      if ( function_exists('tm_fixturelist_' . $displaystyle . '_header') ) {
        call_user_func('tm_fixturelist_' . $displaystyle . '_header', $team_id, $title);
      }

      $rowsdisplayed = 0;
      $now = new DateTime('now');

      // Future fixtures
      uasort( $fixtures, 'tm_fixturelist_cmp_fixtures_by_date_asc');
      foreach($fixtures as $fixture) {
        if ( $rowsdisplayed < $maxfuture && $rowsdisplayed < $maxrows && $fixture->fixturedate >= $now->getTimestamp()) {
          $rowsdisplayed += 1;
          if ( function_exists('tm_fixturelist_' . $displaystyle . '_row') ) {
            call_user_func('tm_fixturelist_' . $displaystyle . '_row', $team_id, $fixture);
          }
        }
      }
      // Past results
      uasort( $fixtures, 'tm_fixturelist_cmp_fixtures_by_date_desc');
      foreach($fixtures as $fixture) {
        if ( $rowsdisplayed < $maxrows && $fixture->fixturedate < $now->getTimestamp()) {
          $rowsdisplayed += 1;
          if ( function_exists('tm_fixturelist_' . $displaystyle . '_row') ) {
            call_user_func('tm_fixturelist_' . $displaystyle . '_row', $team_id, $fixture);
          }
        }
      }

      if ( function_exists('tm_fixturelist_' . $displaystyle . '_footer') ) {
        call_user_func('tm_fixturelist_' . $displaystyle . '_footer', $team_id);
      }
    }
  }
endif;
?>
