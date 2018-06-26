<?php

require_once('team-adminpostlist.php');
require_once('team-metadatabox.php');
require_once('team-metadatabox-coaches.php');
require_once('team-metadatabox-players.php');
require_once('team-update-results.php');
require_once('team-ajax.php');
require_once('team-save-form-fields.php');

// Our custom post type function
if ( ! function_exists( 'tm_create_posttype_team' ) ):
  function tm_create_posttype_team() {

    // Set UI labels for Custom Post Type
    $labels = array(
      'name'                => _x( 'Teams', 'Post Type General Name', 'tm' ),
      'singular_name'       => _x( 'Team', 'Post Type Singular Name', 'tm' ),
      'menu_name'           => __( 'Teams', 'tm' ),
      'parent_item_colon'   => __( 'Parent Team', 'tm' ),
      'all_items'           => __( 'All Teams', 'tm' ),
      'view_item'           => __( 'View Team', 'tm' ),
      'add_new_item'        => __( 'Add New Team', 'tm' ),
      'add_new'             => __( 'Add New', 'tm' ),
      'edit_item'           => __( 'Edit Team', 'tm' ),
      'update_item'         => __( 'Update Team', 'tm' ),
      'search_items'        => __( 'Search Team', 'tm' ),
      'not_found'           => __( 'Not Found', 'tm' ),
      'not_found_in_trash'  => __( 'Not found in Trash', 'tm' ),
    );

    // Set other options for Custom Post Type
    $slug = get_theme_mod( 'fixture_permalink' );
    $slug = ( empty( $slug ) ) ? 'team' : $slug;

    $args = array(
      'label'               => __( 'Teams', 'tm' ),
      'description'         => __( 'Team news and reviews', 'tm' ),
      'labels'              => $labels,
      'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions' ),
      'hierarchical'        => true,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5.01,
      'can_export'          => true,
      'has_archive'         => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'rewrite'             => array( 'slug' => $slug ),
      'capability_type'     => 'page',
    );

    // Registering your Custom Post Type
    register_post_type( 'tm_team', $args );
  }
  add_action( 'init', 'tm_create_posttype_team' );
endif;

?>
