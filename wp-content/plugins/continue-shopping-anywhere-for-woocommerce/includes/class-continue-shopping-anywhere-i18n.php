<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://businessupwebsite.com
 * @since      1.0.0
 *
 * @package    Continue_Shopping_Anywhere
 * @subpackage Continue_Shopping_Anywhere/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Continue_Shopping_Anywhere
 * @subpackage Continue_Shopping_Anywhere/includes
 * @author     Ivan Chernyakov <admin@businessupwebsite.com>
 */
class Continue_Shopping_Anywhere_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'continue-shopping-anywhere',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
