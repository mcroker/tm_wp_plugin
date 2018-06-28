<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once('TMBaseTax.php');

if ( ! class_exists('TMOpposition')):
  class TMOpposition extends TMBaseTax {
    public static $taxonomy = 'tm_opposition';
  protected static $meta_keys = Array(
      'url' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_opposition_url'
      ),
      'logo' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_opposition_logo'
      )
    );
    function __construct($term_id = 0) {
      parent::__construct($term_id);
    }
  }
endif;
?>
