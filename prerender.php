<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://hekkup.com/
 * @since             1.0.0
 * @package           Prerender
 *
 * @wordpress-plugin
 * Plugin Name:       Prerender Plugin
 * Description:       Enable Googlebots to Render Your Site
 * Version:           1.1.1
 * Author:            Hekkup
 * Author URI:        https://hekkup.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       prerender
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
define( 'PRERENDER_VERSION', '1.1.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-prerender-activator.php
 */
function activate_prerender() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-prerender-activator.php';
	Prerender_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-prerender-deactivator.php
 */
function deactivate_prerender() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-prerender-deactivator.php';
	Prerender_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_prerender' );
register_deactivation_hook( __FILE__, 'deactivate_prerender' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-prerender.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_prerender() {

	$plugin = new Prerender();
	$plugin->run();

}
run_prerender();
