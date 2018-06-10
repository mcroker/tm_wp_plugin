<?php
if ( ! function_exists( 'tm_competition_exec_update' ) ):
  function tm_competition_exec_update( $term_id ){
    global $tm_competition_autofetchers;
    $saved_autofetcher = tm_get_competition_autofetcher ( $term_id );
    // Only do something if the autofetcher is still registered
    if ( array_key_exists ($saved_autofetcher, $tm_competition_autofetchers ) && $saved_autofetcher != 'none' ) {
      if ( function_exists( $saved_autofetcher . '_competition_exec_update' ) ) {
        $saved_autofetcheropts = tm_get_competition_autofetcher_options ( $term_id );
        $term = tm_get_competiton_byid( $term_id );
        $saved_autofetcheropts['tm_competition_name'] = $term->name;
        $saved_autofetcheropts['tm_competition_id'] = $term_id;
        $saved_autofetcheropts['tm_competition_slug'] = $term->slug;
        $saved_autofetcheropts['tm_competition_description'] = $term->descriotion;
        if ( ! isset($saved_autofetcheropts['tm_competition_seasons']) || $saved_autofetcheropts['tm_competition_seasons'] == '' ) {
          $saved_autofetcheropts['tm_competition_seasons'] = get_option( 'tm_default_season' );
        }
        $saved_leaguetable = tm_get_competition_leaguetable_data( $term_id );
        $leaguetabledata = call_user_func($saved_autofetcher . '_competition_exec_update', $saved_leaguetable, $saved_autofetcheropts );
        tm_update_competition_leaguetable_data( $term_id , $leaguetabledata );
      }
    }
  }
endif;

if ( ! function_exists( 'tm_competition_exec_update_all' ) ):
  function tm_competition_exec_update_all() {
    echo '{ "competitons": [';
    foreach(tm_get_competitons() as $competition) {
      echo '"competition": "' . $competition->name . '"';
      echo ',"update": {';
      tm_competition_exec_update( $competition->term_id );
      echo '}';
    }
    echo '] }';
  }
endif;

?>
