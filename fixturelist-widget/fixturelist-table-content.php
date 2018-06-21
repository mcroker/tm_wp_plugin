<?php
/**
* The template part for displaying results in search pages.
*
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* @package TM
*/

if ( ! function_exists( 'tm_fixturelist_table_header' ) ):
  function tm_fixturelist_table_header($team, $title) {
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


      if ( ! function_exists( 'tm_fixturelist_table_row' ) ):
        function tm_fixturelist_table_row($team, $fixture) {
          ?>
          <tr class="tm-row tm-post">
            <?php
            $teamlogoid = tm_team_get_logo();
            $opposition = tm_opposition_get_byslug( $fixture->opposition );
            $oppositionlogoid = tm_opposition_get_logo( $opposition->term_id );
            $teamlogo = wp_get_attachment_image( $teamlogoid, "team-logo", "", array( "class" => "team-logo logo-even img-responsive" ) );
            $oppositionlogo = wp_get_attachment_image( $oppositionlogoid, "team-logo", "", array( "class" => "team-logo logo-odd img-responsive" ) );
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


      if ( ! function_exists( 'tm_fixturelist_table_footer' ) ):
        function tm_fixturelist_table_footer($team) {
          ?>
        </tbody>
      </table>
    </div>
    <?php
  }
endif;
?>
