<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/adsric/
 * @since      1.0.0
 *
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/includes
 */
class CMS_Blocks_Activator {

  /**
   * Short Description. (use period)
   *
   * Long Description.
   *
   * @since    1.0.0
   */
  public static function activate() {

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cms-blocks-admin.php';

    CMS_Blocks_Admin::cpt_cms_block();

    flush_rewrite_rules();

  }

}
