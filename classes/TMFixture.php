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
      $atz = $a->kickofftime->getTimestamp();
      $btz =  $b->kickofftime->getTimestamp();
      return ( $atz > $btz );
    }

    public static function sort_by_date_desc($a, $b) {
      $atz = $a->kickofftime->getTimestamp();
      $btz =  $b->kickofftime->getTimestamp();
      return ( $atz < $btz );
    }

    public function __get ($key) {
      switch ($key) {
        case 'url': // ==================================================
        return get_permalink($this->ID);
        // return '/fixtures/' . $this->post->post_name;
        break;

        case 'kickofftime': // ==================================================
        $value = $this->get_value( $key );
        if ($value->getTimestamp() == 0 ) {
          $value = new DateTime();
          $value->setTimestamp($this->get_value( 'fixturedate'));
        }
        return $value;
        break;

        case 'kickofftimeutc': // ===============================================
        $kickofftimeutc = clone $this->kickofftime;
        $kickofftimeutc->setTimezone(new DateTimeZone('UTC'));
        return $kickofftimeutc;
        break;

        case 'endtimeutc': // ===============================================
        $endutc = clone $this->kickofftime;
        $endutc->setTimezone(new DateTimeZone('UTC'));
        $endutc->add(new DateInterval("PT01H30M"));
        return $endutc;
        break;

        default:
        return $this->get_value( $key );
      }
    }

    public function __set ($key, $value) {
      switch ($key) {
        case 'kickofftime': // ==================================================
        $this->update_value('kickofftime', $value);
        $this->update_value('fixturedate', $this->kickofftime->format('Y-m-d'));
        break;

        default:
        $this->update_value($key, $value);
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

      if ( $this->kickofftimeutc->format('H') != '00' ) {
        echo "DTSTART:" . $this->kickofftimeutc->format("Ymd\THis\Z") . "\n";
        echo "DTEND:" . $this->endtimeutc->format("Ymd\THis\Z") . "\n";
      } else {
         echo "DTSTART;VALUE=DATE:" . $this->kickofftimeutc->format("Ymd") . "\n";
      }

      echo "SUMMARY:" . $this->team->title . ":" . $this->title . "\n";

      echo "END:VEVENT\n";
    }

  }
endif;
?>
