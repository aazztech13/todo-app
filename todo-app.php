<?php
/**
 * Todo App
 *
 * @package           TODO_APP
 * @author            AazzTech
 * @copyright         2020 AazzTech
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Todo App
 * Description:       A todo app plugin
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            AazzTech
 * Author URI:        https://directorist.com/
 * Text Domain:       todo-app
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


defined( 'ABSPATH' ) || exit;

define( 'TODO_APP_PLUGIN_FILE',  __FILE__ );
define( 'TODO_APP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) . '/' );


// Load Helper Functions
$helper_functions = TODO_APP_PLUGIN_PATH . 'app/_functions/helper-functions.php';
if ( file_exists( $helper_functions  ) ) {
    include( $helper_functions );
}

// Load The App
$base = TODO_APP_PLUGIN_PATH . 'app/base.php';
if ( ! class_exists( 'TODO_APP' ) && file_exists( $base  ) ) {
    require_once( $base );
}