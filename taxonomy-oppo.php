<?php

function tm_registertaxonomy_oppo() {
  $labels = array(
    'name' => _x( 'Oppositions', 'taxonomy general name', 'twrfc_domain' ),
    'singular_name' => _x('Oppositions', 'taxonomy singular name', 'twrfc_domain'),
    'search_items' => __('Search Opposition', 'twrfc_domain'),
    'popular_items' => __('Common Oppositions', 'twrfc_domain'),
    'all_items' => __('All Oppositions', 'twrfc_domain'),
    'edit_item' => __('Edit Opposition', 'twrfc_domain'),
    'update_item' => __('Update Opposition', 'twrfc_domain'),
    'add_new_item' => __('Add new Opposition', 'twrfc_domain'),
    'new_item_name' => __('New Opposition:', 'twrfc_domain'),
    'add_or_remove_items' => __('Remove Opposition', 'twrfc_domain'),
    'choose_from_most_used' => __('Choose from common Opposition', 'twrfc_domain'),
    'not_found' => __('No Opposition found.', 'twrfc_domain'),
    'menu_name' => __('Oppositions', 'twrfc_domain'),
  );

  $args = array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true
  );

  register_taxonomy('tm_opposition', array('tm_fixture'), $args);

}

?>
