<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once('fixturelist-content.php');
require_once('fixturelist-block-content.php');
require_once('fixturelist-table-content.php');
require_once('fixturelist-shortcode.php');

if ( ! class_exists( 'TMFixtures') ):
  class TMFixturelist extends WP_Widget {

    function __construct() {
      parent::__construct(

        // Base ID of your widget
        'tm_fixturelist',

        // Widget name will appear in UI
        __('TM Fixture-list', 'tm'),

        // Widget description
        array( 'description' => __( 'List of all fixtures', 'tm' ), )
      );
    }

    // Creating widget front-end ==================================================
    public function widget( $args, $instance ) {
      if ( isset($instance['title'])) {
        $title = apply_filters( 'title', $instance['title'] );
        if ( ! empty( $title ) ) $title = $args['before_title'] . $title . $args['after_title'];
      } else {
        $title = '';
      }
      if ( isset($instance['tm_displaystyle']) ) {
        $displaystyle = apply_filters( 'tm_displaystyle', $instance['tm_displaystyle'] );
      } else {
        $displaystyle = 'block';
      }
      if ( isset($instance['tm_team']) ) {
        $team = apply_filters( 'tm_team', $instance['tm_team'] );
      } else {
        $team = '';
      }
      if ( isset($instance['tm_maxrows']) ) {
        $maxrows = apply_filters( 'tm_maxrows', $instance['tm_maxrows'] );
      } else {
        $maxrows = '';
      }
      if ( isset($instance['tm_maxfuture']) ) {
        $maxfuture = apply_filters( 'tm_maxfuture', $instance['tm_maxfuture'] );
      } else {
        $maxfuture = '';
      }

      echo $args['before_widget'];
      tm_fixturelist_widget_content($displaystyle, $title, $team, $maxrows, $maxfuture );
      echo $args['after_widget'];
    }

    // Widget Backend ==================================================
    public function form( $instance ) {
      if ( isset( $instance[ 'title' ] ) ) {
        $title = $instance[ 'title' ];
      }
      else {
        $title = __( 'Fixtures', 'tm' );
      }
      if ( isset( $instance[ 'tm_displaystyle' ] ) ) {
        $displaystyle = $instance[ 'tm_displaystyle' ];
      } else {
        $displaystyle = 'block';
      }
      if ( isset( $instance[ 'tm_team' ] ) ) {
        $team = $instance[ 'tm_team' ];
      } else {
        $team = '';
      }
      if ( isset( $instance[ 'tm_maxrows' ] ) ) {
        $maxrows = $instance[ 'tm_maxrows' ];
      } else {
        $maxrows = 6;
      }
      if ( isset( $instance[ 'tm_maxfuture' ] ) ) {
        $maxfuture = $instance[ 'tm_maxfuture' ];
      } else {
        $maxfuture = 3;
      }

      // Widget admin form ==================================================
      ?>
      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'tm_displaystyle' ); ?>"><?php _e( 'Style:' ); ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id( 'tm_displaystyle' ); ?>" name="<?php echo $this->get_field_name( 'tm_displaystyle' ); ?>">
          <? $styles = Array (
            'block' => 'Block',
            'table' => 'Table'
          );
          foreach ($styles as $styleid => $styledesc) { ?>
            <option value=<?php echo $styleid ?> <?php selected( $displaystyle , $styleid ) ?> > <?php echo $styledesc ?> </option>
          <?php } ?>
        </select>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'tm_team' ); ?>"><?php _e( 'Team:' ); ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id( 'tm_team' ); ?>" name="<?php echo $this->get_field_name( 'tm_team' ); ?>" >
          <option value=''>Page default</option>
          <?php
          foreach (TMTeam::getAll() as $teamitem) { ?>
            <option value=<?php echo $teamitem->ID ?> <?php selected( $team , $teamitem->ID  ) ?> > <?php echo $teamitem->title ?> </option>
          <?php } ?>
        </select>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'tm_maxrows' ); ?>"><?php _e( 'Max Rows:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'tm_maxrows' ); ?>" name="<?php echo $this->get_field_name( 'tm_maxrows' ); ?>" type="text" value="<?php echo esc_attr( $maxrows ); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'tm_maxfuture' ); ?>"><?php _e( 'Max Future:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'tm_maxfuture' ); ?>" name="<?php echo $this->get_field_name( 'tm_maxfuture' ); ?>" type="text" value="<?php echo esc_attr( $maxfuture ); ?>" />
      </p>
      <?php
    }

    // Updating widget replacing old instances with new ==================================================
    public function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
      $instance['tm_team'] = ( ! empty( $new_instance['tm_team'] ) ) ? strip_tags( $new_instance['tm_team'] ) : '';
      $instance['tm_displaystyle'] = ( ! empty( $new_instance['tm_displaystyle'] ) ) ? strip_tags( $new_instance['tm_displaystyle'] ) : '';
      $instance['tm_maxrows'] = ( ! empty( $new_instance['tm_maxrows'] ) ) ? strip_tags( $new_instance['tm_maxrows'] ) : '';
      $instance['tm_maxfuture'] = ( ! empty( $new_instance['tm_maxfuture'] ) ) ? strip_tags( $new_instance['tm_maxfuture'] ) : '';
      return $instance;
    }
  } // Class wpb_widget ends here
endif;

// Register widget ==================================================
if ( ! function_exists('tm_fixturelist_register_widget') ):
  function tm_fixturelist_register_widget() {
    register_widget( 'TMFixturelist');
  }
  add_action( 'widgets_init', 'tm_fixturelist_register_widget' );
endif;

?>
