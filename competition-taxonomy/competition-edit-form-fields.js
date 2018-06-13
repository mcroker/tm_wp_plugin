function selectAutoFetcher(fetcher) {
  var optionsdiv = document.getElementsByClassName("tm-autofetch-options");
  for(var i = 0; i < optionsdiv.length; i++)
  {
    if (optionsdiv.item(i).id == "fetcher_" + fetcher + "_options") {
      optionsdiv.item(i).style.display='table-row-group';
    }
    else {
      optionsdiv.item(i).style.display='none';
    }
  }
  var commonoptionsdiv = document.getElementsByClassName('tm-autofetch-commonoptions');
  for(var i = 0; i < commonoptionsdiv.length; i++)
  {
    if (fetcher == 'none') {
      commonoptionsdiv.item(i).style.display='none';
    }
    else {
      commonoptionsdiv.item(i).style.display='table-row-group';
    }
  }
  var autofetchbutton = document.getElementById('tm-autofetch-button');
  if (fetcher == 'none') {
    autofetchbutton.style.display='none';
  }
  else {
    autofetchbutton.style.display='inline';
  }
}

function execAutoFetcher() {

  var updatespan = document.getElementById('tm-update-status');
  updatespan.textContent = 'Updating ...';

  var fetcherselect = document.getElementById('tm_competition_autofetch');
  var data = {
    'action': 'tm_competition_ajax_update',
    'competition': tm_php_object.term_id,
    'tm_competition_seasons': document.getElementById('tm_competition_seasons').value ,
    'tm_competition_autofetch': fetcherselect.options[fetcherselect.selectedIndex].value
  };
  var formFields = jQuery("#fetcher_" + fetcherselect.options[fetcherselect.selectedIndex].value + "_options").serializeArray();
  jQuery.each(formFields, function(i, field){
    data[field.name] = field.value;
  });

  jQuery.post( tm_php_object.ajax_url , data, function(response) {
    var time = new Date();
    updatespan.textContent = 'Fetched ' + time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds();
    var responseObj = JSON.parse(response);
    var leagueelem = document.getElementById('tm_competition_leaguetable');
    leagueelem.value = JSON.stringify(responseObj.seasons);
    var teamselem = document.getElementById('tm_competition_teams');
    teamselem.value = JSON.stringify(responseObj.teams);
  });
}


function execClearFetcherData() {
  var updatespan = document.getElementById('tm-update-status');
  updatespan.textContent = 'Clearing  ...';
  var data = {
    'action': 'tm_competition_ajax_clearleaguedata',
    'competition': tm_php_object.term_id
  };
  jQuery.post( tm_php_object.ajax_url , data, function(response) {
    var time = new Date();
    updatespan.textContent = 'Cleared ' + time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds();
    var responseObj = JSON.parse(response);
    var leagueelem = document.getElementById('tm_competition_leaguetable');
    leagueelem.value = JSON.stringify(responseObj.seasons);
    var teamselem = document.getElementById('tm_competition_teams');
    teamselem.value = JSON.stringify(responseObj.teams);
  });
}
