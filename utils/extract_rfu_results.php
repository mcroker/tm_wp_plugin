<?

class Result {
  public $fixturedate;
  public $hometeam;
  public $hometeamlogo;
  public $awayteam;
  public $awayteamlogo;
  public $homescore;
  public $awayscore;
  public $opposition;
  public $season;
  public $scorefor;
  public $scoreagainst;
}


function tm_extract_rfu_results($competition, $team) {
  $rfucompbase = 'http://www.englandrugby.com/fixtures-and-results/competitions/';
  $rfucompsuffix = '/#/results';
  $rawHTML = file_get_contents($rfucompbase . $competition . $rfucompsuffix );
  $html = new simple_html_dom();
  $html->load($rawHTML);
  $seasons=$html->getElementById("competitionSeason")->find('li');
  $results = array();
  foreach($seasons as $season) {
    $results = array_merge ( $results , tm_extract_rfu_results_season($competition, $team, $season->plaintext) );
  }
  return $results;
}

function tm_extract_rfu_results_season($competition, $team, $season) {
  $rfucompbase = 'http://www.englandrugby.com/fixtures-and-results/competitions/';
  $rfucompsuffix = '/#/results';
  $srcurl = $rfucompbase . $competition . '/' . $season . $rfucompsuffix;

  $rawHTML = file_get_contents($srcurl);
  $html = new simple_html_dom();
  $html->load($rawHTML);

  $season=$html->find('div[class=selected_season]',0)->plaintext;
  $seasonyear=explode('-', $season);

  $tabscontainer=$html->find('div[class=tabs-container]',0);
  $tabscontent=$tabscontainer->find('div[class=tabs-content]',0);
  $resultstabdiv=$tabscontent->find('div[class=content]',1);
  $results = array();

  $fixturedate = '';
  foreach($resultstabdiv->find('div') as $itemdiv) {
    if ($itemdiv->class == 'row fixturedate') {
     $fixturedatetext = $itemdiv->plaintext;
     // Expected format - Saturday 21st April ... therefore 3rd word = Month
     $fixturedatearr = explode(' ', $fixturedatetext);
     $fixtureday = $fixturedatearr[1];
     $fixturemonth = $fixturedatearr[2];
     if ( in_array ( $fixturemonth , array( 'January' , 'February' , 'March' , 'April' , 'May'))) {
       $fixtureyear=$seasonyear[1];
     } else {
       $fixtureyear=$seasonyear[0];
     }
     $fixturedateformat = $fixtureday . ' ' . $fixturemonth . ' ' . $fixtureyear;
     $fixturedate = DateTime::createFromFormat('jS F Y', $fixturedateformat );
    }
    if ($itemdiv->class == 'row item') {
      $peudotablediv=$itemdiv->find('div[class=columns]',0)->find('div[class=pseudo-table]',0);
      $result = new Result;
      $result->fixturedate = $fixturedate;
      $result->season = $season;
      $result->hometeam=$peudotablediv->find('div[class=fr-team]',0)->plaintext;
      $result->hometeamlogo=$peudotablediv->find('div[class=fr-logo]',0)->plaintext;
      $result->awayteam=$peudotablediv->find('div[class=fr-team]',1)->plaintext;
      $result->awayteamlogo=$peudotablediv->find('div[class=fr-logo]',1)->plaintext;

      $resulttext=$peudotablediv->find('div[class=fr-result]',0)->find('span[class=score]',0)->plaintext;
      $resultarray=explode(' - ', $resulttext);
      $result->homescore=trim($resultarray[0]);
      $result->awayscore=trim($resultarray[1]);
      if ($team == '' || $team == $result->hometeam || $team == $result->awayteam ) {
        if ( $result->hometeam == $team ) {
          $result->opposition = $result->awayteam;
          $result->scorefor = $result->awayscore;
          $result->scoreagainst = $result->homescore;
        } else {
          $result->opposition = $result->hometeam;
          $result->scorefor = $result->homescore;
          $result->scoreagainst = $result->awayscore;
        }
        $results[] = $result;
      }
    }
  }
  return $results;
}


?>
