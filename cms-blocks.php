<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation function, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/adsric/
 * @since             1.0.0
 * @package           CMS_Blocks
 *
 * @wordpress-plugin
 * Plugin Name:       CMS Blocks
 * Plugin URI:        https://github.com/adsric/
 * Description:       Static content blocks for your content.
 * Version:           1.0.0
 * Author:            Adam Richardson
 * Author URI:        https://github.com/adsric/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cms-blocks-activator.php
 */
function activate_cms_blocks() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-cms-blocks-activator.php';
  CMS_Blocks_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_cms_blocks' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cms-blocks.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cms_blocks() {

  $plugin = new CMS_Blocks();
  $plugin->run();

}
run_cms_blocks();
