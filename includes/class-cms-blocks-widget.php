<?php

/**
 * The widget functionality of the plugin.
 *
 * @link       https://github.com/adsric/
 * @since      1.0.0
 *
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/includes
 */

/**
 * The widget functionality of the plugin.
 *
 * @since      1.0.0
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/includes
 */
class CMS_Block_Widget extends WP_Widget {

  /**
   * The ID of this plugin.
   *
   * @since     1.0.0
   * @access    private
   * @var       string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    $this->plugin_name    = 'cms-block';

    $name                 = esc_html__( 'Block' );
    $id                   = 'cms-block-widget';
    $opts['classname']    = 'cms-block';
    $opts['description']  = esc_html__( 'Displays the content from a Block.' );
    $control              = array( 'width' => 300, 'height' => 350 );

    parent::__construct( $id, $name, $opts, $control );
  }

  function widget( $args, $instance ) {
    extract( $args );

    $id = (isset($instance['cms_block_id'])) ? $instance['cms_block_id'] : '';

    if ( ! empty($id) ) {
      // Our variables from the widget settings.
      $title = apply_filters('widget_title', the_cms_block($id, array('return_type'=>'title'), false) );
      $content = the_cms_block($id, '', false);
      $show_title = isset( $instance['show_title'] ) ? $instance['show_title'] : false;

      echo  $before_widget;

      // Display the widget title
      if ( $show_title )
        echo  $before_title . $title . $after_title;

      // Display the content
      if ( $content )
        echo  $content;

      echo  $after_widget;
    }
  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    // Strip tags from title and name to remove HTML
    $instance['cms_block_id'] = ( isset($new_instance['cms_block_id']) ) ? $new_instance['cms_block_id'] : '';
    $instance['show_title'] = ( isset($new_instance['show_title']) ) ? $new_instance['show_title'] : '';

    return $instance;
  }

  function form( $instance ) {

    // Set up some default widget settings.
    $defaults = array( 'cms_block_id' => 0, 'show_title' => false );
    $instance = wp_parse_args( (array) $instance, $defaults ); ?>

    <p>
      <label for="<?php echo esc_attr($this->get_field_id( 'cms_block_id' )); ?>"><?php _e( 'Render block:' ); ?></label>
      <select id="<?php echo esc_attr($this->get_field_id( 'cms_block_id' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'cms_block_id' )); ?>" style="width:100%;" >
      <?php

      $args = array(
        'posts_per_page' => -1,
        'post_type' => 'cms_block'
      );

      $cms_blocks = get_posts($args);

      foreach ($cms_blocks as $key => $value) {
        $id       = $value->ID;
        $title    = $value->post_title;
        echo '<option value="'. esc_attr($id).'" '. selected( $instance['cms_block_id'], $id, false) .'>'.esc_attr($title).'</option>';
      }
      ?>
      </select>
    </p>

    <p>
      <input class="checkbox" type="checkbox" <?php checked( $instance['show_title'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_title' )); ?>" />
      <label for="<?php echo esc_attr($this->get_field_id( 'show_title' )); ?>"><?php _e( 'Show title' ); ?></label>
    </p>

  <?php
  }

}
