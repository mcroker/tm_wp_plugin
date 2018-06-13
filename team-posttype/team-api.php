<?php
function tm_team_test() {
  // 11Guernsey2018-04-210
  //       echo $team_id, $result->opposition , $result->fixturedate->format('Y-m-d') . sizeof( $fixtures ); wp_die();

  //$data = tm_fixture_update_opposition_withslug('Guernsey', 3090 );
  //$data->slug = 'x';


   $fixtures = get_posts(array(
    'numberposts'	=> -1,
    'post_type'		=> 'tm_fixture',
    'post_status' => 'publish',
    'meta_query'	=> array(
      'relation'	=> 'AND',
      array(
        'key'	 	  => 'tm_fixture_team',
        'value'	  => 11,
        'compare' => '='
      ) ,
      array(
        'key'	  	=> 'tm_fixture_date',
        'value'	  => '2017-12-16',
        'compare' => '='
      ),
    ),
    'tax_query' => array(
      array(
        'taxonomy' => 'tm_opposition',
        'field'    => 'slug',
        'terms'    => 'Barnes'
      )),
  ));

  wp_send_json($fixtures);
};

add_action( 'rest_api_init', function () {
  register_rest_route( 'tm/v1', '/test', array(
    'methods' => 'GET',
    'callback' => 'tm_team_test',
  ) );
} );
?>
