<?
if ( ! function_exists( 'tm_competition_ajax_update' ) ):
  function tm_competition_ajax_update() {
    // TODO : Really need to save the form before doing this
    tm_competition_save_values($_POST['competition'], $_POST);
    $competition = tm_competiton_get_byid($_POST['competition']);
    $response = tm_competition_exec_update_all_competitions( Array ( $competition ) );
    $data->seasons = tm_competition_get_leaguetable( $competition->term_id );
    $data->teams = tm_competition_get_teams( $competition->term_id );
    echo json_encode($data,true);
    wp_die();
  }
  add_action('wp_ajax_tm_competition_ajax_update', 'tm_competition_ajax_update' );
endif;

if ( ! function_exists( 'tm_competition_ajax_clearleaguedata' ) ):
  function tm_competition_ajax_clearleaguedata() {
    $competition = tm_competiton_get_byid($_POST['competition']);
    tm_competition_update_leaguetable( $competition->term_id , '' );
    tm_competition_update_teams( $competition->term_id , '' );
    $data->seasons = tm_competition_get_leaguetable( $competition->term_id );
    $data->teams = tm_competition_get_teams( $competition->term_id );
    echo json_encode($data,true);
    wp_die();
  }
  add_action('wp_ajax_tm_competition_ajax_clearleaguedata', 'tm_competition_ajax_clearleaguedata' );
endif;

if ( ! function_exists( 'tm_competition_ajax_getteams' ) ):
  function tm_competition_ajax_getteams() {
    $competition = tm_competiton_get_byid($_POST['competition']);
    $data->teams = tm_competition_get_teams( $competition->term_id );
    echo json_encode($data,true);
    wp_die();
  }
  add_action('wp_ajax_tm_competition_ajax_getteams', 'tm_competition_ajax_getteams' );
endif;
?>
