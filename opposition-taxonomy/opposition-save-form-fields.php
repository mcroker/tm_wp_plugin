<?php
if ( ! function_exists( 'tm_opposition_save' ) ):
  function tm_opposition_save( $term_id, $tt_id ){
    if ( isset($_POST['tm_opposition_url']) ){
      tm_opposition_update_url($term_id, $_POST['tm_opposition_url']);
    }
    if( isset( $_POST['tm_opposition_logo'] ) ) {
      tm_opposition_update_logo( $term_id, $_POST['tm_opposition_logo'] );
    }
  }
  add_action( 'created_tm_opposition', 'tm_opposition_save', 10, 2 );
  add_action( 'edited_tm_opposition', 'tm_opposition_save', 10, 2 );
endif;
?>
