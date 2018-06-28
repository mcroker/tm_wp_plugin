<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

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
        'type'      => 'meta_attrib_object',
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

      public static function sort_by_sortkey_desc($a, $b) {
        return ($a->sortkey > $b->sortkey);
      }

      public static function sort_by_sortkey_asc($a, $b) {
        return ($a->sortkey < $b->sortkey);
      }

      public function __get ($key) {
        switch ($key) {
          case 'autofetcheropts': // ============================================================
          $autofetcheropts = $this->get_value($key);
          $autofetcheropts['tm_competition_name'] = $this->term->name;
          $autofetcheropts['tm_competition_id'] = $this->term->term_id;
          $autofetcheropts['tm_competition_slug'] = $this->term->slug;
          $autofetcheropts['tm_competition_description'] = $this->term->description;
          return $autofetcheropts;
          break;

          case 'leaguetable':
          $data = $this->get_value($key);
          if ( is_array($data) ) {
            return $data;
          } else {
            return Array();
          }
          break;

          default:
          return $this->get_value($key);

        }

      }

      public function autoFetch() {
        if ( tm_autofetch_isvalidplugin($this->autofetcher) ) {
          $this->leaguetable = tm_autofetch_fetch_leaguetable($this->autofetcher, $this->autofetcheropts );
        }
        $teams = Array();
        foreach ($this->leaguetable as $tablentry) {
          if ( ! array_key_exists( $tablentry->team, $teams) ) {
            $teams[] = $tablentry->team;
          }
        }
        $this->teamdata = $teams;
      }

    }
  endif;
