<?php
require_once('competition-add-form-fields.php');
require_once('competition-edit-form-fields.php');
require_once('competition-save-form-fields.php');
require_once('competition-exec-update-leaguetable.php');
require_once('competition-exec-update-results.php');
require_once('competition-exec-update-all.php');
require_once('competition-functions.php');
require_once('competition-api.php');
require_once('competition-ajax.php');

if ( ! function_exists( 'tm_competition_registertaxonomy' ) ):
  function tm_competition_registertaxonomy() {
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
  }
  add_action('init', 'tm_competition_registertaxonomy');
endif;


?>
