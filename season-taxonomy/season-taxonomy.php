<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists( 'tm_season_registertaxonomy' ) ):
  function tm_season_registertaxonomy() {
    $labels = array(
      'name' => _x( 'Seasons', 'taxonomy general name', 'tm' ),
      'singular_name' => _x('Seasons', 'taxonomy singular name', 'tm'),
      'search_items' => __('Search Season', 'tm'),
      'popular_items' => __('Common Seasons', 'tm'),
      'all_items' => __('All Seasons', 'tm'),
      'edit_item' => __('Edit Season', 'tm'),
      'update_item' => __('Update Season', 'tm'),
      'add_new_item' => __('Add new Season', 'tm'),
      'new_item_name' => __('New Season:', 'tm'),
      'add_or_remove_items' => __('Remove Season', 'tm'),
      'choose_from_most_used' => __('Choose from common Season', 'tm'),
      'not_found' => __('No Season found.', 'tm'),
      'menu_name' => __('Seasons', 'tm'),
    );

    $args = array(
      'hierarchical' => false,
      'labels' => $labels,
      'show_ui' => true
    );

    register_taxonomy('tm_season', array('tm_fixture'), $args);
  }
  add_action('init', 'tm_season_registertaxonomy');
endif;
?>
