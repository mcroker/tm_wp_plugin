<?php

require_once('extract_rfu_results.php');

class ExportConfig {
  public $team_name;
  public $sourceurl;

  function __construct($team_name, $competition) {
    $rfucompbase = 'http://www.englandrugby.com/fixtures-and-results/competitions/';
    $rfucompsuffix = '/#/results';
    $this->team_name = $team_name;
    $this->sourceurl = $rfucompbase . $competition . $rfucompsuffix;
  }
}

function upsert_rfu_results() {
  $taxonomies = get_taxonomies(array(
    'name' => 'media_category'
  ));
  foreach ( $taxonomies as $taxonomy ) {
      echo '<p>' . $taxonomy . '</p>';
  }
  $teamsposts = get_posts(array (
    'numberposts'	=> -1,
    'post_type'		=> 'team',
    'post_status' => 'publish',
    'title'       => $result->opposition,
    'meta_query'	=> array (
      array(
        'key'	 	  => 'team_leagueurl',
        'compare' => 'EXISTS'
       )
     )
  ));
  foreach($teamsposts as $teampost) {
    $leagueurl = get_post_meta( $teampost->ID, 'team_leagueurl', true);
    $teams[$teampost->ID] = new ExportConfig ( $teampost->post_title , $leagueurl );
  }

  foreach($teams as $team_pageid => $exportconfig) {
    foreach(extract_rfu_results($exportconfig->sourceurl, $exportconfig->team_name) as $result) {
      echo '<p>"' . $exportconfig->team_name . '" v "' . $result->opposition . '......';
      $fixtures = get_posts(array(
        'numberposts'	=> -1,
        'post_type'		=> 'fixture',
        'post_status' => 'publish',
        'title'       => $result->opposition,
        'meta_query'	=> array(
          'relation'	=> 'AND',
          array(
            'key'	 	  => 'fixture_team',
            'value'	  => $team_pageid,
            'compare' => '='
           ) ,
           array(
            'key'	  	=> 'fixture_date',
            'value'	  => $result->fixturedate->format('Y-m-d'),
            'compare' => '='
           ),
         )
      ));

      // Do any updates
      foreach ($fixtures as $fixture) {
        wp_insert_post (  array(
          'ID' => $fixture->ID,
          'post_title' => $result->opposition,
          'post_status' => 'publish',
          'post_type' => 'fixture'
        ) );
        update_post_meta( $fixture->ID, 'fixture_date', $result->fixturedate->format('Y-m-d') );
        if ( $result->scoreagainst != '' ) { update_post_meta( $fixture->ID, 'fixture_scoreagainst', $result->scoreagainst ); }
        if ( $result->scoreagainst != '' ) { update_post_meta( $fixture->ID, 'fixture_scorefor', $result->scorefor ); }
        update_post_meta( $fixture->ID, 'fixture_team', $team_pageid  );
        echo 'update (' . $fixture->ID . ")</p>";
      }
      if ( sizeof( $fixtures ) == 0 ) {
        $newpostid = wp_insert_post (  array(
          'post_title' => $result->opposition,
          'post_status' => 'publish',
          'post_type' => 'fixture'
        ) );
        update_post_meta( $newpostid, 'fixture_date', $result->fixturedate->format('Y-m-d') );
        if ( $result->scoreagainst != '' ) { update_post_meta( $newpostid, 'fixture_scoreagainst', $result->scoreagainst ); }
        if ( $result->scorefor != '' ) { update_post_meta( $newpostid, 'fixture_scorefor', $result->scorefor ); }
        echo 'new (' . $newpostid . ")</p>";
      }
    }
  }
}
?>
