<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/adsric
 * @since      1.0.0
 *
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/public
 */
class CMS_Blocks_Public {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  public function get_cms_content( $args=array() ) {

    $default = array(
      'id'          => false,
      'post_type'   => 'cms_block',
      'class'       => '',
      'title'       => '',
      'showtitle'   => false,
      'titletag'    => 'h3',
      'return_type' => 'all' // all = everyting, text = text only, title = title only
    );

    $args = (object)array_merge($default,$args);

    // Find the page data
    if ( ! empty($args->id) ) {
      // Get content by ID or slug
      $id = $args->id;
      $id = (!is_numeric($id)) ? get_ID_by_slug($id, $args->post_type) : $id;

      if ( has_filter( 'wpml_translate', 'func_wpml_translate' ) ) {
        $id = apply_filters( 'wpml_translate', 'cms_block', $id );
      }

      // Get the page contenet
      $page_data = get_page( $id );
    } else {
      $page_data = null;
    }

    // Format and return data
    if ( is_null($page_data) ) {
      return '<!-- [No arguments where provided or the values did not match an existing static block] -->';
    } else {

      // The content
      $content = $page_data->post_content;
      $content = apply_filters('cms_content', $content);

      // NOTE: This entire section could be setup as a filter.
      if ( get_post_meta($id, 'content_filters', true) == 'all' ) {
        // Apply all WP content filters, including those added by plugins.
        // This can still have autop turned off with our internal filter.
        $GLOBALS['wpautop_post'] = $page_data; // not default $post so global variable used by wpautop_disable(), if function exists
        $content = apply_filters('the_content', $content);
      } else {
        // Only apply default WP filters. This is the safe way to add basic formatting without any plugin injected filters
        $content = wptexturize($content);
        $content = convert_smilies($content);
        $content = convert_chars($content);
        if ( get_post_meta($id, 'wpautop', true) == 'on' ) { // (!wpautop_disable($id)) {
          $content = wpautop($content); // Add paragraph tags.
        }
        $content = shortcode_unautop($content);
        $content = prepend_attachment($content);
        $style = apply_filters( 'get_vc_row_css', $content );
        $content = do_shortcode($content);
      }
      $class = (!empty($args->class)) ? trim($args->class) : '';
      $content = apply_filters('cms_content_vc', $content, $id);
      $text = '<div id="block-' . $id . '" class="block'. $class .'"'. $style. '>'. $content .'</div>';

      // The title
      if ( ! empty($args->title) ) {
        $title = $args->title;
        $showtitle = true;
      } else {
        $title = $page_data->post_title;
        $showtitle = $args->showtitle;
      }

      if ($showtitle) $title =  '<'. $args->titletag .' class="block-title">'. $page_data->post_title .'</'. $args->titletag .'>';
      if ($showtitle) $text = '<div id="block-' . $id . '" class="block '. $class .'"'. $style. '>'. $title . $content .'</div>';

      $content = $text;

      // Return content (mostly for widgets)
      switch ($args->return_type) {
        // Text only
        case 'text':  return $text;  break;
        // Title only
        case 'title': return $title; break;
        // Return whatever
        default: return $content;
      }
    }
  }

  public function cms_block_shortcode( $args=array() ) {
    if ( ! isset($args['class']) ) {
      $args['class'] = '';
    }
    $args['class'] .= '';
    return CMS_Blocks_Public::get_cms_content($args);
  }

  /**
   * Registers all shortcodes at once
   *
   * @return [type] [description]
   */
  public function register_shortcodes() {
    add_shortcode( 'cms-block', array( $this, 'cms_block_shortcode' ) );
  }

}
