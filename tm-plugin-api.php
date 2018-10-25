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

function tm_team_ical($request_data) {
  $parameters = $request_data->get_params();
  $team = TMTeam::getBySlug( $parameters['team'] );
  if ( $team ) {
    header('Content-type: text/calendar; charset=utf-8');
    header('Content-Disposition: inline; filename=twrfc-' . $team->slug . '-fixtures.ics');
    echo "BEGIN:VCALENDAR\n";
    echo "VERSION:2.0\n";
    echo "PRODID:-//tm/team/" . $team->slug . "\n";
    $team->fixtures_vevents();
    echo "END:VCALENDAR\n";
    exit();
  } else {
    wp_die('Team ' . $parameters['team']. ' not found');
  }
};

function tm_all_ical($request_data){
  header('Content-type: text/calendar; charset=utf-8');
  header('Content-Disposition: inline; filename=twrfc-fixtures.ics');
  echo "BEGIN:VCALENDAR\n";
  echo "VERSION:2.0\n";
  echo "PRODID:-//tm/team/all\n";
  foreach(TMTeam::getAll() as $team) {
    $team->fixtures_vevents();
  }
  echo "END:VCALENDAR\n";
  exit();
};

function tm_festival_generateschedule($request_data){
  $parameters = $request_data->get_params();
  $festival = TMFestival::getBySlug( $parameters['festival'] );
  $festival->generateschedule();
  wp_send_json(null);
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'tm/v1', '/test', array(
    'methods' => 'GET',
    'callback' => 'tm_test'
  ) );
  register_rest_route( 'tm/v1', '/updateall', array(
    'methods' => 'GET',
    'callback' => 'tm_api_updateall'
  ) );
  register_rest_route( 'tm/v1', '/team/updateall', array(
    'methods' => 'GET',
    'callback' => 'tm_team_api_updateall'
  ) );
  register_rest_route( 'tm/v1', '/competition/updateall', array(
    'methods' => 'GET',
    'callback' => 'tm_competition_api_updateall'
  ) );
  register_rest_route( 'tm/v1', '/ical/(?P<team>[a-zA-Z0-9-]+)', array(
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'tm_team_ical'
  ));
  register_rest_route( 'tm/v1', '/ical', array(
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'tm_all_ical'
  ));
  register_rest_route( 'tm/v1', '/festival/(?P<festival>[a-zA-Z0-9-]+)/generate', array(
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'tm_festival_generateschedule'
  ));
} );
?>
