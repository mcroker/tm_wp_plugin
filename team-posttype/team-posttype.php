<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


is_admin() && require_once('team-admin-postlist.php');
is_admin() && require_once('team-admin-metadatabox-autofetch.php');
is_admin() && require_once('team-admin-metadatabox-coaches.php');
is_admin() && require_once('team-admin-metadatabox-players.php');
is_admin() && require_once('team-admin-ajax.php');
is_admin() && require_once('team-admin-savemetadata.php');

// Our custom post type function
if ( ! function_exists( 'tm_team_create_posttype' ) ):
  function tm_team_create_posttype() {

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
    $slug = get_theme_mod( 'team_permalink' );
    $slug = ( empty( $slug ) ) ? 'team' : $slug;

    $args = array(
      'label'               => __( 'Teams', 'tm' ),
      'description'         => __( 'Team news and reviews', 'tm' ),
      'labels'              => $labels,
      'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions' ),
      'hierarchical'        => false,
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
      'rewrite'             => array( 'slug' => $slug, 'with_front' => false ),
      'capability_type'     => 'post',
    );

    // Registering your Custom Post Type
    register_post_type( 'tm_team', $args );
  }
  add_action( 'init', 'tm_team_create_posttype' );
endif;

if ( ! function_exists( 'tm_team_rewriteurl' ) ):
  function tm_team_rewriteurl() {
    // Set other options for Custom Post Type
    $slug = get_theme_mod( 'team_permalink' );
    $slug = ( empty( $slug ) ) ? 'team' : $slug;
    add_rewrite_rule('^' . $slug . '/([^/]+)/(table|fixtures|coaches|players|details|ical|list)/?$','index.php?post_type=tm_team&name=$matches[1]&view=$matches[2]','top');
  }
  add_action('init', 'tm_team_rewriteurl');
endif;

if ( ! function_exists( 'tm_team_add_query_vars' ) ):
  function tm_team_add_query_vars( $vars ) {
    $vars[] = "view";
    return $vars;
  }
add_filter( 'query_vars', 'tm_team_add_query_vars' );
endif;


?>
