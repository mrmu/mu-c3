<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://audilu.com
 * @since             1.0.0
 * @package           Mu_C3
 *
 * @wordpress-plugin
 * Plugin Name:       Mu C3
 * Plugin URI:        https://audilu.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Audi Lu
 * Author URI:        https://audilu.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mu-c3
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
define( 'MU_C3_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mu-c3-activator.php
 */
function activate_mu_c3() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mu-c3-activator.php';
	Mu_C3_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mu-c3-deactivator.php
 */
function deactivate_mu_c3() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mu-c3-deactivator.php';
	Mu_C3_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mu_c3' );
register_deactivation_hook( __FILE__, 'deactivate_mu_c3' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mu-c3.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mu_c3() {

	$plugin = new Mu_C3();
	$plugin->run();

}
run_mu_c3();
