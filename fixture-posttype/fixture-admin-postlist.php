<?php
/**
* Configures tm_fixture post type, admin list
* Adds columns, filters, and sorting
*
* @package TM
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Add custom columns ==================================================
if ( is_admin() && ! function_exists('tm_fixture_table_head') ):
  /**
  * Add custom fields to admin list view for posttype tm_fixture
  * Bound to manage_tm_fixture_posts_columns filter
  *
  * @param Array $defaults Array of columns to display $key => $description
  * @return Array $defaults Ammended array of columns to display
  */
  function tm_fixture_table_head( $defaults ) {
    $defaults['tm_fixture_date'] = _( 'Fixture Date' , 'tm' );
    $defaults['tm_fixture_team'] = _('Team', 'tm');
    $defaults['tm_fixture_homeaway'] = _('H/A', 'tm');
    $defaults['tm_fixture_scorefor'] = _('F', 'tm');
    $defaults['tm_fixture_scoreagainst'] = _('A', 'tm');
    $defaults['tm_fixture_opposition'] = _('Opposition', 'tm');
    $defaults['tm_fixture_competition'] = _('Competition', 'tm');
    $defaults['tm_fixture_season'] = _('Season', 'tm');
    $defaults['tm_fixture_createdbyautofetch'] = _('Sync?', 'tm');
    return $defaults;
  }
  add_filter( 'manage_tm_fixture_posts_columns', 'tm_fixture_table_head');
endif;

// Set sortable columns ==================================================
if ( is_admin() && ! function_exists('tm_fixture_set_custom_sortable_columns') ):
  /**
  * Add custom fields to admin list view for posttype tm_fixture
  * Bound to tm_fixture_sortable_columns filter
  *
  * @param Array $columns Array of columns which are sortable
  * @return Array $columns Ammended array of sortable columns
  */
  function tm_fixture_set_custom_sortable_columns( $columns ) {
    $columns['tm_fixture_opposition'] = 'tm_fixture_opposition';
    $columns['tm_fixture_competition'] = 'tm_fixture_competition';
    $columns['tm_fixture_season'] = 'tm_fixture_season';
    $columns['tm_fixture_date'] = 'tm_fixture_date Date';
    $columns['tm_fixture_team'] = 'tm_fixture_team';
    return $columns;
  }
  add_filter( 'manage_edit-tm_fixture_sortable_columns', 'tm_fixture_set_custom_sortable_columns' );
endif;

// Add CSS ==================================================
if ( is_admin() && ! function_exists('tm_fixture_table_adminhead') ):
  /**
  * Enqueue fixture-admin-postlist.css
  * Bound to admin_head action hook
  *
  * @param void
  * @return void
  */
  function tm_fixture_table_adminhead() {
    $screen = get_current_screen();
    if ( 'tm_fixture' == $screen->post_type ) {
      $plugin_url = plugin_dir_url(__FILE__);
      wp_enqueue_style( 'tm-fixture-admin-postlist', $plugin_url . 'fixture-admin-postlist.css', array(), 'v4.0.0');
      // Remove months dropdown filter
      add_filter('months_dropdown_results', '__return_empty_array');
    }
  }
  add_action('admin_head', 'tm_fixture_table_adminhead');
endif;

// Table content ==================================================
if ( is_admin() && ! function_exists('tm_fixture_table_content') ):
  /**
  * Enqueue fixture-admin-postlist.css, response is echo'ed (i.e. not returned)
  * Bound to admin_head action hook
  *
  * @param string $column_name Name of column being printed
  * @param string $post_id     ID of post being printed
  *
  * @return void
  */
  function tm_fixture_table_content( $column_name, $post_id ) {
    $fixture = new TMFixture($post_id);
    if ($column_name == 'tm_fixture_date') {
      echo date('Y-m-d', $fixture->fixturedate);
    }
    if ($column_name == 'tm_fixture_team') {
      echo esc_html($fixture->team->title);
    }
    if ($column_name == 'tm_fixture_homeaway') {
      $homeaway = $fixture->homeaway;
      switch($homeaway) {
        case 'H': echo 'Home'; break;
        case 'A': echo 'Away'; break;
        default: echo esc_html($homeaway);
      }
    }
    if ($column_name == 'tm_fixture_scorefor') {
      echo esc_html($fixture->scorefor);
    }
    if ($column_name == 'tm_fixture_scoreagainst') {
      echo esc_html($fixture->scoreagainst);
    }
    if ($column_name == 'tm_fixture_opposition') {
      echo esc_html($fixture->opposition->name);
    }
    if ($column_name == 'tm_fixture_season') {
      echo esc_html($fixture->season->name);
    }
    if ($column_name == 'tm_fixture_competition') {
      if ( $fixture->competition ) {
        echo esc_html($fixture->competition->name);
      }
    }
    if ($column_name == 'tm_fixture_createdbyautofetch') {
      if ($fixture->createdbyautofetch && $fixture->useautofetch) {
        echo 'Yes';
      }
    }
  }
  add_action( 'manage_tm_fixture_posts_custom_column', 'tm_fixture_table_content', 10, 2 );
