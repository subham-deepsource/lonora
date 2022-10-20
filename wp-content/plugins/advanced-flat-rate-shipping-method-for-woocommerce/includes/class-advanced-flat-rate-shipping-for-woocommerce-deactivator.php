<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
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
 * Advanced_Flat_Rate_Shipping_For_WooCommerce_Deactivator class.
 */
class Advanced_Flat_Rate_Shipping_For_WooCommerce_Deactivator {
	/**
	 * Short Description. ( use period )
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		if ( get_transient( 'afrsm-admin-notice' ) ) {
			delete_transient( 'afrsm-admin-notice' );
		}
	}
}
