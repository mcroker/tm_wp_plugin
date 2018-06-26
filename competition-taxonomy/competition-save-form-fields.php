<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Save Values ==================================================
if ( is_admin() && ! function_exists( 'tm_competition_save_values' ) ):
  function tm_competition_save_values($term_id, $data){
    $competition = new TMCompetition($term_id);

    $autofetcheropts = $competition->autofetcheropts;

    if ( isset($data['tm_competition_autofetch']) ){
      $competition->autofetcher = $data['tm_competition_autofetch'];
    }

    if ( isset($data['tm_competition_sortkey']) ) {
      $autofetcheropts['tm_competition_sortkey'] = $data['tm_competition_sortkey'];
    }

    $autofetcheropts = tm_autofetch_competition_saveoptions($competition->autofetcher, $data) + $autofetcheropts;

    $competition->autofetcheropts = $autofetcheropts;
  }
endif;

// Save Post ==================================================
if ( is_admin() && ! function_exists( 'tm_competition_save' ) ):
  function tm_competition_save( $term_id, $tt_id ){
    return tm_competition_save_values($term_id, $_POST);
  }
  add_action( 'created_tm_competition', 'tm_competition_save', 10, 2 );
  add_action( 'edited_tm_competition', 'tm_competition_save', 10, 2 );
endif;
?>
