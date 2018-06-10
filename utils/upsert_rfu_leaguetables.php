<?php

require_once('extract_rfu_leaguetable.php');

function tm_upsert_rfu_leaguetables($competition, $season) {

  if ($competition == '') {
    $competitions = tm_get_competitons();
  } else {
    $competitions = Array ( tm_get_competiton( $competition ) );
  }

  foreach ($competitions as $mycompetition) {
    $season = '2017-2018';
    echo '<h3>' . $mycompetition->name . '</h3>';
    echo '<h3>' . $season . '</h3>';
    $leaguetable = Array();
    echo '1';
    $leaguetable[$season] = tm_extract_rfu_leaguetable_season($mycompetition->name , $season);
    echo '2';
    echo '<pre>', htmlentities(print_r($leaguetable[$season], true)), '</pre>';
    tm_update_competition_leaguetable_data($mycompetition->ID , $leaguetable);
    echo '3';
  }

}

?>
