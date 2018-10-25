<?php
// create custom plugin settings menu
add_action('admin_menu', 'tm_festival_create_menu');

if ( ! function_exists('tm_festival_create_menu')):
function tm_festival_create_menu() {

	//create new top-level menu
	add_submenu_page('edit.php?post_type=tm_festival', 'Festival Plugin Settings', 'Festival Settings', 'administrator', __FILE__, 'tm_festival_settings_page' , plugins_url('/images/icon.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'tm_festival_register_settings' );
}
endif;


if ( ! function_exists('tm_festival_register_settings')):
function tm_festival_register_settings() {
	//register our settings
	register_setting( 'my-cool-plugin-settings-group', 'new_option_name' );
	register_setting( 'my-cool-plugin-settings-group', 'some_other_option' );
	register_setting( 'my-cool-plugin-settings-group', 'option_etc' );
}
endif;

if ( ! function_exists('tm_festival_settings_page')):
function tm_festival_settings_page() {
?>
<div class="wrap">
<h1>TM Festival</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">New Option Name</th>
        <td><input type="text" name="new_option_name" value="<?php echo esc_attr( get_option('new_option_name') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Some Other Option</th>
        <td><input type="text" name="some_other_option" value="<?php echo esc_attr( get_option('some_other_option') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Options, Etc.</th>
        <td><input type="text" name="option_etc" value="<?php echo esc_attr( get_option('option_etc') ); ?>" /></td>
        </tr>
    </table>

    <?php submit_button(); ?>

</form>
</div>
<?php }
endif;
?>
