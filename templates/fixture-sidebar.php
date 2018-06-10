<?


function tm_create_sidebars_fixture() {

  register_sidebar( array(
    'name'          => __( 'TM-Fixture-Main', 'tm' ),
    'id'            => 'tm-fixture-main-1',
    'description'   => '',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h1 class="widget-title">',
    'after_title'   => '</h1>',
  ) );

  register_sidebar( array(
    'name'          => __( 'TM-Fixture-Side', 'tm' ),
    'id'            => 'tm-fixture-side-1',
    'description'   => '',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h1 class="widget-title">',
    'after_title'   => '</h1>',
  ) );

}

?>
