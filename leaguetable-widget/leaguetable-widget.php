<?php

require_once('leaguetable-content.php');

// Creating the widget
class tm_leaguetable extends WP_Widget {

  function __construct() {
    parent::__construct(

      // Base ID of your widget
      'tm',

      // Widget name will appear in UI
      __('League Table Widget', 'tm'),

      // Widget description
      array( 'description' => __( 'League Table Widget', 'tm' ), )
    );
  }

  // Creating widget front-end
  public function widget( $args, $instance ) {
    $title = apply_filters( 'tm_title', $instance['title'] );
    $competition = apply_filters( 'tm_competition', $instance['tm_competition'] );
    $seasons = apply_filters( 'tm_seasons', $instance['tm_seasons'] );
    $team = apply_filters( 'tm_team', $instance['team'] );
    $maxrows = apply_filters( 'tm_maxrows', $instance['tm_maxrows'] );

    // before and after widget arguments are defined by themes
    echo $args['before_widget'];
    if ( ! empty( $title ) )
    $displaytitle = $args['before_title'] . $title . $args['after_title'];

    // This is where you run the code and display the output
    tm_leaguetable_widget_content($displaytitle, $competition, $seasons, $team, $maxrows);

    echo $args['after_widget'];
  }

  // Widget Backend
  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    }
    else {
      $title = __( 'Results', 'tm' );
    }
    if ( isset( $instance[ 'tm_competition' ] ) ) {
      $competition = $instance[ 'tm_competition' ];
    }
    if ( isset( $instance[ 'tm_seasons' ] ) ) {
      $season = $instance[ 'tm_seasons' ];
    }
    if ( isset( $instance[ 'tm_team' ] ) ) {
      $team = $instance[ 'tm_team' ];
    }
    if ( isset( $instance[ 'tm_maxrows' ] ) ) {
      $maxrows = $instance[ 'tm_maxrows' ];
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
    <p>
      <label for="<?php echo $this->get_field_id( 'tm_maxrows' ); ?>"><?php _e( 'Max rows:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'tm_maxrows' ); ?>" name="<?php echo $this->get_field_name( 'tm_maxrows' ); ?>" type="text" value="<?php echo esc_attr( $maxrows ); ?>" />
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
    $instance['tm_maxrows'] = ( ! empty( $new_instance['tm_maxrows'] ) ) ? strip_tags( $new_instance['tm_maxrows'] ) : '';
    return $instance;
  }
} // Class wpb_widget ends here

?>
