<?

class TmFixtureObj {
  public $fixtureID;
  public $fixtureDate;
  public $fixtureDateText;
  public $teamname;
  public $scoreagainst;
  public $scorefor;
  public $season;
  public $opposition;
  public $competition;
  public $matchReport;
}

if ( ! function_exists( 'tm_get_fixture_obj' ) ):
  function tm_get_fixture_obj($fixture_post_id) {
    $fixtureobj = new TmFixtureObj();
    $fixtureobj->fixtureID = $fixture_post_id;
    $fixtureobj->fixtureDateText = tm_get_fixture_date( $fixture_post_id );
    $fixtureobj->fixtureDate = DateTime::createFromFormat('F d, Y', $fixtureobj->fixtureDateText );
    // TODO: $fixtureobj->teamID
    $fixtureobj->teamname = tm_get_fixture_teamname( $fixture_post_id );
    $fixtureobj->scorefor = tm_get_fixture_scorefor( $fixture_post_id );
    $fixtureobj->scoreagainst = tm_get_fixture_scoreagainst( $fixture_post_id );
    $fixtureobj->opposition = tm_get_fixture_opposition( $fixture_post_id );
    $fixtureobj->season = tm_get_fixture_season( $fixture_post_id );
    $fixtureobj->competition = tm_get_fixture_competition( $fixture_post_id );
    $fixtureobj->matchReport = tm_get_fixture_matchreport( $fixture_post_id );
    return $fixtureobj;
  }
endif;

if ( ! function_exists( 'tm_get_fixture_date' ) ):
  function tm_get_fixture_date($fixture_post_id) {
    $fixture_date = get_post_meta( $fixture_post_id, 'tm_fixture_date', true );
    return date( _x( 'F d, Y', 'Fixture date format', 'tm' ), strtotime( $fixture_date ) );
  }
endif;

if ( ! function_exists( 'tm_get_fixture_teamname' ) ):
  function tm_get_fixture_teamname($fixture_post_id) {
    $team_id = get_post_meta( $fixture_post_id, 'tm_fixture_team', true );
    $team = get_post( $team_id );
    return esc_html(htmlspecialchars_decode($team->post_title));
  }
endif;

if ( ! function_exists( 'tm_get_fixture_scorefor' ) ):
  function tm_get_fixture_scorefor($fixture_post_id) {
    $fixture_scorefor = get_post_meta( $fixture_post_id, 'tm_fixture_scorefor', true );
    return esc_html(htmlspecialchars_decode($fixture_scorefor));
  }
endif;

if ( ! function_exists( 'tm_get_fixture_scoreagainst' ) ):
  function tm_get_fixture_scoreagainst($fixture_post_id) {
    $fixture_scoreagainst = get_post_meta( $fixture_post_id, 'tm_fixture_scoreagainst', true );
    return esc_html(htmlspecialchars_decode($fixture_scoreagainst));
  }
endif;

if ( ! function_exists( 'tm_get_fixture_opposition' ) ):
  function tm_get_fixture_opposition($fixture_post_id) {
    $fixture_terms = wp_get_post_terms( $fixture_post_id, 'tm_opposition');
    if (sizeof($fixture_terms) > 0) {
      return esc_html(htmlspecialchars_decode($fixture_terms[0]->name));
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_get_fixture_season' ) ):
  function tm_get_fixture_season($fixture_post_id) {
    $fixture_terms = wp_get_post_terms( $fixture_post_id, 'tm_season');
    if (sizeof($fixture_terms) > 0) {
      return esc_html(htmlspecialchars_decode($fixture_terms[0]->name));
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_get_fixture_competition' ) ):
  function tm_get_fixture_competition($fixture_post_id) {
    $fixture_terms = wp_get_post_terms( $fixture_post_id, 'tm_competition');
    if (sizeof($fixture_terms) > 0) {
      return esc_html(htmlspecialchars_decode($fixture_terms[0]->name));
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_get_fixture_matchreport' ) ):
  function tm_get_fixture_matchreport($fixture_post_id) {
    $match_report = htmlspecialchars_decode(get_post_meta( get_the_ID(), 'tm_fixture_matchreport', true ));
    return esc_html(htmlspecialchars_decode($match_report));
  }
endif;

?>
