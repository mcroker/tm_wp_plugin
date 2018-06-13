<?php
/**
* The template part for displaying results in search pages.
*
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* @package TWRFC
*/

if ( ! function_exists( 'tm_cmp_fixtures_by_date_desc' ) ):
function tm_cmp_fixtures_by_date_desc($a, $b)
{
  return ($a->fixtureDate < $b->fixtureDate);
}
endif;

if ( ! function_exists( 'tm_cmp_fixtures_by_date_asc' ) ):
function tm_cmp_fixtures_by_date_asc($a, $b)
{
  return ($a->fixtureDate > $b->fixtureDate);
}
endif;

if ( ! function_exists( 'tm_fixtures_widget_content_row' ) ):
function tm_fixtures_widget_content_row($fixture) {
  ?>
  <tr>
    <td>
      <?php // TODO: Add logos and opposition URL ?>
      <?php echo date('F d, Y', $fixture->fixtureDate) ?><br>
      <?php echo $fixture->teamname . ' v ' . $fixture->opposition ?><br>
      <?php echo $fixture->scorefor . ' v ' . $fixture->scoreagainst ?>
    </td>
  </tr>
  <?php
}
endif;

if ( ! function_exists( 'tm_fixtures_widget_content' ) ):
  function tm_fixtures_widget_content( $team_id = 0, $maxrows = 6, $maxfuture = 3 ) {
    // TODO: Need to all team id to be specified.
    $fixtures = tm_team_get_fixtures_objs( $team_id );
    if (sizeof($fixtures) > 0) {
      echo $displaytitle;

      ?>
      <table>
        <?php
        $rowsdisplayed = 0;
        $now = new DateTime('now');

        // Future fixtures
        uasort( $fixtures, 'tm_cmp_fixtures_by_date_asc');
        foreach($fixtures as $fixture) {
          if ( $rowsdisplayed < $maxfuture && $rowsdisplayed < $maxrows && $fixture->fixtureDate >= $now) {
            $rowsdisplayed += 1;
            tm_fixtures_widget_content_row($fixture);
          }
        }
        // Past results
        uasort( $fixtures, 'tm_cmp_fixtures_by_date_desc');
        foreach($fixtures as $fixture) {
          if ( $rowsdisplayed < $maxrows && $fixture->fixtureDate < $now) {
            $rowsdisplayed += 1;
            tm_fixtures_widget_content_row($fixture);
          }
        }
        ?>
      </table>
      <?php
    }
  }
endif;
?>
