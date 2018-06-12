<?php
if ( ! function_exists( 'tm_competition_save' ) ):
  function tm_competition_save( $term_id, $tt_id ){
    global $tm_competition_autofetchers;
    $autofetcheropts = tm_competition_get_autofetcher_options ( $term_id );
    if( isset( $_POST['tm_competition_autofetch'] ) && '' !== $_POST['tm_competition_autofetch'] ){
      tm_competition_update_autofetcher( $term_id, $_POST['tm_competition_autofetch'] );
    }
    if( isset( $_POST['tm_competition_seasons'] ) ){
      $autofetcheropts['tm_competition_seasons'] = $_POST['tm_competition_seasons'];
    }

    if ( function_exists( $_POST['tm_competition_autofetch'] . '_competition_saveoptions' ) ) {
      $autofetcheropts = call_user_func($_POST['tm_competition_autofetch'] . '_competition_saveoptions', $_POST) + $autofetcheropts;
    }

    tm_competition_update_autofetcher_options( $term_id , $autofetcheropts );
  }
endif;
?>
