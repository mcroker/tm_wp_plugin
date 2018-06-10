<?php

  function tm_fixture_table_head( $defaults ) {
      $defaults['tm_fixture_date'] = 'Fixture Date';
      $defaults['tm_fixture_team'] = 'Team';
      $defaults['tm_fixture_scorefor'] = 'Score For';
      $defaults['tm_fixture_scoreagainst'] = 'Score Against';
      $defaults['tm_fixture_opposition'] = 'Opposition';
      $defaults['tm_fixture_season'] = 'Season';
      $defaults['tm_fixture_competition'] = 'Competition';
      $defaults['title'] = 'Opposition';
      return $defaults;
  }

 function tm_fixture_table_content( $column_name, $post_id ) {
     if ($column_name == 'tm_fixture_date') {
       echo tm_get_fixture_date($post_id);
     }
     if ($column_name == 'tm_fixture_team') {
       echo tm_get_fixture_teamname($post_id);
     }
     if ($column_name == 'tm_fixture_scorefor') {
       echo tm_get_fixture_scorefor($post_id);
     }
     if ($column_name == 'tm_fixture_scoreagainst') {
       echo tm_get_fixture_scoreagainst($post_id);
     }
     if ($column_name == 'tm_fixture_opposition') {
       echo tm_get_fixture_opposition($post_id);
     }
     if ($column_name == 'tm_fixture_season') {
       echo tm_get_fixture_season($post_id);
     }
     if ($column_name == 'tm_fixture_competition') {
       echo tm_get_fixture_competition($post_id);
     }
 }
?>
