<?php
require_once('TMBaseTax.php');
require_once('TMFixture.php');

if ( ! class_exists('TMCompetition')):
  class TMCompetition extends TMBaseTax {
    public static $taxonomy = 'tm_competition';
    protected static $meta_keys = Array(
      'sortkey' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_competition_sortkey'
      ),
      'autofetcher' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_competition_autofetcher'
      ),
      'autofetcheropts' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_competition_autofetcher_options'
      ),
      'teamdata' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_competition_teams'
      ),
      'leaguetable' => Array(
        'type'      => 'meta_attrib',
        'meta_key'  => 'tm_competition_leaguetable'
      ),
      'fixtures' => Array(
        'type'      => 'related_posts',
        'classname' => 'TMFixture'
      )
      );

      function __construct($term_id = 0) {
        parent::__construct($term_id);
      }

      public function __get ($key) {
        switch ($key) {
          case 'autofetcheropts': // ============================================================
          $autofetcheropts = $this->get_cached_value($key);
          $autofetcheropts['tm_competition_name'] = $this->term->name;
          $autofetcheropts['tm_competition_id'] = $this->term->term_id;
          $autofetcheropts['tm_competition_slug'] = $this->term->slug;
          $autofetcheropts['tm_competition_description'] = $this->term->description;
          return $autofetcheropts;
          break;

          case 'leaguetable':
          $data = $this->get_cached_value($key);
          if ( is_array($data) ) {
            return $data;
          } else {
            return Array();
          }
          break;

          default:
          return $this->get_cached_value($key);

          }

        }

      }
    endif;
