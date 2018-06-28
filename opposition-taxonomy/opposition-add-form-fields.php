<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists( 'tm_opposition_add_form_fields' ) ):
  function tm_opposition_add_form_fields($taxonomy) {

    ?>
    <div class="form-field term-group">
      <label for="tm_opposition_url"><?php _e('URL', 'tm'); ?></label>
      <input type="text" name="tm_opposition_url" value="" id="tm_opposition_url"/><br>
    </div>

    <div class="form-field term-group">
      <label for="tm_opposition_url"><?php _e('Logo', 'tm'); ?></label>
      <?php tm_opposition_logo_field(null) ?>
    </div>


    <?php
  }
  add_action( 'tm_opposition_add_form_fields', 'tm_opposition_add_form_fields', 10, 2 );
endif;
?>
