<?php

if ( ! function_exists( 'tm_create_metadatabox_team' ) ):
  function tm_create_metadatabox_team() {
    add_meta_box(
      'tm_teammeta',
      'Team Metadata',
      'tm_team_inner_custom_box',
      'tm_team',
      'side',
      'high'
    );
  }
  add_action( 'add_meta_boxes', 'tm_create_metadatabox_team' );
endif;

if ( ! function_exists( 'tm_remove_metadatabox_team' ) ):
  function tm_remove_metadatabox_team() {
    remove_meta_box( 'tagsdiv-tm_competition', 'tm_team', 'normal' );
    remove_meta_box( 'tagsdiv-tm_section', 'tm_team', 'normal' );
  }
  add_action( 'admin_menu' , 'tm_remove_metadatabox_team' );
endif;

if ( ! function_exists( 'tm_team_inner_custom_box' ) ):
  function tm_team_inner_custom_box($post)
  {
    // Use nonce for verification
    wp_nonce_field( 'tm_team_field_nonce', 'tm_team_nonce' );

    $competitons = tm_competitons_getall();
    $saved_competition = tm_get_team_competition();
    ?>
    <label for="tm_team_competition"> Competition </label><br>
    <select id='tm_team_competition' name='tm_team_competition'>
      <option value=''>None</option>
      <?php foreach($competitons as $competition) { ?>
        <option value='<?php echo $competition->slug ?>' <?php selected( $competition->slug, $saved_competition->slug ) ?>> <?php echo $competition->name ?></option>
      <?php } ?>
    </select>
    <br>

    <?php
    $sections = tm_get_sections();
    $saved_section = tm_get_team_section();
    ?>
    <label for="tm_team_section"> Section </label><br>
    <select id='tm_team_section' name='tm_team_section'>
      <option value=''>None</option>
      <?php foreach($sections as $section) { ?>
        <option value='<?php echo $section->slug ?>' <?php selected( $section->slug, $saved_section->slug ) ?>> <?php echo $section->name ?></option>
      <?php } ?>
    </select>

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
      tm_update_team_competition( $_POST['tm_team_competition'] );
    }

    if ( isset($_POST['tm_team_section']) ){
      tm_update_team_section( $_POST['tm_team_section'] );
    }

  }
  add_action( 'save_post', 'tm_team_save_postdata' );
endif;
?>
