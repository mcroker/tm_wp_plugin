<?php
require_once('TMBasePost.php');

if ( ! class_exists('TMSeason')):
  class TMSeason extends TMBaseTax {
    public static $taxonomy = 'tm_season';
    function __construct($term_id = 0) {
      parent::__construct($term_id);
    }
  }
endif;
?>
