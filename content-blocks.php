<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/adsric
 * @since             1.0.0
 * @package           wp-content-blocks
 *
 * @wordpress-plugin
 * Plugin Name:       Static Content Blocks
 * Plugin URI:
 * Description:       Content blocks for your content.
 * Version:           1.0.0
 * Author:
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

if ( ! function_exists( 'the_static_block' ) ) {
  require_once( plugin_dir_path( __FILE__ ) . 'post-type-static-block.php' );
}

function static_block_update_notification_removed($value) {
  unset($value->response[ plugin_basename(__FILE__) ]);
  return $value;
}
add_filter( 'site_transient_update_plugins', 'static_block_update_notification_removed' );
