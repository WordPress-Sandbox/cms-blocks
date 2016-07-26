<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/adsric/
 * @since      1.0.0
 *
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define admin-specific hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/includes
 */
class CMS_Blocks {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      CMS_Blocks_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct() {

    $this->plugin_name = 'cms-blocks';
    $this->version = '1.0.0';

    $this->load_dependencies();
    $this->define_admin_hooks();
    $this->define_metabox_hooks();
    $this->define_public_hooks();
    $this->define_widget_hooks();

  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
   * - Plugin_Name_i18n. Defines internationalization functionality.
   * - Plugin_Name_Admin. Defines all hooks for the admin area.
   * - Plugin_Name_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies() {

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cms-blocks-loader.php';

    /**
     * The class responsible for all global functions.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cms-blocks-global-functions.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cms-blocks-admin.php';

    /**
     * The class responsible for defining all actions relating to metaboxes.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cms-blocks-admin-metaboxes.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cms-blocks-public.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cms-blocks-widget.php';

    $this->loader = new CMS_Blocks_Loader();

  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {

    $plugin_admin = new CMS_Blocks_Admin( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
    $this->loader->add_action( 'init', $plugin_admin, 'cpt_cms_block' );
    $this->loader->add_action( 'init', $plugin_admin, 'add_cpt_columns' );


  }

  /**
   * Register all of the hooks related to metaboxes
   *
   * @since     1.0.0
   * @access    private
   */
  private function define_metabox_hooks() {
    $plugin_metaboxes = new CMS_Blocks_Admin_Metaboxes( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'save_post_cms_block', $plugin_metaboxes, 'validate_meta', 10, 2 );
    $this->loader->add_action( 'add_meta_boxes', $plugin_metaboxes, 'add_metaboxes' );
    $this->loader->add_action( 'edit_form_after_title', $plugin_metaboxes, 'metabox_position_top' );
  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since     1.0.0
   * @access    private
   */
  private function define_public_hooks() {
    $plugin_public = new CMS_Blocks_Public( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
  }

  /**
   * Register all of the hooks shared between public-facing and admin functionality
   * of the plugin.
   *
   * @since     1.0.0
   * @access    private
   */
  private function define_widget_hooks() {
    $this->loader->add_action( 'widgets_init', $this, 'plugin_widgets_init' );
    $this->loader->add_action( 'save_post_cms_block', $this, 'flush_widget_cache' );
    $this->loader->add_action( 'deleted_post', $this, 'flush_widget_cache' );
    $this->loader->add_action( 'switch_theme', $this, 'flush_widget_cache' );
  }

  /**
   * Flushes widget cache
   *
   * @since     1.0.0
   * @access    public
   * @param     int   $post_id    The post ID
   * @return    void
   */
  public function flush_widget_cache( $post_id ) {
    if ( wp_is_post_revision( $post_id ) ) { return; }
    $post = get_post( $post_id );
  }

  /**
   * Registers widgets with WordPress
   *
   * @since     1.0.0
   * @access    public
   */
  public function plugin_widgets_init() {
    register_widget( 'CMS_Block_Widget' );
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    CMS_Blocks_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader() {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }

}
