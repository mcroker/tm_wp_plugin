<?php
/**
* The template part for displaying results in search pages.
*
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* @package TWRFC
*/

$fixtures = get_posts(array(
  'numberposts'	=> -1,
  'post_type'		=> 'tm_fixture',
  'post_status' => 'publish',
  'meta_key'	  => 'tm_fixture_team',
  'meta_value'	=> get_the_id()
));
if (sizeof($fixtures) > 0) {
    echo $displaytitle;
}
?>
<table>
  <?php
  // TODO: Apply $maxrows
  // TODO: Sort to show recent/next
  foreach($fixtures as $fixture) {
    $fixture_scorefor = tm_get_fixture_scorefor( $fixture->ID );
    $fixture_scoreagainst = tm_get_fixture_scoreagainst( $fixture->ID );
    $fixture_opposition = tm_get_fixture_opposition( $fixture->ID );
    $fixture_teamname = tm_get_fixture_teamname( $fixture->ID );
  ?>
    <tr>
      <td>
        <?php
        // TODO: Add logos and opposition URL
        echo $fixture_teamname . ' v ' . $fixture_opposition ?>
        <br>
        <?php echo $fixture_scorefor . ' v ' . $fixture_scoreagainst ?>
      </td>
    </tr>
  <?php } ?>
</table>
