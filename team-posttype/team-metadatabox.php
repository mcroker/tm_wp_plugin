<?php

if ( ! function_exists( 'tm_team_create_metadatabox' ) ):
  function tm_team_create_metadatabox() {
    add_meta_box(
      'tm_teammeta',
      'Team Metadata',
      'tm_team_inner_custom_box',
      'tm_team',
      'side',
      'high'
    );
  }
  add_action( 'add_meta_boxes', 'tm_team_create_metadatabox' );
  $plugin_url = plugin_dir_url(__FILE__);
  wp_enqueue_script( 'team-metabox-js', $plugin_url . 'team-metadatabox.js', array('jquery'), 'v4.0.0', true );
  wp_enqueue_style( 'plugin-css', dirname($plugin_url) . 'style.css', array(), 'v4.0.0');
endif;

if ( ! function_exists( 'tm_team_remove_metadatabox' ) ):
  function tm_team_remove_metadatabox() {
    remove_meta_box( 'tagsdiv-tm_competition', 'tm_team', 'normal' );
    remove_meta_box( 'tagsdiv-tm_section', 'tm_team', 'normal' );
  }
  add_action( 'admin_menu' , 'tm_team_remove_metadatabox' );
endif;

if ( ! function_exists( 'tm_team_inner_custom_box' ) ):
  function tm_team_inner_custom_box($post)
  {
    wp_localize_script( 'team-metabox-js', 'tm_php_object', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'team_id' => $post->ID
    ) );

    // Use nonce for verification
    wp_nonce_field( 'tm_team_field_nonce', 'tm_team_nonce' );

    // TODO : This all needs to move to a enquesed script
    ?>
    <script>

    </script>
    <?
    $competitions = tm_competiton_getall();
    $saved_competition = tm_team_get_competition();
    ?>
    <label for="tm_team_competition"> Competition </label><br>
    <select class="tm-meta-fullinput" id='tm_team_competition' name='tm_team_competition' onchange='java_script_:getLeagueTeams(this.options[this.selectedIndex].value)'>
      <option value=''>None</option>
      <?php foreach($competitions as $competition) { ?>
        <option value='<?php echo $competition->term_id ?>' <?php selected( $competition->term_id, $saved_competition->term_id ) ?>> <?php echo $competition->name ?></option>
      <?php } ?>
    </select>
    <br>

    <?php
    $leagueteams = tm_competition_get_teams( $saved_competition->term_id );
    $saved_leagueteam = tm_team_get_leagueteam();
    ?>
    <label for="tm_team_leagueteam"> Team </label><br>
    <select class="tm-meta-fullinput" id='tm_team_leagueteam' name='tm_team_leagueteam'>
      <?php foreach($leagueteams as $leagueteam) { ?>
        <option value='<?php echo $leagueteam ?>' <?php selected( $leagueteam , $saved_leagueteam ) ?>> <?php echo $leagueteam ?></option>
      <?php } ?>
    </select><br>

    <?php
    $sections = tm_section_getall();
    $saved_section = tm_team_get_section();
    ?>
    <label for="tm_team_section"> Section </label><br>
    <select class="tm-meta-fullinput" id='tm_team_section' name='tm_team_section'>
      <option value=''>None</option>
      <?php foreach($sections as $section) { ?>
        <option value='<?php echo $section->term_id ?>' <?php selected( $section->term_id, $saved_section->term_id ) ?>> <?php echo $section->name ?></option>
      <?php } ?>
    </select><br>

    <input id='tm-team-autofetch' class='button' type='button' onclick='java_script_:execTeamAutoFetcher()' value='Fetch Fixtures'>
    <div><span id='tm-update-status'></span></div>

    <?php
  }
endif;

if ( ! function_exists( 'tm_team_save_postdata' ) ):
  function tm_team_save_postdata( $post_id )
  {
    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['tm_team_nonce'], 'tm_team_field_nonce' ) )
    return;

    if ( isset($_POST['tm_team_competition']) ){
      tm_team_update_competition( $_POST['tm_team_competition'] );
    }

    if ( isset($_POST['tm_team_leagueteam']) ){
      tm_team_update_leagueteam( $_POST['tm_team_leagueteam'] );
    }

    if ( isset($_POST['tm_team_section']) ){
      tm_team_update_section( $_POST['tm_team_section'] );
    }

  }
  add_action( 'save_post', 'tm_team_save_postdata' );
endif;
?>
