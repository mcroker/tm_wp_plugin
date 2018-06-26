<?php
/**
* @package TM
*/

class TM_Leaguetable_Fixtures {
  public $comp;
  public $sortkey;
  public $tableentries;
}

if ( ! function_exists( 'tm_leaguetable_cmp_competitions_by_sortkey_desc' ) ):
  function tm_leaguetable_cmp_competitions_by_sortkey_desc($a, $b)
  {
    return ($a->sortkey < $b->sortkey);
  }
endif;

if ( ! function_exists( 'tm_leaguetable_widget_content' ) ):
  function tm_leaguetable_widget_content($displaytitle, $competitionname, $team, $args) {
    $team = new TMTeam( get_the_id() );
    if (empty($competitionname)) {
      $competitions = $team->competitions;
    } else {
      $competitions = Array ( TMCompetition::getByName($competitionname) );
    }

    if ( sizeof($competitions) > 0 ) {
      $defaultcompetition = max(array_map(create_function('$item', 'return $item->sortkey;') , $competitions));

      $internalcomps = Array();
      foreach ($competitions as $competition) {
        $comp = new TM_Leaguetable_Fixtures();
        $comp->comp = $competition;
        $comp->sortkey = $competition->sortkey;
        $comp->tableentries = $competition->leaguetable;
        $internalcomps[] = $comp;
      }
      uasort($internalcomps, 'tm_leaguetable_cmp_competitions_by_sortkey_desc');
      $defaultcompetition = $internalcomps[0]->sortkey;

      $plugin_url = plugin_dir_url(__FILE__);
      wp_enqueue_style( 'plugin-css', dirname($plugin_url) . '/style.css', array(), 'v4.0.0');

      ?>
      <script>
      function tmLeaguetableSelectCompetition(competition) {
        var leaguebody = document.getElementsByClassName("tm-competition-leaguetable");
        for(var i = 0; i < leaguebody.length; i++)
        {
          if (leaguebody.item(i).id == "leaguetable_competition_" + competition) {
            leaguebody.item(i).style.display='table-row-group';
          }
          else {
            leaguebody.item(i).style.display='none';
          }
        }
      }
      </script>
      <?php echo $displaytitle;  ?>
      <div class="tm-table-wrapper">
        <table class="tm-league-table tm-data-table">
          <thead class="tm-table-caption">
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
          <?php $even = false ?>
          <?php
          foreach($internalcomps as $intcomp) {
            $competition = $intcomp->comp;
            $sortkey = $intcomp->sortkey;
            $tableentries = $intcomp->tableentries;
            ?>
            <tbody id='leaguetable_competition_<?php echo esc_attr($sortkey) ?>' class='tm-competition-leaguetable' <?php if ( $sortkey != $defaultcompetition ) { echo "style='display:none'"; } ?>>
              <?php
              foreach($tableentries as $tableentry) {
                if ( $tableentry->team == $team ) {
                  $entryclass='tm-highlightedleagueentry';
                } else {
                  $entryclass='tm-leagueentry';
                }
                if ( $even ) {
                  $evenodd = 'even';
                } else {
                  $evenodd = 'odd';
                }
                $even = ! $even;
                ?>
                <tr class="<?php echo $entryclass ?> <?php echo $evenodd ?>">
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
          <?php }  ?>
        </table>
        <div class="tm-leaguetable-competition-select">
          <?php if ( sizeof($competitions) > 1) { ?>
            <select name="tm_leaguetable_competitions" onchange="java_script_:tmLeaguetableSelectCompetition(this.options[this.selectedIndex].value)">
              <? foreach($competitions as $competition) {
                ?><option value='<?php echo $competition->sortkey ?>' <?php selected( $sortkey, $defaultcompetition ) ?>><?php echo $competition->name ?></option><?php
              } ?>
            </select>
          <?php } else { ?>
            <span><?php echo $competitions[0]->name; ?></span>
          <?php } ?>
        </div>
      </div>
      <?php
    }
  }
endif;
?>
