<?php
require_once('fixture-functions.php');
require_once('fixture-posttype.php');
require_once('fixture-adminpostlist.php');
require_once('fixture-metadatabox-meta.php');
require_once('fixture-metadatabox-matchreport.php');

// Our custom post type function
if ( ! function_exists( 'tm_create_posttype_fixture' )):
  function tm_create_posttype_fixture() {
    // Set UI labels for Custom Post Type
    $labels = array(
      'name'                => _x( 'Fixtures', 'Post Type General Name', 'tm' ),
      'singular_name'       => _x( 'Fixture', 'Post Type Singular Name', 'tm' ),
      'menu_name'           => __( 'Fixtures', 'tm' ),
      'parent_item_colon'   => __( 'Parent Fixture', 'tm' ),
      'all_items'           => __( 'All Fixtures', 'tm' ),
      'view_item'           => __( 'View Fixture', 'tm' ),
      'add_new_item'        => __( 'Add New Fixture', 'tm' ),
      'add_new'             => __( 'Add New', 'tm' ),
      'edit_item'           => __( 'Edit Fixture', 'tm' ),
      'update_item'         => __( 'Update Fixture', 'tm' ),
      'search_items'        => __( 'Search Fixture', 'tm' ),
      'not_found'           => __( 'Not Found', 'tm' ),
      'not_found_in_trash'  => __( 'Not found in Trash', 'tm' ),
    );

    $slug = get_theme_mod( 'fixture_permalink' );
    $slug = ( empty( $slug ) ) ? 'fixture' : $slug;

    $args = array(
      'label'               => __( 'Fixtures', 'tm' ),
      'description'         => __( 'Fixture news and reviews', 'tm' ),
      'labels'              => $labels,
      // Features this CPT supports in Post Editor
      'supports'            => array( 'title', 'editor', 'revisions'),
      //'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
      // You can associate this CPT with a taxonomy or custom taxonomy.
      // 'taxonomies'          => array( 'genres' ),
      /* A hierarchical CPT is like Pages and can have
      * Parent and child items. A non-hierarchical CPT
      * is like Posts.
      */
      'hierarchical'        => true,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5,
      'can_export'          => true,
      'has_archive'         => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'rewrite'             => array( 'slug' => $slug ),
      'capability_type'     => 'page',
    );

    register_post_type( 'tm_fixture', $args );
  }
  add_action( 'init', 'tm_create_posttype_fixture' );
endif;
?>
