
REPORT=""
COVERAGEREPORT=""
MD=false
COVERAGE=false
STYLE=true
UNIT=true

while getopts 'xhcCmMuUsS' c
do
  case $c in
    x) REPORT=xml ;;
    h) REPORT=html ;;
    c) COVERAGE=true ;;
    C) COVERAGE=false ;;
    m) MD=true ;;
    M) MD=false ;;
    u) UNIT=true ;;
    U) UNIT=false ;;
    s) STYLE=true ;;
    S) STYLE=false ;;
  esac
done

case "$REPORT" in
html)
  MDREPORT=html
  MDARGS="--reportfile reports/phpmd.html"
  if [ $COVERAGE == true ]; then COVERAGEREPORT=html ; fi
  ;;
xml)
  MDREPORT=xml
  MDARGS="--reportfile reports/phpmd.xml"
  if [ $COVERAGE == true ]; then COVERAGEREPORT=clover ; fi
  ;;
*)
  MDREPORT=text
  MDARGS=
  if [ $COVERAGE == true ]; then COVERAGEREPORT=text ; fi
esac

if [ $MD == true ]; then
  phpmd --exclude test,vendor --suffixes php $MDARGS . $MDREPORT .phpmd.xml
fi

if [ $STYLE == true ]; then
  phpcs class-*
fi

if [ $UNIT == true ]; then
  docker-compose up -d
  docker-compose exec wordpress /usr/local/bin/exec-phpunit.sh tm_wp_plugin $COVERAGEREPORT
fi

# Scafold tests - now part of /tests
# docker-compose exec wordpress wp scaffold plugin-tests tm_wp_plugin --allow-root
