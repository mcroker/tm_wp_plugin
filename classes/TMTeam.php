<?php
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
    );

    function __construct($teamid = 0) {
      parent::__construct($teamid);
    }

  }
endif;
?>
