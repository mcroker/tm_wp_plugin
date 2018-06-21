<?php
function tm_test() {

$data->upload_max_filesize = ini_get( 'upload_max_filesize' );
$data->post_max_size = ini_get( 'post_max_size' );

$data->u_bytes = wp_convert_hr_to_bytes( ini_get( 'upload_max_filesize' ) );
$data->p_bytes = wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) );

$data->wp_max_upload_size = wp_max_upload_size();

$data->display = sprintf( __( 'Maximum upload file size: %s.' ), esc_html( size_format( $data->wp_max_upload_size ) ) );

wp_send_json($data);

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
