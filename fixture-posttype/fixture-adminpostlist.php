<?php
if ( ! function_exists('tm_fixture_table_head') ):
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
  add_filter( 'manage_tm_fixture_posts_columns', 'tm_fixture_table_head');
endif;

if ( ! function_exists('tm_fixture_table_content') ):
  function tm_fixture_table_content( $column_name, $post_id ) {
    if ($column_name == 'tm_fixture_date') {
      echo date('Y-m-D', tm_fixture_get_date($post_id));
;
    }
    if ($column_name == 'tm_fixture_team') {
      echo tm_fixture_get_teamname($post_id);
    }
    if ($column_name == 'tm_fixture_scorefor') {
      echo tm_fixture_get_scorefor($post_id);
    }
    if ($column_name == 'tm_fixture_scoreagainst') {
      echo tm_fixture_get_scoreagainst($post_id);
    }
    if ($column_name == 'tm_fixture_opposition') {
      echo tm_fixture_get_opposition($post_id);
    }
    if ($column_name == 'tm_fixture_season') {
      echo tm_fixture_get_season($post_id)->name;
    }
    if ($column_name == 'tm_fixture_competition') {
      echo tm_fixture_get_competition($post_id)->name;
    }
  }
  add_action( 'manage_tm_fixture_posts_custom_column', 'tm_fixture_table_content', 10, 2 );
endif;
?>
