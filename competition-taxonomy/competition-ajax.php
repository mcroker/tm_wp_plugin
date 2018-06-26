<?
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// tm_competition_ajax_update ==================================================
if ( is_admin() && ! function_exists( 'tm_competition_ajax_update' ) ):
  function tm_competition_ajax_update() {
    tm_competition_save_values($_POST['competition'], $_POST);
    $competition = new TMCompetition($_POST['competition']);
    tm_competition_update_all_competitions( Array ( $competition ) );
    $data->leaguetable = tm_competition_get_leaguetable( $competition->term_id );
    $data->teams = tm_competition_get_teams( $competition->term_id );
    echo json_encode($data,true);
    wp_die();
  }
  add_action('wp_ajax_tm_competition_ajax_update', 'tm_competition_ajax_update' );
endif;

// tm_competition_ajax_clearleaguedata ==================================================
if ( is_admin() && ! function_exists( 'tm_competition_ajax_clearleaguedata' ) ):
  function tm_competition_ajax_clearleaguedata() {
    $competition = new TMCompetition($_POST['competition']);
    tm_competition_update_leaguetable( $competition->term_id , '' );
    tm_competition_update_teams( $competition->term_id , '' );
    $data->leaguetable = tm_competition_get_leaguetable( $competition->term_id );
    $data->teams = tm_competition_get_teams( $competition->term_id );
    echo json_encode($data,true);
    wp_die();
  }
  add_action('wp_ajax_tm_competition_ajax_clearleaguedata', 'tm_competition_ajax_clearleaguedata' );
endif;

// tm_competition_ajax_getteams ==================================================
if ( is_admin() && ! function_exists( 'tm_competition_ajax_getteams' ) ):
  function tm_competition_ajax_getteams() {
    // TODO Remove current team from the list
    $data = new stdClass();
    $competition = new TMCompetition($_POST['competition']);
    $data->teams = tm_competition_get_teams( $competition->term_id );
    echo json_encode($data,true);
    wp_die();
  }
  add_action('wp_ajax_tm_competition_ajax_getteams', 'tm_competition_ajax_getteams' );
endif;
?>
