<?

// TODO : These classes need to be merged
if ( ! class_exists('TM_Fixture')):
class TM_Fixture {
  public $fixtureID;
  public $fixturedate;
  public $competition;
  public $teamname;
  public $homeaway; // 'H' or 'A'
  public $opposition;
  public $season;
  public $scorefor;
  public $scoreagainst;
  public $matchReport;
  public $url;
}
endif;

if ( ! function_exists( 'tm_fixture_getobj' ) ):
  function tm_fixture_getobj($fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    $fixtureobj = new TM_Fixture();
    $fixtureobj->fixtureID = $fixture_post_id;
    $fixtureobj->url= '/fixtures/' . tm_fixture_get_byid( $fixture_post_id )->post_name;
    $fixtureobj->fixturedate = tm_fixture_get_date( $fixture_post_id );
    $fixtureobj->teamname = tm_fixture_get_teamname( $fixture_post_id );
    $fixtureobj->homeaway = tm_fixture_get_homeaway( $fixture_post_id );
    $fixtureobj->scorefor = tm_fixture_get_scorefor( $fixture_post_id );
    $fixtureobj->scoreagainst = tm_fixture_get_scoreagainst( $fixture_post_id );
    $fixtureobj->opposition = tm_fixture_get_opposition( $fixture_post_id );
    $fixtureobj->season = tm_fixture_get_season( $fixture_post_id );
    $fixtureobj->competition = tm_fixture_get_competition( $fixture_post_id );
    $fixtureobj->matchReport = tm_fixture_get_matchreport( $fixture_post_id );
    return $fixtureobj;
  }
endif;

if ( ! function_exists( 'tm_fixture_get_byid' ) ):
  function tm_fixture_get_byid($fixtureid) {
    return get_post( $fixtureid , 'tm_fixture' );
  }
endif;

// Fixure Date ============================================================
if ( ! function_exists( 'tm_fixture_get_date' ) ):
  function tm_fixture_get_date($fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    $fixture_date = get_post_meta( $fixture_post_id, 'tm_fixture_date', true );
    if ( is_null($fixture_date) || $fixture_date == '') {
      return 0;
    } else {
      return strtotime($fixture_date);
    }
  }
endif;

if ( ! function_exists( 'tm_fixture_update_date' ) ):
  function tm_fixture_update_date($newdate, $fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    if ( is_string($newdate) ) {
      $newdate=strtotime($newdate);
    }
    return update_post_meta( $fixture_post_id, 'tm_fixture_date', date('Y-m-d', $newdate));
  }
endif;

// Fixure Teamname ============================================================
if ( ! function_exists( 'tm_fixture_get_team' ) ):
  function tm_fixture_get_team($fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    $team_id = get_post_meta( $fixture_post_id, 'tm_fixture_team', true );
    $team = get_post( $team_id );
    return $team;
  }
endif;

if ( ! function_exists( 'tm_fixture_get_teamname' ) ):
  function tm_fixture_get_teamname($fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    return tm_fixture_get_team($fixture_post_id)->post_title;
  }
endif;

if ( ! function_exists( 'tm_fixture_update_team' ) ):
  function tm_fixture_update_team($team_id, $fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    return update_post_meta( $fixture_post_id, 'tm_fixture_team', $team_id );
  }
endif;

// Fixure Scorefor ============================================================
if ( ! function_exists( 'tm_fixture_get_scorefor' ) ):
  function tm_fixture_get_scorefor($fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    $fixture_scorefor = get_post_meta( $fixture_post_id, 'tm_fixture_scorefor', true );
    return $fixture_scorefor;
  }
endif;

if ( ! function_exists( 'tm_fixture_update_scorefor' ) ):
  function tm_fixture_update_scorefor($fixture_scorefor, $fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    return update_post_meta( $fixture_post_id, 'tm_fixture_scorefor', $fixture_scorefor );
  }
endif;


// Fixure Scoreagainst ============================================================
if ( ! function_exists( 'tm_fixture_get_scoreagainst' ) ):
  function tm_fixture_get_scoreagainst($fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    $fixture_scoreagainst = get_post_meta( $fixture_post_id, 'tm_fixture_scoreagainst', true );
    return $fixture_scoreagainst;
  }
endif;

if ( ! function_exists( 'tm_fixture_update_scoreagainst' ) ):
  function tm_fixture_update_scoreagainst($fixture_scoreagainst, $fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    return update_post_meta( $fixture_post_id, 'tm_fixture_scoreagainst', $fixture_scoreagainst );
  }
endif;

// Fixure Home or Away ============================================================
if ( ! function_exists( 'tm_fixture_get_homeaway' ) ):
  function tm_fixture_get_homeaway($fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    $fixture_homeaway = get_post_meta( $fixture_post_id, 'tm_fixture_homeaway', true );
    return $fixture_homeaway;
  }
