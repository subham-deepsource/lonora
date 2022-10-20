<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Advanced_Flat_Rate_Shipping_For_WooCommerce_Activator class.
 */
class Advanced_Flat_Rate_Shipping_For_WooCommerce_Activator {
	/**
	 * Short Description. ( use period )
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		set_transient( '_welcome_screen_afrsm__mode_activation_redirect_data', true, 30 );
		add_option( 'afrsm_version', AFRSM_PLUGIN_VERSION );
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) && ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
			wp_die( "<strong>Advanced Flat Rate Shipping For WooCommerce</strong> plugin requires <strong>WooCommerce</strong>. Return to <a href='" . esc_url( get_admin_url( null, 'plugins.php' ) ) . "'>Plugins page</a>." );
		} else {
			update_option( 'chk_enable_logging', 'on' );
		}
	}
}
