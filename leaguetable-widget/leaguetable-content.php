<?php
/**
* @package TM
*/
if ( ! function_exists( 'tm_leaguetable_widget_content' ) ):
  function tm_leaguetable_widget_content($displaytitle, $competitionname, $seasons, $team) {

    if (empty($competition)) {
      $competition = tm_get_team_competition();
    } else {
      $competition = tm_competiton_get_byname($competitionname);
    }

    $tableentries = tm_competition_get_leaguetable($competition->term_id);

    if (empty($seasons)) {
      $seasons = get_option('tm_default_season');
    }
    $seasonsarray = Array();
    foreach (explode(',', $seasons) as $season) {
      if ( sizeof($tableentries[$season]) > 0 ) {
        $seasonsarray[] = $season;
      }
    }

    if (empty($team)) {
      $team = get_the_title();
    }

    if (!empty($tableentries) && sizeof($seasonsarray) > 0 ) {
      $defaultseason = max($seasonsarray);
      echo $displaytitle;
      ?>
      <script>
      function selectSeason(season) {
        var leaguebody = document.getElementsByClassName("tm-season-leaguetable");
        for(var i = 0; i < leaguebody.length; i++)
        {
          if (leaguebody.item(i).id == "leaguetable_season_" + season) {
            leaguebody.item(i).style.display='table-row-group';
          }
          else {
            leaguebody.item(i).style.display='none';
          }
        }
      }
      </script>
      <?php if ( sizeof($seasonsarray) > 1) { ?>
      <select id="tm_leaguetable_seasons" name="tm_leaguetable_seasons" onchange="java_script_:selectSeason(this.options[this.selectedIndex].value)">
        <? foreach($seasonsarray as $season) {
            ?><option value='<?php echo $season ?>' <?php selected( $season, $defaultseason ) ?>><?php echo $season ?></option><?php
        } ?>
      </select>
    <?php } else { ?>
      <span><?php echo $seasonsarray[0]; ?></span>
    <?php } ?>
      <table>
        <thead class="">
          <th class="tm-col-pri1">Pos</th>
          <th class="tm-col-pri1">Team</th>
          <th class="tm-col-pri3">P</th>
          <th class="tm-col-pri3">W</th>
          <th class="tm-col-pri3">D</th>
          <th class="tm-col-pri3">L</th>
          <th class="tm-col-pri5">PF</th>
          <th class="tm-col-pri5">PA</th>
          <th class="tm-col-pri4">PD</th>
          <th class="tm-col-pri4">TBP</th>
          <th class="tm-col-pri4">LBP</th>
          <th class="tm-col-alt4">BP</th>
          <th class="tm-col-pri2">Pts</th>
        </thead>
        <? foreach($seasonsarray as $season) { ?>
            <tbody id='leaguetable_season_<?php echo $season ?>' class='tm-season-leaguetable' <?php if ( $season != $defaultseason ) { echo "style='display:none'"; } ?>>
              <?php
              foreach($tableentries[$season] as $tableentry) {
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
            </tbody>
        <?php } ?>
      </table>
      <?php
    }
  }
endif;
?>
