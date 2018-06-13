<?php
/* Adds a box to the main column on the Post and Page edit screens */
if ( ! function_exists( 'tm_create_metadatabox_fixture' )):
  function tm_create_metadatabox_fixture() {
    add_meta_box(
      'tm_fixturemeta',
      'Fixture Metadata',
      'tm_fixture_inner_custom_box',
      'tm_fixture',
      'side',
      'high'
    );
  }
endif;

if ( ! function_exists( 'tm_fixture_inner_custom_box' )):
  function tm_fixture_inner_custom_box($post)
  {

    // Use nonce for verification
    wp_nonce_field( 'tm_fixture_field_nonce', 'tm_fixture_nonce' );

    // Get saved value, if none exists, "default" is selected
    $saved_team = get_post_meta( $post->ID, 'tm_fixture_team', true);
    $teams = get_posts(array (
      'post_type' => 'tm_team'
    ));
    wp_reset_query();
    printf('<label for="tm_fixture_team"> Team </label><br>');
    printf('<select class="tm-meta-fullinput" name="tm_fixture_team" id="tm_fixture_team"/>');
    foreach($teams as $team)
    {
      printf(
        '<option value="%1$s" %2$s > %3$s </option>',
        esc_attr($team->ID),
        selected($saved_team, $team->ID, false),
        esc_html($team->post_title)
      );
    }
    printf('</select>');

    $competitions = tm_competiton_getall();
    $saved_competition = tm_fixture_get_competition();
    ?>
    <label for="tm_team_competition"> Competition </label><br>
    <select class="tm-meta-fullinput" id='tm_team_competition' name='tm_team_competition'>
      <option value=''>None/Friendly</option>
      <?php foreach($competitions as $competition) { ?>
        <option value='<?php echo $competition->term_id ?>' <?php selected( $competition->term_id, $saved_competition->term_id ) ?>> <?php echo $competition->name ?></option>
      <?php } ?>
    </select>
    <br>

    <?php
    $seasons = tm_season_getall( $saved_competition->term_id );
    $saved_season = tm_fixture_get_season();
    ?><div class="tm-meta-smallinput">
      <label for="tm_team_leagueteam"> Season </label>
      <select id='tm_team_leagueteam' name='tm_team_leagueteam'>
        <?php foreach($seasons as $season) { ?>
          <option value='<?php echo $season->term_id ?>' <?php selected( $season->term_id , $saved_season->term_id ) ?>> <?php echo $season->name ?></option>
        <?php } ?>
      </select><br>
    </div><?php

    $saved_date = tm_fixture_get_date();
    ?><div class="tm-meta-smallinput"><?php
    printf(
      '<label for="tm_fixture_date">Fixture Date</label>'.
      '<input type="date" name="tm_fixture_date" value="%1$s" id="tm_fixture_date"/>',
      date('Y-m-d',$saved_date)
    );
    ?></div><?php

    $saved_scorefor = get_post_meta( $post->ID, 'tm_fixture_scorefor', true);
    ?><div class="tm-meta-smallinput"><?php
    printf(
      '<label for="tm_fixture_scorefor">Team Score</label>'.
      '<input type="number" name="tm_fixture_scorefor" value="%1$s" id="tm_fixture_scorefor"/></br>',
      esc_attr(trim($saved_scorefor))
    );
    ?></div><?php

    $saved_scoreagainst = get_post_meta( $post->ID, 'tm_fixture_scoreagainst', true);
    ?><div class="tm-meta-smallinput"><?php
    printf(
      '<label for="tm_fixture_scoreagainst">Opposition Score</label>'.
      '<input type="number" name="tm_fixture_scoreagainst" value="%1$s" id="tm_fixture_scoreagainst"/></br>',
      esc_attr(trim($saved_scoreagainst))
    );
    ?></div>


 <?php
  }
  add_action( 'add_meta_boxes', 'tm_create_metadatabox_fixture' );
  $plugin_url = plugin_dir_url(dirname(__FILE__));
  wp_enqueue_style( 'team-metabox-css', $plugin_url . 'style.css', array(), 'v4.0.0');
endif;

if ( ! function_exists( 'tm_fixture_remove_metadatabox' ) ):
  function tm_fixture_remove_metadatabox() {
    remove_meta_box( 'tagsdiv-tm_opposition', 'tm_fixture', 'normal' );
    remove_meta_box( 'tagsdiv-tm_season', 'tm_fixture', 'normal' );
    remove_meta_box( 'tagsdiv-tm_competition', 'tm_fixture', 'normal' );
  }
  add_action( 'admin_menu' , 'tm_fixture_remove_metadatabox' );
endif;

/* When the post is saved, saves our custom data */
if ( ! function_exists( 'tm_fixture_save_postdata' ) ):
  function tm_fixture_save_postdata( $post_id )
  {
    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['tm_fixture_nonce'], 'tm_fixture_field_nonce' ) )
    return;

    if ( isset($_POST['tm_fixture_team']) ){
      update_post_meta( $post_id, 'tm_fixture_team', $_POST['tm_fixture_team'] );
    }

    if ( isset($_POST['tm_team_competition']) ){
      tm_fixture_update_competition( $_POST['tm_team_competition'] );
    }

    if ( isset($_POST['tm_fixture_season']) ){
      tm_fixture_update_section( $_POST['tm_fixture_season'] );
    }

    if ( isset($_POST['tm_fixture_date']) ){
      tm_fixture_update_date( $_POST['tm_fixture_date'] );
    }

    if ( isset($_POST['tm_fixture_scorefor']) ){
      tm_fixture_update_scorefor( $_POST['tm_fixture_scorefor'] );
    }

    if ( isset($_POST['tm_fixture_scoreagainst']) ){
      tm_fixture_update_scoreagainst( $_POST['tm_fixture_scoreagainst'] );
    }

  }
  add_action( 'save_post', 'tm_fixture_save_postdata' );
endif;
?>
