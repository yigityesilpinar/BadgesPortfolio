<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://on-lingua.com
 * @since             1.0.0
 * @package           Badge_Portfolio
 *
 * @wordpress-plugin
 * Plugin Name:       Badge Portfolio
 * Plugin URI:        http://on-lingua.com
 * Description:       Mozilla OpenBadges issuing for language skills.
 * Version:           1.0.0
 * Author:            My Language Skills
 * Author URI:        http://on-lingua.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       badge-portfolio
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-badge-portfolio-activator.php
 */
function activate_badge_portfolio() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-badge-portfolio-activator.php';
	BadgePortfolio_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-badge-portfolio-deactivator.php
 */
function deactivate_badge_portfolio() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-badge-portfolio-deactivator.php';
	BadgePortfolio_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_badge_portfolio' );
register_deactivation_hook( __FILE__, 'deactivate_badge_portfolio' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-badge-portfolio.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_badge_portfolio() {

	$plugin = new BadgePortfolio();
	$plugin->run();

}
run_badge_portfolio();
