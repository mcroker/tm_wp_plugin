<?php
if ( ! function_exists( 'tm_competition_edit_form_fields' ) ):
  function tm_competition_edit_form_fields($term) {
    global $tm_competition_autofetchers;

    $saved_leaguetable = tm_get_competition_leaguetable_data( $term->term_id );
    $saved_autofetcher = tm_get_competition_autofetcher ( $term->term_id );
    $saved_autofetcheropts = tm_get_competition_autofetcher_options ( $term->term_id );
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
      var commonoptionsdiv = document.getElementsByClassName("tm-autofetch-commonoptions");
      for(var i = 0; i < commonoptionsdiv.length; i++)
      {
        if (fetcher == 'none') {
          commonoptionsdiv.item(i).style.display='none';
        }
        else {
          commonoptionsdiv.item(i).style.display='table-row-group';
        }
      }
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
      <label for="tm_competition_leaguetable"><?php _e('League Table Data', 'tm'); ?></label>
    </th>
    <td>
      <textarea rows=10 id="tm_competition_leaguetable" name="tm_competition_leaguetable" readonly="true"><?php echo htmlentities(print_r($saved_leaguetable, true)) ?></textarea><br>
    </td>
  </tr>

  <?php
}
endif;
?>
