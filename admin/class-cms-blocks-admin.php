<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/adsric
 * @since      1.0.0
 *
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/admin
 */
class CMS_Blocks_Admin {

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
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Plugin_Name_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Plugin_Name_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cms-blocks-admin.css', array(), $this->version, 'all' );

  }

  /**
   * Adds a help page link to a menu
   *
   * @link      https://codex.wordpress.org/Administration_Menus
   * @since     1.0.0
   * @return    void
   */
  public function add_menu() {

    add_submenu_page(
      'edit.php?post_type=cms_block',
      apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'CMS Blocks Help' ) ),
      apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Help' ) ),
      'manage_options',
      $this->plugin_name . '-help',
      array( $this, 'page_help' )
    );
  }

  /**
   * Creates a new custom post type
   *
   * @since     1.0.0
   * @uses      register_post_type()
   */
  public static function cpt_cms_block() {
    $cpt = 'cms_block';

    register_post_type( strtolower( $cpt ),
      array(
        'labels' => array(
          'name'                => __( 'CMS Blocks' ),
          'singular_name'       => __( 'CMS Block' ),
          'add_new'             => __( 'Add New' ),
          'add_new_item'        => __( 'Add New CMS Block' ),
          'edit_item'           => __( 'Edit CMS Block' ),
          'new_item'            => __( 'New CMS Block' ),
          'all_items'           => __( 'All CMS Blocks' ),
          'view_item'           => __( 'View CMS Block' ),
          'search_items'        => __( 'Search' ),
          'not_found'           => __( 'No CMS Blocks found' ),
          'not_found_in_trash'  => __( 'No CMS Blocks found in Trash' ),
          'parent_item_colon'   => '',
          'menu_name'           => 'CMS Blocks'
        ),
        'public'                => false,
        'exclude_from_search'   => true,
        'show_in_nav_menus'     => false,
        'publicly_queryable'    => false,
        'show_ui'               => true,
        'menu_icon'             => 'dashicons-editor-table',
        'query_var'             => false, // Remove single query
        'rewrite'               => array( 'slug' => $cpt ),
        'supports'              => array(
          'title',
          'editor',
          'revisions',
        ),
      )
    );
  }

  // Update admin columns throughout the admin area for pages and posts.
  public function cpt_cms_block_columns( $col ) {
    $col['shortcode'] = 'Shortcode';

    unset(
      $col['author'],
      $col['comments'],
      $col['date'],
      $col['gadwp_stats'],
      $col['id'],
      $col['tags']
    );

    $col['date'] = 'Date';

    return $col;
  }

  // Add ID column to Posts and Pages listing.
  public function column_shortcode( $col, $id ) {
    global $post;

    $shortcode = '[cms-block id="' . $id .'"]';

    switch ( $col ) {
      case 'shortcode':
        echo '<span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value="' . esc_attr( $shortcode ) . '" class="large-text code"></span>';
        break;
      default:
        break;
    }
  }

  public function add_cpt_columns() {
    add_filter( 'manage_edit-cms_block_columns', array( $this, 'cpt_cms_block_columns' ) );
    add_action( 'manage_cms_block_posts_custom_column', array( $this, 'column_shortcode' ), 10, 2 );
  }

  /**
   * Creates the help page
   *
   * @since     1.0.0
   * @return    void
   */
  public function page_help() {
    include( plugin_dir_path( __FILE__ ) . 'partials/cms-blocks-admin-page-help.php' );
  }

}
