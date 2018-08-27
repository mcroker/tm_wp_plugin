<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once('TMBasePost.php');
require_once('TMFixture.php');
require_once('TMSection.php');
require_once('TMCompetition.php');

if ( ! class_exists('TMTeam')):
  class TMTeam extends TMBasePost {
    protected static $post_type = 'tm_team';

    protected static $meta_keys = Array(
      'leagueteam' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_team_leagueteam'
      ),
      'useautofetch' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_team_useautofetch'
      ),
      'coachestext' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_team_coachestext'
      ),
      'playerstext' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_team_playerstext'
      ),
      'competitions' => Array(
        'type'      => 'related_tax',
        'classname' => 'TMCompetition',
        'single'    => false
      ),
      'sections' => Array(
        'type'      => 'related_tax',
        'classname' => 'TMSection',
        'single'    => false
      ),
      'fixtures' => Array(
        'type'      => 'related_posts',
        'classname' => 'TMFixture',
        'meta_key'  => 'tm_fixture_team'
      ),
      'mailshortcode' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_team_mailshortcode'
      ),
      'newsshortcode' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_team_newsshortcode'
      ),
    );

    public static function sort_by_title_asc($a, $b) {
      return ( $a->title > $b->title );
    }

    function __construct($teamid = 0) {
      parent::__construct($teamid);
    }

    public function loop_fixtures_now_and_next($callbackstem, $maxrows = null, $maxfuture = null, $userargs = [], $oldestfirst = false ) {
      if ( empty($maxrows) ) $maxrows = 6;
      if ( empty($maxfuture) ) $maxfuture = 3;

      if ( function_exists($callbackstem . '_header') ) {
        call_user_func($callbackstem . '_header', $this->ID, $userargs);
      }
      $rowsdisplayed = 0;
      $nowtimestamp = time();
      $displayfixtures = [];
      $fixtures = $this->fixtures;
      // Next $maxfuture Future fixtures
      uasort( $fixtures, array('TMFixture','sort_by_date_asc'));
      foreach($fixtures as $fixture) {
        if ( $rowsdisplayed < $maxfuture && $rowsdisplayed < $maxrows && $fixture->kickofftime->getTimestamp() >= $nowtimestamp) {
          $rowsdisplayed += 1;
          $displayfixtures[] = $fixture;
        }
      }
      // Past $maxrows-$maxfuture results
      uasort( $fixtures, array('TMFixture','sort_by_date_desc'));
      foreach($fixtures as $fixture) {
        if ( $rowsdisplayed < $maxrows && $fixture->kickofftime->getTimestamp() < $nowtimestamp) {
          $rowsdisplayed += 1;
          $displayfixtures[] = $fixture;
        }
      }
      // Display Fixtures
      if ( $oldestfirst ) {
        uasort( $displayfixtures, array('TMFixture','sort_by_date_asc'));
      } else {
        uasort( $displayfixtures, array('TMFixture','sort_by_date_desc'));
      }
      foreach($displayfixtures as $fixture) {
        if ( function_exists($callbackstem . '_row') ) {
          call_user_func($callbackstem . '_row', $fixture, $userargs);
        }
      }

      if ( function_exists($callbackstem . '_footer') ) {
        call_user_func($callbackstem . '_footer', $this->ID, $userargs);
      }
    }

    public function loop_fixtures_by_season($callbackstem, $userargs = [], $oldestfirst = false ) {
      // Populate array of fixture $seasons
      $seasons = [];
      $seasonfixtures = [];
      foreach($this->fixtures as $fixture) {
        $seasonid = $fixture->season->ID;
        if( !array_key_exists($seasonid, $seasons) ) {
          $seasons[$seasonid] = $fixture->season;
          $seasonfixtures[$seasonid] = [];
        }
        $seasonfixtures[$seasonid][] = $fixture;
      }

      // Commence itteration
      uasort($seasons, array('TMBaseTax','sort_by_slug_desc'));
      reset($seasons);
      $defaultseason = key($seasons);

      if ( function_exists($callbackstem . '_header') ) {
        call_user_func($callbackstem . '_header', $this->ID, $seasons, $defaultseason, $userargs);
      }

      foreach($seasons as $seasonid => $season) {
        $isdefaultseason = ( $seasonid == $defaultseason );
        if ( function_exists($callbackstem . '_seasonheader') ) {
          call_user_func($callbackstem . '_seasonheader', $this->ID, $season, $isdefaultseason, $userargs);
        }

        if ( $oldestfirst ) {
          uasort($seasonfixtures[$seasonid], array('TMFixture','sort_by_date_desc'));
        } else {
          uasort($seasonfixtures[$seasonid], array('TMFixture','sort_by_date_asc'));
        }
        foreach($seasonfixtures[$seasonid] as $fixturekey => $fixture) {
          if ( function_exists($callbackstem . '_row') ) {
            call_user_func($callbackstem . '_row', $this->ID, $fixture, $userargs);
          }
        }

        if ( function_exists($callbackstem . '_seasonfooter') ) {
          call_user_func($callbackstem . '_seasonfooter',  $this->ID, $season, $isdefaultseason, $userargs);
        }
      }

      if ( function_exists($callbackstem . '_footer') ) {
        call_user_func($callbackstem . '_footer',  $this->ID, $seasons, $defaultseason, $userargs);
      }

    }

    public function fixtures_vevents() {
      foreach($this->fixtures as $fixture) {
        $fixture->vevent();
      }
    }

  }
endif;
?>
