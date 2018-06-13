<?php
if ( ! function_exists( 'tm_competition_add_form_fields' ) ):
  function tm_competition_add_form_fields($taxonomy) {

    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_script( 'competition-add-form-js', $plugin_url . 'competition-add-form-fields.js', array(), 'v4.0.0', true );
    ?>
    <div class="form-field term-group">
      <label for="tm_competition_autofetch"><?php _e('Automatic Fetch Plugin', 'tm'); ?></label>
      <select name="tm_competition_autofetch" id="tm_competition_autofetch" onchange="java_script_:selectAutoFetcher(this.options[this.selectedIndex].value)"/>
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

    <div style="display:none" class="tm-autofetch-commonoptions">
      <label for="tm_competition_seasons"><?php _e('Fetch Seasons', 'tm'); ?></label>
      <input type="text" name="tm_competition_seasons" value="" id="tm_competition_seasons"/><br>
    </div>

    <?php
    // TODO: Hide/Show based on selected autosaver
    foreach(tm_autofetch_get_plugins() as $fetcherkey => $fetcherdesc) {
      if ( function_exists( $fetcherkey . '_competition_add_form_fields' ) ) {
        ?><div style="display:none" class="tm-autofetch-options" id="fetcher_<?php echo $fetcherkey ?>_options"><?php
        call_user_func($fetcherkey . '_competition_add_form_fields');
        ?></div><?php
      }
    }
    ?>
  </div><?php
}
    add_action( 'tm_competition_add_form_fields', 'tm_competition_add_form_fields', 10, 2 );
endif;
?>
