<?php
/**
 * Plugin name: WPgreen
 * Plugin URI: https://github.com/RomainPetiot/wpgreen
 * Description: As coca-cola light but for your wordpress website !
 * Author : Romain Petiot
 * Author URI: https://www.romainpetiot.com
 * Contributors:Romain Petiot
 * Domain Path: /languages
 * Text Domain: wpgreen-plugin
 * Version: 1.0
 * Stable tag: 1.0
 */

/**
 * Bloquer les accès directs
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require plugin_dir_path( __FILE__ ) . 'admin.php';
require plugin_dir_path( __FILE__ ) . 'interface-admin.php';

add_action( 'init', 'wpgreen_init' );
function wpgreen_init() {
  $plugin_dir = basename( dirname( __FILE__ ) ) . '/languages';
  load_plugin_textdomain( 'wpgreen-plugin', false, $plugin_dir );
}
