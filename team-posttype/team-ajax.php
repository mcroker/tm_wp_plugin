<?
if ( ! function_exists( 'tm_team_ajax_update' ) ):
  function tm_team_ajax_update() {
    // TODO : Really need to save the form before doing this
    tm_team_update_team_results($_POST['team_id']);
    $team = new TMTeam($_POST['team_id']);
    $data->fixtures = $team->fixtures;
    // echo json_encode($data,true);
    // wp_die();
  }
  add_action('wp_ajax_tm_team_ajax_update', 'tm_team_ajax_update' );
endif;
?>