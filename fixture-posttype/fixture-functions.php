<?

function tm_get_fixture_date($fixture_post_id) {
  $fixture_date = get_post_meta( $fixture_post_id, 'tm_fixture_date', true );
  return date( _x( 'F d, Y', 'Fixture date format', 'tm' ), strtotime( $fixture_date ) );
}

function tm_get_fixture_teamname($fixture_post_id) {
  $team_id = get_post_meta( $fixture_post_id, 'tm_fixture_team', true );
  $team = get_post( $team_id );
  return esc_html(htmlspecialchars_decode($team->post_title));
}

function tm_get_fixture_scorefor($fixture_post_id) {
  $fixture_scorefor = get_post_meta( $fixture_post_id, 'tm_fixture_scorefor', true );
  return esc_html(htmlspecialchars_decode($fixture_scorefor));
}

function tm_get_fixture_scoreagainst($fixture_post_id) {
  $fixture_scoreagainst = get_post_meta( $fixture_post_id, 'tm_fixture_scoreagainst', true );
  return esc_html(htmlspecialchars_decode($fixture_scoreagainst));
}

function tm_get_fixture_opposition($fixture_post_id) {
  $fixture_terms = wp_get_post_terms( $fixture_post_id, 'tm_opposition');
  if (sizeof($fixture_terms > 0)) {
    return esc_html(htmlspecialchars_decode($fixture_terms[0]->name));
  } else {
    return '';
  }
}

function tm_get_fixture_season($fixture_post_id) {
  $fixture_terms = wp_get_post_terms( $fixture_post_id, 'tm_season');
  if (sizeof($fixture_terms > 0)) {
    return esc_html(htmlspecialchars_decode($fixture_terms[0]->name));
  } else {
    return '';
  }
}

function tm_get_fixture_competition($fixture_post_id) {
  $fixture_terms = wp_get_post_terms( $fixture_post_id, 'tm_competition');
  if (sizeof($fixture_terms > 0)) {
    return esc_html(htmlspecialchars_decode($fixture_terms[0]->name));
  } else {
    return '';
  }
}

function tm_get_fixture_matchreport($fixture_post_id) {
  $match_report = htmlspecialchars_decode(get_post_meta( get_the_ID(), 'tm_fixture_matchreport', true ));
  return esc_html(htmlspecialchars_decode($match_report));
}

?>
