<?php
function tm_test() {

  $term_id = 81;
  $autofetcher = tm_competition_get_autofetcher ( $term_id );

  // Only do something if the autofetcher is still registered
  if ( tm_autofetch_isvalidplugin($autofetcher) ) {
    $autofetcheropts = tm_competition_get_autofetcher_options ( $term_id );

    // Update
    $leaguetable = tm_autofetch_fetch_leaguetable($autofetcher, $autofetcheropts );
  }
 wp_send_json($leaguetable);
};

function tm_api_updateall() {
  tm_competition_update_all_competitions();
  tm_team_update_all_results();
  wp_send_json(null);
};

function tm_team_api_updateall() {
  tm_team_update_all_results();
  wp_send_json(null);
};

function tm_competition_api_updateall() {
  tm_competition_update_all_competitions();
  wp_send_json(null);
};

add_action( 'rest_api_init', function () {
  register_rest_route( 'tm/v1', '/test', array(
    'methods' => 'GET',
    'callback' => 'tm_test',
  ) );
  register_rest_route( 'tm/v1', '/updateall', array(
    'methods' => 'GET',
    'callback' => 'tm_api_updateall',
  ) );
  register_rest_route( 'tm/v1', '/team/updateall', array(
    'methods' => 'GET',
    'callback' => 'tm_team_api_updateall',
  ) );
  register_rest_route( 'tm/v1', '/competition/updateall', array(
    'methods' => 'GET',
    'callback' => 'tm_competition_api_updateall',
  ) );
} );
?>
