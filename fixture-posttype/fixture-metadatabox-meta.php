<?php

/* Adds a box to the main column on the Post and Page edit screens */
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

/* Prints the box content */
function tm_fixture_inner_custom_box($post)
{
  // Use nonce for verification
  wp_nonce_field( 'tm_fixture_field_nonce', 'tm_fixture_nonce' );

  // Get saved value, if none exists, "default" is selected
  $saved_team = get_post_meta( $post->ID, 'tm_fixture_team', true);
  $teams = get_posts(array (
    'post_type' => 'tm_team',
    'post_status' => 'published'
  ));
  wp_reset_query();
  printf('<select name="tm_fixture_team" id="tm_fixture_team"/>');
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
  printf('<label for="tm_fixture_team"> Team </label><br>');

  $saved_date = get_post_meta( $post->ID, 'tm_fixture_date', true);
  printf(
    '<input type="date" name="tm_fixture_date" value="%1$s" id="tm_fixture_date"/>'.
    '<label for="tm_fixture_date">Fixture Date' .
    '</label><br>',
    esc_attr(trim($saved_date))
  );

  $saved_scorefor = get_post_meta( $post->ID, 'tm_fixture_scorefor', true);
  printf(
    '<input type="number" name="tm_fixture_scorefor" value="%1$s" id="tm_fixture_scorefor"/>'.
    '<label for="tm_fixture_scorefor">Home Score</label><br>',
    esc_attr(trim($saved_scorefor))
  );

  $saved_scoreagainst = get_post_meta( $post->ID, 'tm_fixture_scoreagainst', true);
  printf(
    '<input type="number" name="tm_fixture_scoreagainst" value="%1$s" id="tm_fixture_scoreagainst"/>'.
    '<label for="tm_fixture_scoreagainst">Opposition Score</label><br>',
    esc_attr(trim($saved_scoreagainst))
  );

}

/* When the post is saved, saves our custom data */
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

  if ( isset($_POST['tm_fixture_date']) && $_POST['tm_fixture_date'] != "" ){
    update_post_meta( $post_id, 'tm_fixture_date', $_POST['tm_fixture_date'] );
  }

  if ( isset($_POST['tm_fixture_team']) && $_POST['tm_fixture_team'] != "" ){
    update_post_meta( $post_id, 'tm_fixture_team', $_POST['tm_fixture_team'] );
  }

  if ( isset($_POST['tm_fixture_scorefor']) && $_POST['tm_fixture_scorefor'] != "" ){
    update_post_meta( $post_id, 'tm_fixture_scorefor', $_POST['tm_fixture_scorefor'] );
  }

  if ( isset($_POST['tm_fixture_scoreagainst']) && $_POST['tm_fixture_scoreagainst'] != "" ){
    update_post_meta( $post_id, 'tm_fixture_scoreagainst', $_POST['tm_fixture_scoreagainst'] );
  }

}

?>
