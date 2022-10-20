<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://businessupwebsite.com
 * @since             1.0.0
 * @package           Continue_Shopping_Anywhere
 *
 * @wordpress-plugin
 * Plugin Name:       Continue Shopping Anywhere for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/continue-shopping-anywhere-for-woocommerce
 * Description:       Adds a continue shopping link on any woocommerce page.
 * Version:           1.3.0
 * Author:            Ivan Chernyakov
 * Author URI:        https://businessupwebsite.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       continue-shopping-anywhere
 * Domain Path:       /languages
 * WC tested up to: 6.8
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
define( 'CONTINUE_SHOPPING_ANYWHERE_VERSION', '1.3.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-continue-shopping-anywhere-activator.php
 */
function activate_continue_shopping_anywhere() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-continue-shopping-anywhere-activator.php';
	Continue_Shopping_Anywhere_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-continue-shopping-anywhere-deactivator.php
 */
function deactivate_continue_shopping_anywhere() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-continue-shopping-anywhere-deactivator.php';
	Continue_Shopping_Anywhere_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_continue_shopping_anywhere' );
register_deactivation_hook( __FILE__, 'deactivate_continue_shopping_anywhere' );

/**
 * Add settings link
 * 
 * @since    1.0.0
 */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'continue_shopping_anywhere_add_plugin_settings_link' );

/**
 * Add settings action link to the plugins page.
 *
 * @since    1.0.0
 */
function continue_shopping_anywhere_add_plugin_settings_link( $links ) {
	$new_links = '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=woocsa' ) ) . '">' . __( 'Settings', 'continue-shopping-anywhere' ) . '</a>';
	array_unshift( $links, $new_links );
	return $links;
}	

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-continue-shopping-anywhere.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_continue_shopping_anywhere() {

	$plugin = new Continue_Shopping_Anywhere();
	$plugin->run();

}
run_continue_shopping_anywhere();
