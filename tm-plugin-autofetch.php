<?php
// Autofetchers ===================================================
$tm_autofetch_registeredplugins = Array (
  'none' => 'No Automatic Data Update'
);

if ( ! function_exists( 'tm_autofetch_init' ) ):
  function tm_autofetch_init() {
    do_action( 'tm_autofetch_register_plugins' );
  }
  add_action('init', 'tm_autofetch_init');
endif;

if ( ! function_exists( 'tm_autofetch_register_plugin' ) ):
  function tm_autofetch_register_plugin( $autofetchid , $autofetchdescription = '' ) {
    global $tm_autofetch_registeredplugins;
    if ( $autofetchdescription == '' ) {
      $autofetchdescription = $autofetchid;
    }
    $tm_autofetch_registeredplugins[$autofetchid] = $autofetchdescription;
  }
endif;

if ( ! function_exists( 'tm_autofetch_get_plugins' ) ):
  function tm_autofetch_get_plugins() {
    global $tm_autofetch_registeredplugins;
    return $tm_autofetch_registeredplugins;
  }
endif;

if ( ! function_exists( 'tm_autofetch_fetch_results' ) ):
  function tm_autofetch_isvalidplugin($plugin_id) {
    global $tm_autofetch_registeredplugins;
    if ( array_key_exists ($plugin_id, $tm_autofetch_registeredplugins ) && $plugin_id != 'none' ) {
      return true;
    } else {
      return false;
    }
  }
endif;

// Invoke plugin functions interface ========================================
if ( ! function_exists( 'tm_autofetch_competition_saveoptions' ) ):
  function tm_autofetch_competition_saveoptions($plugin_id, $postdata) {
    if ( tm_autofetch_isvalidplugin($plugin_id) && function_exists($plugin_id . '_competition_saveoptions') ) {
      return call_user_func($plugin_id . '_competition_saveoptions', $postdata);
    } else {
      return Array();
    }
  }
endif;

if ( ! function_exists( 'tm_autofetch_fetch_seasons' ) ):
  function tm_autofetch_fetch_seasons($plugin_id, $autofetcheropts) {
    if ( tm_autofetch_isvalidplugin($plugin_id) && function_exists($plugin_id . '_fetch_seasons') ) {
      return call_user_func($plugin_id . '_fetch_seasons', $autofetcheropts);
    } else {
      return '';
    }
  }
endif;

if ( ! function_exists( 'tm_autofetch_fetch_leaguetable' ) ):
  function tm_autofetch_fetch_leaguetable($plugin_id, $autofetcheropts) {
    if ( tm_autofetch_isvalidplugin($plugin_id) && function_exists($plugin_id . '_fetch_leaguetable') ) {
      return call_user_func($plugin_id . '_fetch_leaguetable', $autofetcheropts);
    } else {
      return Array();
    }
  }
endif;

if ( ! function_exists( 'tm_autofetch_fetch_results' ) ):
  function tm_autofetch_fetch_results($plugin_id, $autofetcheropts) {
    if ( tm_autofetch_isvalidplugin($plugin_id) && function_exists($plugin_id . '_fetch_results') ) {
      return call_user_func($plugin_id . '_fetch_results', $autofetcheropts);
    } else {
      return '';
    }
  }
endif;

?>
