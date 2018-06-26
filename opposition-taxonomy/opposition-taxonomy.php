<?php
require_once('opposition-logo-field.php');
require_once('opposition-edit-form-fields.php');
require_once('opposition-add-form-fields.php');
require_once('opposition-save-form-fields.php');

if ( ! function_exists( 'tm_registertaxonomy_opposition' ) ):
  function tm_registertaxonomy_opposition() {
    $labels = array(
      'name' => _x( 'Oppositions', 'taxonomy general name', 'tm' ),
      'singular_name' => _x('Oppositions', 'taxonomy singular name', 'tm'),
      'search_items' => __('Search Opposition', 'tm'),
      'popular_items' => __('Common Oppositions', 'tm'),
      'all_items' => __('All Oppositions', 'tm'),
      'edit_item' => __('Edit Opposition', 'tm'),
      'update_item' => __('Update Opposition', 'tm'),
      'add_new_item' => __('Add new Opposition', 'tm'),
      'new_item_name' => __('New Opposition:', 'tm'),
      'add_or_remove_items' => __('Remove Opposition', 'tm'),
      'choose_from_most_used' => __('Choose from common Opposition', 'tm'),
      'not_found' => __('No Opposition found.', 'tm'),
      'menu_name' => __('Oppositions', 'tm'),
    );

    $args = array(
      'hierarchical' => false,
      'labels' => $labels,
      'show_ui' => true
    );

    register_taxonomy('tm_opposition', array('tm_fixture'), $args);
  }
  add_action('init', 'tm_registertaxonomy_opposition');
endif;
?>
