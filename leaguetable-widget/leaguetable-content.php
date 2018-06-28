<?php
/**
* @package TM
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists( 'tm_leaguetable_widget_content' ) ):
  function tm_leaguetable_widget_content($displaytitle, $competitionname, $team, $args) {
    $team = new TMTeam( get_the_id() );
    //TODO : Should use post_type
    if (empty($competitionname)) {
      $competitions = $team->competitions;
    } else {
      $competitions = Array ( TMCompetition::getByName($competitionname) );
    }

    if ( sizeof($competitions) > 0 ) {

      // TODO - This shouldn't be there
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
      <?php echo esc_html($displaytitle)  ?>
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
          <?php
          $even = false;
          uasort($competitions, Array('TMCompetition', 'sort_by_sortkey_asc'));
          $defaultcompetition = reset($competitions);
          $populatedcompetitions = [];
          foreach($competitions as $competition) {
            $tableentries = $competition->leaguetable;
            if ( sizeof($tableentries) > 0 ) {
              $populatedcompetitions[]=$competition;
              ?>
              <tbody id='leaguetable_competition_<?php echo esc_attr($competition->ID) ?>' class='tm-competition-leaguetable' <?php if ( $competition->ID != $defaultcompetition->ID ) { echo "style='display:none'"; } ?>>
                <?php
                foreach($tableentries as $tableentry) {
                  $entry = new TMLeagueTableEntry($tableentry);
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
                  <tr class="<?php echo $entryclass ?> <?php echo esc_html($evenodd) ?>">
                    <td class="tm-col-pri1"><?php echo esc_html($entry->position) ?></td>
                    <td class="tm-col-pri1"><?php echo esc_html($entry->team) ?></td>
                    <td class="tm-col-pri3"><?php echo esc_html($entry->played) ?></td>
                    <td class="tm-col-pri3"><?php echo esc_html($entry->wins) ?></td>
                    <td class="tm-col-pri3"><?php echo esc_html($entry->draws) ?></td>
                    <td class="tm-col-pri3"><?php echo esc_html($entry->lost) ?></td>
                    <td class="tm-col-pri5"><?php echo esc_html($entry->pointsfor) ?></td>
                    <td class="tm-col-pri5"><?php echo esc_html($entry->pointsagainst) ?></td>
                    <td class="tm-col-pri4"><?php echo esc_html($entry->pointsdiff) ?></td>
                    <td class="tm-col-pri4"><?php echo esc_html($entry->trybonus) ?></td>
                    <td class="tm-col-pri4"><?php echo esc_html($entry->losingbonus) ?></td>
                    <td class="tm-col-alt4"><?php echo esc_html($entry->losingbonus + $entry->trybonus) ?></td>
                    <td class="tm-col-pri2"><?php echo esc_html($entry->points) ?></td>
                  </tr>
                <?php  } ?>
              </tbody>
            <?php }  ?>
          <?php }  ?>
        </table>
        <div class="tm-leaguetable-competition-select">
          <?php if ( sizeof($competitions) > 1) { ?>
            <select name="tm_leaguetable_competitions" onchange="java_script_:tmLeaguetableSelectCompetition(this.options[this.selectedIndex].value)">
              <?php
                uasort($populatedcompetitions, Array('TMCompetition', 'sort_by_sortkey_asc'));
                foreach($populatedcompetitions as $competition) {
              ?>
                <option value='<?php echo esc_attr($competition->ID) ?>' <?php selected( $competition->ID, $defaultcompetition->ID ) ?>><?php echo esc_html($competition->name) ?></option>
              <?php } ?>
            </select>
          <?php } else { ?>
            <span><?php echo esc_html($competitions[0]->name) ?></span>
          <?php } ?>
        </div>
      </div>
      <?php
    }
  }
endif;
?>
