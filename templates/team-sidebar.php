<?
if ( ! function_exists('tm_create_sidebars_team' ) ):
  function tm_create_sidebars_team() {

    register_sidebar( array(
      'name'          => __( 'TM-Teampage-Main', 'tm' ),
      'id'            => 'tm-teampage-main-1',
      'description'   => '',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<h1 class="widget-title">',
      'after_title'   => '</h1>',
    ) );

    register_sidebar( array(
      'name'          => __( 'TM-Teampage-Side', 'tm' ),
      'id'            => 'tm-teampage-side-1',
      'description'   => '',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<h1 class="widget-title">',
      'after_title'   => '</h1>',
    ) );

  }
  add_action('init', 'tm_create_sidebars_team');
endif;
?>
