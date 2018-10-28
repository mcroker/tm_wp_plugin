<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once('TMBaseTax.php');

if ( ! class_exists('TMSection')):
  class TMSection extends TMBaseTax {
    public static $taxonomy = 'tm_section';

    protected static $associate_post_types = array('tm_team');

    protected static $labels = Array(
      'singular_name'       => 'Section'
    );

    protected static $args = Array (
    );

    function __construct($term_id = 0) {
      parent::__construct($term_id);
    }
  }
  TMSection::init();
endif;
?>
