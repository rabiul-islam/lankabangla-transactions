<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              http://selise.ch/
* @since             1.0.0
* @package           Lankabangla_Transactions
*
* @wordpress-plugin
* Plugin Name:       Lankabangla Transactions
* Plugin URI:        http://selise.ch/
* Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
* Version:           1.0.0
* Author:            Selise Team (ITSM)
* Author URI:        http://selise.ch/
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       lankabangla-transactions
* Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
   die;
}

/**
* Currently plugin version.
* Start at version 1.0.0 and use SemVer - https://semver.org
* Rename this for your plugin and update it as you release new versions.
*/
define( 'LANKABANGLA_TRANSACTIONS_VERSION', '1.0.0' );

/**
* The code that runs during plugin activation.
* This action is documented in includes/class-lankabangla-transactions-activator.php
*/
function activate_lankabangla_transactions() {
   require_once plugin_dir_path( __FILE__ ) . 'includes/class-lankabangla-transactions-activator.php';
   Lankabangla_Transactions_Activator::activate();
}

/**
* The code that runs during plugin deactivation.
* This action is documented in includes/class-lankabangla-transactions-deactivator.php
*/
function deactivate_lankabangla_transactions() {
   require_once plugin_dir_path( __FILE__ ) . 'includes/class-lankabangla-transactions-deactivator.php';
   Lankabangla_Transactions_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_lankabangla_transactions' );
register_deactivation_hook( __FILE__, 'deactivate_lankabangla_transactions' );

/**
* The core plugin class that is used to define internationalization,
* admin-specific hooks, and public-facing site hooks.
*/
require plugin_dir_path( __FILE__ ) . 'includes/class-lankabangla-transactions.php';
 
/**
* Begins execution of the plugin.
*
* Since everything within the plugin is registered via hooks,
* then kicking off the plugin from this point in the file does
* not affect the page life cycle.
*
* @since    1.0.0
*/
function run_lankabangla_transactions() {
   $plugin = new Lankabangla_Transactions();
   $plugin->run();
}
run_lankabangla_transactions();