<?
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// tm_competition_ajax_update ==================================================
if ( is_admin() && ! function_exists( 'tm_competition_ajax_update' ) ):
  function tm_competition_ajax_update() {
    tm_competition_save_values($_POST['competition'], $_POST);
    $competition = new TMCompetition($_POST['competition']);
    tm_autofetch_update_all_competitions( Array ( $competition ) );
    $data->leaguetable = $competition->leaguetable;
    $data->teams = $competition->teamdata;
    echo json_encode($data,true);
    wp_die();
  }
  add_action('wp_ajax_tm_competition_ajax_update', 'tm_competition_ajax_update' );
endif;

// tm_competition_ajax_clearleaguedata ==================================================
if ( is_admin() && ! function_exists( 'tm_competition_ajax_clearleaguedata' ) ):
  function tm_competition_ajax_clearleaguedata() {
    $competition = new TMCompetition($_POST['competition']);
    $competition->leaguetable = '';
    $competition->teamdata = '';
    $data->leaguetable = $competition->leaguetable;
    $data->teams = $competition->teamdata;
    echo json_encode($data,true);
    wp_die();
  }
  add_action('wp_ajax_tm_competition_ajax_clearleaguedata', 'tm_competition_ajax_clearleaguedata' );
endif;

// tm_competition_ajax_getteams ==================================================
if ( is_admin() && ! function_exists( 'tm_competition_ajax_getteams' ) ):
  function tm_competition_ajax_getteams() {
    $data = new stdClass();
    if ( $_POST['competition'] != '' ) {
      // TODO Remove current team from the list
     $competition = new TMCompetition($_POST['competition']);
     $data->teams = $competition->teamdata;
   } else {
     $data->teams = array_map(create_function('$opposition', 'return $opposition->name;') , TMOpposition::getAll());
   }
    echo json_encode($data,true);
    wp_die();
  }
  add_action('wp_ajax_tm_competition_ajax_getteams', 'tm_competition_ajax_getteams' );
endif;
?>
