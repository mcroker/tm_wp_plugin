<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Edit Form Fields ==================================================
if ( is_admin() && ! function_exists( 'tm_competition_edit_form_fields' ) ):
  function tm_competition_edit_form_fields($term) {

    $competition = new TMCompetition($term);

    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_script( 'competition-edit-form-js', $plugin_url . 'competition-edit-form-fields.js', array('jquery'), 'v4.0.0', true );
    wp_localize_script( 'competition-edit-form-js', 'tmphpobject', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'term_id' => $term->term_id
    ) );
    ?>

    <tr class="form-field term-group-wrap">
      <th scope="row">
        <label for="tm_competition_sortkey"><?php _e('Sort Key', 'tm'); ?></label>
      </th>
      <td>
        <input type="text" name="tm_competition_sortkey" value="<?php echo $competition->sortkey ?>" id="tm_competition_sortkey"/><br>
      </td>
    </tr>

    <tr class="form-field term-group-wrap">
      <th scope="row">
        <label for="tm_competition_autofetch"><?php _e('Automatic Fetch Plugin', 'tm'); ?></label>
      </th>
      <td>
        <select name="tm_competition_autofetch" id="tm_competition_autofetch" onchange="java_script_:tmCompetitionSelectAutoFetcher(this.options[this.selectedIndex].value)"/>
        <?php
        foreach(tm_autofetch_get_plugins() as $fetcherkey => $fetcherdesc)
        {
          printf(
            '<option value="%1$s" %2$s > %3$s </option>',
            esc_attr($fetcherkey),
            selected($fetcherkey, $competition->autofetcher, false),
            esc_html($fetcherdesc)
          );
        }
        ?>
      </select>
      <input id='tm-autofetch-button' <?php if ( $competition->autofetcher == 'none' ) { echo " style='display:none' "; } ?> class='button' type='button' onclick='java_script_:tmCompetitionExecAutoFetcher()' value='Exec Autofetch'>
        <span id='tm-update-status'></span>
      </td>
    </tr>


    <?php
    foreach(tm_autofetch_get_plugins() as $fetcherkey => $fetcherdesc) {
      if ( function_exists( $fetcherkey . '_competition_edit_form_fields' ) ) {
        ?><tbody <?php if ( $fetcherkey != $competition->autofetcher ) { ?>style="display:none"<?php } ?> class="tm-autofetch-options" id="fetcher_<?php echo $fetcherkey ?>_options"><?php
        call_user_func($fetcherkey . '_competition_edit_form_fields', $competition->autofetcheropts);
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
        <textarea rows=10 id="tm_competition_leaguetable" name="tm_competition_leaguetable" readonly="true"><?php echo json_encode($competition->leaguetable) ?></textarea><br>
        <label for="tm_competition_teams"><?php _e('Teams', 'tm'); ?></label><br>
        <textarea rows=10 id="tm_competition_teams" name="tm_competition_teams" readonly="true"><?php echo json_encode($competition->teamdata) ?></textarea><br>
        <input id='tm-autofetch-cleardata' class='button' type='button' onclick='java_script_:tmCompetitionExecClearFetchedData()' value='Clear Data'>
      </td>
    </tr>

    <?php
  }
  add_action( 'tm_competition_edit_form_fields', 'tm_competition_edit_form_fields', 10, 2 );
endif;
?>
