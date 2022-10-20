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
if ( class_exists( 'AFRSM_Init_Shipping_Methods' ) ) {
	return;
}
/**
 * AFRSM_Init_Shipping_Methods class.
 */
class AFRSM_Init_Shipping_Methods extends WC_Shipping_Method {
	/**
	 * The hook for external class
	 *
	 * @since    1.0.0
	 * @var      string $admin_object The class of external plugin.
	 */
	private static $admin_object = null;
	/**
	 * Constructor
	 *
	 * @since 4.0
	 */
	public function __construct() {
		$get_id                = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
		$post_title            = isset( $get_id ) ? get_the_title( $get_id ) : '';
		$shipping_method_id    = isset( $get_id ) && ! empty( $get_id ) ? $get_id : 'advanced_flat_rate_shipping';
		$shipping_method_title = ! empty( $post_title ) ? $post_title : esc_html__( 'Advanced Flat Rate Shipping', 'advanced-flat-rate-shipping-for-woocommerce' );
		$this->id              = $shipping_method_id;
		$this->title           = esc_html__( 'Advanced Flat Rate Shipping', 'advanced-flat-rate-shipping-for-woocommerce' );
		$this->method_title    = esc_html( $shipping_method_title );
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		self::$admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin( '', '' );
	}
	/**
	 * Calculate shipping.
	 *
	 * @param array $package List containing all products for this method.
	 *
	 * @return bool false if $matched_shipping_methods is false then it will return false
	 * @since 4.0
	 *
	 * @uses  get_default_language()
	 * @uses  afrsm_match_methods()
	 * @uses  WC_Cart::get_cart()
	 * @uses  afrsm_allow_customer()
	 * @uses  afrsm_forceall()
	 * @uses  afrsm_fees_per_qty_on_ap_rules_off()
	 * @uses  afrsm_cart_subtotal_before_discount_cost()
	 * @uses  afrsm_cart_subtotal_after_discount_cost()
	 * @uses  afrsm_evaluate_cost()
	 * @uses  afrsm_get_package_item_qty()
	 * @uses  afrsm_find_shipping_classes()
	 * @uses  get_term_by()
	 * @uses  WC_Shipping_Method::add_rate()
	 */
	public function calculate_shipping( $package = array() ) {
		global $sitepress;
		global $woocommerce_wpml;
		$default_lang             = self::$admin_object->afrsfwa_get_default_langugae_with_sitpress();
		$matched_shipping_methods = $this->afrsmsm_shipping_match_methods( $package, $sitepress, $default_lang );
		if ( false === $matched_shipping_methods || ! is_array( $matched_shipping_methods ) || empty( $matched_shipping_methods ) ) {
			return false;
		}
		$cart_array            = self::$admin_object->afrsfwa_get_cart();
		$get_what_to_do_method = get_option( 'what_to_do_method' );
		$get_what_to_do_method = ! empty( $get_what_to_do_method ) ? $get_what_to_do_method : 'allow_customer';
		if ( 'allow_customer' === $get_what_to_do_method || 'apply_smallest' === $get_what_to_do_method || 'apply_highest' === $get_what_to_do_method ) {
			$matched_shipping_methods = $this->afrsmsm_allow_customer__premium_only( $matched_shipping_methods, $default_lang );
		}
		if ( 'force_all' === $get_what_to_do_method ) {
			$matched_shipping_methods = $this->afrsmsm_forceall__premium_only( $cart_array, $matched_shipping_methods, $sitepress, $default_lang );
		}
		if ( ! empty( $matched_shipping_methods ) ) {
			foreach ( $matched_shipping_methods as $main_shipping_method_id_val ) {
				if ( ! empty( $main_shipping_method_id_val ) || 0 !== $main_shipping_method_id_val ) {
					if ( ! empty( $sitepress ) ) {
						$shipping_method_id_val = apply_filters( 'wpml_object_id', $main_shipping_method_id_val, 'wc_afrsm', true, $default_lang );
					} else {
						$shipping_method_id_val = $main_shipping_method_id_val;
					}
					$shipping_title = get_the_title( $shipping_method_id_val );
					$shipping_rate  = array(
						'id'    => 'advanced_flat_rate_shipping:' . $shipping_method_id_val,
						'label' => esc_html( $shipping_title ),
						'cost'  => 0,
					);
					$cart_based_qty = '0';
					if ( ! empty( $cart_array ) ) {
						foreach ( $cart_array as $value ) {
							if ( ! empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ) {
								$product_id_lan = $value['variation_id'];
							} else {
								$product_id_lan = $value['product_id'];
							}
							$_product = wc_get_product( $product_id_lan );
							if ( ! ( $_product->is_virtual( 'yes' ) ) && ( false === strpos($_product->get_type(), 'bundle') ) ) {
								$cart_based_qty += $value['quantity'];
							}
						}
					}
					$has_costs    = false;
					$get_costs    = get_post_meta( $shipping_method_id_val, 'sm_product_cost', true );
					$cost_args    = array(
						'qty'  => $this->afrsmsm_get_package_item_qty( $package ),
						'cost' => $package['contents_cost'],
					);
					$costs        = $this->afrsmsm_evaluate_cost( $get_costs, $cost_args );
					$how_to_apply = get_post_meta( $shipping_method_id_val, 'how_to_apply', true );
					if ( 'apply_per_qty' === $how_to_apply ) {
						$get_fees_per_qty            = get_post_meta( $shipping_method_id_val, 'sm_fee_per_qty', true );
						$extra_product_cost_original = get_post_meta( $shipping_method_id_val, 'sm_extra_product_cost', true );
					} else {
						$get_fees_per_qty            = '';
						$extra_product_cost_original = '';
					}
					$cost_on_product_status                 = get_post_meta( $shipping_method_id_val, 'cost_on_product_status', true );
					$cost_on_category_status                = get_post_meta( $shipping_method_id_val, 'cost_on_category_status', true );
					$cost_on_total_cart_qty_status          = get_post_meta( $shipping_method_id_val, 'cost_on_total_cart_qty_status', true );
					$cost_on_product_weight_status          = get_post_meta( $shipping_method_id_val, 'cost_on_product_weight_status', true );
					$cost_on_category_weight_status         = get_post_meta( $shipping_method_id_val, 'cost_on_category_weight_status', true );
					$cost_on_total_cart_weight_status       = get_post_meta( $shipping_method_id_val, 'cost_on_total_cart_weight_status', true );
					$cost_on_total_cart_subtotal_status     = get_post_meta( $shipping_method_id_val, 'cost_on_total_cart_subtotal_status', true );
					$cost_on_product_subtotal_status        = get_post_meta( $shipping_method_id_val, 'cost_on_product_subtotal_status', true );
					$cost_on_category_subtotal_status       = get_post_meta( $shipping_method_id_val, 'cost_on_category_subtotal_status', true );
					$cost_on_shipping_class_subtotal_status = get_post_meta( $shipping_method_id_val, 'cost_on_shipping_class_subtotal_status', true );
					if ( 'advance_shipping_rules' === $how_to_apply ) {
						$cost_rule_match = get_post_meta( $shipping_method_id_val, 'cost_rule_match', true );
						if ( ! empty( $cost_rule_match ) ) {
							if ( is_serialized( $cost_rule_match ) ) {
								$cost_rule_match = maybe_unserialize( $cost_rule_match );
							} else {
								$cost_rule_match = $cost_rule_match;
							}
							if ( array_key_exists( 'cost_on_product_rule_match', $cost_rule_match ) ) {
								$cost_on_product_rule_match = $cost_rule_match['cost_on_product_rule_match'];
							} else {
								$cost_on_product_rule_match = 'any';
							}
							if ( array_key_exists( 'cost_on_product_weight_rule_match', $cost_rule_match ) ) {
								$cost_on_product_weight_rule_match = $cost_rule_match['cost_on_product_weight_rule_match'];
							} else {
								$cost_on_product_weight_rule_match = 'any';
							}
							if ( array_key_exists( 'cost_on_product_subtotal_rule_match', $cost_rule_match ) ) {
								$cost_on_product_subtotal_rule_match = $cost_rule_match['cost_on_product_subtotal_rule_match'];
							} else {
								$cost_on_product_subtotal_rule_match = 'any';
							}
							if ( array_key_exists( 'cost_on_category_rule_match', $cost_rule_match ) ) {
								$cost_on_category_rule_match = $cost_rule_match['cost_on_category_rule_match'];
							} else {
								$cost_on_category_rule_match = 'any';
							}
							if ( array_key_exists( 'cost_on_category_weight_rule_match', $cost_rule_match ) ) {
								$cost_on_category_weight_rule_match = $cost_rule_match['cost_on_category_weight_rule_match'];
							} else {
								$cost_on_category_weight_rule_match = 'any';
							}
							if ( array_key_exists( 'cost_on_category_subtotal_rule_match', $cost_rule_match ) ) {
								$cost_on_category_subtotal_rule_match = $cost_rule_match['cost_on_category_subtotal_rule_match'];
							} else {
								$cost_on_category_subtotal_rule_match = 'any';
							}
							if ( array_key_exists( 'cost_on_total_cart_qty_rule_match', $cost_rule_match ) ) {
								$cost_on_total_cart_qty_rule_match = $cost_rule_match['cost_on_total_cart_qty_rule_match'];
							} else {
								$cost_on_total_cart_qty_rule_match = 'any';
							}
							if ( array_key_exists( 'cost_on_total_cart_weight_rule_match', $cost_rule_match ) ) {
								$cost_on_total_cart_weight_rule_match = $cost_rule_match['cost_on_total_cart_weight_rule_match'];
							} else {
								$cost_on_total_cart_weight_rule_match = 'any';
							}
							if ( array_key_exists( 'cost_on_total_cart_subtotal_rule_match', $cost_rule_match ) ) {
								$cost_on_total_cart_subtotal_rule_match = $cost_rule_match['cost_on_total_cart_subtotal_rule_match'];
							} else {
								$cost_on_total_cart_subtotal_rule_match = 'any';
							}
							if ( array_key_exists( 'cost_on_shipping_class_subtotal_rule_match', $cost_rule_match ) ) {
								$cost_on_shipping_class_subtotal_rule_match = $cost_rule_match['cost_on_shipping_class_subtotal_rule_match'];
							} else {
								$cost_on_shipping_class_subtotal_rule_match = 'any';
							}
						} else {
							$cost_on_product_rule_match                 = 'any';
							$cost_on_product_weight_rule_match          = 'any';
							$cost_on_product_subtotal_rule_match        = 'any';
							$cost_on_category_rule_match                = 'any';
							$cost_on_category_weight_rule_match         = 'any';
							$cost_on_category_subtotal_rule_match       = 'any';
							$cost_on_total_cart_qty_rule_match          = 'any';
							$cost_on_total_cart_weight_rule_match       = 'any';
							$cost_on_total_cart_subtotal_rule_match     = 'any';
							$cost_on_shipping_class_subtotal_rule_match = 'any';
						}
						$get_condition_array_ap_product                 = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_product', true );
						$get_condition_array_ap_category                = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_category', true );
						$get_condition_array_ap_total_cart_qty          = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_total_cart_qty', true );
						$get_condition_array_ap_product_weight          = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_product_weight', true );
						$get_condition_array_ap_category_weight         = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_category_weight', true );
						$get_condition_array_ap_total_cart_weight       = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_total_cart_weight', true );
						$get_condition_array_ap_total_cart_subtotal     = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_total_cart_subtotal', true );
						$get_condition_array_ap_product_subtotal        = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_product_subtotal', true );
						$get_condition_array_ap_category_subtotal       = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_category_subtotal', true );
						$get_condition_array_ap_shipping_class_subtotal = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_shipping_class_subtotal', true );
					} else {
						$get_condition_array_ap_product                 = '';
						$get_condition_array_ap_category                = '';
						$get_condition_array_ap_total_cart_qty          = '';
						$get_condition_array_ap_product_weight          = '';
						$get_condition_array_ap_category_weight         = '';
						$get_condition_array_ap_total_cart_weight       = '';
						$get_condition_array_ap_total_cart_subtotal     = '';
						$get_condition_array_ap_product_subtotal        = '';
						$get_condition_array_ap_category_subtotal       = '';
						$get_condition_array_ap_shipping_class_subtotal = '';
						$cost_on_product_rule_match                     = 'any';
						$cost_on_product_weight_rule_match              = 'any';
						$cost_on_product_subtotal_rule_match            = 'any';
						$cost_on_category_subtotal_rule_match           = 'any';
						$cost_on_category_rule_match                    = 'any';
						$cost_on_category_weight_rule_match             = 'any';
						$cost_on_total_cart_qty_rule_match              = 'any';
						$cost_on_total_cart_weight_rule_match           = 'any';
						$cost_on_total_cart_subtotal_rule_match         = 'any';
						$cost_on_shipping_class_subtotal_rule_match     = 'any';
					}
					if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
						$extra_product_cost = $woocommerce_wpml->multi_currency->prices->convert_price_amount( $extra_product_cost_original );
					} else {
						$extra_product_cost = $extra_product_cost_original;
					}
					$cost               = $costs;
					$products_based_qty = 0;
					if ( 'apply_per_qty' === $how_to_apply ) {
						if ( 'qty_cart_based' === $get_fees_per_qty ) {
							$cost = $costs + ( ( $cart_based_qty - 1 ) * $extra_product_cost );
						} elseif ( 'qty_product_based' === $get_fees_per_qty ) {
							$products_based_qty = $this->afrsmsm_fees_per_qty_on_ap_rules_off__premium_only( $shipping_method_id_val, $cart_array, $products_based_qty, $sitepress, $default_lang );
							$extra_product_cost = $this->afrsmsm_price_format( $extra_product_cost );
							$cost               = $costs + ( ( $products_based_qty - 1 ) * $extra_product_cost );
						}
					} else {
						$cost = $costs;
					}
					$sm_taxable                     = get_post_meta( $shipping_method_id_val, 'sm_select_taxable', true );
					$sm_extra_cost_calculation_type = get_post_meta( $shipping_method_id_val, 'sm_extra_cost_calculation_type', true );
					if ( '' !== $cost ) {
						$has_costs             = true;
						$cost_args             = array(
							'qty'  => $this->afrsmsm_get_package_item_qty( $package ),
							'cost' => $package['contents_cost'],
						);
						$shipping_rate['cost'] = $this->afrsmsm_evaluate_cost( $cost, $cost_args );
					}
					$found_shipping_classes = $this->afrsmsm_find_shipping_classes( $package );
					$highest_class_cost     = 0;
					if ( ! empty( $found_shipping_classes ) ) {
						foreach ( $found_shipping_classes as $shipping_class => $products ) {
							$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
							$shipping_extra_id   = '';
							if ( false !== $shipping_class_term ) {
								if ( ! empty( $sitepress ) ) {
									$shipping_extra_id = apply_filters( 'wpml_object_id', $shipping_class_term->term_id, 'product_shipping_class', true, $default_lang );
								} else {
									$shipping_extra_id = $shipping_class_term->term_id;
								}
							}
							$sm_extra_cost     = get_post_meta( $shipping_method_id_val, 'sm_extra_cost', true );
							$class_cost_string = isset( $sm_extra_cost[ $shipping_extra_id ] ) && ! empty( $sm_extra_cost[ $shipping_extra_id ] ) ? $sm_extra_cost[ $shipping_extra_id ] : '';
							if ( '' === $class_cost_string ) {
								continue;
							}
							$has_costs  = true;
							$class_cost = $this->afrsmsm_evaluate_cost(
								$class_cost_string,
								array(
									'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
									'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
								)
							);
							if ( 'per_class' === $sm_extra_cost_calculation_type ) {
								$shipping_rate['cost'] += $class_cost;
							} else {
								$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
							}
						}
						if ( 'per_order' === $sm_extra_cost_calculation_type && $highest_class_cost ) {
							$shipping_rate['cost'] += $highest_class_cost;
						}
					}
					if ( 'no' === $sm_taxable ) {
						$shipping_rate['taxes'] = false;
					} else {
						$shipping_rate['taxes'] = '';
					}
					$match_advance_rule = array();
					if ( 'on' === $cost_on_product_status && ! empty( $get_condition_array_ap_product ) ) {
						$match_advance_rule['hfbopq'] = $this->afrsmsm_advance_pricing_rules_product_per_qty__premium_only( $get_condition_array_ap_product, $cart_array, $sitepress, $default_lang, $cost_on_product_rule_match );
					}
					if ( 'on' === $cost_on_product_subtotal_status && ! empty( $get_condition_array_ap_product_subtotal ) ) {
						$match_advance_rule['hfbops'] = $this->afrsmsm_advance_pricing_rules_product_subtotal__premium_only( $get_condition_array_ap_product_subtotal, $cart_array, $cost_on_product_subtotal_rule_match, $sitepress, $default_lang );
					}
					if ( 'on' === $cost_on_product_weight_status && ! empty( $get_condition_array_ap_product_weight ) ) {
						$match_advance_rule['hfbopw'] = $this->afrsmsm_advance_pricing_rules_product_per_weight__premium_only( $get_condition_array_ap_product_weight, $cart_array, $sitepress, $default_lang, $cost_on_product_weight_rule_match );
					}
					if ( 'on' === $cost_on_category_status && ! empty( $get_condition_array_ap_category ) ) {
						$match_advance_rule['hfbocq'] = $this->afrsmsm_advance_pricing_rules_category_per_qty__premium_only( $get_condition_array_ap_category, $cart_array, $sitepress, $default_lang, $cost_on_category_rule_match );
					}
					if ( 'on' === $cost_on_category_subtotal_status && ! empty( $get_condition_array_ap_category_subtotal ) ) {
						$match_advance_rule['hfbocs'] = $this->afrsmsm_advance_pricing_rules_category_subtotal__premium_only( $get_condition_array_ap_category_subtotal, $cart_array, $cost_on_category_subtotal_rule_match, $sitepress, $default_lang );
					}
					if ( 'on' === $cost_on_category_weight_status && ! empty( $get_condition_array_ap_category_weight ) ) {
						$match_advance_rule['hfbocw'] = $this->afrsmsm_advance_pricing_rules_category_per_weight__premium_only( $get_condition_array_ap_category_weight, $cart_array, $sitepress, $default_lang, $cost_on_category_weight_rule_match );
					}
					if ( 'on' === $cost_on_total_cart_qty_status && ! empty( $get_condition_array_ap_total_cart_qty ) ) {
						$match_advance_rule['hfbotcq'] = $this->afrsmsm_advance_pricing_rules_total_cart_qty__premium_only( $get_condition_array_ap_total_cart_qty, $cart_array, $cost_on_total_cart_qty_rule_match );
					}
					if ( 'on' === $cost_on_total_cart_weight_status && ! empty( $get_condition_array_ap_total_cart_weight ) ) {
						$match_advance_rule['hfbotcw'] = $this->afrsmsm_advance_pricing_rules_total_cart_weight__premium_only( $get_condition_array_ap_total_cart_weight, $cart_array, $cost_on_total_cart_weight_rule_match );
					}
					if ( 'on' === $cost_on_total_cart_subtotal_status && ! empty( $get_condition_array_ap_total_cart_subtotal ) ) {
						$match_advance_rule['hfbotcs'] = $this->afrsmsm_advance_pricing_rules_total_cart_subtotal__premium_only( $get_condition_array_ap_total_cart_subtotal, $cart_array, $cost_on_total_cart_subtotal_rule_match );
					}
					if ( 'on' === $cost_on_shipping_class_subtotal_status && ! empty( $get_condition_array_ap_shipping_class_subtotal ) ) {
						$match_advance_rule['hfbscs'] = $this->afrsmsm_advance_pricing_rules_shipping_class_subtotal__premium_only( $get_condition_array_ap_shipping_class_subtotal, $cart_array, $cost_on_shipping_class_subtotal_rule_match, $sitepress, $default_lang );
					}
					$advance_shipping_rate = 0;
					if ( isset( $match_advance_rule ) && ! empty( $match_advance_rule ) && is_array( $match_advance_rule ) ) {
						foreach ( $match_advance_rule as $val ) {
							if ( '' !== $val['flag'] && 'yes' === $val['flag'] ) {
								$advance_shipping_rate += $val['total_amount'];
							}
						}
					}
					$advance_shipping_rate  = $this->afrsmsm_price_format( $advance_shipping_rate );
					$shipping_rate['cost'] += $advance_shipping_rate;
					$shipping_rate['cost']  = $this->afrsmsm_price_format( $shipping_rate['cost'] );
					if ( $has_costs ) {
						if ( 'force_all' === $get_what_to_do_method ) {
							$force_all_shipping_rate_pass_rate = array(
								'id'    => 'forceall',
								'label' => __( 'Forceall', 'advanced-flat-rate-shipping-for-woocommerce' ),
								'cost'  => 0,
								'taxes' => 0,
							);
							$this->add_rate( $force_all_shipping_rate_pass_rate );
						}
						if ( $shipping_rate['cost'] < 0 ) {
							$shipping_rate['label'] = $shipping_rate['label'];
						}
						$this->add_rate( $shipping_rate );
					}
					do_action( 'woocommerce_' . $this->id . '_shipping_add_rate', $this, $shipping_rate, $package );
				}
			}
		}
	}
	/**
	 * Match methods.
	 *
	 * Check all created AFRSM shipping methods have a matching condition group.
	 *
	 * @param array|object $package      List of shipping package data.
	 * @param string       $sitepress    sitepress is use for multilanguage.
	 * @param string       $default_lang get default language.
	 *
	 * @return array $matched_methods   List of all matched shipping methods.
	 *
	 * @uses  afrsmsm_match_conditions()
	 * @uses  Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin::afrsm_pro_get_shipping_method()
	 *
	 * @since 4.0
	 *
	 * @uses  get_posts()
	 */
	public function afrsmsm_shipping_match_methods( $package, $sitepress, $default_lang ) {
		$matched_methods  = array();
		$sm_args          = array(
			'post_type'        => 'wc_afrsm',
			'posts_per_page'   => - 1,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'suppress_filters' => false,
		);
		$get_all_shipping = new WP_Query( $sm_args );
		if ( $get_all_shipping->have_posts() ) :
			while ( $get_all_shipping->have_posts() ) :
				$get_all_shipping->the_post();
				if ( ! empty( $sitepress ) ) {
					$sm_post_id = apply_filters( 'wpml_object_id', get_the_ID(), 'wc_afrsm', true, $default_lang );
				} else {
					$sm_post_id = get_the_ID();
				}
				if ( ! empty( $sitepress ) ) {
					if ( version_compare( ICL_SITEPRESS_VERSION, '3.2', '>=' ) ) {
						$language_information = apply_filters( 'wpml_post_language_details', null, $sm_post_id );
					} else {
						$language_information = wpml_get_language_information( $sm_post_id );
					}
					$post_id_language_code = $language_information['language_code'];
				} else {
					$post_id_language_code = self::$admin_object->afrsfwa_get_default_langugae_with_sitpress();
				}
				if ( $post_id_language_code === $default_lang ) {
					$is_match = $this->afrsmsm_match_conditions( $sm_post_id, $package );
					if ( true === $is_match ) {
						$matched_methods[] = $sm_post_id;
					}
				}
			endwhile;
		endif;
		wp_reset_postdata();
		update_option( 'matched_method', $matched_methods );
		return $matched_methods;
	}
	/**
	 * Match conditions.
	 *
	 * Check if conditions match, if all conditions in one condition group
	 * matches it will return true and the shipping method will display.
	 *
	 * @param array $sm_post_data Get all shipping rule.
	 * @param array $package      List of shipping package data.
	 *
	 * @return BOOL true if all the conditions in one of the condition groups matches true.
	 * @since 1.0.0
	 */
	public function afrsmsm_match_conditions( $sm_post_data, $package = array() ) {
		if ( empty( $sm_post_data ) ) {
			return false;
		}
		if ( ! empty( $sm_post_data ) ) {
			$final_condition_flag = apply_filters( 'afrsm_condition_match_rules', $sm_post_data, $package );
			if ( $final_condition_flag ) {
				return true;
			}
		}
		return false;
	}
	/**
	 * Display all shipping method which will selectable
	 *
	 * @param array  $matched_shipping_methods Check matched methods.
	 * @param string $default_lang             get default language.
	 *
	 * @return array $matched_shipping_methods
	 * @since 3.4
	 */
	public function afrsmsm_allow_customer__premium_only( $matched_shipping_methods, $default_lang ) {
		if ( ! empty( $matched_shipping_methods ) ) {
			$get_sort_order = get_option( 'sm_sortable_order_' . $default_lang );
			$sort_order     = array();
			if ( ! empty( $get_sort_order ) ) {
				foreach ( $get_sort_order as $get_sort_order_id ) {
					settype( $get_sort_order_id, 'integer' );
					if ( in_array( $get_sort_order_id, $matched_shipping_methods, true ) ) {
						$sort_order[] = $get_sort_order_id;
					}
				}
				unset( $matched_shipping_methods );
				$matched_shipping_methods = $sort_order;
			} else {
				$matched_shipping_methods = $matched_shipping_methods;
			}
		}
		return $matched_shipping_methods;
	}
	/**
	 * Combine all shipping method in one shipping method with forceall key
	 *
	 * @param array  $cart_array               Get cart array.
	 * @param array  $matched_shipping_methods Check matched methods.
	 * @param string $sitepress                sitepress is use for multilanguage.
	 * @param string $default_lang             get default language.
	 *
	 * @return array $matched_shipping_methods
	 * @since 3.4
	 */
	public function afrsmsm_forceall__premium_only( $cart_array, $matched_shipping_methods, $sitepress, $default_lang ) {
		if ( ! empty( $matched_shipping_methods ) ) {
			$costs_array = array();
			foreach ( $matched_shipping_methods as $main_shipping_method_id_val ) {
				if ( ! empty( $sitepress ) ) {
					$shipping_method_id = apply_filters( 'wpml_object_id', $main_shipping_method_id_val, 'wc_afrsm', true, $default_lang );
				} else {
					$shipping_method_id = $main_shipping_method_id_val;
				}
				$cart_based_qty   = '0';
				$cart_based_price = '0';
				$args             = array();
				if ( ! empty( $cart_array ) ) {
					foreach ( $cart_array as $value ) {
						$cart_based_qty   += intval( $value['quantity'] );
						$cart_based_price += $value['line_subtotal'];
						$args['qty']       = $cart_based_qty;
						$args['cost']      = $cart_based_price;
					}
				}
				$costs                              = get_post_meta( $shipping_method_id, 'sm_product_cost', true );
				$costs_array[ $shipping_method_id ] = $costs;
			}
			$forceall     = array();
			$total_costs  = 0;
			$i            = 0;
			$k_with_comma = array();
			foreach ( $costs_array as $k => $v ) {
				$new_total_costs    = $this->afrsmsm_evaluate_cost( $v, $args );
				$total_costs        = $total_costs + $new_total_costs;
				$forceall[ $i ]     = $k;
				$k_with_comma[ $i ] = $k;
				$i ++;
			}
			$forceall['forceall']     = 0;
			$matched_shipping_methods = $forceall;
		}
		return $matched_shipping_methods;
	}
	/**
	 * Evaluate a cost from a sum/string.
	 *
	 * @param string $shipping_cost_sum Get shipping price.
	 * @param array  $args              shipping args.
	 *
	 * @return string $shipping_cost_sum if shipping cost is empty then it will return 0
	 * @since 1.0.0
	 *
	 * @uses  wc_get_price_decimal_separator()
	 * @uses  WC_Eval_Math_Extra::evaluate()
	 */
	protected function afrsmsm_evaluate_cost( $shipping_cost_sum, $args = array() ) {
		require_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';
		$wc_eval        = new WC_Eval_Math();
		$args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $shipping_cost_sum, $this );
		$locale         = localeconv();
		$decimals       = array(
			wc_get_price_decimal_separator(),
			$locale['decimal_point'],
			$locale['mon_decimal_point'],
		);
		$this->fee_cost = $args['cost'];
		add_shortcode( 'fee', array( $this, 'fee' ) );
		$shipping_cost_sum = do_shortcode(
			str_replace(
				array(
					'[qty]',
					'[cost]',
				),
				array(
					$args['qty'],
					$args['cost'],
				),
				$shipping_cost_sum
			)
		);
		remove_shortcode( 'fee', array( $this, 'fee' ) );
		$shipping_cost_sum = preg_replace( '/\s+/', '', $shipping_cost_sum );
		$shipping_cost_sum = str_replace( $decimals, '.', $shipping_cost_sum );
		$shipping_cost_sum = rtrim( ltrim( $shipping_cost_sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );
		return $shipping_cost_sum ? $wc_eval::evaluate( $shipping_cost_sum ) : 0;
	}
	/**
	 * Get items in package.
	 *
	 * @param array|object $package Get cart package.
	 *
	 * @return int $total_quantity
	 * @since 1.0.0
	 */
	public function afrsmsm_get_package_item_qty( $package ) {
		$total_quantity = 0;
		foreach ( $package['contents'] as $values ) {
			if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
				$total_quantity += $values['quantity'];
			}
		}
		return $total_quantity;
	}
	/**
	 * Count qty for product based and cart based when apply per qty option is on. This rule will apply when advance pricing rule will disable
	 *
	 * @param int    $shipping_method_id_val Get current shipping method id.
	 * @param array  $cart_array             Get cart array.
	 * @param int    $products_based_qty     Apply per aty on product based.
	 * @param string $sitepress              sitepress is use for multilanguage.
	 * @param string $default_lang           get default language.
	 *
	 * @return int $total_products_based_qty
	 * @uses  get_post_meta()
	 * @uses  get_post()
	 * @uses  get_terms()
	 *
	 * @since 3.4
	 */
	public function afrsmsm_fees_per_qty_on_ap_rules_off__premium_only( $shipping_method_id_val, $cart_array, $products_based_qty, $sitepress, $default_lang ) {
		$product_fees_array = get_post_meta( $shipping_method_id_val, 'sm_metabox', true );
		$all_rule_check     = array();
		if ( ! empty( $product_fees_array ) ) {
			foreach ( $product_fees_array as $condition ) {
				if ( array_search( 'product', $condition, true ) ) {
					$site_product_id           = '';
					$cart_final_products_array = array();
					if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							foreach ( $condition['product_fees_conditions_values'] as $product_id ) {
								settype( $product_id, 'integer' );
								foreach ( $cart_array as $value ) {
									$_product = wc_get_product( $value['product_id'] );
									if ( ! ( $_product->is_virtual( 'yes' ) ) && ( false === strpos($_product->get_type(), 'bundle') ) ) {
										if ( ! empty( $sitepress ) ) {
											$site_product_id = apply_filters( 'wpml_object_id', $value['product_id'], 'product', true, $default_lang );
										} else {
											$site_product_id = $value['product_id'];
										}
										if ( $product_id === $site_product_id ) {
											if ( array_key_exists( $site_product_id, $cart_final_products_array ) ) {
												$cart_final_products_array[ $site_product_id ] += $value['quantity'];	
											} else {
												$cart_final_products_array[ $site_product_id ] = $value['quantity'];
											}
										}
									}
								}
							}
						}
					} elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							foreach ( $condition['product_fees_conditions_values'] as $product_id ) {
								settype( $product_id, 'integer' );
								foreach ( $cart_array as $value ) {
									if ( ! empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ) {
										$product_id_lan = $value['variation_id'];
									} else {
										$product_id_lan = $value['product_id'];
									}
									$_product = wc_get_product( $product_id_lan );
									if ( ! ( $_product->is_virtual( 'yes' ) ) && ( false === strpos($_product->get_type(), 'bundle') ) ) {
										if ( ! empty( $sitepress ) ) {
											$site_product_id = apply_filters( 'wpml_object_id', $product_id_lan, 'product', true, $default_lang );
										} else {
											$site_product_id = $product_id_lan;
										}
										if ( $product_id !== $site_product_id ) {
											if ( array_key_exists( $site_product_id, $cart_final_products_array ) ) {
												$cart_final_products_array[ $site_product_id ] += $value['quantity'];
											} else {
												$cart_final_products_array[ $site_product_id ] = $value['quantity'];
											}
										}
									}
								}
							}
						}
					}
					if ( ! empty( $cart_final_products_array ) ) {
						foreach ( $cart_final_products_array as $prd_id => $cart_item ) {
							$all_rule_check[ $prd_id ] = $cart_item;
						}
					}
				}
				if ( array_search( 'variableproduct', $condition, true ) ) {
					$cart_final_var_products_array = array();
					if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							foreach ( $condition['product_fees_conditions_values'] as $product_id ) {
								settype( $product_id, 'integer' );
								foreach ( $cart_array as $value ) {
									if ( ! empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ) {
										$product_id_lan = $value['variation_id'];
									} else {
										$product_id_lan = $value['product_id'];
									}
									if ( ! ( $value['data']->is_virtual() ) && ( false === strpos($value['data'], 'bundle') )  ) {
										if ( ! empty( $sitepress ) ) {
											$site_product_id = apply_filters( 'wpml_object_id', $product_id_lan, 'product', true, $default_lang );
										} else {
											$site_product_id = $product_id_lan;
										}
										if ( $product_id === $site_product_id ) {
											if ( array_key_exists( $site_product_id, $cart_final_products_array ) ) {
												$cart_final_var_products_array[ $site_product_id ] += $value['quantity'];
											} else {
												$cart_final_var_products_array[ $site_product_id ] = $value['quantity'];
											}
										}
									}
								}
							}
						}
					} elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							foreach ( $condition['product_fees_conditions_values'] as $product_id ) {
								settype( $product_id, 'integer' );
								foreach ( $cart_array as $value ) {
									if ( ! empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ) {
										$product_id_lan = $value['variation_id'];
									} else {
										$product_id_lan = $value['product_id'];
									}
									if ( ! ( $value['data']->is_virtual() ) && ( false === strpos($value['data']->get_type(), 'bundle') )  ) {
										if ( ! empty( $sitepress ) ) {
											$site_product_id = apply_filters( 'wpml_object_id', $product_id_lan, 'product', true, $default_lang );
										} else {
											$site_product_id = $product_id_lan;
										}
										if ( $product_id !== $site_product_id ) {
											if ( array_key_exists( $site_product_id, $cart_final_products_array ) ) {
												$cart_final_var_products_array[ $product_id_lan ] += $value['quantity'];
											} else {
												$cart_final_var_products_array[ $product_id_lan ] = $value['quantity'];
											}
										}
									}
								}
							}
						}
					}
					if ( ! empty( $cart_final_var_products_array ) ) {
						foreach ( $cart_final_var_products_array as $prd_id => $cart_item ) {
							$all_rule_check[ $prd_id ] = $cart_item;
						}
					}
				}
				if ( array_search( 'category', $condition, true ) ) {
					$final_cart_products_cats_ids  = array();
					$cart_final_cat_products_array = array();
					$all_cats                      = get_terms(
						array(
							'taxonomy' => 'product_cat',
							'fields'   => 'ids',
						)
					);
					if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							foreach ( $condition['product_fees_conditions_values'] as $category_id ) {
								settype( $category_id, 'integer' );
								$final_cart_products_cats_ids[] = $category_id;
							}
						}
					} elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							$final_cart_products_cats_ids = array_diff( $all_cats, $condition['product_fees_conditions_values'] );
						}
					}
					foreach ( $cart_array as $product ) {
						$cart_product_category = wp_get_post_terms( $product['product_id'], 'product_cat', array( 'fields' => 'ids' ) );
						if ( ! empty( $cart_product_category ) ) {
							$id          = ( ! empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ) ? $product['variation_id'] : $product['product_id'];
							$get_product = wc_get_product( $id );
							if ( ! ( $get_product->is_virtual( 'yes' ) ) && ( false === strpos($get_product->get_type(), 'bundle') )  ) {
								if ( array_intersect($cart_product_category, $final_cart_products_cats_ids) ) {
									// if( array_key_exists($cart_product_category[0], $cart_final_cat_products_array) && array_key_exists($id, $cart_final_cat_products_array[ $cart_product_category[0] ]) ){
									// 	$cart_final_cat_products_array[ $cart_product_category[0] ][ $id ] += $product['quantity'];	
									// } else {
									// 	$cart_final_cat_products_array[ $cart_product_category[0] ][ $id ] = $product['quantity'];
									// }
									if ( array_key_exists( $id, $cart_final_cat_products_array ) ) {
										$cart_final_cat_products_array[ $id ] += $product['quantity'];	
									} else {
										$cart_final_cat_products_array[ $id ] = $product['quantity'];
									}
								}
							}
						}
					}
					// if ( ! empty( $cart_final_cat_products_array ) ) {
					// 	foreach ( $cart_final_cat_products_array as $cat_data ) {
					// 		foreach ( $cat_data as $prd_id => $cart_item ) {
					// 			$all_rule_check[ $prd_id ] = $cart_item;
					// 		}
					// 	}
					// }
					if ( ! empty( $cart_final_cat_products_array ) ) {
						foreach ( $cart_final_cat_products_array as $prd_id => $cart_item ) {
							$all_rule_check[ $prd_id ] = $cart_item;
						}
					}
				}
				if ( array_search( 'tag', $condition, true ) ) {
					$final_cart_products_tag_ids         = array();
					$cart_final_tag_products_array       = array();
					$final_cart_products_tag_not_in_flag = 0;
					$all_tags                            = get_terms(
						array(
							'taxonomy' => 'product_tag',
							'fields'   => 'ids',
						)
					);
					if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							foreach ( $condition['product_fees_conditions_values'] as $tag_id ) {
								$final_cart_products_tag_ids[] = $tag_id;
							}
						}
					} elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							$final_cart_products_tag_not_in_flag = 1;
							$final_cart_products_tag_ids         = array_diff( $all_tags, $condition['product_fees_conditions_values'] );
						}
					}
					$tag_args           = array(
						'post_type'      => 'product',
						'posts_per_page' => - 1,
						'order'          => 'ASC',
						'fields'         => 'ids',
						'tax_query'      => array(
							array(
								'taxonomy' => 'product_tag',
								'field'    => 'term_id',
								'terms'    => $final_cart_products_tag_ids,
							),
						),
					);
					$tag_products_query = new WP_Query( $tag_args );
					$tag_products_ids   = $tag_products_query->posts;
					foreach ( $cart_array as $value ) {
						$id          = ( ! empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ) ? $value['variation_id'] : $value['product_id'];
						$tag_product_cart = wc_get_product( $id );
						if ( ! ( $tag_product_cart->is_virtual( 'yes' ) ) && ( false === strpos($tag_product_cart->get_type(), 'bundle') )  ) {
							if ( in_array( $id, $tag_products_ids, true ) ) {
								if ( array_key_exists( $id, $cart_final_tag_products_array ) ) {
									$cart_final_tag_products_array[ $id ] += $value['quantity'];
								} else {
									$cart_final_tag_products_array[ $id ] = $value['quantity'];
								}
							}
						}
					}
					if ( ! empty( $cart_final_tag_products_array ) ) {
						foreach ( $cart_final_tag_products_array as $prd_id => $cart_item ) {
							$all_rule_check[ $prd_id ] = $cart_item;
						}
					}
				}
				if ( array_search( 'sku', $condition, true ) ) {
					$site_product_id       		= '';
					$final_cart_products_skus   = array();
					$cart_final_skus_array 		= array();

					foreach ( $cart_array as $value ) {
						if ( ! empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ) {
							$product_id_lan = $value['variation_id'];
						} else {
							$product_id_lan = $value['product_id'];
						}
						if ( ! ( $value['data']->is_virtual() ) && ( false === strpos($value['data']->get_type(), 'bundle' ) ) ) {
							$cart_product_sku = get_post_meta( $product_id_lan, '_sku', true );
							if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
								if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
									if ( in_array( $cart_product_sku, $condition['product_fees_conditions_values'], true ) ) {
										if ( array_key_exists($product_id_lan, $cart_final_skus_array) ) {
											$cart_final_skus_array[ $product_id_lan ] += $value['quantity'];
										} else {
											$cart_final_skus_array[ $product_id_lan ] = $value['quantity'];
										}
									}
								} elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
									if ( !in_array( $cart_product_sku, $condition['product_fees_conditions_values'], true ) ) {
										if ( array_key_exists( $product_id_lan, $cart_final_skus_array ) ) {
											$cart_final_skus_array[ $product_id_lan ] += $value['quantity'];
										} else {
											$cart_final_skus_array[ $product_id_lan ] = $value['quantity'];
										}
									}
								}
							}
						}
					}
					if ( ! empty( $cart_final_skus_array ) ) {
						foreach ( $cart_final_skus_array as $prd_id => $cart_item ) {
							$all_rule_check[ $prd_id ] = $cart_item;
						}
					}
				}
				/** Custom code here. */
				$final_cart_products_size_slugs       = array();
				$final_cart_products_size_not_in_flag = 0;
				if ( array_search( 'pa_size', $condition, true ) ) {
					if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							$final_cart_products_size_slugs = $condition['product_fees_conditions_values'];
						}
					} elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							$final_cart_products_size_not_in_flag = 1;
							$final_cart_products_size_slugs       = $condition['product_fees_conditions_values'];
						}
					}
				}
			}
		}
		if ( ! empty( $all_rule_check ) ) {
			foreach ( $all_rule_check as $cart_item ) {
				if ( is_array( $cart_item ) ) {
					/** Custom code here */
					if ( isset( $cart_item[1] ) && ! empty( $cart_item[1] ) && ! empty( $final_cart_products_size_slugs ) ) {
						if ( 0 === $final_cart_products_size_not_in_flag ) {
							if ( in_array( $cart_item[1], $final_cart_products_size_slugs, true ) ) {
								$products_based_qty += $cart_item[0];
							}
						} else {
							if ( ! in_array( $cart_item[1], $final_cart_products_size_slugs, true ) ) {
								$products_based_qty += $cart_item[0];
							}
						}
					} else {
						$products_based_qty += $cart_item[0];
					}
				} else {
					$products_based_qty += $cart_item;
				}
			}
		}
		return $products_based_qty;
	}
	/**
	 * Price format
	 *
	 * @param string $price convert price to float.
	 *
	 * @return string $price
	 * @since  4.0
	 */
	public function afrsmsm_price_format( $price ) {
		$price = floatval( $price );
		return $price;
	}
	/**
	 * Finds and returns shipping classes and the products with said class.
	 *
	 * @param array|object $package Get shipping package.
	 *
	 * @return array $found_shipping_classes
	 * @since 1.0.0
	 */
	public function afrsmsm_find_shipping_classes( $package ) {
		$found_shipping_classes = array();
		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['data']->needs_shipping() ) {
				$found_class = $values['data']->get_shipping_class();
				if ( ! empty( $found_class ) ) {
					if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
						$found_shipping_classes[ $found_class ] = array();
					}
					$found_shipping_classes[ $found_class ][ $item_id ] = $values;
				}
			}
		}
		return $found_shipping_classes;
	}
	/**
	 * Cost for product per qty in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_product Get all product list.
	 * @param array  $woo_cart_array                 Get cart array.
	 * @param string $sitepress                      sitepress is use for multilanguage.
	 * @param string $default_lang                   get default language.
	 * @param string $cost_on_product_rule_match     check rule match for product per qty.
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wc_get_product()
	 * @uses  WC_Product::is_type()
	 */
	public function afrsmsm_advance_pricing_rules_product_per_qty__premium_only( $get_condition_array_ap_product, $woo_cart_array, $sitepress, $default_lang, $cost_on_product_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_prd = array();
			if ( ! empty( $get_condition_array_ap_product ) || '' !== $get_condition_array_ap_product ) {
				foreach ( $get_condition_array_ap_product as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_products'] ) || '' !== $get_condition['ap_fees_products'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_products'],
							$woo_cart_array,
							$sitepress,
							$default_lang,
							'product',
							'qty'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws__premium_only(
							$get_condition['ap_fees_ap_prd_min_qty'],
							$get_condition['ap_fees_ap_prd_max_qty'],
							$get_condition['ap_fees_ap_price_product'],
							'qty'
						);
						$is_passed_from_here_prd[] = $this->afrsm_check_passed_rule__premium_only(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_cost_per_prd_qty',
							'has_fee_based_on_cost_per_prd_price',
							$get_condition['ap_fees_ap_price_product'],
							$total_qws,
							'qty'
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_prd,
				'has_fee_based_on_cost_per_prd_qty',
				'has_fee_based_on_cost_per_prd_price',
				$cost_on_product_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Count qty for Product, Category and Total Cart
	 *
	 * @param array  $ap_selected_id Get select product id.
	 * @param array  $woo_cart_array Get cart array.
	 * @param string $sitepress      sitepress is use for multilanguage.
	 * @param string $default_lang   get default language.
	 * @param string $type           Product,category or subtotal.
	 * @param string $qws            Get qty, subtotal or weight.
	 *
	 * @return int $total
	 *
	 * @since 3.6
	 *
	 * @uses  wc_get_product()
	 * @uses  WC_Product::is_type()
	 * @uses  wp_get_post_terms()
	 * @uses  afrsm_get_prd_category_from_cart__premium_only()
	 */
	public function afrsm_get_count_qty__premium_only( $ap_selected_id, $woo_cart_array, $sitepress, $default_lang, $type, $qws ) {
		$total_qws = 0;
		if ( 'shipping_class' !== $type ) {
			$ap_selected_id = array_map( 'intval', $ap_selected_id );
		}
		foreach ( $woo_cart_array as $woo_cart_item ) {
			$main_product_id_lan = $woo_cart_item['product_id'];
			if ( ! empty( $woo_cart_item['variation_id'] ) && 0 !== $woo_cart_item['variation_id'] ) {
				$product_id_lan = $woo_cart_item['variation_id'];
			} else {
				$product_id_lan = $woo_cart_item['product_id'];
			}
			$_product = wc_get_product( $product_id_lan );
			if ( ! ( $_product->is_virtual( 'yes' ) ) && ( false === strpos($_product->get_type(), 'bundle') )  ) {
				if ( ! empty( $sitepress ) ) {
					$product_id_lan = intval( apply_filters( 'wpml_object_id', $product_id_lan, 'product', true, $default_lang ) );
				} else {
					$product_id_lan = intval( $product_id_lan );
				}
				if ( 'product' === $type ) {
					if ( in_array( $product_id_lan, $ap_selected_id, true ) ) {
						if ( 'qty' === $qws ) {
							$total_qws += intval( $woo_cart_item['quantity'] );
						}
						if ( 'weight' === $qws ) {
							$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
						}
						if ( 'subtotal' === $qws ) {
							if ( ! empty( $woo_cart_item['line_tax'] ) ) {
								$woo_cart_item['line_tax'] = $woo_cart_item['line_tax'];
							}
							$total_qws += $this->afrsm_pro_get_specific_subtotal__premium_only( $woo_cart_item['line_total'], $woo_cart_item['line_tax'] );
						}
					}
				}
				if ( 'category' === $type ) {
					$cat_id_list        = wp_get_post_terms(
						$main_product_id_lan,
						'product_cat',
						array(
							'fields' => 'ids',
						)
					);
					$cat_id_list_origin = $this->afrsm_get_prd_category_from_cart__premium_only( $cat_id_list, $sitepress, $default_lang );
					if ( ! empty( $cat_id_list_origin ) && is_array( $cat_id_list_origin ) ) {
						foreach ( $ap_selected_id as $ap_fees_categories_key_val ) {
							if ( in_array( $ap_fees_categories_key_val, $cat_id_list_origin, true ) ) {
								if ( 'qty' === $qws ) {
									$total_qws += intval( $woo_cart_item['quantity'] );
								}
								if ( 'weight' === $qws ) {
									$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
								}
								if ( 'subtotal' === $qws ) {
									if ( ! empty( $woo_cart_item['line_tax'] ) ) {
										$woo_cart_item['line_tax'] = $woo_cart_item['line_tax'];
									}
									$total_qws += $this->afrsm_pro_get_specific_subtotal__premium_only( $woo_cart_item['line_total'], $woo_cart_item['line_tax'] );
								}
								break;
							}
						}
					}
				}
				if ( 'shipping_class' === $type ) {
					$prd_shipping_class = $_product->get_shipping_class();
					if ( in_array( $prd_shipping_class, $ap_selected_id, true ) ) {
						if ( 'qty' === $qws ) {
							$total_qws += intval( $woo_cart_item['quantity'] );
						}
						if ( 'weight' === $qws ) {
							$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
						}
						if ( 'subtotal' === $qws ) {
							if ( ! empty( $woo_cart_item['line_tax'] ) ) {
								$woo_cart_item['line_tax'] = $woo_cart_item['line_tax'];
							}
							$total_qws += $this->afrsm_pro_get_specific_subtotal__premium_only( $woo_cart_item['line_total'], $woo_cart_item['line_tax'] );
						}
					}
				}
			}
		}
		return $total_qws;
	}
	/**
	 * Get specific subtotal for product and category
	 *
	 * @param float $line_total Get line total.
	 * @param float $line_tax   Get line tax.
	 *
	 * @return float $subtotal Get specific subtotal.
	 *
	 * @since    3.6
	 */
	public function afrsm_pro_get_specific_subtotal__premium_only( $line_total, $line_tax ) {
		$get_customer            = WC()->cart->get_customer();
		$get_customer_vat_exempt = WC()->customer->get_is_vat_exempt();
		$tax_display_cart        = WC()->cart->tax_display_cart;
		$wc_prices_include_tax   = wc_prices_include_tax();
		$tax_enable              = wc_tax_enabled();
		$cart_subtotal           = 0;
		if ( true === $tax_enable ) {
			if ( true === $wc_prices_include_tax ) {
				if ( 'incl' === $tax_display_cart && ! ( $get_customer && $get_customer_vat_exempt ) ) {
					$cart_subtotal += $line_total + $line_tax;
				} else {
					$cart_subtotal += $line_total;
				}
			} else {
				if ( 'incl' === $tax_display_cart && ! ( $get_customer && $get_customer_vat_exempt ) ) {
					$cart_subtotal += $line_total + $line_tax;
				} else {
					$cart_subtotal += $line_total;
				}
			}
		} else {
			$cart_subtotal += $line_total;
		}
		return $cart_subtotal;
	}
	/**
	 * Get Product category from cart
	 *
	 * @param array  $cat_id_list  List of category.
	 * @param string $sitepress    sitepress is use for multilanguage.
	 * @param string $default_lang get default language.
	 *
	 * @return array $cat_id_list_origin
	 *
	 * @since 3.6
	 */
	public function afrsm_get_prd_category_from_cart__premium_only( $cat_id_list, $sitepress, $default_lang ) {
		$cat_id_list_origin = array();
		if ( isset( $cat_id_list ) && ! empty( $cat_id_list ) ) {
			foreach ( $cat_id_list as $cat_id ) {
				if ( ! empty( $sitepress ) ) {
					$cat_id_list_origin[] = (int) apply_filters( 'wpml_object_id', $cat_id, 'product_cat', true, $default_lang );
				} else {
					$cat_id_list_origin[] = (int) $cat_id;
				}
			}
		}
		return $cat_id_list_origin;
	}
	/**
	 * Check Min and max qty, weight and subtotal
	 *
	 * @param int|float $min   min qty, weight or subtotal.
	 * @param int|float $max   max qty, weight or subtotal.
	 * @param float     $price price for rule.
	 * @param string    $qws   specific qty or weight or subtotal.
	 *
	 * @return array
	 *
	 * @since 3.4
	 */
	public function afrsm_check_min_max_qws__premium_only( $min, $max, $price, $qws ) {
		$min_val = $min;
		if ( '' === $max || '0' === $max ) {
			$max_val = 2000000000;
		} else {
			$max_val = $max;
		}
		$price_val = $price;
		if ( 'qty' === $qws ) {
			settype( $min_val, 'integer' );
			settype( $max_val, 'integer' );
		} else {
			settype( $min_val, 'float' );
			settype( $max_val, 'float' );
		}
		return array(
			'min'   => $min_val,
			'max'   => $max_val,
			'price' => $price_val,
		);
	}
	/**
	 * Check rule passed or not
	 *
	 * @param string    $key       Dynamic key for specific rule.
	 * @param string    $min       Min amount or qty.
	 * @param string    $max       Max amount or qty.
	 * @param string    $hbc       unique key.
	 * @param string    $hbp       unique key.
	 * @param float     $price     product price for specific rule.
	 * @param int|float $total_qws total qty or weight or subtotal.
	 * @param string    $qws       specific qty or weight or subtotal.
	 *
	 * @return array
	 * @since    3.6
	 */
	public function afrsm_check_passed_rule__premium_only( $key, $min, $max, $hbc, $hbp, $price, $total_qws, $qws ) {
		$is_passed_from_here_prd = array();
		if ( ( $min <= $total_qws ) && ( $total_qws <= $max ) ) {
			$is_passed_from_here_prd[ $hbc ][ $key ] = 'yes';
			$is_passed_from_here_prd[ $hbp ][ $key ] = $price;
		} else {
			$is_passed_from_here_prd[ $hbc ][ $key ] = 'no';
			$is_passed_from_here_prd[ $hbp ][ $key ] = $price;
		}
		return $is_passed_from_here_prd;
	}
	/**
	 * Find unique id based on given array
	 *
	 * @param array  $is_passed                 fetch all matched rule.
	 * @param string $has_fee_checked           check fee matched rule.
	 * @param string $has_fee_based             check matched key.
	 * @param string $advance_inside_rule_match check advanced rule for any or all rule.
	 *
	 * @return array
	 * @since    3.6
	 */
	public function afrsm_pro_check_all_passed_advance_rule__premium_only( $is_passed, $has_fee_checked, $has_fee_based, $advance_inside_rule_match ) {
		$get_cart_total = WC()->cart->get_cart_contents_total();
		$main_is_passed = 'no';
		$flag           = array();
		$sum_ammount    = 0;
		if ( ! empty( $is_passed ) ) {
			foreach ( $is_passed as $main_is_passed ) {
				foreach ( $main_is_passed[ $has_fee_checked ] as $key => $is_passed_value ) {
					if ( 'yes' === $is_passed_value ) {
						foreach ( $main_is_passed[ $has_fee_based ] as $hfb_key => $hfb_is_passed_value ) {
							if ( $hfb_key === $key ) {
								$final_price  = $this->afrsm_check_percantage_price__premium_only( $hfb_is_passed_value, $get_cart_total );
								$sum_ammount += $final_price;
							}
						}
						$flag[ $key ] = true;
					} else {
						$flag[ $key ] = false;
					}
				}
			}
			if ( 'any' === $advance_inside_rule_match ) {
				if ( in_array( true, $flag, true ) ) {
					$main_is_passed = 'yes';
				} else {
					$main_is_passed = 'no';
				}
			} else {
				if ( in_array( false, $flag, true ) ) {
					$main_is_passed = 'no';
				} else {
					$main_is_passed = 'yes';
				}
			}
		}
		return array(
			'flag'         => $main_is_passed,
			'total_amount' => $sum_ammount,
		);
	}
	/**
	 * Add shipping rate
	 *
	 * @param float $price          Price for cart or product.
	 * @param float $get_cart_total Gte total cart.
	 *
	 * @return float $shipping_rate_cost
	 *
	 * @since 3.4
	 */
	public function afrsm_check_percantage_price__premium_only( $price, $get_cart_total ) {
		if ( ! empty( $price ) ) {
			$is_percent = substr( $price, - 1 );
			if ( '%' === $is_percent ) {
				$percent = substr( $price, 0, - 1 );
				$percent = number_format( $percent, 2, '.', '' );
				if ( ! empty( $percent ) ) {
					$percent_total = ( $percent / 100 ) * $get_cart_total;
					$price         = $percent_total;
				}
			} else {
				$price = $this->afrsmsm_price_format( $price );
			}
		}
		return $price;
	}
	/**
	 * Cost for Product subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_product_subtotal Get all product subtotal.
	 * @param array  $woo_cart_array                          all cart item.
	 * @param string $cost_on_product_subtotal_rule_match     Check rule for product subtotal.
	 * @param string $sitepress                               sitepress is use for multilanguage.
	 * @param string $default_lang                            get default language.
	 *
	 * @return array $main_is_passed
	 * @since 3.6
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 */
	public function afrsmsm_advance_pricing_rules_product_subtotal__premium_only( $get_condition_array_ap_product_subtotal, $woo_cart_array, $cost_on_product_subtotal_rule_match, $sitepress, $default_lang ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_ps = array();
			if ( ! empty( $get_condition_array_ap_product_subtotal ) || '' !== $get_condition_array_ap_product_subtotal ) {
				foreach ( $get_condition_array_ap_product_subtotal as $key => $get_condition ) {
					$total_qws                = $this->afrsm_get_count_qty__premium_only(
						$get_condition['ap_fees_product_subtotal'],
						$woo_cart_array,
						$sitepress,
						$default_lang,
						'product',
						'subtotal'
					);
					$get_min_max              = $this->afrsm_check_min_max_qws__premium_only(
						$get_condition['ap_fees_ap_product_subtotal_min_subtotal'],
						$get_condition['ap_fees_ap_product_subtotal_max_subtotal'],
						$get_condition['ap_fees_ap_price_product_subtotal'],
						'subtotal'
					);
					$is_passed_from_here_ps[] = $this->afrsm_check_passed_rule__premium_only(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_ps',
						'has_fee_based_on_ps_price',
						$get_condition['ap_fees_ap_price_product_subtotal'],
						$total_qws,
						'subtotal'
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_ps,
				'has_fee_based_on_ps',
				'has_fee_based_on_ps_price',
				$cost_on_product_subtotal_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for product per weight in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_product_weight Get all product weight.
	 * @param array  $woo_cart_array                        all cart item.
	 * @param string $sitepress                             sitepress is use for multilanguage.
	 * @param string $default_lang                          get default language.
	 * @param string $cost_on_product_weight_rule_match     Check rule for product weight.
	 *
	 * @return array $main_is_passed
	 *
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wc_get_product()
	 * @uses  WC_Product::is_type()
	 */
	public function afrsmsm_advance_pricing_rules_product_per_weight__premium_only( $get_condition_array_ap_product_weight, $woo_cart_array, $sitepress, $default_lang, $cost_on_product_weight_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_prd = array();
			if ( ! empty( $get_condition_array_ap_product_weight ) || '' !== $get_condition_array_ap_product_weight ) {
				foreach ( $get_condition_array_ap_product_weight as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_product_weight'] ) || '' !== $get_condition['ap_fees_product_weight'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_product_weight'],
							$woo_cart_array,
							$sitepress,
							$default_lang,
							'product',
							'weight'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws__premium_only(
							$get_condition['ap_fees_ap_product_weight_min_qty'],
							$get_condition['ap_fees_ap_product_weight_max_qty'],
							$get_condition['ap_fees_ap_price_product_weight'],
							'weight'
						);
						$is_passed_from_here_prd[] = $this->afrsm_check_passed_rule__premium_only(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_cost_ppw',
							'has_fee_based_on_cost_ppw_price',
							$get_condition['ap_fees_ap_price_product_weight'],
							$total_qws,
							'weight'
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_prd,
				'has_fee_based_on_cost_ppw',
				'has_fee_based_on_cost_ppw_price',
				$cost_on_product_weight_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for category per qty in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_category Get all category.
	 * @param array  $woo_cart_array                  all cart item.
	 * @param string $sitepress                       sitepress is use for multilanguage.
	 * @param string $default_lang                    get default language.
	 * @param string $cost_on_category_rule_match     Check rule for category.
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  WC_Product::is_type()
	 */
	public function afrsmsm_advance_pricing_rules_category_per_qty__premium_only( $get_condition_array_ap_category, $woo_cart_array, $sitepress, $default_lang, $cost_on_category_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_cat = array();
			if ( ! empty( $get_condition_array_ap_category ) || '' !== $get_condition_array_ap_category ) {
				foreach ( $get_condition_array_ap_category as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_categories'] ) || '' !== $get_condition['ap_fees_categories'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_categories'],
							$woo_cart_array,
							$sitepress,
							$default_lang,
							'category',
							'qty'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws__premium_only(
							$get_condition['ap_fees_ap_cat_min_qty'],
							$get_condition['ap_fees_ap_cat_max_qty'],
							$get_condition['ap_fees_ap_price_category'],
							'qty'
						);
						$is_passed_from_here_cat[] = $this->afrsm_check_passed_rule__premium_only(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_per_category',
							'has_fee_based_on_cost_per_cat_price',
							$get_condition['ap_fees_ap_price_category'],
							$total_qws,
							'qty'
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_cat,
				'has_fee_based_on_per_category',
				'has_fee_based_on_cost_per_cat_price',
				$cost_on_category_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for Category subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_category_subtotal Get all category subtotal.
	 * @param array  $woo_cart_array                           all cart item.
	 * @param string $cost_on_category_subtotal_rule_match     Check rule for category subtotal.
	 * @param string $sitepress                                sitepress is use for multilanguage.
	 * @param string $default_lang                             get default language.
	 *
	 * @return array $main_is_passed
	 * @since 3.6
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 */
	public function afrsmsm_advance_pricing_rules_category_subtotal__premium_only( $get_condition_array_ap_category_subtotal, $woo_cart_array, $cost_on_category_subtotal_rule_match, $sitepress, $default_lang ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_cs = array();
			if ( ! empty( $get_condition_array_ap_category_subtotal ) || '' !== $get_condition_array_ap_category_subtotal ) {
				foreach ( $get_condition_array_ap_category_subtotal as $key => $get_condition ) {
					$total_qws                = $this->afrsm_get_count_qty__premium_only(
						$get_condition['ap_fees_category_subtotal'],
						$woo_cart_array,
						$sitepress,
						$default_lang,
						'category',
						'subtotal'
					);
					$get_min_max              = $this->afrsm_check_min_max_qws__premium_only(
						$get_condition['ap_fees_ap_category_subtotal_min_subtotal'],
						$get_condition['ap_fees_ap_category_subtotal_max_subtotal'],
						$get_condition['ap_fees_ap_price_category_subtotal'],
						'subtotal'
					);
					$is_passed_from_here_cs[] = $this->afrsm_check_passed_rule__premium_only(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_cs',
						'has_fee_based_on_cs_price',
						$get_condition['ap_fees_ap_price_category_subtotal'],
						$total_qws,
						'subtotal'
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_cs,
				'has_fee_based_on_cs',
				'has_fee_based_on_cs_price',
				$cost_on_category_subtotal_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for product per weight in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_category_weight Get all category weight.
	 * @param array  $woo_cart_array                         all cart item.
	 * @param string $sitepress                              sitepress is use for multilanguage.
	 * @param string $default_lang                           get default language.
	 * @param string $cost_on_category_weight_rule_match     Check rule for category weight.
	 *
	 * @return array $main_is_passed
	 *
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 */
	public function afrsmsm_advance_pricing_rules_category_per_weight__premium_only( $get_condition_array_ap_category_weight, $woo_cart_array, $sitepress, $default_lang, $cost_on_category_weight_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_cat = array();
			if ( ! empty( $get_condition_array_ap_category_weight ) || '' !== $get_condition_array_ap_category_weight ) {
				foreach ( $get_condition_array_ap_category_weight as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_categories_weight'] ) || '' !== $get_condition['ap_fees_categories_weight'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_categories_weight'],
							$woo_cart_array,
							$sitepress,
							$default_lang,
							'category',
							'weight'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws__premium_only(
							$get_condition['ap_fees_ap_category_weight_min_qty'],
							$get_condition['ap_fees_ap_category_weight_max_qty'],
							$get_condition['ap_fees_ap_price_category_weight'],
							'weight'
						);
						$is_passed_from_here_cat[] = $this->afrsm_check_passed_rule__premium_only(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_per_cw',
							'has_fee_based_on_cost_per_cw',
							$get_condition['ap_fees_ap_price_category_weight'],
							$total_qws,
							'weight'
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_cat,
				'has_fee_based_on_per_cw',
				'has_fee_based_on_cost_per_cw',
				$cost_on_category_weight_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for total cart qty in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_total_cart_qty Get all total cart qty subtotal.
	 * @param array  $woo_cart_array                        all cart item.
	 * @param string $cost_on_total_cart_qty_rule_match     Check rule for total cart qty subtotal.
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 */
	public function afrsmsm_advance_pricing_rules_total_cart_qty__premium_only( $get_condition_array_ap_total_cart_qty, $woo_cart_array, $cost_on_total_cart_qty_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_tcq = array();
			if ( ! empty( $get_condition_array_ap_total_cart_qty ) || '' !== $get_condition_array_ap_total_cart_qty ) {
				foreach ( $get_condition_array_ap_total_cart_qty as $key => $get_condition ) {
					$total_qws = 0;
					foreach ( $woo_cart_array as $woo_cart_item ) {
						if ( ! ( $woo_cart_item['data']->is_virtual() ) && ( false === strpos($woo_cart_item['data']->get_type(), 'bundle') )  ) {
							$total_qws += $woo_cart_item['quantity'];
						}
					}
					$get_min_max               = $this->afrsm_check_min_max_qws__premium_only(
						$get_condition['ap_fees_ap_total_cart_qty_min_qty'],
						$get_condition['ap_fees_ap_total_cart_qty_max_qty'],
						$get_condition['ap_fees_ap_price_total_cart_qty'],
						'qty'
					);
					$is_passed_from_here_tcq[] = $this->afrsm_check_passed_rule__premium_only(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_tcq',
						'has_fee_based_on_tcq_price',
						$get_condition['ap_fees_ap_price_total_cart_qty'],
						$total_qws,
						'qty'
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_tcq,
				'has_fee_based_on_tcq',
				'has_fee_based_on_tcq_price',
				$cost_on_total_cart_qty_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for total cart weight in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_total_cart_weight Get all total cart weight subtotal.
	 * @param array  $woo_cart_array                           all cart item.
	 * @param string $cost_on_total_cart_weight_rule_match     Check rule for total cart weight subtotal.
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 */
	public function afrsmsm_advance_pricing_rules_total_cart_weight__premium_only( $get_condition_array_ap_total_cart_weight, $woo_cart_array, $cost_on_total_cart_weight_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_tcw = array();
			if ( ! empty( $get_condition_array_ap_total_cart_weight ) || '' !== $get_condition_array_ap_total_cart_weight ) {
				foreach ( $get_condition_array_ap_total_cart_weight as $key => $get_condition ) {
					$total_qws = 0;
					foreach ( $woo_cart_array as $woo_cart_item ) {
						if ( ! empty( $woo_cart_item['variation_id'] ) && 0 !== $woo_cart_item['variation_id'] ) {
							$product_id_lan = $woo_cart_item['variation_id'];
						} else {
							$product_id_lan = $woo_cart_item['product_id'];
						}
						$_product = wc_get_product( $product_id_lan );
						if ( ! ( $_product->is_virtual( 'yes' ) ) && ( false === strpos($_product->get_type(), 'bundle') )  ) {
							$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
						}
					}
					$get_min_max               = $this->afrsm_check_min_max_qws__premium_only(
						$get_condition['ap_fees_ap_total_cart_weight_min_weight'],
						$get_condition['ap_fees_ap_total_cart_weight_max_weight'],
						$get_condition['ap_fees_ap_price_total_cart_weight'],
						'weight'
					);
					$is_passed_from_here_tcw[] = $this->afrsm_check_passed_rule__premium_only(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_tcw',
						'has_fee_based_on_tcw_price',
						$get_condition['ap_fees_ap_price_total_cart_weight'],
						$total_qws,
						'weight'
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_tcw,
				'has_fee_based_on_tcw',
				'has_fee_based_on_tcw_price',
				$cost_on_total_cart_weight_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for total cart subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_total_cart_subtotal Get all total cart subtotal.
	 * @param array  $woo_cart_array                             all cart item.
	 * @param string $cost_on_total_cart_subtotal_rule_match     Check rule for total cart subtotal.
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 */
	public function afrsmsm_advance_pricing_rules_total_cart_subtotal__premium_only( $get_condition_array_ap_total_cart_subtotal, $woo_cart_array, $cost_on_total_cart_subtotal_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_tcw = array();
			if ( ! empty( $get_condition_array_ap_total_cart_subtotal ) || '' !== $get_condition_array_ap_total_cart_subtotal ) {
				foreach ( $get_condition_array_ap_total_cart_subtotal as $key => $get_condition ) {
					$total_qws                 = self::$admin_object->afrsfwa_get_cart_subtotal();
					$get_min_max               = $this->afrsm_check_min_max_qws__premium_only(
						$get_condition['ap_fees_ap_total_cart_subtotal_min_subtotal'],
						$get_condition['ap_fees_ap_total_cart_subtotal_max_subtotal'],
						$get_condition['ap_fees_ap_price_total_cart_subtotal'],
						'weight'
					);
					$is_passed_from_here_tcw[] = $this->afrsm_check_passed_rule__premium_only(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_tcs',
						'has_fee_based_on_tcs_price',
						$get_condition['ap_fees_ap_price_total_cart_subtotal'],
						$total_qws,
						'weight'
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_tcw,
				'has_fee_based_on_tcs',
				'has_fee_based_on_tcs_price',
				$cost_on_total_cart_subtotal_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for Shipping class subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_shipping_class_subtotal Get all shipping class.
	 * @param array  $woo_cart_array                                 all cart item.
	 * @param string $cost_on_shipping_class_subtotal_rule_match     Check rule for shipping class subtotal.
	 * @param string $sitepress                                      sitepress is use for multilanguage.
	 * @param string $default_lang                                   get default language.
	 *
	 * @return array $main_is_passed
	 * @since 3.6
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 */
	public function afrsmsm_advance_pricing_rules_shipping_class_subtotal__premium_only( $get_condition_array_ap_shipping_class_subtotal, $woo_cart_array, $cost_on_shipping_class_subtotal_rule_match, $sitepress, $default_lang ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_scs = array();
			if ( ! empty( $get_condition_array_ap_shipping_class_subtotal ) || '' !== $get_condition_array_ap_shipping_class_subtotal ) {
				foreach ( $get_condition_array_ap_shipping_class_subtotal as $key => $get_condition ) {
					$total_qws                 = $this->afrsm_get_count_qty__premium_only(
						$get_condition['ap_fees_shipping_class_subtotals'],
						$woo_cart_array,
						$sitepress,
						$default_lang,
						'shipping_class',
						'subtotal'
					);
					$get_min_max               = $this->afrsm_check_min_max_qws__premium_only(
						$get_condition['ap_fees_ap_shipping_class_subtotal_min_subtotal'],
						$get_condition['ap_fees_ap_shipping_class_subtotal_max_subtotal'],
						$get_condition['ap_fees_ap_price_shipping_class_subtotal'],
						'subtotal'
					);
					$is_passed_from_here_scs[] = $this->afrsm_check_passed_rule__premium_only(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_scs',
						'has_fee_based_on_scs_price',
						$get_condition['ap_fees_ap_price_shipping_class_subtotal'],
						$total_qws,
						'subtotal'
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_scs,
				'has_fee_based_on_scs',
				'has_fee_based_on_scs_price',
				$cost_on_shipping_class_subtotal_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Display array column
	 *
	 * @param array $input      This variable's for inpur array.
	 * @param int   $column_key This variable's for input array's key.
	 * @param int   $index_key  This variable's for index key.
	 *
	 * @return array $array It will return array if any error generate then it will return false
	 * @since  1.0.0
	 *
	 * @uses   trigger_error()
	 */
	public function afrsmsm_fee_array_column( array $input, $column_key, $index_key = null ) {
		$array = array();
		foreach ( $input as $value ) {
			if ( ! isset( $value[ $column_key ] ) ) {
				wp_die( esc_html__( 'key', 'advanced-flat-rate-shipping-for-woocommerce' ) . esc_html( $column_key ) . esc_html__( 'does not exist in array', 'advanced-flat-rate-shipping-for-woocommerce' ) );
				return false;
			}
			if ( is_null( $index_key ) ) {
				$array[] = $value[ $column_key ];
			} else {
				if ( ! isset( $value[ $index_key ] ) ) {
					wp_die( esc_html__( 'key', 'advanced-flat-rate-shipping-for-woocommerce' ) . esc_html( $index_key ) . esc_html__( 'does not exist in array', 'advanced-flat-rate-shipping-for-woocommerce' ) );
					return false;
				}
				if ( ! is_scalar( $value[ $index_key ] ) ) {
					wp_die( esc_html__( 'key', 'advanced-flat-rate-shipping-for-woocommerce' ) . esc_html( $index_key ) . esc_html__( 'does not contain scalar value', 'advanced-flat-rate-shipping-for-woocommerce' ) );
					return false;
				}
				$array[ $value[ $index_key ] ] = $value[ $column_key ];
			}
		}
		return $array;
	}
	/**
	 * Work out fee ( shortcode ).
	 *
	 * @param array $atts all attribute.
	 *
	 * @return string $calculated_fee
	 * @since 1.0.0
	 *
	 * @uses  afrsmsm_string_sanitize
	 */
	public function fee( $atts ) {
		$atts            = shortcode_atts(
			array(
				'percent' => '',
				'min_fee' => '',
				'max_fee' => '',
			),
			$atts
		);
		$atts['percent'] = $this->afrsmsm_string_sanitize( $atts['percent'] );
		$atts['min_fee'] = $this->afrsmsm_string_sanitize( $atts['min_fee'] );
		$atts['max_fee'] = $this->afrsmsm_string_sanitize( $atts['max_fee'] );
		$calculated_fee  = 0;
		if ( $atts['percent'] ) {
			$calculated_fee = $this->fee_cost * ( floatval( $atts['percent'] ) / 100 );
		}
		if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
			$calculated_fee = $atts['min_fee'];
		}
		if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] ) {
			$calculated_fee = $atts['max_fee'];
		}
		return $calculated_fee;
	}
	/**
	 * Sanitize string
	 *
	 * @param mixed $string string.
	 *
	 * @return string $result
	 * @since 1.0.0
	 */
	public function afrsmsm_string_sanitize( $string ) {
		$result = preg_replace( '/[^ A-Za-z0-9_=.*()+\-\[\]\/]+/', '', html_entity_decode( $string, ENT_QUOTES ) );
		return $result;
	}
}
