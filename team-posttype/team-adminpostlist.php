<?php
if ( ! function_exists( 'tm_team_table_head' ) ):
  function tm_team_table_head( $defaults ) {
    $defaults['title'] = 'Team';
    $defaults['tm_section'] = 'Section';
    $defaults['tm_competition'] = 'Competition';
    $defaults['tm_leagueteam'] = 'Competition_Teamname';
    return $defaults;
  }
  add_filter( 'manage_tm_team_posts_columns', 'tm_team_table_head');
endif;

if ( ! function_exists( 'tm_team_table_content' ) ):
  function tm_team_table_content( $column_name, $post_id ) {
    if ($column_name == 'tm_section') {
      $section = tm_team_get_section( $post_id );
      echo $section->name;
    }
    if ($column_name == 'tm_competition') {
      $competition = tm_team_get_competition( $post_id );
      echo $competition->name;
    }
    if ($column_name == 'tm_leagueteam') {
      $leagueteam = tm_team_get_leagueteam( $post_id );
      echo $leagueteam;
    }
  }
  add_action( 'manage_tm_team_posts_custom_column', 'tm_team_table_content', 10, 2 );
endif;
?>
