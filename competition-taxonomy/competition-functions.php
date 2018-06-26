<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists('TM_LeagueTableEntry')):
  class TM_LeagueTableEntry {
    public $position;
    public $team;
    public $played;
    public $wins;
    public $draws;
    public $lost;
    public $pointsfor;
    public $pointsagainst;
    public $pointsdiff;
    public $trybonus;
    public $losingbonus;
    public $points;
  };
endif;
?>
