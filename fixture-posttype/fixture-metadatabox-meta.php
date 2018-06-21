<?php
if ( ! function_exists( 'tm_fixture_create_metadatabox' )):
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
if ( ! function_exists( 'tm_fixture_enqueue_adminscripts' )):
  function tm_fixture_enqueue_adminscripts($hook) {
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_style( 'plugin-css', dirname($plugin_url) . '/style.css', array(), 'v4.0.0');
    wp_enqueue_script( 'fixture-metabox-meta-js', $plugin_url . 'fixture-metadatabox-meta.js', array('jquery'), 'v4.0.0', false );
    wp_localize_script( 'fixture-metabox-meta-js', 'tmphpobject', array(
       'ajax_url' => admin_url( 'admin-ajax.php' )
     ) );
  }
  add_action( 'admin_enqueue_scripts', 'tm_fixture_enqueue_adminscripts' );
endif;

// Remove other metaboxes ========================================
if ( ! function_exists( 'tm_fixture_remove_metadatabox' ) ):
  function tm_fixture_remove_metadatabox($hook) {
    remove_meta_box( 'tagsdiv-tm_opposition', 'tm_fixture', 'normal' );
    remove_meta_box( 'tagsdiv-tm_season', 'tm_fixture', 'normal' );
    remove_meta_box( 'tagsdiv-tm_competition', 'tm_fixture', 'normal' );
  }
  add_action( 'admin_menu' , 'tm_fixture_remove_metadatabox' );
endif;

