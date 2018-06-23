<?php
/**
* The template part for displaying results in search pages.
*
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* @package TM
*/

class TM_FixtureList_Fixtures {
  public $comp;
  public $sortkey;
  public $tableentries;
}

if ( ! function_exists( 'tm_fixturelist_cmp_competitions_by_sortkey_desc' ) ):
  function tm_fixturelist_cmp_competitions_by_sortkey_desc($a, $b)
  {
    return ($a->sortkey < $b->sortkey);
  }
endif;

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

if ( ! function_exists( 'tm_fixturelist_cmp_tax_by_slug' ) ):
  function tm_fixturelist_cmp_tax_by_slug($a, $b)
  {
    return ($a->slug > $b->slug);
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


      switch ( $displaystyle ) {
        case "block":
        if ( function_exists('tm_fixturelist_' . $displaystyle . '_header') ) {
          call_user_func('tm_fixturelist_' . $displaystyle . '_header', $team_id, $title, $fixtures);
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
          call_user_func('tm_fixturelist_' . $displaystyle . '_footer', $team_id, $title, $fixtures);
        }
        break;

        default: {
          $competitions = Array();
          foreach ($fixtures as $fixture) {
            if ( !array_key_exists($fixture->competition->slug, $competitions) ) {
              $compobj = new TM_Leaguetable_Fixtures();
              $compobj->comp = tm_competition_get_byid($fixture->competition);
              $compobj->sortkey = tm_competition_get_sortkey($fixture->competition->term_id);
              $compobj->fixtures = [];
              $competitions[$fixture->competition->slug] = $compobj;
            }
            $competitions[$fixture->competition->slug]->fixtures[] = $fixture;
          }
          uasort($competitions, 'tm_fixturelist_cmp_competitions_by_sortkey_desc');
          $defaultcompetitionslug = reset($competitions)->comp->slug;
          if ( function_exists('tm_fixturelist_' . $displaystyle . '_header') ) {
            call_user_func('tm_fixturelist_' . $displaystyle . '_header', $team_id, $title, $fixtures, $competitions, $defaultcompetitionslug);
          }
          foreach($competitions as $compslug => $competition) {
            $isdefaultcompetition = ( $compslug == $defaultcompetitionslug );
            if ( function_exists('tm_fixturelist_' . $displaystyle . '_competitionheader') ) {
              call_user_func('tm_fixturelist_' . $displaystyle . '_competitionheader', $team_id, $competition->comp, $isdefaultcompetition);
            }
            uasort( $competition->fixtures, 'tm_fixturelist_cmp_fixtures_by_date_desc');
            foreach ($competition->fixtures as $fixture) {
              if ( function_exists('tm_fixturelist_' . $displaystyle . '_row') ) {
                call_user_func('tm_fixturelist_' . $displaystyle . '_row', $team_id, $fixture);
              }
            }
            if ( function_exists('tm_fixturelist_' . $displaystyle . '_competitionfooter') ) {
              call_user_func('tm_fixturelist_' . $displaystyle . '_competitionfooter', $team_id, $competition->comp, $isdefaultcompetition);
            }
          }
        }
        if ( function_exists('tm_fixturelist_' . $displaystyle . '_footer') ) {
          call_user_func('tm_fixturelist_' . $displaystyle . '_footer', $team_id, $title, $fixtures, $competitions, $defaultcompetitionslug);
        }
      }

    }
  }
endif;
?>
