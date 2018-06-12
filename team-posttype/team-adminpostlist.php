<?php
if ( ! function_exists( 'tm_team_table_head' ) ):
  function tm_team_table_head( $defaults ) {
    $defaults['title'] = 'Team';
    $defaults['tm_section'] = 'Section';
    $defaults['tm_competition'] = 'Competition';
    return $defaults;
  }
  add_filter( 'manage_tm_team_posts_columns', 'tm_team_table_head');
endif;

if ( ! function_exists( 'tm_team_table_content' ) ):
  function tm_team_table_content( $column_name, $post_id ) {
    if ($column_name == 'tm_section') {
      $section = tm_get_team_section( $post_id );
      echo $section->name;
    }
    if ($column_name == 'tm_competition') {
      $competition = tm_get_team_competition( $post_id );
      echo $competition->name;
    }
  }
  add_action( 'manage_tm_team_posts_custom_column', 'tm_team_table_content', 10, 2 );
endif;
?>
