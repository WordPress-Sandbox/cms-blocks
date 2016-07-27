<?php

/**
 * The metabox-specific functionality of the plugin.
 *
 * @link       https://github.com/adsric
 * @since      1.0.0
 *
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/admin
 */

/**
 * The metabox-specific functionality of the plugin.
 *
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/admin
 */
class CMS_Blocks_Admin_Metaboxes {

  /**
   * The post meta data
   *
   * @since     1.0.0
   * @access    private
   * @var       string     $meta    The post meta data.
   */
  private $meta;

  /**
   * The ID of this plugin.
   *
   * @since     1.0.0
   * @access    private
   * @var       string     $plugin_name     The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since     1.0.0
   * @access    private
   * @var       string      $version     The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  public function metabox_field_args() {
    return array(
      'fields'    => array(
        array(
           'name' => __( 'Content Filters' ),
           'desc' => __( 'Apply all WP content filters? This will include plugin added filters.' ),
           'id'   => 'content_filters',
           'type' => 'radio',
           'std'  => '',
           'options' => array(
            'default' => __( 'Defaults (recommended)' ),
            'all'     => __( 'All Content Filters' )
          )
        ),
        array(
           'name' => __( 'Auto Paragraphs' ),
           'desc' => __( 'Add &lt;p&gt; and &lt;br&gt; tags automatically.<br>(disabling may fix layout issues)' ),
           'id'   => 'wpautop',
           'type' => 'radio',
           'std'  => '',
           'options' => array(
            'on'  => __( 'On' ),
            'off' => __( 'Off' )
          )
        )
      )
    );
  }

  // Add metabox to CMS Block edit screen
  public function add_metaboxes() {
    $metabox_args =  array(
      'id'        => 'cms_block_metabox',
      'title'     => __( 'Options' ),
      'page'      => 'cms_block',
      'context'   => 'side',
      'priority'  => 'default'
    );

    add_meta_box($metabox_args['id'], $metabox_args['title'], array( $this, 'cms_block_metabox' ), $metabox_args['page'], $metabox_args['context'], $metabox_args['priority']);

    $metabox_title_args =  array(
      'id'        => 'cms_block_title_metabox',
      'title'     => __( 'Shortcode' ),
      'page'      => 'cms_block',
      'context'   => 'top',
      'priority'  => 'high'
    );

    add_meta_box($metabox_title_args['id'], $metabox_title_args['title'], array( $this, 'cms_block_title_metabox' ), $metabox_title_args['page'], $metabox_title_args['context'], $metabox_title_args['priority']);

  }

  public function get_metabox_fields() {
    return array(
      'fields'    => array(
        array(
           'name' => __( 'Class' ),
           'desc' => __( 'Add a styling class to the block.' ),
           'id'   => 'class',
           'type' => 'text'
        ),
        array(
           'name' => __( 'Content Filters' ),
           'desc' => __( 'Apply all WP content filters? This will include plugin added filters.' ),
           'id'   => 'content_filters',
           'type' => 'radio',
           'std'  => '',
           'options' => array(
            'default' => __( 'Defaults (recommended)' ),
            'all'     => __( 'All Content Filters' )
          )
        ),
        array(
           'name' => __( 'Auto Paragraphs' ),
           'desc' => __( 'Add &lt;p&gt; and &lt;br&gt; tags automatically.<br>(disabling may fix layout issues)' ),
           'id'   => 'wpautop',
           'type' => 'radio',
           'std'  => '',
           'options' => array(
            'on'  => __( 'On' ),
            'off' => __( 'Off' )
          )
        )
      )
    );
  }

  // Callback function to show fields in meta box
  public function cms_block_metabox($post) {

    $metabox_fields = $this->get_metabox_fields();

    // Use nonce for verification
    echo '<input type="hidden" name="cms_blocks_metabox_nonce" value="', wp_create_nonce( basename(__FILE__) ), '" />';

    $increment = 0;
    foreach ($metabox_fields['fields'] as $field) {
      // some styling
      $style = ($increment) ? 'border-top: 1px solid #dfdfdf;' : '';
      // get current post meta data
      $meta = get_post_meta($post->ID, $field['id'], true);

      switch ($field['type']) {

        // If text array
        case 'text':

          echo '<div class="metaField_field_wrapper metaField_field_'.$field['id'].'" style="'.$style.'">',
             '<p><label for="'.$field['id'].'"><strong>'.$field['name'].'</strong></label></p>';

          echo '<input class="metaField_text" type="text" name="'.$field['id'].'" value="'.$meta.'">';

          echo '<p class="metaField_caption" style="color:#999">'.$field['desc'].'</p>',
            '</div>';

        break;

        // If radio array
        case 'radio':

          echo '<div class="metaField_field_wrapper metaField_field_'.$field['id'].'" style="'.$style.'">',
             '<p><label for="'.$field['id'].'"><strong>'.$field['name'].'</strong></label></p>';

          $count = 0;
          foreach ($field['options'] as $key => $label) {
            $checked = ($meta == $key || (!$meta && !$count)) ? 'checked="checked"' : '';
            echo '<label class="metaField_radio" style="display: block; padding: 2px 0;"><input class="metaField_radio" type="radio" name="'.$field['id'].'" value="'.$key.'" '.$checked.'> '.$label.'</label>';
            $count++;
          }

          echo '<p class="metaField_caption" style="color:#999">'.$field['desc'].'</p>',
             '</div>';

        break;
      }
      $increment++;
    }
  }

  public function cms_block_title_metabox($post) {

    $shortcode = '[cms-block id="' . $post->ID .'"]';

    ?>
    <p class="description">
      <label for="shortcode"><?php echo esc_html( __( "Copy this shortcode and paste it into your post, page, or text widget content:", 'cms-block' ) ); ?></label>
      <span class="shortcode wp-ui-highlight">
        <input type="text" id="shortcode" onfocus="this.select();" readonly="readonly" class="large-text code" value="<?php echo esc_attr( $shortcode ); ?>">
      </span>
    </p>
    <?php
  }

  // Save data when post is edited
  public function validate_meta($post_id) {
    $metabox_fields = $this->get_metabox_fields();

    // verify nonce
    if ( !isset($_POST['cms_blocks_metabox_nonce']) || !wp_verify_nonce( $_POST['cms_blocks_metabox_nonce'], basename(__FILE__) ) ) {
      return $post_id;
    }

    // check permissions
    if ( !current_user_can('edit_post', $post_id) ) {
      return $post_id;
    }

    foreach ($metabox_fields['fields'] as $field) {
      $old = get_post_meta($post_id, $field['id'], true);
      $new = $_POST[$field['id']];

      if ($new && $new != $old) {
        update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
      } elseif ('' == $new && $old) {
        delete_post_meta($post_id, $field['id'], $old);
      }
    }
  }

  public function metabox_position_top() {

    # Get the globals:
    global $post, $wp_meta_boxes;

    # Output the "top meta boxes:
    do_meta_boxes( get_current_screen(), 'top', $post );

    # Remove the initial "top" meta boxes:
    unset($wp_meta_boxes['post']['top']);
  }

}
