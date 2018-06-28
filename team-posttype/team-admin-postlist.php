<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( is_admin() && ! function_exists( 'tm_team_table_head' ) ):
  function tm_team_table_head( $defaults ) {
    $defaults['title'] = 'Team';
    $defaults['tm_section'] = 'Section';
    $defaults['tm_competition'] = 'Competition';
    $defaults['tm_leagueteam'] = 'Competition_Teamname';
    $defaults['tm_team_useautofetch'] = 'Sync?';
    return $defaults;
  }
  add_filter( 'manage_tm_team_posts_columns', 'tm_team_table_head');
endif;

if ( is_admin() && ! function_exists( 'tm_team_table_content' ) ):
  function tm_team_table_content( $column_name, $post_id ) {
    $team = new TMTeam( $post_id );
    if ($column_name == 'tm_section') {
      $sections = $team->sections;
      $sectionnames = array_map(create_function('$competition', 'return $competition->name;') , $sections);
      echo esc_html(implode(',', $sectionnames ));
    }
    if ($column_name == 'tm_competition') {
      $competitions = $team->competitions;
      $competitionnames = array_map(create_function('$competition', 'return $competition->name;') , $competitions);
      echo esc_html(implode(',', $competitionnames ));
    }
    if ($column_name == 'tm_leagueteam') {
      echo esc_html($team->leagueteam);
    }
    if ($column_name == 'tm_team_useautofetch') {
      if ( $team->useautofetch == 1 ) {
        echo _("Yes", 'tm');
      }
    }
  }
  add_action( 'manage_tm_team_posts_custom_column', 'tm_team_table_content', 10, 2 );
endif;
?>
