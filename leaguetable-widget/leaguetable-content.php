<?php
/**
* The template part for displaying results in search pages.
*
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* @package TWRFC
*/

if (empty($competition)) {
  $competition = tm_get_team_competition();
}
if (empty($season)) {
  // TODO: This should come from Global Settings/Dropdown
  $season = '2017-2018';
}
if (empty($team)) {
  $team = get_the_title();
}
if (!empty($competition)) {
    echo $displaytitle;
?>
<table>
  <thead>
    <td>Pos</td>
    <td>Team</td>
    <td>P</td>
    <td>W</td>
    <td>D</td>
    <td>L</td>
    <td>PF</td>
    <td>PA</td>
    <td>PD</td>
    <td>TBP</td>
    <td>LBP</td>
    <td>Pts</td>
  </thead>
  <?php
  $tableentries = tm_get_competition_leaguetable($competition->term_id, $season);
  foreach($tableentries as $tableentry) {
    if ( $tableentry->team == $team ) {
      $entryclass='tm-highlightedleagueentry';
    } else {
      $entryclass='tm-leagueentry';
    }
    ?>
    <tr class="<?php echo $entryclass ?>">
      <td><?php echo $tableentry->position ?></td>
      <td><?php echo $tableentry->team ?></td>
      <td><?php echo $tableentry->played ?></td>
      <td><?php echo $tableentry->wins ?></td>
      <td><?php echo $tableentry->draws ?></td>
      <td><?php echo $tableentry->lost ?></td>
      <td><?php echo $tableentry->pointsfor ?></td>
      <td><?php echo $tableentry->pointsagainst ?></td>
      <td><?php echo $tableentry->pointsdiff ?></td>
      <td><?php echo $tableentry->trybonus ?></td>
      <td><?php echo $tableentry->losingbonus ?></td>
      <td><?php echo $tableentry->points ?></td>
    </tr>
  <?php  } ?>
</table>
<?php
}
?>
