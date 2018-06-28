<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Enqueue admin scripts ========================================
if ( is_admin() && ! function_exists( 'tm_competition_enqueue_adminscripts' )):
/**
* Enqueus fixture-admin-metadatabox.js & style.css
* Bound to admin_enqueue_scripts action hook
*
* @param WP_Hook? $hook Wonder what this does // TODO
* @return void
*/
  function tm_competition_enqueue_adminscripts($hook) {
    // TODO: Need to do this only for tm_competition
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_script( 'competition-admin-form', $plugin_url . 'competition-admin-form.js', array(), 'v4.0.0', true );
  }
  add_action( 'admin_enqueue_scripts', 'tm_competition_enqueue_adminscripts' );
endif;

// Add form fields ==================================================
if ( is_admin() && ! function_exists( 'tm_competition_add_form_fields' ) ):
  function tm_competition_add_form_fields($taxonomy) {
    ?>

    <div class="form-field term-group">
      <label for="tm_competition_sortkey"><?php _e('Sort Key', 'tm'); ?></label>
      <input type="text" name="tm_competition_sortkey" value="" id="tm_competition_sortkey"/><br>
    </div>

    <div class="form-field term-group">
      <label for="tm_competition_autofetch"><?php _e('Automatic Fetch Plugin', 'tm'); ?></label>
      <select name="tm_competition_autofetch" id="tm_competition_autofetch" onchange="java_script_:tmCompetitionSelectAutoFetcher(this.options[this.selectedIndex].value)"/>
      <?php
      $registered_plugins = tm_autofetch_get_plugins();

      foreach($registered_plugins as $fetcherkey => $fetcherdesc)
      {
        printf(
          '<option value="%1$s"> %2$s </option>',
          esc_attr($fetcherkey),
          esc_html($fetcherdesc)
        );
      }
      ?>
    </select>
  </div>
  <div class="form-field term-group">
    <?php
    foreach(tm_autofetch_get_plugins() as $fetcherkey => $fetcherdesc) {
      if ( function_exists( $fetcherkey . '_competition_add_form_fields' ) ) {
        ?><div style="display:none" class="tm-autofetch-options" id="fetcher_<?php echo esc_attr($fetcherkey) ?>_options"><?php
        call_user_func($fetcherkey . '_competition_add_form_fields');
        ?></div><?php
      }
    }
    ?>
  </div>
  <?php
  }
  add_action( 'tm_competition_add_form_fields', 'tm_competition_add_form_fields', 10, 2 );
endif;

// Edit Form Fields ==================================================
if ( is_admin() && ! function_exists( 'tm_competition_edit_form_fields' ) ):
  function tm_competition_edit_form_fields($term) {

    $competition = new TMCompetition($term);

    wp_localize_script( 'competition-admin-form', 'tmphpobject', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'term_id' => $term->term_id
    ) );
    ?>

    <tr class="form-field term-group-wrap">
      <th scope="row">
        <label for="tm_competition_sortkey"><?php _e('Sort Key', 'tm'); ?></label>
      </th>
      <td>
        <input type="text" name="tm_competition_sortkey" value="<?php echo esc_attr($competition->sortkey) ?>" id="tm_competition_sortkey"/><br>
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
        ?><tbody <?php if ( $fetcherkey != $competition->autofetcher ) { ?>style="display:none"<?php } ?> class="tm-autofetch-options" id="fetcher_<?php echo esc_attr($fetcherkey) ?>_options"><?php
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
        <textarea rows=10 id="tm_competition_leaguetable" name="tm_competition_leaguetable" readonly="true"><?php echo esc_html(json_encode($competition->leaguetable)) ?></textarea><br>
        <label for="tm_competition_teams"><?php _e('Teams', 'tm'); ?></label><br>
        <textarea rows=10 id="tm_competition_teams" name="tm_competition_teams" readonly="true"><?php echo esc_html(json_encode($competition->teamdata)) ?></textarea><br>
        <input id='tm-autofetch-cleardata' class='button' type='button' onclick='java_script_:tmCompetitionExecClearFetchedData()' value='Clear Data'>
      </td>
    </tr>

    <?php
  }
  add_action( 'tm_competition_edit_form_fields', 'tm_competition_edit_form_fields', 10, 2 );
endif;
?>
