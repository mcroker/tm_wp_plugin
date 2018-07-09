// tmfixtureuseautofetch ==================================================
function tmfixtureuseautofetch(usefetcher) {
  var disableoptions = document.getElementsByClassName("tm-meta-disableifautofetched");
  for(var i = 0; i < disableoptions.length; i++)
  {
    disableoptions.item(i).disabled = usefetcher;
  }
};

// tmFixturePopulateTeamsDropdown ==================================================
function tmFixturePopulateTeamsDropdown(teams) {
  var teamsselect = document.getElementById('tm_fixture_leagueteam_select');
  for(var i = teamsselect.options.length - 1 ; i >= 0 ; i--)
  {
    teamsselect.remove(i);
  };

  var opt = document.createElement('option');
  opt.value = '';
  opt.text = '';

  teamsselect.options.add(opt);
  for (var i = 0; i < teams.length; i++) {
    var opt = document.createElement('option');
    opt.value = teams[i];
    opt.text = teams[i];
    teamsselect.options.add(opt);
  };
};

// tmfixturegetLeagueTeams ==================================================
var tmfixtureleagueTeams = [];
function tmfixturegetLeagueTeams(competitionid) {
  var oppselect = document.getElementById('tm_fixture_leagueteam_select');
  var opptext = document.getElementById('tm_fixture_leagueteam_text');
  var useautofetch = document.getElementById('tm_fixture_useautofetch');

  if ( competitionid == '' ) {
    useautofetch.disabled = true;
    useautofetch.checked = false;
  } else {
    useautofetch.disabled = false;
  }

  if (tmfixtureleagueTeams[competitionid]) {
    tmFixturePopulateTeamsDropdown(tmfixtureleagueTeams[competitionid]);
  } else {
    var data = {
      'action': 'tm_competition_ajax_getteams',
      'competition': competitionid
    };
    jQuery.post( tmphpobject.ajax_url , data, function(response) {
      var responseObj = JSON.parse(response);
      tmfixtureleagueTeams[competitionid] = responseObj.teams;
      tmFixturePopulateTeamsDropdown(tmfixtureleagueTeams[competitionid]);
    });
  };
};
