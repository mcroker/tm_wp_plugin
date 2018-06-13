function selectAutoFetcher(fetcher) {
  var optionsdiv = document.getElementsByClassName("tm-autofetch-options");
  for(var i = 0; i < optionsdiv.length; i++)
  {
    if (optionsdiv.item(i).id == "fetcher_" + fetcher + "_options") {
      optionsdiv.item(i).style.display='inline';
    }
    else {
      optionsdiv.item(i).style.display='none';
    }
  }
  var commonoptionsdiv = document.getElementsByClassName("tm-autofetch-commonoptions");
  for(var i = 0; i < commonoptionsdiv.length; i++)
  {
    if (fetcher == 'none') {
      commonoptionsdiv.item(i).style.display='none';
    }
    else {
      commonoptionsdiv.item(i).style.display='inline';
    }
  }
}
