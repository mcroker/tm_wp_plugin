<?php
if ( ! function_exists('tm_fixture_table_head') ):
  function tm_fixture_table_head( $defaults ) {
    $defaults['tm_fixture_date'] = 'Fixture Date';
    $defaults['tm_fixture_team'] = 'Team';
    $defaults['tm_fixture_homeaway'] = 'Home/Away';
    $defaults['tm_fixture_scorefor'] = 'Score For';
    $defaults['tm_fixture_scoreagainst'] = 'Score Against';
    $defaults['tm_fixture_opposition'] = 'Opposition';
    $defaults['tm_fixture_competition'] = 'Competition';
    $defaults['tm_fixture_season'] = 'Season';
    $defaults['tm_fixture_createdbyautofetch'] = 'Sync?';
    return $defaults;
  }
  add_filter( 'manage_tm_fixture_posts_columns', 'tm_fixture_table_head');
endif;

if ( ! function_exists('tm_fixture_table_adminhead') ):
  function tm_fixture_table_adminhead() {
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_style( 'tm-fixture-adminpostlist-css', $plugin_url . 'fixture-adminpostlist.css', array(), 'v4.0.0');
  }
  add_action('admin_head', 'tm_fixture_table_adminhead');
endif;

if ( ! function_exists('tm_fixture_table_content') ):
  function tm_fixture_table_content( $column_name, $post_id ) {
    if ($column_name == 'tm_fixture_date') {
      echo date('Y-m-d', tm_fixture_get_date($post_id));
    }
    if ($column_name == 'tm_fixture_team') {
      echo tm_fixture_get_teamname($post_id);
    }
    if ($column_name == 'tm_fixture_homeaway') {
      $homeaway = tm_fixture_get_homeaway($post_id);
      switch($homeaway) {
        case 'H': echo 'Home'; break;
        case 'A': echo 'Away'; break;
        default: echo $homeaway;
      }
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
      $competition = tm_fixture_get_competition($post_id);
      if ( $competition ) {
        echo $competition->name;
      }
    }
    if ($column_name == 'tm_fixture_createdbyautofetch') {
      if (tm_fixture_get_createdbyautofetch($post_id) && tm_fixture_get_useautofetch($post_id) ) {
        echo 'Yes';
      }
    }
  }
  add_action( 'manage_tm_fixture_posts_custom_column', 'tm_fixture_table_content', 10, 2 );
endif;
?>
