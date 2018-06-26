<?php
if ( ! function_exists( 'tm_opposition_save' ) ):
  function tm_opposition_save( $term_id, $tt_id ){

    $opposition = new TMOpposition($term_id);

    if ( isset($_POST['tm_opposition_url']) ){
      $opposition->url = $_POST['tm_opposition_url'];
    }
    if( isset( $_POST['tm_opposition_logo'] ) ) {
      $opposition->logo = $_POST['tm_opposition_logo'];
    }
  }
  add_action( 'created_tm_opposition', 'tm_opposition_save', 10, 2 );
  add_action( 'edited_tm_opposition', 'tm_opposition_save', 10, 2 );
endif;
?>
