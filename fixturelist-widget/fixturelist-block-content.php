<?php
/**
* The template part for displaying results in search pages.
*
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*
* @package TM
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Header --------------------------------------------------
if ( ! function_exists( 'tm_fixturelist_block_header' ) ):
  function tm_fixturelist_block_header($team, $userargs ) {
    ?>
    <div class="tm-table-wrapper">
      <table class="tm-event-blocks tm-data-table tm-paginated-table">
        <thead class="tm-table-caption">
        <?php if ( ! empty ($userargs['title']) ) { ?>
          <th><?php echo $userargs['title'] ?></th>
        <?php } ?>
        </thead>
        <tbody>
    <?php
  }
endif;

// Row --------------------------------------------------
if ( ! function_exists( 'tm_fixturelist_block_row' ) ):
  function tm_fixturelist_block_row($fixture, $userargs) {
    $options = get_option( 'tm', array() );
    if ( array_key_exists( 'logo_url', $options ) && ! empty( $options['logo_url'] ) ) {
      $teamlogo = esc_url( set_url_scheme( $options['logo_url'] ) );
    }
    if ( $fixture->homeaway != 'A' ) {
      $hometeam = $fixture->team->title;
      $awayteam = $fixture->opposition->name;
      $homescore = $fixture->scorefor;
      $awayscore = $fixture->scoreagainst;
      $homelogo = '<img class="team-logo logo-odd img-responsive" src="' . $teamlogo . '" />';
      $awaylogo = wp_get_attachment_image( $fixture->opposition->logo, "team-logo", "", array( "class" => "team-logo logo-even img-responsive" ) );
    } else {
      $hometeam = $fixture->opposition->name;
      $awayteam = $fixture->team->title;
      $homescore = $fixture->scoreagainst;
      $awayscore = $fixture->scorefor;
      $homelogo = wp_get_attachment_image( $fixture->opposition->logo, "team-logo", "", array( "class" => "team-logo logo-odd img-responsive" ) );
      $awaylogo = '<img class="team-logo logo-even img-responsive" src="' . $teamlogo . '" />';
    }
    ?>
          <tr class="tm-row tm-post">
            <td>
              <span class="team-logo logo-odd"><?php echo $homelogo ?></span>
              <span class="team-logo logo-even"><?php echo $awaylogo ?></span>
              <time class="tm-event-date">
                <?php
                $kickoff = $fixture->kickofftime;
                if ( ! empty($kickoff) ) { ?>
                  <a href="<?php echo esc_attr($fixture->url) ?>"><?php echo $fixture->kickofftime->format('F d, Y') ?></a>
                  <?php if ( $fixture->kickofftime->format('H') != '00' && empty($homescore) && empty($awayscore) ) { ?>
                    <br><a href="<?php echo esc_attr($fixture->url) ?>"><?php echo $fixture->kickofftime->format('H:i') ?></a>
                  <?php } ?>
                <?php } else { ?>
                  <a href="<?php echo esc_attr($fixture->url) ?>"><?php echo $fixture->kickofftime->format('F d, Y') ?></a>
                <?php } ?>
              </time><br>
              <?php if ( ! empty($homescore) || ! empty($awayscore) ) { ?>
                <h5 class="tm-event-results"><?php echo esc_html($homescore) . __( ' - ' , 'tm' ) . esc_html($awayscore) ?></h5>
              <?php } ?>
              <h4 class="tm-event-title"><?php echo esc_html($hometeam) . __( ' Vs. ' , 'tm' ) . esc_html($awayteam) ?></h4>
            </td>
          </tr>
    <?php
  }
endif;

// Footer --------------------------------------------------
if ( ! function_exists( 'tm_fixturelist_block_footer' ) ):
  function tm_fixturelist_block_footer($team, $userargs) {
    ?>
        </tbody>
      </table>
    </div>
    <?php
  }
endif;
?>
