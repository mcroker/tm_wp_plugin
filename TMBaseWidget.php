<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists('TMBaseWidget')):
  class TMBaseWidget extends WP_Widget {

    public static $title = 'Unnamed Widget';
    public static $description = 'Unnamed Widget';

    protected static $meta_keys = [];

    function __construct() {
      $classname = get_called_class();
      parent::__construct( $classname , __($classname::$title, 'tm'), array( 'description' => __( $classname::$description, 'tm' ), ) );
    }

    // ==================================================
    static function init() {
      $classname = get_called_class();
      add_action( 'widgets_init', $classname . '::register_widget' );
      add_shortcode( $classname, $classname . '::register_shortcode');
    }

    // ==================================================
    static function register_widget() {
      $classname = get_called_class();
      register_widget( $classname );
    }

    // ==================================================
    function content( $metavalues ) {
    }

    // Creating widget front-end ==================================================
    public function widget( $args, $instance ) {
      $classname = get_called_class();
      $metavalues = [];
      foreach ($classname::$meta_keys as $key => $fieldmeta) {
        if ( isset($instance[$key])) {
          $metavalues[$key] = apply_filters( $key, $instance[$key] );
          if ( ! empty( $metavalues[$key] ) ) {
            $metavalues[$key] = $args['before_' . $key] . $metavalues[$key] . $args['after_' . $key];
          };
        } else {
          // Empty value
          switch($metavalues['type']) {
            case 'meta_attrib_check': $metavalues[$key] = false; break;
            default:                  $metavalues[$key] = '';
          }
        }
      }
      echo $args['before_widget'];
      $this->content( $metavalues );
      echo $args['after_widget'];
    }

    // Widget Backend ==================================================
    // Widget admin form ==================================================
    public function form( $instance ) {
      $classname = get_called_class();
      $metavalues = [];
      foreach ($classname::$meta_keys as $key => $fieldmeta) {
        if ( isset( $instance[$key] ) ) {
          $metavalues[$key] = $instance[$key];
        }
        else {
          $metavalues[$key] = __( $fieldmeta['default'], 'tm' );
        }

        $this->widget_admin_field($key, $metavalues[$key]);
      }
    }

    // Updating widget replacing old instances with new ==================================================
    public function update( $new_instance, $old_instance ) {
      $classname = get_called_class();
      $instance = array();
      foreach ($classname::$meta_keys as $key => $fieldmeta) {
        $instance[$key] = ( ! empty( $new_instance[$key] ) ) ? strip_tags( $new_instance[$key] ) : '';
      }
      return $instance;
    }

    // ==================================================
    public function widget_admin_field($key, $value, $label = "_AUTO", $type = "_AUTO", $settings = "_AUTO") {
      $classname = get_called_class();
      if ( $label  == "_AUTO" ) {
        $label = $classname::$meta_keys[$key]['label'];
      }
      if ( $type == "_AUTO" ) {
        $type = $classname::$meta_keys[$key]['type'];
      }
      if ( $settings == "_AUTO" ) {
        $settings = $classname::$meta_keys[$key]['settings'];
      }
      ?>
      <p>
        <label for="<?php echo $this->get_field_id( $key ); ?>"><?php _e($label, 'tm'); ?></label>
        <?php
        switch($type) {
          case 'meta_attrib_select':  $classname::widget_admin_field_select($key, $value, $label, $settings); break;
          case 'related_post':        $classname::widget_admin_field_relatedpost($key, $value, $label, $settings); break;
          default:                    $classname::widget_admin_field_string($key, $value, $label, $settings);
        }
        ?>
      </p>
      <?php
    }

    // ==================================================
    public function widget_admin_field_string($key, $value ="", $label = "", $settings = []) {
      ?>
      <input class="widefat" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
      <?php
    }

    // ==================================================
    public function widget_admin_field_select($key, $value ="", $label = "", $settings = []) {
      ?>
      <select class="widefat" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" >
        <option value=''>Page default</option>
        <?php
        foreach ($settings['options'] as $optionkey => $optiontext ) { ?>
          <option value=<?php echo $optionkey ?> <?php selected( $value , $optionkey  ) ?> > <?php echo $optiontext ?> </option>
        <?php } ?>
      </select>
      <?php
    }

    // ==================================================
    public function widget_admin_field_relatedpost($key, $value ="", $label = "", $settings = []) {
      $classname = get_called_class();
      $fieldmeta = $classname::$meta_keys[$key];
      $relatedclass = $fieldmeta['classname'];
      $allposts = $relatedclass::getAll();
      $settings['options'] = [];
      foreach ($allposts as $relatedpost ) {
        $settings['options'][$relatedpost->ID] = $relatedpost->title;
      }
      $this->widget_admin_field_select($key, $value, $label, $settings );
    }

    // ==================================================
    function shortcode($atts = [], $content = null, $tag = '') {
      // normalize attribute keys, lowercase
      $atts = array_change_key_case((array)$atts, CASE_LOWER);

      // override default attributes with user attributes
      $classname = get_called_class();
      $atts_pairs = [];
      foreach ($classname::$meta_keys as $key => $fieldmeta) {
        $atts_pairs[$key] = $fieldmeta['default'];
      };
      $parsed_atts = shortcode_atts($atts_pairs, $atts, $tag);

      ob_start();
      $classname::content($parsed_atts);
      $o = ob_get_clean();
      // return output
      return $o;
    }


} // Class wpb_widget ends here
endif;
?>
