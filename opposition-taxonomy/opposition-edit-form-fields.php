<?php
if ( ! function_exists( 'tm_opposition_edit_form_fields' ) ):
  function tm_opposition_edit_form_fields($term) {

    $opposition = new TMOpposition($term);
    ?>

    <tr class="form-field term-group-wrap">
      <th scope="row">
        <label for="tm_opposition_url"><?php _e('URL', 'tm'); ?></label>
      </th>
      <td>
        <input type="text" name="tm_opposition_url" value="<?php echo $opposition->url ?>" id="tm_opposition_url"/><br>
      </td>
    </tr>

    <tr class="form-field term-group-wrap">
      <th scope="row">
        <label for="tm_opposition_logo"><?php _e('Logo', 'tm'); ?></label>
      </th>
      <td>
        <?php tm_opposition_logo_field($term) ?>
      </td>
    </tr>

    ();
    <?php
  }
  add_action( 'tm_opposition_edit_form_fields', 'tm_opposition_edit_form_fields', 10, 2 );
endif;
?>
