<?php
function tm_competition_api_updateall() {
  $data = tm_competition_exec_update_all();
  wp_send_json($data);
  // print_r($data);
};

add_action( 'rest_api_init', function () {
  register_rest_route( 'tm/v1', '/updateall', array(
    'methods' => 'GET',
    'callback' => 'tm_competition_api_updateall',
  ) );
} );
?>
