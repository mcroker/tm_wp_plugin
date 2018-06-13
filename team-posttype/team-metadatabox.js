var leagueTeams = [];
function getLeagueTeams(competitionid) {
  if (competitionid[leagueTeams]) {
    populateTeamsDropdown(leagueTeams[competitionid]);
  } else {
    var data = {
      'action': 'tm_competition_ajax_getteams',
      'competition': competitionid
    };
    jQuery.get( ajax_object.ajax_url , data, function(response) {
      responseObj = JSON.parse(response);
      leagueTeams[competitionid] = responseObj.teams;
      populateTeamsDropdown(leagueTeams[competitionid]);
    });
  };
};

function populateTeamsDropdown(teams) {
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
