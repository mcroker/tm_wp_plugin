<?php

function tm_plugin_templates( $template ) {
    $post_types = array( 'tm_fixture', 'tm_team' );
    $post = get_post();

    // if ( is_post_type_archive( $post_types ) && ! file_exists( get_stylesheet_directory() . '/archive-fixture.php' ) )
    //    $template = 'fixture-template.php';
    if ( is_singular( $post_types ) && ! file_exists( get_stylesheet_directory() . '/single-' . $post->post_type . '.php' ) )
        $template = plugin_dir_path( __FILE__ ) . '/templates/' . substr($post->post_type,3) . '-template.php';

    return $template;
}

?>
