<?php
namespace Cf7io;

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Cf7_inbound_organizer
 *
 * @wordpress-plugin
 * Plugin Name:       CF7 Inbound Organizer
 * Plugin URI:        https://wordpress.org/plugins/cf7-inbound-organizer/
 * Description:       Extends CF7 + Flamingo to organize submitted forms to follow up on.
 * Version:           1.0.1
 * Author:            De Internet Managers
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cf7-inbound-organizer
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
define( 'CF7_inbound_organizer_VERSION', '1.0.1' );

define ( 'CF7_inbound_organizer_NAME', plugin_basename( __FILE__) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cf7-inbound-organizer-activator.php
 */
function activate_cf7_inbound_organizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cf7-inbound-organizer-activator.php';
	Cf7_inbound_organizer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cf7-inbound-organizer-deactivator.php
 */
function deactivate_cf7_inbound_organizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cf7-inbound-organizer-deactivator.php';
	Cf7_inbound_organizer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'Cf7io\\activate_cf7_inbound_organizer' );
register_deactivation_hook( __FILE__, 'Cf7io\\deactivate_cf7_inbound_organizer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cf7-inbound-organizer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cf7_inbound_organizer() {

	$plugin = new Cf7_inbound_organizer();
	$plugin->run();

}

run_cf7_inbound_organizer();
