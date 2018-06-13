<?

// TODO : These classes need to be merged
if ( ! class_exists('TM_Fixture')):
class TM_Fixture {
  public $fixturedate;
  public $hometeam;
  public $hometeamlogo;
  public $awayteam;
  public $awayteamlogo;
  public $homescore;
  public $awayscore;
  public $opposition;
  public $season;
  public $scorefor;
  public $scoreagainst;
}
endif;

class TmFixtureObj {
  public $fixtureID;
  public $fixtureDate;
  public $teamname;
  public $scoreagainst;
  public $scorefor;
  public $season;
  public $opposition;
  public $competition;
  public $matchReport;
}

if ( ! function_exists( 'tm_fixture_getobj' ) ):
  function tm_fixture_getobj($fixture_post_id) {
    $fixtureobj = new TmFixtureObj();
    $fixtureobj->fixtureID = $fixture_post_id;
    $fixtureobj->fixtureDate = tm_fixture_get_date( $fixture_post_id );
    // TODO: $fixtureobj->teamID
    $fixtureobj->teamname = tm_fixture_get_teamname( $fixture_post_id );
    $fixtureobj->scorefor = tm_fixture_get_scorefor( $fixture_post_id );
    $fixtureobj->scoreagainst = tm_fixture_get_scoreagainst( $fixture_post_id );
    $fixtureobj->opposition = tm_fixture_get_opposition( $fixture_post_id );
    $fixtureobj->season = tm_fixture_get_season( $fixture_post_id );
    $fixtureobj->competition = tm_fixture_get_competition( $fixture_post_id );
    $fixtureobj->matchReport = tm_fixture_get_matchreport( $fixture_post_id );
    return $fixtureobj;
  }
endif;

// Fixure Date ============================================================
if ( ! function_exists( 'tm_fixture_get_date' ) ):
  function tm_fixture_get_date($fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    $fixture_date = get_post_meta( $fixture_post_id, 'tm_fixture_date', true );
    if ( ! is_date($fixture_date) ) {
      $fixture_date = strtotime( $fixture_date );
    }
    return $fixture_date;
  }
endif;

if ( ! function_exists( 'tm_fixture_update_date' ) ):
  function tm_fixture_update_date($newdate, $fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    if ( ! is_date($newdate) ) {
      $newdate_date = strtotime($newdate);
    }
    return update_post_meta( $fixture_post_id, 'tm_fixture_date', $newdate_date );
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
  function tm_fixture_update_teamname($team_id, $fixture_post_id = 0) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    return update_post_meta( $fixture_post_id, 'tm_fixture_team', $team_id );
  }
endif;

// Fixure Scorefor ============================================================
if ( ! function_exists( 'tm_fixture_get_scorefor' ) ):
  function tm_fixture_get_scorefor($fixture_post_id) {
    $fixture_scorefor = get_post_meta( $fixture_post_id, 'tm_fixture_scorefor', true );
    return esc_html(htmlspecialchars_decode($fixture_scorefor));
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
  function tm_fixture_get_scoreagainst($fixture_post_id) {
    $fixture_scoreagainst = get_post_meta( $fixture_post_id, 'tm_fixture_scoreagainst', true );
    return esc_html(htmlspecialchars_decode($fixture_scoreagainst));
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

// Fixure ionOpposit ============================================================
if ( ! function_exists( 'tm_fixture_get_opposition' ) ):
  function tm_fixture_get_opposition($fixture_post_id) {
    $fixture_terms = wp_get_post_terms( $fixture_post_id, 'tm_opposition');
    if (sizeof($fixture_terms) > 0) {
      return esc_html(htmlspecialchars_decode($fixture_terms[0]->name));
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_fixture_update_opposition_withslug' ) ):
  function tm_fixture_update_opposition_withslug($term_slug, $fixture_post_id = 0) {
    // Get term ID OR create term if it doesn't exist
    $oppositionterm = tm_opposition_get_byslug($term_slug);
    if ( is_null ( $oppositionterm) ) {
      $oppositionterm = tm_opposition_insert_term( $term_slug );
    }
    // Add the term to the fixture
    return tm_opposition_updateon_object($oppositionterm->term_id, $fixture_post_id);
  }
endif;

// Fixure Matchreport ============================================================
if ( ! function_exists( 'tm_fixture_get_matchreport' ) ):
  function tm_fixture_get_matchreport($fixture_post_id) {
    $match_report = htmlspecialchars_decode(get_post_meta( get_the_ID(), 'tm_fixture_matchreport', true ));
    return esc_html(htmlspecialchars_decode($match_report));
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
    return tm_season_getfrom_object($fixture_post_id);
  }
endif;

if ( ! function_exists( 'tm_fixture_update_season' ) ):
  function tm_fixture_update_season($term_id, $fixture_post_id = 0) {
    return tm_season_updateon_object($term_id, $fixture_post_id);
  }
endif;

if ( ! function_exists( 'tm_fixture_update_season_withslug' ) ):
  function tm_fixture_update_season_withslug($term_slug, $fixture_post_id = 0) {
    // Get term ID OR create term if it doesn't exist
    $seasonterm = tm_season_get_byslug($term_slug);
    if ( is_null ( $seasonterm) ) {
      $seasonterm = tm_season_insert_term( $term_slug );
    }
    // Add the term to the fixture
    return tm_season_updateon_object($seasonterm->term_id, $fixture_post_id);
  }
endif;

// Fixture Competition =========================================
if ( ! function_exists( 'tm_fixture_get_competition' ) ):
  function tm_fixture_get_competition( $team_post_id = 0 ) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    return tm_competition_getfrom_object($team_post_id);
  }
endif;

if ( ! function_exists( 'tm_fixture_update_competition' ) ):
  function tm_fixture_update_competition( $term_id , $team_post_id = 0 ) {
    if ( $fixture_post_id == 0) {
      $fixture_post_id = get_the_id();
    }
    return tm_competition_updateon_object($term_id , $team_post_id);
  }
endif;

?>
