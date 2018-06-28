<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function tm_test() {
};

function tm_api_updateall() {
  tm_autofetch_update_all_competitions();
  tm_autofetch_update_all_results();
  wp_send_json(null);
};

function tm_team_api_updateall() {
  tm_autofetch_update_all_results();
  wp_send_json(null);
};

function tm_competition_api_updateall() {
  tm_autofetch_update_all_competitions();
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
