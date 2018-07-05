<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

is_admin() && require_once('fixture-admin-postlist.php');
is_admin() && require_once('fixture-admin-metadatabox.php');
is_admin() && require_once('fixture-admin-savemetadata.php');

// Our custom post type function
if ( ! function_exists( 'tm_fixture_create_posttype' )):
  function tm_fixture_create_posttype() {
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
    $slug = ( empty( $slug ) ) ? 'fixtures' : $slug;

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
      'hierarchical'        => false,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5,
      'can_export'          => true,
      'has_archive'         => false,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'rewrite'             => array( 'slug' => 'team/%teamname%/fixtures/%season%', 'with_front' => false ),
      'capability_type'     => 'post'
    );
    register_post_type( 'tm_fixture', $args );
  }
  add_action( 'init', 'tm_fixture_create_posttype' );
endif;

/*
      'rewrite'             => array( 'slug' => $slug, 'with_front' => false ),
*/

function tm_fixture_rewriteurl() {
  $teamslug = get_theme_mod( 'team_permalink' );
  $teamslug = ( empty( $slug ) ) ? 'team' : $slug;
  add_rewrite_rule('^' . $teamslug . '/([^/]+)/fixtures/([^/]+)/([^/]+)/?','index.php?post_type=tm_fixture&teamname=$matches[1]&season=$matches[2]&name=$matches[3]','top');
   global $wp_rewrite;
   $wp_rewrite->flush_rules(false);
}
add_action('init', 'tm_fixture_rewriteurl');

function tm_fixture_permalinks( $post_link, $post ){
    if ( is_object( $post ) && $post->post_type == 'tm_fixture' ) {
        $fixture = new TMFixture($post);
        if ( ( $fixture->team ) ) {
            $post_link = str_replace( '%teamname%' , $fixture->team->slug , $post_link );
        }
        if ( ( $fixture->season ) ) {
            $post_link = str_replace( '%season%' , $fixture->season->slug, $post_link );
        }
    }
    return $post_link;
}
add_filter( 'post_type_link', 'tm_fixture_permalinks', 1, 2 );
?>
