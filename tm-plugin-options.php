<?php

function tm_register_settings() {
   add_option( 'tm_default_season', '');
   register_setting( 'tm_options', 'tm_default_season' );
}

function tm_register_options_page() {
  add_options_page('TM Plugin Options', 'TM Plugin', 'manage_options', 'tm_options', 'tm_options_page');
}

function tm_options_page()
{
?>
  <div>
  <?php screen_icon(); ?>
  <h2>TM Plugin Options</h2>
  <form method="post" action="options.php">
  <?php settings_fields( 'tm_options' ); ?>
  <table>
  <tr valign="top">
  <th scope="row"><label for="tm_default_season">Default Season(s)</label></th>
  <td><input type="text" id="tm_default_season" name="tm_default_season" value="<?php echo get_option('tm_default_season'); ?>" /></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
} ?>
