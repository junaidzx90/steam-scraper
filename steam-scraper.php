<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fiverr.com/junaidzx90
 * @since             1.0.0
 * @package           Steam_Scraper
 *
 * @wordpress-plugin
 * Plugin Name:       Steam Scraper
 * Plugin URI:        https://www.fiverr.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.3
 * Author:            Developer Junayed
 * Author URI:        https://www.fiverr.com/junaidzx90
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       steam-scraper
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
define( 'STEAM_SCRAPER_VERSION', '1.0.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-steam-scraper-activator.php
 */
function activate_steam_scraper() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-steam-scraper-activator.php';
	Steam_Scraper_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-steam-scraper-deactivator.php
 */
function deactivate_steam_scraper() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-steam-scraper-deactivator.php';
	Steam_Scraper_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_steam_scraper' );
register_deactivation_hook( __FILE__, 'deactivate_steam_scraper' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-steam-scraper.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_steam_scraper() {

	$plugin = new Steam_Scraper();
	$plugin->run();

}
run_steam_scraper();
