<?php

function tm_registertaxonomy_competition() {
  $labels = array(
    'name' => _x( 'Competitions', 'taxonomy general name', 'tm' ),
    'singular_name' => _x('Competitions', 'taxonomy singular name', 'tm'),
    'search_items' => __('Search Competition', 'tm'),
    'popular_items' => __('Common Competitions', 'tm'),
    'all_items' => __('All Competitions', 'tm'),
    'edit_item' => __('Edit Competition', 'tm'),
    'update_item' => __('Update Competition', 'tm'),
    'add_new_item' => __('Add new Competition', 'tm'),
    'new_item_name' => __('New Competition:', 'tm'),
    'add_or_remove_items' => __('Remove Competition', 'tm'),
    'choose_from_most_used' => __('Choose from common Competition', 'tm'),
    'not_found' => __('No Competition found.', 'tm'),
    'menu_name' => __('Competitions', 'tm'),
  );

  $args = array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true
  );

  register_taxonomy('tm_competition', array('tm_fixture','tm_team'), $args);
  add_action( 'tm_competition_add_form_fields', 'tm_competition_add_form_fields', 10, 2 );
  add_action( 'tm_competition_edit_form_fields', 'tm_competition_edit_form_fields', 10, 2 );
  add_action( 'created_tm_competition', 'tm_competition_save', 10, 2 );
  add_action( 'edited_tm_competition', 'tm_competition_save', 10, 2 );
}

function tm_competition_add_form_fields($taxonomy) {
    ?><div class="form-field term-group">
        <label for="tm_competition_leaguetable"><?php _e('League Table', 'tm'); ?></label>
        <textarea id="tm_competition_leaguetable" name="tm_competition_leaguetable"></textarea>
    </div><?php
}

function tm_competition_edit_form_fields($term) {
  $leaguetable = get_term_meta( $term->ID, 'tm_competition_leaguetable' );
  ?><div class="form-field term-group">
      <label for="tm_competition_leaguetable"><?php _e('League Table', 'tm'); ?></label>
      <textarea id="tm_competition_leaguetable" name="tm_competition_leaguetable"><?php echo $leaguetable ?></textarea>
  </div><?php
}

function tm_competition_save( $term_id, $tt_id ){
    if( isset( $_POST['tm_competition_leaguetable'] ) && '' !== $_POST['tm_competition_leaguetable'] ){
        add_term_meta( $term_id, 'tm_competition_leaguetable', $_POST['tm_competition_leaguetable'], true );
    }
}

// Helper Functions ===================================================
function tm_get_competitons() {
  return get_terms( 'tm_competition' );
}

function tm_get_competiton($competition) {
  return get_term( Array ( 'name' => $competition ), 'tm_competition' );
}

function tm_get_competition_leaguetable_data($term_id) {
  return get_term_meta( $term_id, 'tm_competition_leaguetable' );
}

function tm_get_competition_leaguetable($term_id, $season) {
  $data = tm_get_competition_leaguetable_data($term_id);
  return $data[$season];
}

function tm_update_competition_leaguetable_data($term_id, $data) {
  return update_term_meta( $term_id, 'tm_competition_leaguetable' , $data );
}

?>
