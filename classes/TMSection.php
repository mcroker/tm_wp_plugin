<?php
require_once('TMBaseTax.php');

if ( ! class_exists('TMSection')):
  class TMSection extends TMBaseTax {
    public static $taxonomy = 'tm_section';
    function __construct($term_id = 0) {
      parent::__construct($term_id);
    }
  }
endif;
?>
