<?php

if ( ! function_exists( 'tm_team_create_metadatabox' ) ):
  function tm_team_create_metadatabox() {
    add_meta_box(
      'tm_teammeta',
      'Team Metadata',
      'tm_team_inner_custom_box',
      'tm_team',
      'side',
      'default'
    );
  }
  // add_action( 'add_meta_boxes', 'tm_team_create_metadatabox' );
endif;


if ( ! function_exists( 'tm_team_enqueue_adminscripts' )):
  function tm_team_enqueue_adminscripts($hook) {
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_script( 'team-metabox-js', $plugin_url . 'team-metadatabox.js', array('jquery'), 'v4.0.0', true );
    wp_enqueue_style( 'plugin-css', dirname($plugin_url) . '/style.css', array(), 'v4.0.0');
  }
  // add_action( 'admin_enqueue_scripts', 'tm_team_enqueue_adminscripts' );
endif;




if ( ! function_exists( 'tm_team_inner_custom_box' ) ):
  function tm_team_inner_custom_box($post)
  {
    wp_localize_script( 'team-metabox-js', 'tmphpobject', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'team_id' => $post->ID
    ) );

    // Use nonce for verification
    wp_nonce_field( 'tm_team_field_nonce', 'tm_team_nonce' );

    $team = new TMTeam($post);
    ?>

    <?php $leagueteams = tm_competition_get_teams( $saved_competition->term_id ); ?>
    <label for="tm_team_leagueteam"> Team </label><br>
    <select class="tm-meta-fullinput" id='tm_team_leagueteam' name='tm_team_leagueteam'>
      <?php foreach($leagueteams as $leagueteam) { ?>
        <option value='<?php echo $leagueteam ?>' <?php selected( $leagueteam , $team->leagueteam ) ?>> <?php echo $leagueteam ?></option>
      <?php } ?>
    </select><br>

    <div class="tm-meta-smallinput">
      <label for="tm_team_autofetch"> Automatically fetch fixtures? </label>
      <input type='checkbox' class='tm-meta-smallinput' id='tm_team_useautofetch' name='tm_team_useautofetch' <?php checked($team->useautofetch) ?>>
    </div><br>

    <input id='tm-team-autofetch' class='button' type='button' onclick='java_script_:execTeamAutoFetcher()' value='Fetch Fixtures'>
    <div><span id='tm-update-status'></span></div>

    <?php
  }
endif;

?>
