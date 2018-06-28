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
  function tm_fixturelist_table_header($team, $seasons, $defaultseason, $userargs) {
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
      if ( ! function_exists( 'tm_fixturelist_table_seasonheader' ) ):
        function tm_fixturelist_table_seasonheader($team, $season, $isdefaultseason, $userargs) {
          ?>
          <tbody id='fixturelist_season_<?php echo esc_attr($season->slug) ?>'
             class='tm-fixturelist-competitions'
             <?php if ( ! $isdefaultseason ) { echo "style='display:none'"; } ?>
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

            <td> <a href="<?php echo esc_attr($fixture->url) ?>"><?php echo date('F d, Y', $fixture->fixturedate) ?></a> </td>
            <td> <?php echo esc_html($opposition->name) ?> </td>
            <td> <?php echo esc_html($fixture->homeaway) ?> </td>
            <td> <?php echo esc_html($fixture->scorefor) ?> </td>
            <td> <?php echo esc_html($fixture->scoreagainst) ?> </td>
          </td>
        </tr>
        <?php
      }
    endif;


    // Competition Footer ==================================================
    if ( ! function_exists( 'tm_fixturelist_table_seasonfooter' ) ):
      function tm_fixturelist_table_seasonfooter($team, $season, $isdefaultseason, $userargs) {
        ?>
      </tbody>
        <?php
      }
    endif;


    // Table Footer  ==================================================
    if ( ! function_exists( 'tm_fixturelist_table_footer' ) ):
      function tm_fixturelist_table_footer($team, $seasons, $defaultseason, $userargs) {
        ?>
      </tbody>
    </table>
    <div class="tm-fixture--season-select">
      <?php if ( sizeof($seasons) > 1) { ?>
        <select name="tm_fixturelist_competitions" onchange="java_script_:TMFixturelistSelectCompetition(this.options[this.selectedIndex].value)">
          <? foreach($seasons as $season) {
            ?><option value='<?php echo esc_attr($season->slug) ?>' <?php selected( $season->slug, $defaultseason ) ?>><?php echo esc_html($season->name) ?></option><?php;
          } ?>
        </select>
      <?php } else { ?>
        <span><?php echo esc_attr($seasons[0]->name) ?></span>
      <?php } ?>
    </div>
    <script>
    function TMFixturelistSelectCompetition(competition) {
      var leaguebody = document.getElementsByClassName("tm-fixturelist-competitions");
      for(var i = 0; i < leaguebody.length; i++)
      {
        if (leaguebody.item(i).id == "fixturelist_season_" + competition) {
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
