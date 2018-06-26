<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Add form fields ==================================================
if ( is_admin() && ! function_exists( 'tm_competition_add_form_fields' ) ):
  function tm_competition_add_form_fields($taxonomy) {

    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_script( 'competition-add-form-js', $plugin_url . 'competition-add-form-fields.js', array(), 'v4.0.0', true );
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
        ?><div style="display:none" class="tm-autofetch-options" id="fetcher_<?php echo $fetcherkey ?>_options"><?php
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
?>
