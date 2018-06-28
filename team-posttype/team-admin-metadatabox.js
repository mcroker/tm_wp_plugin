var tmteamleagueTeams = [];
function tmteamgetLeagueTeams(competitionid) {
  if (tmteamleagueTeams[competitionid]) {
    tmteampopulateTeamsDropdown(tmteamleagueTeams[competitionid]);
  } else {
    var data = {
      'action': 'tm_competition_ajax_getteams',
      'competition': competitionid
    };
    jQuery.post( tmphpobject.ajax_url , data, function(response) {
      responseObj = JSON.parse(response);
      tmteamleagueTeams[competitionid] = responseObj.teams;
      tmteampopulateTeamsDropdown(tmteamleagueTeams[competitionid]);
    });
  };
};

function tmteampopulateTeamsDropdown(teams) {
  var teamsselect = document.getElementById('tm_team_leagueteam');
  for(i = teamsselect.options.length - 1 ; i >= 0 ; i--)
  {
    teamsselect.remove(i);
  }
  var opt = document.createElement('option');
  opt.value = 'none';
  opt.text = 'None';
  teamsselect.options.add(opt);
  for (i = 0; i < teams.length; i++) {
    var opt = document.createElement('option');
    opt.value = teams[i];
    opt.text = teams[i];
    teamsselect.options.add(opt);
  }
};

function execTeamAutoFetcher() {
  var updatespan = document.getElementById('tm-update-status');
  updatespan.textContent = 'Fetching ...';
  var data = {
    'action': 'tm_team_ajax_update',
    'team_id': tmphpobject.team_id
  };
  jQuery.post( tmphpobject.ajax_url , data, function(response) {
    var time = new Date();
    var responseObj = JSON.parse(response);
    updatespan.textContent = responseObj.fixtures.length + ' fixtures updated ' + time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds();
  });
};
