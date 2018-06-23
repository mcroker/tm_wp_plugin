<?php
/**
* The template part for displaying results in search pages.
*
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* @package TM
*/

if ( ! function_exists( 'tm_fixturelist_block_header' ) ):
  function tm_fixturelist_block_header($team, $title, $fixtures) {
    ?>
    <div class="tm-table-wrapper">
      <table class="tm-event-blocks tm-data-table tm-paginated-table">
        <thead class="tm-table-caption">
        <?php if ( ! empty ($title) ) { ?>
          <th><?php echo $title ?></th>
        <?php } ?>
        </thead>
        <tbody>
          <?php
        }
      endif;


      if ( ! function_exists( 'tm_fixturelist_block_row' ) ):
        function tm_fixturelist_block_row($team_id, $fixture) {
          ?>
          <tr class="tm-row tm-post">
            <td>
            <?php
            $options = get_option( 'tm', array() );
            if ( array_key_exists( 'logo_url', $options ) && ! empty( $options['logo_url'] ) ) {
              $teamlogo = $options['logo_url'];
              $teamlogo = esc_url( set_url_scheme( $teamlogo ) );
            }
            $opposition = tm_opposition_get_byslug( $fixture->opposition );
            $oppositionlogoid = tm_opposition_get_logo( $opposition->term_id );

            if ( $fixture->homeaway != 'A' ) {
              $hometeam = $fixture->teamname;
              $awayteam = $fixture->opposition;
              $homescore = $fixture->scorefor;
              $awayscore = $fixture->scoreagainst;
              $homelogo = '<img class="team-logo logo-odd img-responsive" src="' . $teamlogo . '" />';
              $awaylogo = wp_get_attachment_image( $oppositionlogoid, "team-logo", "", array( "class" => "team-logo logo-even img-responsive" ) );
            } else {
              $hometeam = $fixture->opposition;
              $awayteam = $fixture->teamname;
              $homescore = $fixture->scoreagainst;
              $awayscore = $fixture->scorefor;
              $homelogo = wp_get_attachment_image( $oppositionlogoid, "team-logo", "", array( "class" => "team-logo logo-odd img-responsive" ) );
              $awaylogo = '<img class="team-logo logo-even img-responsive" src="' . $teamlogo . '" />';
            }
            ?>
              <span class="team-logo logo-odd"><?php echo $homelogo ?></span>
              <span class="team-logo logo-even"><?php echo $awaylogo ?></span>
              <time class="tm-event-date"><a href="<?php echo $fixture->url ?>"><?php echo date('F d, Y', $fixture->fixturedate) ?></a></time><br>
              <h5 class="tm-event-results"><?php echo $homescore . __( ' - ' , 'tm' ) . $awayscore ?></h5>
              <h4 class="tm-event-title"><?php echo $hometeam . __( ' Vs. ' , 'tm' ) . $awayteam ?></h4>
            </td>
          </tr>
          <?php
        }
      endif;


      if ( ! function_exists( 'tm_fixturelist_block_footer' ) ):
        function tm_fixturelist_block_footer($team, $title, $fixtures) {
          ?>
        </tbody>
      </table>
    </div>
    <?php
  }
endif;
?>
