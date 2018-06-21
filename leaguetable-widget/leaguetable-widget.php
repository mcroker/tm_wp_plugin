<?php
require_once('leaguetable-content.php');
require_once('leaguetable-shortcode.php');

if ( ! class_exists( 'TM_Leaguetable') ):
  class TM_Leaguetable extends WP_Widget {

    function __construct() {
      parent::__construct(

        // Base ID of your widget
        'tm_leaguetable',

        // Widget name will appear in UI
        __('TM League Table', 'tm'),

        // Widget description
        array( 'description' => __( 'League table display', 'tm' ), )
      );
    }

    // Creating widget front-end
    public function widget( $args, $instance ) {
      if ( isset($instance['title']) ) {
        $title = apply_filters( 'tm_title', $instance['title'] );
        if ( ! empty($title) ) $title = $args['before_title'] . $title . $args['after_title'];
      } else {
        $title = $args['before_title'] . __( 'Leaguetable' , 'tm' ) . $args['after_title'];
      }
      if ( isset($instance['tm_competition']) )  {
        $competition = apply_filters( 'tm_competition', $instance['tm_competition'] );
      } else {
        $competition ='';
      }
      if ( isset($instance['tm_seasons']) ) {
        $seasons = apply_filters( 'tm_seasons', $instance['tm_seasons'] );
      } else {
        $seasons ='';
      }
      if ( isset($instance['team']) ) {

        $team = apply_filters( 'tm_team', $instance['team'] );
      } else {
        $team = '';
      }

    // This is where you run the code and display the output
      if ( isset($args['before_widget']) ) {
        echo $args['before_widget'];
      }
      tm_leaguetable_widget_content($title, $competition, $seasons, $team, $args);
      if ( isset($args['after_widget']) ) {
        echo $args['after_widget'];
      }
    }

    // Widget Backend
    public function form( $instance ) {
      if ( isset( $instance[ 'title' ] ) ) {
        $title = $instance[ 'title' ];
      }
      else {
        $title = __( 'Leaguetable', 'tm' );
      }
      if ( isset( $instance[ 'tm_competition' ] ) ) {
        $competition = $instance[ 'tm_competition' ];
      } else {
        $competition = '';
      }
      if ( isset( $instance[ 'tm_seasons' ] ) ) {
        $seasons = $instance[ 'tm_seasons' ];
      } else {
        $seasons = '';
      }
      if ( isset( $instance[ 'tm_team' ] ) ) {
        $team = $instance[ 'tm_team' ];
      } else {
        $team = '';
      }
      // Widget admin form
      ?>
      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'tm_competition' ); ?>"><?php _e( 'Competiton:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'tm_competition' ); ?>" name="<?php echo $this->get_field_name( 'tm_competition' ); ?>" type="text" value="<?php echo esc_attr( $competition ); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'tm_team' ); ?>"><?php _e( 'Team:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'tm_team' ); ?>" name="<?php echo $this->get_field_name( 'tm_team' ); ?>" type="text" value="<?php echo esc_attr( $team ); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'tm_seasons' ); ?>"><?php _e( 'Season:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'tm_seasons' ); ?>" name="<?php echo $this->get_field_name( 'tm_seasons' ); ?>" type="text" value="<?php echo esc_attr( $seasons ); ?>" />
      </p>

      <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['tm_title'] = ( ! empty( $new_instance['tm_title'] ) ) ? strip_tags( $new_instance['tm_title'] ) : '';
      $instance['tm_competition'] = ( ! empty( $new_instance['tm_competition'] ) ) ? strip_tags( $new_instance['tm_competition'] ) : '';
      $instance['tm_team'] = ( ! empty( $new_instance['tm_team'] ) ) ? strip_tags( $new_instance['tm_team'] ) : '';
      $instance['tm_seasons'] = ( ! empty( $new_instance['tm_seasons'] ) ) ? strip_tags( $new_instance['tm_seasons'] ) : '';
      return $instance;
    }
  } // Class ends here
endif;

if ( ! function_exists('tm_leaguetable_register_widget') ):
  function tm_leaguetable_register_widget() {
    register_widget( 'TM_Leaguetable');
  }
  add_action( 'widgets_init', 'tm_leaguetable_register_widget' );
endif;

?>
