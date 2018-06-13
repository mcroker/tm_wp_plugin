<?php
if ( ! function_exists( 'tm_competition_edit_form_fields' ) ):
  function tm_competition_edit_form_fields($term) {

    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_script( 'competition-edit-form-js', $plugin_url . 'competition-edit-form-fields.js', array('jquery'), 'v4.0.0', true );
    wp_localize_script( 'competition-edit-form-js', 'tm_php_object', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'term_id' => $term->term_id
    ) );

    $saved_leaguetable = tm_competition_get_leaguetable($term->term_id);
    $saved_teamdata = tm_competition_get_teams($term->term_id);
    $saved_autofetcher = tm_competition_get_autofetcher($term->term_id);
    $saved_autofetcheropts = tm_competition_get_autofetcher_options($term->term_id);
    ?>

    <tr class="form-field term-group-wrap">
      <th scope="row">
        <label for="tm_competition_autofetch"><?php _e('Automatic Fetch Plugin', 'tm'); ?></label>
      </th>
      <td>
        <select name="tm_competition_autofetch" id="tm_competition_autofetch" onchange="java_script_:selectAutoFetcher(this.options[this.selectedIndex].value)"/>
        <?php
        foreach(tm_autofetch_get_plugins() as $fetcherkey => $fetcherdesc)
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
          <input type="text" name="tm_competition_seasons" value="<?php echo implode(',',$saved_autofetcheropts['tm_competition_seasons']) ?>" id="tm_competition_seasons"/><br>
        </td>
      </tr>
    </tbody>

    <?php
    foreach(tm_autofetch_get_plugins() as $fetcherkey => $fetcherdesc) {
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
  add_action( 'tm_competition_edit_form_fields', 'tm_competition_edit_form_fields', 10, 2 );
endif;
?>
