<?php
require_once('teamtabs-content.php');

if ( ! class_exists( 'TM_Teamtabs') ):
  class TM_Teamtabs extends WP_Widget {

    function __construct() {
      parent::__construct(

        // Base ID of your widget
        'tm_teamtabs',

        // Widget name will appear in UI
        __('TM TeamTabs', 'tm'),

        // Widget description
        array( 'description' => __( 'Tabs View of Team', 'tm' ), )
      );
    }

    // Creating widget front-end
    public function widget( $args, $instance ) {
      if ( isset($instance['title']) ) {
        $title = apply_filters( 'tm_title', $instance['title'] );
        if ( ! empty($title) ) $title = $args['before_title'] . $title . $args['after_title'];
      } else {
        $title = $args['before_title'] . __( 'Team' , 'tm' ) . $args['after_title'];
      }
      if ( isset($instance['competition']) )  {
        $competition = apply_filters( 'tm_competition', $instance['competition'] );
      } else {
        $competition ='';
      }
      if ( isset($instance['seasons']) ) {
        $seasons = apply_filters( 'tm_seasons', $instance['seasons'] );
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
      tm_teamtabs_widget_content($title, $competition, $seasons, $team, $args);
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
        $title = __( 'Team', 'tm' );
      }
      if ( isset( $instance[ 'competition' ] ) ) {
        $competition = $instance[ 'competition' ];
      } else {
        $competition = '';
      }
      if ( isset( $instance[ 'seasons' ] ) ) {
        $seasons = $instance[ 'seasons' ];
      } else {
        $seasons = '';
      }
      if ( isset( $instance[ 'team' ] ) ) {
        $team = $instance[ 'team' ];
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
        <label for="<?php echo $this->get_field_id( 'competition' ); ?>"><?php _e( 'Competiton:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'competition' ); ?>" name="<?php echo $this->get_field_name( 'competition' ); ?>" type="text" value="<?php echo esc_attr( $competition ); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'team' ); ?>"><?php _e( 'Team:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'team' ); ?>" name="<?php echo $this->get_field_name( 'team' ); ?>" type="text" value="<?php echo esc_attr( $team ); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'seasons' ); ?>"><?php _e( 'Season:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'seasons' ); ?>" name="<?php echo $this->get_field_name( 'seasons' ); ?>" type="text" value="<?php echo esc_attr( $seasons ); ?>" />
      </p>

      <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
      $instance['competition'] = ( ! empty( $new_instance['competition'] ) ) ? strip_tags( $new_instance['competition'] ) : '';
      $instance['team'] = ( ! empty( $new_instance['team'] ) ) ? strip_tags( $new_instance['team'] ) : '';
      $instance['seasons'] = ( ! empty( $new_instance['seasons'] ) ) ? strip_tags( $new_instance['seasons'] ) : '';
      return $instance;
    }
  } // Class ends here
endif;

if ( ! function_exists('tm_teamtabs_register_widget') ):
  function tm_teamtabs_register_widget() {
    register_widget( 'TM_Teamtabs');
  }
  add_action( 'widgets_init', 'tm_teamtabs_register_widget' );
endif;

?>
