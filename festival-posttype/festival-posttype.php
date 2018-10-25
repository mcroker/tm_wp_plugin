<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//is_admin() && require_once('festival-admin-settings.php');
// is_admin() && require_once('fixture-admin-metadatabox.php');
// is_admin() && require_once('fixture-admin-savemetadata.php');

/*
      'rewrite'             => array( 'slug' => 'team/%teamname%/fixtures/%season%', 'with_front' => false ),

if ( ! function_exists( 'tm_fixture_rewriteurl' )):
function tm_fixture_rewriteurl() {
  $teamslug = get_theme_mod( 'team_permalink' );
  $teamslug = ( empty( $slug ) ) ? 'team' : $slug;
  add_rewrite_rule('^' . $teamslug . '/([^/]+)/fixtures/([^/]+)/([^/]+)/?','index.php?post_type=tm_fixture&teamname=$matches[1]&season=$matches[2]&name=$matches[3]','top');
   global $wp_rewrite;
   $wp_rewrite->flush_rules(false);
}
add_action('init', 'tm_fixture_rewriteurl');
endif;


if ( ! function_exists( 'tm_fixture_permalinks' )):
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
endif;
*/
?>
