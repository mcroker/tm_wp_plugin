<?php
/**
* @package TM
*/
if ( ! function_exists( 'tm_teamtabs_widget_content' ) ):
  function tm_teamtabs_widget_content($displaytitle, $competitionname, $seasons, $team_id) {
    if ( empty($maxrows) ) $maxrows = 6;
    if ( empty($maxfuture) ) $maxfuture = 3;
    if ( empty($team_id) ) {
      switch ( get_post_type() ) {
        case 'tm_team':
        $team_id = get_the_id();
        break;

        case 'tm_fixture':
        $team_id = tm_fixture_get_team( $fixtures->ID )->ID;
        break;
      }
    }
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_script( 'teamtabs-js', $plugin_url . 'teamtabs.js', array('jquery'), 'v4.0.0', true );
    wp_enqueue_style( 'w3-css', $plugin_url . '/w3.css', array(), 'v4.0.0');

    ?>
    <div class="w3-container">
      <div class="w3-row">
        <a href="javascript:void(0)" onclick="openTab(event, 'TMDetails');">
          <div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding w3-border-red">Details</div>
        </a>
        <a href="javascript:void(0)" onclick="openTab(event, 'TMLeaguetable');">
          <div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding">Table</div>
        </a>
        <a href="javascript:void(0)" onclick="openTab(event, 'TMFixtures');">
          <div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding">Fixtures</div>
        </a>
        <a href="javascript:void(0)" onclick="openTab(event, 'TMCoaches');">
          <div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding">Coaches</div>
        </a>
        <a href="javascript:void(0)" onclick="openTab(event, 'TMPlayers');">
          <div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding">Players</div>
        </a>
      </div>

      <div id="TMDetails" class="teamtab">
        <?php
        if (have_posts()):
          while (have_posts()) : the_post();
          the_content();
        endwhile;
      endif;
        ?>
      </div>

      <div id="TMLeaguetable" class="teamtab" style="display:none">
        <?php tm_leaguetable_widget_content('', $competition, $seasons, $team_id, $args); ?>
      </div>

      <div id="TMFixtures" class="teamtab" style="display:none">
        <?php  tm_fixturelist_widget_content('table', '', $team_id, $maxrows, $maxfuture ); ?>
      </div>

      <div id="TMCoaches" class="teamtab" style="display:none">
      </div>

      <div id="TMPlayers" class="teamtab" style="display:none">
      </div>


    </div>

    <?php
  }
endif;
?>
