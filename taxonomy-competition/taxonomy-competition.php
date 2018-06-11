<?php

require_once('add-form-fields.php');
require_once('edit-form-fields.php');
require_once('save-form-fields.php');
require_once('exec-update.php');
require_once('competition-shortcode.php');
require_once('competition-functions.php');
require_once('update-api.php');
require_once('update-ajax.php');

add_action('init', 'tm_registertaxonomy_competition');

if ( ! function_exists( 'tm_registertaxonomy_competition' ) ):
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

    do_action( 'tm_competition_register_plugins' );
  }
endif;

// Autofetchers ===================================================
$tm_competition_autofetchers = Array (
  'none' => 'No Automatic Data Update'
);

?>
