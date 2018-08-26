<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( is_admin() && ! function_exists( 'tm_team_create_metadatabox_autofetch' ) ):
  function tm_team_create_metadatabox_autofetch() {
    add_meta_box(
      'tm_team_autofetch',
      'Team Metadata',
      'tm_team_inner_custom_box',
      'tm_team',
      'side',
      'default'
    );
  }
  add_action( 'add_meta_boxes', 'tm_team_create_metadatabox_autofetch' );
endif;

if ( is_admin() && ! function_exists( 'tm_team_enqueue_adminscripts' )):
  function tm_team_enqueue_adminscripts($hook) {
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_script( 'team-admin-metadatabox', $plugin_url . 'team-admin-metadatabox.js', array('jquery'), 'v4.0.0', true );
    wp_enqueue_style( 'plugin-css', dirname($plugin_url) . '/style.css', array(), 'v4.0.0');
  }
  add_action( 'admin_enqueue_scripts', 'tm_team_enqueue_adminscripts' );
endif;


if ( is_admin() && ! function_exists( 'tm_team_inner_custom_box' ) ):
  function tm_team_inner_custom_box($post)
  {
    wp_localize_script( 'team-admin-metadatabox', 'tmphpobject', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'team_id' => $post->ID
    ) );

    // Use nonce for verification
    wp_nonce_field( 'tm_team_field_nonce', 'tm_team_nonce' );

    $team = new TMTeam($post);

    ?>
    <label for="tm_team_leagueteam"><?php echo esc_html__('Team','tm') ?></label><br>
    <input id='tm_team_leagueteam' type="text" class="tm-meta-fullinput" name="tm_team_leagueteam" value="<?php echo esc_attr($team->leagueteam) ?>"/><br>

    <div class="tm-meta-smallinput">
      <label for="tm_team_autofetch"><?php echo esc_html__('Automatically fetch fixtures?','tm') ?></label>
      <input type='checkbox' class='tm-meta-smallinput' id='tm_team_useautofetch' name='tm_team_useautofetch' <?php checked($team->useautofetch) ?>>
    </div><br>

    <input id='tm-team-autofetch' class='button' type='button' onclick='java_script_:execTeamAutoFetcher()' value='<?php echo esc_attr__('Fetch Fixtures','tm') ?>' >
    <div><span id='tm-update-status'></span></div>

    <label for="tm_team_mailshortcode"><?php echo esc_html__('Mail shortcode','tm') ?></label><br>
    <input id='tm_team_mailshortcode' type="text" class="tm-meta-fullinput" name="tm_team_mailshortcode" value="<?php echo esc_attr($team->mailshortcode) ?>"/><br>

    <label for="tm_team_newsshortcode"><?php echo esc_html__('News shortcode','tm') ?></label><br>
    <input id='tm_team_newsshortcode' type="text" class="tm-meta-fullinput" name="tm_team_newsshortcode" value="<?php echo esc_attr($team->newsshortcode) ?>"/><br>

    <?php
  }
endif;

// Save ==================================================
if ( is_admin() && ! function_exists( 'tm_team_save_metadatabox_autofetch' ) ):
  function tm_team_save_metadatabox_autofetch( $post_id )
  {

    $post_type = get_post_type($post_id);
    if ( "tm_team" != $post_type ) return;

    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;

    $team = new TMTeam($post_id);

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (! isset( $_POST['tm_team_nonce']) )
    return;
    if ( !wp_verify_nonce( $_POST['tm_team_nonce'], 'tm_team_field_nonce' ) )
    return;

    if ( isset($_POST['tm_team_leagueteam']) ){
      $team->leagueteam = $_POST['tm_team_leagueteam'];
    }

    $team->useautofetch = ( isset($_POST['tm_team_useautofetch']) );

    if ( isset($_POST['tm_team_mailshortcode']) ){
      $team->mailshortcode = $_POST['tm_team_mailshortcode'];
    }

    if ( isset($_POST['tm_team_newsshortcode']) ){
      $team->newsshortcode = $_POST['tm_team_newsshortcode'];
    }

  }
  add_action( 'save_post', 'tm_team_save_metadatabox_autofetch' );
endif;
?>
