<?php
require_once('section-functions.php');

if ( ! function_exists( 'tm_registertaxonomy_competition' ) ):
  function tm_registertaxonomy_section() {
    $labels = array(
      'name' => _x( 'Sections', 'taxonomy general name', 'tm' ),
      'singular_name' => _x('Sections', 'taxonomy singular name', 'tm'),
      'search_items' => __('Search Section', 'tm'),
      'popular_items' => __('Common Sections', 'tm'),
      'all_items' => __('All Sections', 'tm'),
      'edit_item' => __('Edit Section', 'tm'),
      'update_item' => __('Update Section', 'tm'),
      'add_new_item' => __('Add new Section', 'tm'),
      'new_item_name' => __('New Section:', 'tm'),
      'add_or_remove_items' => __('Remove Section', 'tm'),
      'choose_from_most_used' => __('Choose from common Section', 'tm'),
      'not_found' => __('No Section found.', 'tm'),
      'menu_name' => __('Sections', 'tm'),
    );

    $args = array(
      'hierarchical' => false,
      'labels' => $labels,
      'show_ui' => true
    );

    register_taxonomy('tm_section', array('tm_team'), $args);
  }
  add_action('init', 'tm_registertaxonomy_section');
endif;
?>
