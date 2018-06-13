<?php
require_once('fixtures-content.php');
require_once('fixtures-shortcode.php');

if ( ! class_exists( 'TM_Fixtures') ):
  class TM_Fixtures extends WP_Widget {

    function __construct() {
      parent::__construct(

        // Base ID of your widget
        'tm_results',

        // Widget name will appear in UI
        __('Results Widget', 'tm'),

        // Widget description
        array( 'description' => __( 'Results Widget', 'tm' ), )
      );
    }

    // Creating widget front-end
    public function widget( $args, $instance ) {
      $title = apply_filters( 'tm_title', $instance['title'] );
      $competition = apply_filters( 'tm_competition', $instance['tm_competition'] );
      $compgroup = apply_filters( 'tm_compgroup', $instance['tm_compgroup'] );
      $season = apply_filters( 'tm_season', $instance['tm_season'] );
      $team = apply_filters( 'tm_team', $instance['team'] );
      $maxrows = apply_filters( 'tm_maxrows', $instance['tm_maxrows'] );
      $maxfuture = apply_filters( 'tm_maxfuture', $instance['tm_maxfuture'] );

      // before and after widget arguments are defined by themes
      echo $args['before_widget'];
      if ( ! empty( $title ) )
      $displaytitle = $args['before_title'] . $title . $args['after_title'];

      // This is where you run the code and display the output
      // TODO : Need to validate and parse arguements
      tm_fixtures_widget_content();

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
      if ( isset( $instance[ 'tm_compgroup' ] ) ) {
        $compgroup = $instance[ 'tm_compgroup' ];
      }
      if ( isset( $instance[ 'tm_season' ] ) ) {
        $season = $instance[ 'tm_season' ];
      }
      if ( isset( $instance[ 'tm_team' ] ) ) {
        $team = $instance[ 'tm_team' ];
      }
      if ( isset( $instance[ 'tm_maxrows' ] ) ) {
        $maxrows = $instance[ 'tm_maxrows' ];
      }
      if ( isset( $instance[ 'tm_maxfuture' ] ) ) {
        $maxfuture = $instance[ 'tm_maxfuture' ];
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
        <label for="<?php echo $this->get_field_id( 'tm_compgroup' ); ?>"><?php _e( 'Group:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'tm_compgroup' ); ?>" name="<?php echo $this->get_field_name( 'tm_compgroup' ); ?>" type="text" value="<?php echo esc_attr( $compgroup ); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'tm_team' ); ?>"><?php _e( 'Team:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'tm_team' ); ?>" name="<?php echo $this->get_field_name( 'tm_team' ); ?>" type="text" value="<?php echo esc_attr( $team ); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id( 'tm_season' ); ?>"><?php _e( 'Season:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'tm_season' ); ?>" name="<?php echo $this->get_field_name( 'tm_season' ); ?>" type="text" value="<?php echo esc_attr( $team ); ?>" />
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

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['tm_title'] = ( ! empty( $new_instance['tm_title'] ) ) ? strip_tags( $new_instance['tm_title'] ) : '';
      $instance['tm_competition'] = ( ! empty( $new_instance['tm_competition'] ) ) ? strip_tags( $new_instance['tm_competition'] ) : '';
      $instance['tm_compgroup'] = ( ! empty( $new_instance['tm_compgroup'] ) ) ? strip_tags( $new_instance['tm_compgroup'] ) : '';
      $instance['tm_team'] = ( ! empty( $new_instance['tm_team'] ) ) ? strip_tags( $new_instance['tm_team'] ) : '';
      $instance['tm_season'] = ( ! empty( $new_instance['tm_season'] ) ) ? strip_tags( $new_instance['tm_season'] ) : '';
      $instance['tm_maxrows'] = ( ! empty( $new_instance['tm_maxrows'] ) ) ? strip_tags( $new_instance['tm_maxrows'] ) : '';
      $instance['tm_maxfuture'] = ( ! empty( $new_instance['tm_maxfuture'] ) ) ? strip_tags( $new_instance['tm_maxfuture'] ) : '';
      return $instance;
    }
  } // Class wpb_widget ends here
endif;

if ( ! function_exists('tm_fixtures_register_widget') ):
  function tm_fixtures_register_widget() {
    register_widget( 'TM_Fixtures');
  }
  add_action( 'widgets_init', 'tm_fixtures_register_widget' );
endif;

?>