// Fixture Inner Custom Box ========================================
if ( ! function_exists( 'tm_fixture_inner_custom_box' )):
  function tm_fixture_inner_custom_box($post)
  {

    // Use nonce for verification
    wp_nonce_field( 'tm_fixture_field_nonce', 'tm_fixture_nonce' );
    wp_reset_query();
    ?>

    <?php
    if ( tm_fixture_get_createdbyautofetch() ) {
      $useautofetch = tm_fixture_get_useautofetch();
      ?>
      <div class="tm-meta-smallinput">
        <label for="tm_fixture_useautofetch"> Sync with autofetch? </label>
        <input type="checkbox" id="tm_fixture_useautofetch" name="tm_fixture_useautofetch"
        onchange="java_script_:tmfixtureuseautofetch(this.checked);" <?php checked($useautofetch) ?> />
      </div>
    <?php } else {
      $useautofetch = false;
    } ?>

    <?php
    $teams = tm_team_getall();
    $saved_team = tm_fixture_get_team();
    ?>
    <label for="tm_fixture_team"> Team </label><br>
    <select class="tm-meta-fullinput tm-meta-disableifautofetched"
    <?php disabled($useautofetch, true, true ) ?> name="tm_fixture_team" id="tm_fixture_team" >
      <?php foreach($teams as $team)  { ?>
        <option value="<?php echo esc_attr($team->ID) ?>" <?php selected($saved_team->ID, $team->ID, true) ?> >
          <?php echo esc_html($team->post_title) ?>
        </option>
      <?php } ?>
    </select>

    <?php
    $competitions = tm_competition_getall();
    $saved_competition = tm_fixture_get_competition();
    if ( $saved_competition ) {
      $saved_competition_id = $saved_competition->term_id;
    } else {
      $saved_competition_id = false;
    }
    ?>
    <label for="tm_fixture_competition"> Competition </label><br>
    <select class="tm-meta-fullinput tm-meta-disableifautofetched" id='tm_fixture_competition'
    name='tm_fixture_competition' <?php disabled($useautofetch, true, true ) ?>
    onchange='java_script_:tmfixturegetLeagueTeams(this.options[this.selectedIndex].value);'>
    <option value=''> None/Friendly </option>
    <?php foreach($competitions as $competition) { ?>
      <option value="<?php echo esc_attr($competition->term_id) ?>" <?php selected( $competition->term_id, $saved_competition_id ) ?> >
        <?php echo esc_html($competition->name) ?>
      </option>
    <?php } ?>
  </select>
  <br>

  <?php
  if ( $saved_competition ) {
    $leagueteams = tm_competition_get_teams( $saved_competition->term_id );
  } else {
    $leagueteams = Array();
  }
  $saved_opposition = tm_fixture_get_opposition();
  ?>
  <label for="tm_fixture_leagueteam_select"> Opposition </label><br>
  <select id='tm_fixture_leagueteam_select' name='tm_fixture_leagueteam_select'
  class='tm-meta-fullinput tm-meta-disableifautofetched' <?php disabled($useautofetch, true, true ) ?>
  <?php if ( ! $saved_competition_id ) { ?> style='display:none;' <?php } ?> >
    <?php foreach($leagueteams as $leagueteam) { ?>
      <option value='<?php echo $leagueteam ?>' <?php selected( $leagueteam , $saved_opposition ) ?> >
        <?php echo $leagueteam ?>
      </option>
    <?php } ?>
  </select>
  <input
    id='tm_fixture_leagueteam_text' name='tm_fixture_leagueteam_text'
    type='text' class='tm-meta-fullinput' <?php disabled($useautofetch, true, true ) ?>
    value='<?php echo esc_attr($saved_opposition) ?>'
    <?php if ( $saved_competition_id ) { ?> style='display:none;' <?php } ?>
  />

  <div class="tm-meta-smallinput">
    <label for="tm_fixture_date">Fixture Date</label>
    <input class="tm-meta-disableifautofetched" type="date" name="tm_fixture_date"
    <?php disabled($useautofetch, true, true ) ?>
    id="tm_fixture_date" value="<?php echo date('Y-m-d',tm_fixture_get_date()) ?>"  />
  </div>

  <?php
  $seasons = tm_season_getall();
  $saved_season = tm_fixture_get_season();
  ?>
  <div class="tm-meta-smallinput">
    <label for="tm_fixture_season"> Season </label>
    <select id='tm_fixture_season' class='tm-meta-disableifautofetched' name='tm_fixture_season'
    <?php disabled($useautofetch, true, true ) ?> >
      <?php foreach($seasons as $season) { ?>
        <option value='<?php echo esc_attr($season->term_id) ?>' <?php selected( $season->term_id , $saved_season->term_id ) ?> >
          <?php echo esc_html($season->name) ?>
        </option>
      <?php } ?>
    </select>
  </div>

  <?php
  $homeaways = Array (
    'H' => 'Home',
    'A' => 'Away'
  );
  ?>
  <div class="tm-meta-smallinput">
    <label for="tm_fixture_homeaway"> Home/Away </label>
    <select class="tm-meta-disableifautofetched" id='tm_fixture_homeaway' name='tm_fixture_homeaway' <?php disabled($useautofetch, true, true ) ?> >
      <?php foreach($homeaways as $key => $value) { ?>
        <option value='<?php echo esc_attr($key) ?>' <?php selected( $key, tm_fixture_get_homeaway() , true ) ?> >
          <?php echo esc_html($value) ?>
        </option>
      <?php } ?>
    </select>
  </div>

  <div class="tm-meta-smallinput">
    <label for="tm_fixture_scorefor">Team Score</label>
    <input class="tm-meta-disableifautofetched" type="number" name="tm_fixture_scorefor" id="tm_fixture_scorefor"
    value="<?php echo esc_attr(tm_fixture_get_scorefor()) ?>" <?php disabled($useautofetch, true, true ) ?> />
  </div>

  <div class="tm-meta-smallinput">
    <label for="tm_fixture_scoreagainst">Opposition Score</label>
    <input class="tm-meta-disableifautofetched" type="number" name="tm_fixture_scoreagainst" id="tm_fixture_scoreagainst"
    value="<?php echo esc_attr(tm_fixture_get_scoreagainst()) ?>"  <?php disabled($useautofetch, true, true ) ?> />
  </div>

  <?php
}
endif;
?>
