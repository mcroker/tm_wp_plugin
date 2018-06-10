<?php

require_once('extract_rfu_results.php');

class ExportConfig {
  public $team_name;
  public $competition;

  function __construct($team_name, $competition) {
    $this->team_name = $team_name;
    $this->competition = $competition;
  }

}

function tm_upsert_rfu_results($seasons) {
  $teamsposts = get_posts(array (
    'numberposts'	=> -1,
    'post_type'		=> 'tm_team',
    'post_status' => 'publish',
    'title'       => $result->opposition,
    'meta_query'	=> array (
      array(
        'key'	 	  => 'tm_team_rfucompetition',
        'compare' => 'EXISTS'
       )
     )
  ));
  foreach($teamsposts as $teampost) {
    $competition = get_post_meta( $teampost->ID, 'tm_team_rfucompetition', true);
    $teams[$teampost->ID] = new ExportConfig ( $teampost->post_title , $competition );
  }

  foreach($teams as $team_pageid => $exportconfig) {
    echo '<h2>' . $exportconfig->team_name . '</h2>';
    if ( $seasons == '' ) {
      $rfudata = tm_extract_rfu_results($exportconfig->competition, $exportconfig->team_name);
    } else {
      $rfudata = array();
      foreach (explode(',', $seasons) as $season) {
        $rfudata = array_merge( $rfudata , tm_extract_rfu_results_season($exportconfig->competition, $exportconfig->team_name, $season) );
      }
    }
    foreach($rfudata as $result) {
      echo '<p>"' . $exportconfig->team_name . '" v "' . $result->opposition . '"......';
      $fixtures = get_posts(array(
        'numberposts'	=> -1,
        'post_type'		=> 'tm_fixture',
        'post_status' => 'publish',
        'title'       => $result->opposition,
        'meta_query'	=> array(
          'relation'	=> 'AND',
          array(
            'key'	 	  => 'tm_fixture_team',
            'value'	  => $team_pageid,
            'compare' => '='
           ) ,
           array(
            'key'	  	=> 'tm_fixture_date',
            'value'	  => $result->fixturedate->format('Y-m-d'),
            'compare' => '='
           ),
         )
      ));

      // Do any updates
      wp_insert_term( $result->season, 'tm_season', $args = array() );
      wp_insert_term( $result->opposition, 'tm_opposition', $args = array() );
      wp_insert_term( $exportconfig->competition, 'tm_competition', $args = array() );

      // Create if not existing
      if ( sizeof( $fixtures ) == 0 ) {
        $newfixture = wp_insert_post ( array(
          'post_title' => $result->opposition,
          'post_status' => 'publish',
          'post_type' => 'tm_fixture'
        ) );
        $fixtures[] = get_post ($newfixture );
        echo 'create (' . $newfixture . ")....";
      }
      // Loop through posts updating data & netadata
      foreach ($fixtures as $fixture) {
        wp_insert_post (  array(
          'ID' => $fixture->ID,
          'post_title' => $result->opposition,
          'post_status' => 'publish',
          'post_type' => 'tm_fixture'
        ) );
        update_post_meta( $fixture->ID, 'tm_fixture_date', $result->fixturedate->format('Y-m-d') );
        update_post_meta( $fixture->ID, 'tm_fixture_team', $team_pageid  );
        if ( $result->scoreagainst != '' ) { update_post_meta( $fixture->ID, 'tm_fixture_scoreagainst', $result->scoreagainst ); }
        if ( $result->scorefor != '' ) { update_post_meta( $fixture->ID, 'tm_fixture_scorefor', $result->scorefor ); }
        wp_set_post_terms( $fixture->ID, $result->season, 'tm_season', true );
        wp_set_post_terms( $fixture->ID, $result->opposition, 'tm_opposition', true );
        wp_set_post_terms( $fixture->ID, $exportconfig->competition, 'tm_competition', true );
        echo 'update (' . $fixture->ID . ")</p>";
      }
    }
  }
}
?>
