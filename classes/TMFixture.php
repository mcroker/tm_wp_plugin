<?
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once('TMFixture.php');
require_once('TMTeam.php');
require_once('TMSeason.php');
require_once('TMCompetition.php');
require_once('TMOpposition.php');

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists('TMFixture')):
  class TMFixture extends TMBasePost {
    protected static $post_type = 'tm_fixture';

    protected static $meta_keys = Array(
      'scorefor' => Array(
           'type'      => 'meta_attrib',
           'meta_key'  => 'tm_fixture_scorefor'
      ),
      'scoreagainst' => Array(
           'type'      => 'meta_attrib',
           'meta_key'  => 'tm_fixture_scoreagainst'
      ),
      'scoreagainst' => Array(
           'type'      => 'meta_attrib',
           'meta_key'  => 'tm_fixture_scoreagainst'
      ),
      'homeaway' => Array(
           'type'      => 'meta_attrib',
           'meta_key'  => 'tm_fixture_homeaway'
      ),
      'useautofetch' => Array(
           'type'      => 'meta_attrib',
           'meta_key'  => 'tm_fixture_useautofetch'
      ),
      'createdbyautofetch' => Array(
           'type'      => 'meta_attrib',
           'meta_key'  => 'tm_fixture_createdbyautofetch'
      ),
      'fixturedate' => Array(
           'type'      => 'meta_attrib_date',
           'meta_key'  => 'tm_fixture_date'
      ),
      'kickofftime' => Array(
           'type'      => 'meta_attrib_time',
           'meta_key'  => 'tm_fixture_kickofftime'
      ),
      'team' => Array(
           'type'      => 'related_post',
           'classname' => 'TMTeam',
           'meta_key'  => 'tm_fixture_team'
      ),
      'season' => Array(
           'type'      => 'related_tax',
           'classname' => 'TMSeason',
           'single'    => true
      ),
      'competition' => Array(
           'type'      => 'related_tax',
           'classname' => 'TMCompetition',
           'single'    => true
      ),
      'opposition' => Array(
           'type'      => 'related_tax',
           'classname' => 'TMOpposition',
           'single'    => true
      )
    );

    function __construct($fixtureid = 0) {
      parent::__construct($fixtureid);
    }

    public static function sort_by_date_asc($a, $b) {
      return ($a->kickofftime > $b->kickofftime);
    }

    public static function sort_by_date_desc($a, $b) {
      return ($a->kickofftime < $b->kickofftime);
    }

    public function __get ($key) {
      switch ($key) {
        case 'url': // ==================================================
        return get_permalink($this->ID);
        // return '/fixtures/' . $this->post->post_name;
        break;

        default:
        return $this->get_value( $key );
      }
    }

    function vevent() {
      echo "BEGIN:VEVENT\n";

      echo "UID:" . get_site_url() . "/fixture/" . $this->ID . "\n";
      $createuStampUTC = strtotime($this->post->post_date_gmt);
      $createstamp  = date("Ymd\THis\Z", $createuStampUTC);
      echo "DTSTAMP:" . $createstamp . "\n";

      $modifieduStampUTC = strtotime($this->post->post_modified_gmt);
      $modifiedstamp  = date("Ymd\THis\Z", $modifieduStampUTC);
      echo "LAST-MODIFIED:" . $modifiedstamp . "\n";

      echo "ORGANIZER;CN=TWRFC:MAILTO:noreply@twrfc.com\n";

      if ( $this->homeaway == 'H') {
        echo "LOCATION:Home\n";
      } else if ( $this->homeaway == 'A' ) {
        echo "LOCATION:Away\n";
      }

      if ( $kickofftime->format('H') != '00' ) {
        $startuStampUTC = $this->kickofftime;
        $startstamp  = date("Ymd\THis\Z", $startuStampUTC);
        $enduStampUTC = $startuStampUTC + ( 90 * 60 );
        $endstamp  = date("Ymd\THis\Z", $enduStampUTC);
        echo "DTSTART:" . $startstamp . "\n";
        echo "DTEND:" . $endstamp . "\n";
      } else {
         $uStampUTC = $this->fixturedate + (get_option('gmt_offset') * 3600);
         echo "DTSTART;VALUE=DATE:" . $kickofftime->format("Ymd") . "\n";
      }

      echo "SUMMARY:" . $this->team->title . ":" . $this->title . "\n";

      echo "END:VEVENT\n";
    }

  }
endif;
?>
