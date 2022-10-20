<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'AFRSM_Forceall_Shipping_Method' ) ) {
	/**
	 * AFRSM_Forceall_Shipping_Method class.
	 */
	class AFRSM_Forceall_Shipping_Method extends WC_Shipping_Method {
		/**
		 * Constructor for your shipping class
		 *
		 * @return void
		 * @since  1.0.0
		 */
		public function __construct() {
			$this->id                 = 'forceall';
			$this->method_title       = __( 'Advanced Flat Rate Shipping ( Force All Shipping Methods )', 'advanced-flat-rate-shipping-for-woocommerce' );
			$this->method_description = __( 'You can configure this special shipping option from Advanced Flat Rate Shipping Method settings.', 'advanced-flat-rate-shipping-for-woocommerce' );
			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		}
		/**
		 * Calculate_shipping function for force all method
		 *
		 * @param mixed $package get shipping package.
		 *
		 * @return void
		 * @uses   WC_Shipping_Method::add_rate()
		 *
		 * @since  1.0.0
		 *
		 * @uses   get_woocommerce_currency_symbol()
		 */
		public function calculate_shipping( $package = array() ) {
			$combine_default_shipping_with_forceall = get_option( 'combine_default_shipping_with_forceall' );
			if ( isset( $combine_default_shipping_with_forceall ) && 'yes' === $combine_default_shipping_with_forceall ) {
				global $woocommerce_wpml;
				$forceall_shipping_cost  = 0;
				$forceall_shipping_label = '';
				if ( ! empty( $package['rates'] ) ) {
					foreach ( $package['rates'] as $pvalue ) {
						if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
							$final_cost = $woocommerce_wpml->multi_currency->prices->convert_price_amount( $pvalue->cost );
						} else {
							$final_cost = $pvalue->cost;
						}
						$tax_sum                        = array_sum( $pvalue->taxes );
						$forceall_taxable_shipping_cost = $final_cost + $tax_sum;
						$forceall_shipping_cost        += $forceall_taxable_shipping_cost;
						$forceall_shipping_label       .= '( ' . $pvalue->label . ': ' . get_woocommerce_currency_symbol() . $forceall_taxable_shipping_cost . ' )';
					}
				}
				$rate = array(
					'id'    => $this->id,
					'label' => esc_html( $forceall_shipping_label ),
					'cost'  => wc_clean(
						wp_unslash( $forceall_shipping_cost )
					),
					'taxes' => false,
				);
				$this->add_rate( $rate );
				do_action( 'woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate, $package );
			}
		}
	}
}
