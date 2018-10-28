<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once('TMBasePost.php');

if ( ! class_exists('TMSeason')):
  class TMSeason extends TMBaseTax {
    public static $taxonomy = 'tm_season';

    protected static $associate_post_types = array('tm_fixture');

    protected static $labels = Array(
      'singular_name'       => 'Season'
    );

    protected static $args = Array (
    );

    function __construct($term_id = 0) {
      parent::__construct($term_id);
    }

  }
  TMSeason::init();
endif;
?>
