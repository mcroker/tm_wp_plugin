<?php
if ( ! function_exists( 'tm_competition_edit_form_fields' ) ):
  function tm_competition_edit_form_fields($term) {
    global $tm_competition_autofetchers;

    $saved_leaguetable = tm_competition_get_leaguetable( $term->term_id );
    $saved_teamdata = tm_competition_get_teams( $term->term_id );
    $saved_autofetcher = tm_competition_get_autofetcher ( $term->term_id );
    $saved_autofetcheropts = tm_competition_get_autofetcher_options ( $term->term_id );

    // TODO : This all needs to move to a enquesed script
    ?>
    <script>
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
	    var data = {
		    'action': 'tm_competition_ajax_update',
		    'competition': '<?php echo $term->term_id ?>'
	    };
	    jQuery.post( ajaxurl , data, function(response) {
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
        'competition': '<?php echo $term->term_id ?>'
      };
      jQuery.post( ajaxurl , data, function(response) {
        var time = new Date();
        updatespan.textContent = 'Cleared ' + time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds();
        var responseObj = JSON.parse(response);
        var leagueelem = document.getElementById('tm_competition_leaguetable');
        leagueelem.value = JSON.stringify(responseObj.seasons);
        var teamselem = document.getElementById('tm_competition_teams');
        teamselem.value = JSON.stringify(responseObj.teams);
      });
    }
    </script>
    <tr class="form-field term-group-wrap">
      <th scope="row">
        <label for="tm_competition_autofetch"><?php _e('Automatic Fetch Plugin', 'tm'); ?></label>
      </th>
      <td>
        <select name="tm_competition_autofetch" id="tm_competition_autofetch" onchange="java_script_:selectAutoFetcher(this.options[this.selectedIndex].value)"/>
        <?php
        foreach($tm_competition_autofetchers as $fetcherkey => $fetcherdesc)
        {
          printf(
            '<option value="%1$s" %2$s > %3$s </option>',
            esc_attr($fetcherkey),
            selected($fetcherkey, $saved_autofetcher, false),
            esc_html($fetcherdesc)
          );
        }
        ?>
      </select>
      <input id='tm-autofetch-button' <?php if ( $saved_autofetcher == 'none' ) { echo " style='display:none' "; } ?> class='button' type='button' onclick='java_script_:execAutoFetcher()' value='Exec Autofetch'>
      <span id='tm-update-status'></span>
    </td>
  </tr>

  <tbody class="tm-autofetch-commonoptions">
    <tr class="form-field term-group-wrap">
      <th scope="row">
        <label for="tm_competition_seasons"><?php _e('Fetch Seasons', 'tm'); ?></label>
      </th>
      <td>
        <input type="text" name="tm_competition_seasons" value="<?php echo $saved_autofetcheropts['tm_competition_seasons'] ?>" id="tm_competition_seasons"/><br>
      </td>
    </tr>
  </tbody>

  <?php
  // TODO: Hide/Show based on selected autosaver
  foreach($tm_competition_autofetchers as $fetcherkey => $fetcherdesc) {
    if ( function_exists( $fetcherkey . '_competition_edit_form_fields' ) ) {
      ?><tbody <?php if ( $fetcherkey != $saved_autofetcher ) { ?>style="display:none"<?php } ?> class="tm-autofetch-options" id="fetcher_<?php echo $fetcherkey ?>_options"><?php
      call_user_func($fetcherkey . '_competition_edit_form_fields', $saved_autofetcheropts);
      ?></tbody><?php
    }
  }
  ?>

  <tr class="form-field term-group-wrap">
    <th scope="row">
      <label for="tm_competition_leaguetable"><?php _e('Fetched Data', 'tm'); ?></label>
    </th>
    <td>
      <label for="tm_competition_leaguetable"><?php _e('Leaguetable', 'tm'); ?></label><br>
      <textarea rows=10 id="tm_competition_leaguetable" name="tm_competition_leaguetable" readonly="true"><?php echo json_encode($saved_leaguetable) ?></textarea><br>
      <label for="tm_competition_teams"><?php _e('Teams', 'tm'); ?></label><br>
      <textarea rows=10 id="tm_competition_teams" name="tm_competition_teams" readonly="true"><?php echo json_encode($saved_teamdata) ?></textarea><br>
      <input id='tm-autofetch-cleardata' class='button' type='button' onclick='java_script_:execClearFetcherData()' value='Clear Data'>
    </td>
  </tr>


  <?php

}
endif;
?>