endif;


// Add filter form ==================================================
if ( is_admin() && ! function_exists('tm_fixture_restrict_manage_posts') ):
  /**
  * Add form to allow tm_fixture postlist to be filtered, output via echo rather than return
  * Bound to restrict_manage_posts action hook
  *
  * @param void
  * @return void
  */
  function tm_fixture_restrict_manage_posts(){
    $type = 'post';
    if (isset($_GET['post_type'])) {
      $type = $_GET['post_type'];
    }
    if ('tm_fixture' == $type){
      ?>

      <select name="tm_fixture_adminfilter_team">
        <option value=""><?php _e('Filter by team:', 'tm'); ?></option>
        <?php
        $current_v = isset($_GET['tm_fixture_adminfilter_team']) ? $_GET['tm_fixture_adminfilter_team'] :  '';
        foreach (TMTeam::getAll() as $value) {
          printf(
            '<option value="%s"%s>%s</option>',
            $value->ID,
            $value->ID == $current_v ? ' selected="selected"' : '',
            $value->title
          );
        }
        ?>
      </select>

      <select name="tm_fixture_adminfilter_opposition">
        <option value=""><?php _e('Filter by opposition:', 'tm'); ?></option>
        <?php
        $current_v = isset($_GET['tm_fixture_adminfilter_opposition']) ? $_GET['tm_fixture_adminfilter_opposition'] : '';
        foreach (TMOpposition::getAll() as $value) {
          printf
          (
            '<option value="%s"%s>%s</option>',
            $value->ID,
            $value->ID == $current_v? ' selected="selected"':'',
            $value->name
          );
        }
        ?>
      </select>

      <select name="tm_fixture_adminfilter_season">
        <option value=""><?php _e('Filter by season:', 'tm'); ?></option>
        <?php
        $current_v = isset($_GET['tm_fixture_adminfilter_season']) ? $_GET['tm_fixture_adminfilter_season'] :  '';
        foreach (TMSeason::getAll() as $value) {
          printf(
            '<option value="%s"%s>%s</option>',
            $value->ID,
            $value->ID == $current_v ? ' selected="selected"' : '',
            $value->name
          );
        }
        ?>
      </select>

      <?php
    }
  }
  add_action( 'restrict_manage_posts', 'tm_fixture_restrict_manage_posts' );
endif;

// Apply filter ==================================================
if ( is_admin() && ! function_exists('tm_fixture_posts_filter') ):
  /**
  * Apply filter to tm_fixture postlist prior to retrieval
  * Bound to parse_query filter
  *
  * @param WP_Query $query   Query object about to be retrieved, this is amended in-situ
  * @return void
  */
  function tm_fixture_posts_filter( $query ){
    global $pagenow;
    $type = isset($_GET['post_type']) ? $_GET['post_type'] : 'post';
    if ( 'tm_fixture' == $type && $pagenow=='edit.php' && $query->is_main_query() ) {

      $tax_query = [];
      if ( isset($_GET['tm_fixture_adminfilter_opposition']) && $_GET['tm_fixture_adminfilter_opposition'] != '' ) {
        $tax_query[] = array(
          'taxonomy' => 'tm_opposition',                  // taxonomy name
          'field' => 'term_id',                           // term_id, slug or name
          'terms' => $_GET['tm_fixture_adminfilter_opposition']   // term id, term slug or term name
        );
      }
      if ( isset($_GET['tm_fixture_adminfilter_season']) && $_GET['tm_fixture_adminfilter_season'] != '' ) {
        $tax_query[] = array(
          'taxonomy' => 'tm_season',                  // taxonomy name
          'field' => 'term_id',                           // term_id, slug or name
          'terms' => $_GET['tm_fixture_adminfilter_season']   // term id, term slug or term name
        );
      }
      switch ( sizeof($tax_query) ) {
        case 0:  break; // Do nothing
        case 1:  $query->query_vars['tax_query'] =  Array ( $tax_query ); break; // Nest n array
        default: $query->query_vars['tax_query'] =  array_merge ( Array ( 'relation' => 'AND' ) , $tax_query ); break;
      }

      if ( isset($_GET['tm_fixture_adminfilter_team']) && $_GET['tm_fixture_adminfilter_team'] != '' ) {
        $query->query_vars['meta_query'] = array(
          array(
            'key'	 	   => 'tm_fixture_team',
            'value'	   => $_GET['tm_fixture_adminfilter_team'] ,
            'compare'  => '='
          )
        );
        /// $query->query_vars['meta_key'] = 'tm_fixture_opposition';
        // $query->query_vars['meta_value'] = $_GET['tm_fixture_adminfilter_opposition'];
      }
    }
  }
  add_filter( 'parse_query', 'tm_fixture_posts_filter' );
endif;
?>
