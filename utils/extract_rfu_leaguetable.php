<?

class TableEntry {
  public $position;
  public $team;
  public $played;
  public $wins;
  public $draws;
  public $lost;
  public $pointsfor;
  public $pointsagainst;
  public $pointsdiff;
  public $trybonus;
  public $losingbonus;
  public $points;
};


function tm_extract_rfu_leaguetable_season($competition, $season) {
  $rfucompbase = 'http://www.englandrugby.com/fixtures-and-results/competitions/';
  $rfucompsuffix = '/#/table';
  echo 'xxxxxxxxx' . $rfucompbase . $competition . '/' . $season . $rfucompsuffix ;
  $rawHTML = file_get_contents($rfucompbase . $competition . '/' . $season . $rfucompsuffix );
  $html = new simple_html_dom();
  $html->load($rawHTML);

  $tabscontainer=$html->find('div[class=tabs-container]',0);
  $tabscontent=$tabscontainer->find('div[class=tabs-content]',0);

  $tabletabsdiv=$tabscontent->find('div[class=content]',2);
  $results = array();

  foreach($tabletabsdiv->find('div') as $itemdiv) {
    if ($itemdiv->class == 'row table_item') {
      $tableul=$itemdiv->find('div[class=columns]',0)->find('ul[class=tablegrid]',0);
      $result = new TableEntry;
      $result->position=$tableul->find('li',0)->plaintext;
      $result->team=$tableul->find('li',1)->find('div',0)->plaintext;
      $result->played=$tableul->find('li',2)->plaintext;
      $result->wins=$tableul->find('li',3)->plaintext;
      $result->draws=$tableul->find('li',4)->plaintext;
      $result->lost=$tableul->find('li',5)->plaintext;
      $result->pointsfor=$tableul->find('li',6)->plaintext;
      $result->pointsagainst=$tableul->find('li',7)->plaintext;
      $result->pointsdiff=$tableul->find('li',8)->plaintext;
      $result->trybonus=$tableul->find('li',9)->plaintext;
      $result->losingbonus=$tableul->find('li',10)->plaintext;
      $result->points=$tableul->find('li',11)->plaintext;
      $results[] = $result;
    }
  }
  return $results;
}

?>
