<?php
/**
* Adds Metadatabox to tm_fixture custom post-type
* Supresses default tm_opposition, tm_season and tm_compeition metadataboxes
*
* @package TM
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( is_admin() && ! function_exists( 'tm_fixture_create_metadatabox' )):
/**
* Creates Fixture metadatabox and associates to tm_fixture custom post-type
* Bound to add_meta_boxes action hook
*
* @param void
* @return void
*/
  function tm_fixture_create_metadatabox() {
    add_meta_box(
      'tm_fixturemeta',
      'Fixture Metadata',
      'tm_fixture_inner_custom_box',
      'tm_fixture',
      'side',
      'low'
    );
  }
  add_action( 'add_meta_boxes', 'tm_fixture_create_metadatabox' );
endif;

// Enqueue admin scripts ========================================
if ( is_admin() && ! function_exists( 'tm_fixture_enqueue_adminscripts' )):
/**
* Enqueus fixture-admin-metadatabox.js & style.css
* Bound to admin_enqueue_scripts action hook
*
* @param WP_Hook? $hook Wonder what this does // TODO
* @return void
*/
  function tm_fixture_enqueue_adminscripts($hook) {
    // TODO: Need to do this only for tm_fixture
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_style( 'plugin-css', dirname($plugin_url) . '/style.css', array(), 'v4.0.0');
    wp_enqueue_script( 'fixture-admin-metadatabox', $plugin_url . 'fixture-admin-metadatabox.js', array('jquery'), 'v4.0.0', false );
    wp_localize_script( 'fixture-admin-metadatabox', 'tmphpobject', array(
       'ajax_url' => admin_url( 'admin-ajax.php' )
     ) );
  }
  add_action( 'admin_enqueue_scripts', 'tm_fixture_enqueue_adminscripts' );
endif;

// Remove other metaboxes ========================================
if ( is_admin() && ! function_exists( 'tm_fixture_remove_metadatabox' ) ):
/**
* Removes metadata boxes from tm_fixture : tm_opposition tm_season, tm_competition
* Bound to admin_menu action hook
*
* @param WP_Hook? $hook Wonder what this does // TODO
* @return void
*/
  function tm_fixture_remove_metadatabox($hook) {
    remove_meta_box( 'tagsdiv-tm_opposition', 'tm_fixture', 'normal' );
    remove_meta_box( 'tagsdiv-tm_season', 'tm_fixture', 'normal' );
    remove_meta_box( 'tagsdiv-tm_competition', 'tm_fixture', 'normal' );
  }
  add_action( 'admin_menu' , 'tm_fixture_remove_metadatabox' );
endif;

