<?php
  function tm_team_table_head( $defaults ) {
      $defaults['title'] = 'Team';
      $defaults['tm_section'] = 'Section';
      return $defaults;
  }

 function tm_team_table_content( $column_name, $post_id ) {
   if ($column_name == 'tm_section') {
     return tm_get_team_section( $post_id );
   }
 }
?>
