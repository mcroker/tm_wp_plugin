<?php

 function tm_get_team_section( $team_post_id ) {
   $fixture_terms = wp_get_post_terms( $team_post_id, 'tm_section');
   if ( sizeof ($fixture_terms ) > 0 ) {
     return esc_html(htmlspecialchars_decode($fixture_terms[0]->name));
   } else {
     return '';
   }
 }

  function tm_get_team_competition( $team_post_id ) {
    $rfucomp = get_post_meta( $team_post_id->ID, 'tm_team_rfucompetition', true);
    return esc_html(htmlspecialchars_decode($rfucomp));
  }

?>
