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
$tableentries = tm_get_competition_leaguetable($competition->term_id, $season);
if (!empty($tableentries)) {
    echo $displaytitle;
?>
<table>
  <thead class="xtm-table-header">
    <th class="tm-col-pri1">Pos</td>
    <th class="tm-col-pri1">Team</td>
    <th class="tm-col-pri3">P</td>
    <th class="tm-col-pri3">W</td>
    <th class="tm-col-pri3">D</td>
    <th class="tm-col-pri3">L</td>
    <th class="tm-col-pri5">PF</td>
    <th class="tm-col-pri5">PA</td>
    <th class="tm-col-pri4">PD</td>
    <th class="tm-col-pri4">TBP</td>
    <th class="tm-col-pri4">LBP</td>
    <th class="tm-col-alt4">BP</td>
    <th class="tm-col-pri2">Pts</td>
  </thead>
  <?php
  foreach($tableentries as $tableentry) {
    if ( $tableentry->team == $team ) {
      $entryclass='tm-highlightedleagueentry';
    } else {
      $entryclass='tm-leagueentry';
    }
    ?>
    <tr class="<?php echo $entryclass ?>">
      <td class="tm-col-pri1"><?php echo $tableentry->position ?></td>
      <td class="tm-col-pri1"><?php echo $tableentry->team ?></td>
      <td class="tm-col-pri3"><?php echo $tableentry->played ?></td>
      <td class="tm-col-pri3"><?php echo $tableentry->wins ?></td>
      <td class="tm-col-pri3"><?php echo $tableentry->draws ?></td>
      <td class="tm-col-pri3"><?php echo $tableentry->lost ?></td>
      <td class="tm-col-pri5"><?php echo $tableentry->pointsfor ?></td>
      <td class="tm-col-pri5"><?php echo $tableentry->pointsagainst ?></td>
      <td class="tm-col-pri4"><?php echo $tableentry->pointsdiff ?></td>
      <td class="tm-col-pri4"><?php echo $tableentry->trybonus ?></td>
      <td class="tm-col-pri4"><?php echo $tableentry->losingbonus ?></td>
      <td class="tm-col-alt4"><?php echo $tableentry->losingbonus + $tableentry->trybonus ?></td>
      <td class="tm-col-pri2"><?php echo $tableentry->points ?></td>
    </tr>
  <?php  } ?>
</table>
<?php
}
?>
