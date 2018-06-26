<?php
/**
* The template part for displaying results in search pages.
*
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* @package TM
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Table Header  ==================================================
if ( ! function_exists( 'tm_fixturelist_table_header' ) ):
  function tm_fixturelist_table_header($team, $title, $fixtures, $competitions, $defaultcompetitionslug) {
    ?>
    <div class="tm-table-wrapper">
      <table class="tm-event-lists tm-data-table tm-paginated-table">
        <thead class="tm-table-caption">
          <th>Date</th>
          <th>Opposition</th>
          <th>H/A</th>
          <th>Score For</th>
          <th>Score Against</th>
        </thead>
        <tbody>
          <?php
        }
      endif;

      // Competition Header  ==================================================
      if ( ! function_exists( 'tm_fixturelist_table_competitionheader' ) ):
        function tm_fixturelist_table_competitionheader($team, $competition, $isdefaultcompetition) {
          ?>
          <tbody id='fixturelist_competition_<?php echo esc_attr($competition->slug) ?>'
             class='tm-fixturelist-competitions'
             <?php if ( ! $isdefaultcompetition ) { echo "style='display:none'"; } ?>
          >
          <?php
        }
      endif;

      // Row ==================================================
      if ( ! function_exists( 'tm_fixturelist_table_row' ) ):
        function tm_fixturelist_table_row($team, $fixture) {
          ?>
          <tr class="tm-row tm-post">
            <?php
            $opposition = $fixture->opposition;
            ?>

            <td> <a href="<?php echo $fixture->url ?>"><?php echo date('F d, Y', $fixture->fixturedate) ?></a> </td>
            <td> <?php echo $opposition->name ?> </td>
            <td> <?php echo $fixture->homeaway ?> </td>
            <td> <?php echo $fixture->scorefor ?> </td>
            <td> <?php echo $fixture->scoreagainst ?> </td>
          </td>
        </tr>
        <?php
      }
    endif;


    // Competition Footer ==================================================
    if ( ! function_exists( 'tm_fixturelist_table_competitionfooter' ) ):
      function tm_fixturelist_table_competitionfooter($team, $competition, $isdefaultcompetition) {
        ?>
      </tbody>
        <?php
      }
    endif;


    // Table Footer  ==================================================
    if ( ! function_exists( 'tm_fixturelist_table_footer' ) ):
      function tm_fixturelist_table_footer($team, $title, $fixtures, $competitions, $defaultcompetitionslug ) {
        ?>
      </tbody>
    </table>
    <div class="tm-fixture-competition-select">
      <?php if ( sizeof($competitions) > 1) { ?>
        <select name="tm_fixturelist_competitions" onchange="java_script_:TMFixturelistSelectCompetition(this.options[this.selectedIndex].value)">
          <? foreach($competitions as $competition) {
            ?><option value='<?php echo $competition->comp->slug ?>' <?php selected( $competition->comp->slug, $defaultcompetitionslug ) ?>><?php echo $competition->comp->name ?></option><?php;
          } ?>
        </select>
      <?php } else { ?>
        <span><?php echo $competitions[0]->comp->name; ?></span>
      <?php } ?>
    </div>
    <script>
    function TMFixturelistSelectCompetition(competition) {
      var leaguebody = document.getElementsByClassName("tm-fixturelist-competitions");
      for(var i = 0; i < leaguebody.length; i++)
      {
        if (leaguebody.item(i).id == "fixturelist_competition_" + competition) {
          leaguebody.item(i).style.display='table-row-group';
        }
        else {
          leaguebody.item(i).style.display='none';
        }
      }
    }
    </script>
  </div>
  <?php
}
endif;
?>
