<?php

if ( ! function_exists( 'tm_competition_add_form_fields' ) ):
function tm_competition_add_form_fields($taxonomy) {
  global $tm_competition_autofetchers;
  ?>
  <script>
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
</script>
<div class="form-field term-group">

  <label for="tm_competition_autofetch"><?php _e('Automatic Fetch Plugin', 'tm'); ?></label>
  <select name="tm_competition_autofetch" id="tm_competition_autofetch" onchange="java_script_:selectAutoFetcher(this.options[this.selectedIndex].value)"/>
  <?php

  foreach($tm_competition_autofetchers as $fetcherkey => $fetcherdesc)
  {
    printf(
      '<option value="%1$s"> %2$s </option>',
      esc_attr($fetcherkey),
      esc_html($fetcherdesc)
    );
    // selected($fetcherkey, $team->ID, false),
  }
  ?>
</select>

<div style="display:none" class="tm-autofetch-commonoptions">
  <label for="tm_competition_seasons"><?php _e('Fetch Seasons', 'tm'); ?></label>
  <input type="text" name="tm_competition_seasons" value="" id="tm_competition_seasons"/><br>
</div>

<?php
// TODO: Hide/Show based on selected autosaver
foreach($tm_competition_autofetchers as $fetcherkey => $fetcherdesc) {
  if ( function_exists( $fetcherkey . '_competition_add_form_fields' ) ) {
    ?><div style="display:none" class="tm-autofetch-options" id="fetcher_<?php echo $fetcherkey ?>_options"><?php
    call_user_func($fetcherkey . '_competition_add_form_fields');
    ?></div><?php
  }
}
?>
</div><?php
}
endif;
?>