endif;

if ( ! function_exists( 'tm_fixture_update_homeaway' ) ):
  function tm_fixture_update_homeaway($fixture_homeaway, $fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    return update_post_meta( $fixture_post_id, 'tm_fixture_homeaway', $fixture_homeaway );
  }
endif;

// Fixure Opposition ============================================================
if ( ! function_exists( 'tm_fixture_get_opposition' ) ):
  function tm_fixture_get_opposition($fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    $fixture_terms = wp_get_post_terms( $fixture_post_id, 'tm_opposition');
    if (sizeof($fixture_terms) > 0) {
      return $fixture_terms[0]->name;
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_fixture_update_opposition_withslug' ) ):
  function tm_fixture_update_opposition_withslug($term_slug, $fixture_post_id = 0) {
    // Get term ID OR create term if it doesn't exist
    $oppositionterm = tm_opposition_get_byslug($term_slug);
    if ( ! $oppositionterm ) {
      $oppositionterm = tm_opposition_insert_term( $term_slug );
    }

    // Add the term to the fixture
    return tm_opposition_updateon_object($oppositionterm->term_id, $fixture_post_id);
  }
endif;

// Fixure Matchreport ============================================================
if ( ! function_exists( 'tm_fixture_get_matchreport' ) ):
  function tm_fixture_get_matchreport($fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    return get_post_meta( get_the_ID(), 'tm_fixture_matchreport', true );
  }
endif;

if ( ! function_exists( 'tm_fixture_update_matchreport' ) ):
  function tm_fixture_update_matchreport($match_report, $fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    return update_post_meta( $fixture_post_id, 'tm_fixture_matchreport', $match_report );
  }
endif;

// Fixture Season =========================================
if ( ! function_exists( 'tm_fixture_get_season' ) ):
  function tm_fixture_get_season($fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    return tm_season_getfrom_object($fixture_post_id);
  }
endif;

if ( ! function_exists( 'tm_fixture_update_season' ) ):
  function tm_fixture_update_season($term_id, $fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    return tm_season_updateon_object($term_id, $fixture_post_id);
  }
endif;

if ( ! function_exists( 'tm_fixture_update_season_withslug' ) ):
  function tm_fixture_update_season_withslug($term_slug, $fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    // Get term ID OR create term if it doesn't exist
    $seasonterm = tm_season_get_byslug($term_slug);
    if ( ! $seasonterm ) {
      $seasonterm = tm_season_insert_term( $term_slug );
    }
    // Add the term to the fixture
    return tm_season_updateon_object($seasonterm->term_id, $fixture_post_id);
  }
endif;

// Fixture Competition =========================================
if ( ! function_exists( 'tm_fixture_get_competition' ) ):
  function tm_fixture_get_competition( $term_id = 0 ) {
    if ( $term_id == 0) {
      $term_id = get_the_id();
    }
    $result = tm_competition_getfrom_object($term_id);
    if ( sizeof($result) >= 0) {
      return $result[0];
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_fixture_update_competition' ) ):
  function tm_fixture_update_competition( $term_id , $team_post_id = 0 ) {
    if ( $term_id == 0) {
      $term_id = get_the_id();
    }
    return tm_competition_updateon_object($term_id , $team_post_id);
  }
endif;

// Autofetch fixtures =========================================
if ( ! function_exists( 'tm_fixture_get_useautofetch' ) ):
  function tm_fixture_get_useautofetch( $term_id = 0 ) {
    if ( $term_id == 0 ) {
      $term_id = get_the_id();
    }
    return get_post_meta( $term_id, 'tm_fixture_useautofetch' , true );
  }
endif;

if ( ! function_exists( 'tm_fixture_update_useautofetch' ) ):
  function tm_fixture_update_useautofetch($data, $term_id = 0 ) {
    if ( $term_id == 0 ) {
      $term_id = get_the_id();
    }
    return update_post_meta( $term_id, 'tm_fixture_useautofetch' , $data );
  }
endif;

// Created by autofetch  =========================================
if ( ! function_exists( 'tm_fixture_get_createdbyautofetch' ) ):
  function tm_fixture_get_createdbyautofetch( $term_id = 0 ) {
    if ( $term_id == 0 ) {
      $term_id = get_the_id();
    }
    return get_post_meta( $term_id, 'tm_fixture_createdbyautofetch' , true );
  }
endif;

if ( ! function_exists( 'tm_fixture_update_createdbyautofetch' ) ):
  function tm_fixture_update_createdbyautofetch($data, $term_id = 0 ) {
    if ( $term_id == 0 ) {
      $term_id = get_the_id();
    }
    return update_post_meta( $term_id, 'tm_fixture_createdbyautofetch' , $data );
  }
endif;

?>
