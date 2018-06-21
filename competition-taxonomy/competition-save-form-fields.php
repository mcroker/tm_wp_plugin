<?php

if ( ! function_exists( 'tm_competition_save_values' ) ):
  function tm_competition_save_values($term_id, $data){
    $autofetcheropts = tm_competition_get_autofetcher_options($term_id);

    if ( isset($data['tm_competition_autofetch']) ){
      tm_competition_update_autofetcher($term_id, $data['tm_competition_autofetch']);
    }

    if ( isset($data['tm_competition_seasons']) ) {
      $autofetcheropts['tm_competition_seasons'] = $data['tm_competition_seasons'];
    }

    $autofetcheropts = tm_autofetch_competition_saveoptions($data['tm_competition_autofetch'], $data) + $autofetcheropts;

    tm_competition_update_autofetcher_options($term_id , $autofetcheropts);
  }
endif;


if ( ! function_exists( 'tm_competition_save' ) ):
  function tm_competition_save( $term_id, $tt_id ){
    return tm_competition_save_values($term_id, $_POST);
  }
  add_action( 'created_tm_competition', 'tm_competition_save', 10, 2 );
  add_action( 'edited_tm_competition', 'tm_competition_save', 10, 2 );
endif;
?>
