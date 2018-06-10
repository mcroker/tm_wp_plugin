<?php
function tm_competition_api_updateall() {
  ob_start();
  tm_competition_exec_update_all();
  $o = ob_get_clean();
  return $o;
};

add_action( 'rest_api_init', function () {
  register_rest_route( 'tm/v1', '/updateall', array(
    'methods' => 'GET',
    'callback' => 'tm_competition_api_updateall',
  ) );
} );
?>
