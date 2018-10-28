<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once('TMBaseTax.php');

if ( ! class_exists('TMOpposition')):
  class TMOpposition extends TMBaseTax {
    public static $taxonomy = 'tm_opposition';

    protected static $associate_post_types = array('tm_fixture');

    protected static $labels = Array(
      'singular_name'       => 'Opposition',
      'name'                => 'Opposition'
    );

    protected static $args = Array (
    );

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
  TMOpposition::init();
endif;
?>
