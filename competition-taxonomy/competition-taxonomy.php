<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

is_admin() && require_once('competition-admin-form.php');
is_admin() && require_once('competition-admin-save.php');
is_admin() && require_once('competition-admin-ajax.php');

/*
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
      'parent_item' => __('Parent', 'tm'),
      'parent_item_colon' => __('Parent:', 'tm'),
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
*/
?>
