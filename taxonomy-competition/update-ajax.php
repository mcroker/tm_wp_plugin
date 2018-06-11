<?
// add_action('after-tm_competition-table','custom_export_button');

add_action('wp_ajax_tm_competition_ajax_update', 'tm_competition_ajax_update' );

if ( ! function_exists( 'tm_competition_ajax_update' ) ):
  function tm_competition_ajax_update() {
    $competition = tm_get_competiton_byid($_POST['competition']);
    $response = tm_competition_exec_update_all( Array ( $competition ) );
    if ( $response->status == 'OK' ) {
      echo $response->status;
    } else {
      echo $response->status . ': ' . $response->message;
    }
    wp_die();
  }
endif;

?>