// Fixture Inner Custom Box ========================================
if ( is_admin() && ! function_exists( 'tm_fixture_inner_custom_box' )):
/**
* Inner custombox for tm_fixture.  Generates the metabox html content
* Invoked through inclusion in tm_fixture defintion (function:tm_fixture_create_metadatabox)
*
* @param WP_Post $post Post object being viewed (will be a tm_fixture post_type)
* @return void
*/
  function tm_fixture_inner_custom_box($post)
  {

    // Use nonce for verification
    wp_nonce_field( 'tm_fixture_field_nonce', 'tm_fixture_nonce' );
    wp_reset_query();

    $fixture = new TMFixture($post);

    // Only permit fixtures created by autofetch to be autofetch-able
    if ( $fixture->createdbyautofetch ) {
      ?>
      <div class="tm-meta-smallinput">
        <label for="tm_fixture_useautofetch"> Sync with autofetch? </label>
        <input type="checkbox" id="tm_fixture_useautofetch" name="tm_fixture_useautofetch" onchange="java_script_:tmfixtureuseautofetch(this.checked);" <?php checked($fixture->useautofetch) ?> />
      </div>
    <?php } else { // not created by autofetch
      $useautofetch = false;
    } // endif: created by autofetch

    // Team --------------------
    $teams = TMTeam::getAll();
    ?>
    <label for="tm_fixture_team"> Team </label><br>
    <select class="tm-meta-fullinput tm-meta-disableifautofetched"
    <?php disabled($fixture->useautofetch, true, true ) ?> name="tm_fixture_team" id="tm_fixture_team" >
      <?php foreach($teams as $team)  { ?>
        <option value="<?php echo esc_attr($team->ID) ?>" <?php selected($fixture->team->ID, $team->ID, true) ?> >
          <?php echo esc_html($team->title) ?>
        </option>
      <?php } ?>
    </select>

    <?php // Competition --------------------
    // onchange triggers tmfixturegetLeagueTeams which invokes ajax to retrieve possible team names
    $competitions = tm_competition_getall();
    $saved_competition = $fixture->competition;
    if ( $saved_competition ) {
      $saved_competition_id = $saved_competition->term_id;
    } else {
      $saved_competition_id = false;
    }
    ?>
    <label for="tm_fixture_competition"> Competition </label><br>
    <select class="tm-meta-fullinput tm-meta-disableifautofetched" id='tm_fixture_competition'
    name='tm_fixture_competition' <?php disabled($fixture->useautofetch, true, true ) ?>
    onchange='java_script_:tmfixturegetLeagueTeams(this.options[this.selectedIndex].value);'>
    <option value=''> None/Friendly </option>
    <?php foreach($competitions as $competition) { ?>
      <option value="<?php echo esc_attr($competition->term_id) ?>" <?php selected( $competition->term_id, $saved_competition_id ) ?> >
        <?php echo esc_html($competition->name) ?>
      </option>
    <?php } ?>
  </select>
  <br>

  <?php // League teams (retrieve based on competition using ajax)--------------------
  if ( $saved_competition ) {
    $leagueteams = tm_competition_get_teams( $saved_competition->term_id );
  } else {
    $leagueteams = Array();
  }
  ?>
  <label for="tm_fixture_leagueteam_select"> Opposition </label><br>
  <select id='tm_fixture_leagueteam_select' name='tm_fixture_leagueteam_select'
  class='tm-meta-fullinput tm-meta-disableifautofetched' <?php disabled($fixture->useautofetch, true, true ) ?>
  <?php if ( ! $saved_competition_id ) { ?> style='display:none;' <?php } ?> >
    <?php foreach($leagueteams as $leagueteam) { ?>
      <option value='<?php echo esc_attr($leagueteam) ?>' <?php selected( $leagueteam , $fixture->opposition ) ?> >
        <?php echo esc_html($leagueteam) ?>
      </option>
    <?php } ?>
  </select>
  <?php // If no competition selected, present freeform textbox rather than select ?>
  <input id='tm_fixture_leagueteam_text' name='tm_fixture_leagueteam_text' type='text' class='tm-meta-fullinput' <?php disabled($fixture->useautofetch, true, true ) ?> value='<?php echo esc_attr($saved_opposition) ?>' <?php if ( $saved_competition_id ) { ?> style='display:none;' <?php } ?> />

  <?php // Fixture date -------------------- ?>
  <div class="tm-meta-smallinput">
    <label for="tm_fixture_date">Fixture Date</label>
    <input class="tm-meta-disableifautofetched" type="date" name="tm_fixture_date" <?php disabled($fixture->useautofetch, true, true ) ?> id="tm_fixture_date" value="<?php echo date('Y-m-d',$fixture->fixturedate) ?>"  />
  </div>

  <?php // Season -------------------- ?>
  <div class="tm-meta-smallinput">
    <label for="tm_fixture_season"> Season </label>
    <select id='tm_fixture_season' class='tm-meta-disableifautofetched' name='tm_fixture_season'
    <?php disabled($fixture->useautofetch, true, true ) ?> >
      <?php foreach(TMSeason::getAll() as $season) { ?>
        <option value='<?php echo esc_attr($season->ID) ?>' <?php selected( $season->ID , $fixture->season->term_id ) ?> >
          <?php echo esc_html($season->name) ?>
        </option>
      <?php } ?>
    </select>
  </div>

  <?php // Home/Away --------------------
  $homeaways = Array (
    'H' => 'Home',
    'A' => 'Away'
  );
  ?>
  <div class="tm-meta-smallinput">
    <label for="tm_fixture_homeaway"> Home/Away </label>
    <select class="tm-meta-disableifautofetched" id='tm_fixture_homeaway' name='tm_fixture_homeaway' <?php disabled($fixture->useautofetch, true, true ) ?> >
      <?php foreach($homeaways as $key => $value) { ?>
        <option value='<?php echo esc_attr($key) ?>' <?php selected( $key, $fixture->homeaway , true ) ?> >
          <?php echo esc_html($value) ?>
        </option>
      <?php } ?>
    </select>
  </div>

  <?php // Score for -------------------- ?>
  <div class="tm-meta-smallinput">
    <label for="tm_fixture_scorefor">Team Score</label>
    <input class="tm-meta-disableifautofetched" type="number" name="tm_fixture_scorefor" id="tm_fixture_scorefor"
    value="<?php echo esc_attr($fixture->scorefor) ?>" <?php disabled($fixture->useautofetch, true, true ) ?> />
  </div>

  <?php // Score against -------------------- ?>
  <div class="tm-meta-smallinput">
    <label for="tm_fixture_scoreagainst">Opposition Score</label>
    <input class="tm-meta-disableifautofetched" type="number" name="tm_fixture_scoreagainst" id="tm_fixture_scoreagainst"
    value="<?php echo esc_attr($fixture->scoreagainst) ?>"  <?php disabled($fixture->useautofetch, true, true ) ?> />
  </div>

  <?php
}
endif;
?>
