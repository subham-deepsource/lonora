<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce/admin
 */
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin class.
 */
class Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin {
	/**
	 * Plugin's post type
	 *
	 * @since 4.1
	 */
	const AFRSFWA_SHIPPING_POST_TYPE = 'wc_afrsm';
	/**
	 * Plugin's Plugin's post type
	 *
	 * @since 4.1
	 */
	const AFRSFWA_ZONE_POST_TYPE = 'wc_afrsm_zone';

	const POST_TYPE = 'wc_afrsm';
	const AFRSM_ZONE_POST_TYPE = 'wc_afrsm_zone';
	/**
	 * The hook for external class
	 *
	 * @since    1.0.0
	 * @var      string $hook The class of external plugin.
	 */
	public static $hook = null;
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string $version The current version of this plugin.
	 */
	private $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param string $hook display current page name.
	 *
	 * @since    1.0.0
	 */
	public function afrsfwa_enqueue_styles( $hook ) {
		if ( false !== strpos( $hook, 'woocommerce_page_afrsm' ) ) {
			wp_enqueue_style( $this->plugin_name . 'select2-min', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), 'all' );
			wp_enqueue_style( $this->plugin_name . '-jquery-ui-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-timepicker-min-css', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.min.css', $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . 'main-style', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), 'all' );
			wp_enqueue_style( $this->plugin_name . 'media-css', plugin_dir_url( __FILE__ ) . 'css/media.css', array(), 'all' );
		}
		wp_enqueue_style( $this->plugin_name . 'notice-css', plugin_dir_url( __FILE__ ) . 'css/notice.css', array(), 'all' );
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @param string $hook display current page name.
	 *
	 * @since    1.0.0
	 */
	public function afrsfwa_enqueue_scripts( $hook ) {
		if ( false !== strpos( $hook, 'woocommerce_page_afrsm' ) ) {
			global $wp;
			wp_enqueue_script( 'jquery-ui-accordion' );
			wp_enqueue_script( 'jquery-ui-draggable' );
			wp_enqueue_script(
				$this->plugin_name .
				'-select2-full-min',
				plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js',
				array(
					'jquery',
					'jquery-ui-datepicker',
				),
				$this->version,
				false
			);
			wp_enqueue_script( $this->plugin_name . '-timepicker-js', plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'js/advanced-flat-rate-shipping-for-woocommerce-admin.js',
				array(
					'jquery',
					'jquery-ui-dialog',
					'jquery-ui-accordion',
					'jquery-ui-sortable',
					'select2',
				),
				$this->version,
				false
			);
			$current_url = home_url( add_query_arg( $wp->query_vars, $wp->request ) );
			wp_localize_script(
				$this->plugin_name,
				'coditional_vars',
				array(
					'ajaxurl'                          => admin_url( 'admin-ajax.php' ),
					'ajax_icon'                        => esc_url( plugin_dir_url( __FILE__ ) . '/images/ajax-loader.gif' ),
					'plugin_url'                       => plugin_dir_url( __FILE__ ),
					'dsm_ajax_nonce'                   => wp_create_nonce( 'dsm_nonce' ),
					'country'                          => esc_html__( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'state'                            => esc_html__( 'State', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'postcode'                         => esc_html__( 'Postcode', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'zone'                             => esc_html__( 'Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_contains_product'            => esc_html__( 'Cart contains product', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_contains_variable_product'   => esc_html__( 'Cart contains variable product', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_contains_category_product'   => esc_html__( 'Cart contains category\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_contains_tag_product'        => esc_html__( 'Cart contains tag\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_contains_sku_product'        => esc_html__( 'Cart contains SKU\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_contains_product_qty'        => esc_html__( 'Cart contains product\'s quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'user'                             => esc_html__( 'User', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'user_role'                        => esc_html__( 'User Role', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_subtotal_before_discount'    => esc_html__( 'Cart Subtotal ( Before Discount )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_subtotal_after_discount'     => esc_html__( 'Cart Subtotal ( After Discount )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'quantity'                         => esc_html__( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'weight'                           => esc_html__( 'Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'coupon'                           => esc_html__( 'Coupon', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'shipping_class'                   => esc_html__( 'Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'min_quantity'                     => esc_html__( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'max_quantity'                     => esc_html__( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'amount'                           => esc_html__( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'equal_to'                         => esc_html__( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'not_equal_to'                     => esc_html__( 'Not Equal to ( ! = )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'less_or_equal_to'                 => esc_html__( 'Less or Equal to ( <= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'less_than'                        => esc_html__( 'Less then ( < )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'greater_or_equal_to'              => esc_html__( 'greater or Equal to ( >= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'greater_than'                     => esc_html__( 'greater then ( > )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'validation_length1'               => esc_html__( 'Please enter 3 or more characters', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'select_some_options'              => esc_html__( 'Select some options', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'select_category'                  => esc_html__( 'Select Category', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'delete'                           => esc_html__( 'Delete', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_qty'                         => esc_html__( 'Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_weight'                      => esc_html__( 'Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'min_weight'                       => esc_html__( 'Min Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'max_weight'                       => esc_html__( 'Max Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_subtotal'                    => esc_html__( 'Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'min_subtotal'                     => esc_html__( 'Min Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'max_subtotal'                     => esc_html__( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'validation_length2'               => esc_html__( 'Please enter', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'validation_length3'               => esc_html__( 'or more characters', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'location_specific'                => esc_html__( 'Location Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'product_specific'                 => esc_html__( 'Product Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'user_specific'                    => esc_html__( 'User Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_specific'                    => esc_html__( 'Cart Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'attribute_specific'               => esc_html__( 'Attribute Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'checkout_specific'                => esc_html__( 'Checkout Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'payment_method'                   => esc_html__( 'Payment Method', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'min_max_qty_error'                => esc_html__( 'Max qty should greater then min qty', 'woo-hide-shipping-methods' ),
					'min_max_weight_error'             => esc_html__( 'Max weight should greater then min weight', 'woo-hide-shipping-methods' ),
					'min_max_subtotal_error'           => esc_html__( 'Max subtotal should greater then min subtotal', 'woo-hide-shipping-methods' ),
					'warning_msg1'                     => sprintf( __( '<p><b style="color: red;">Note: </b>If entered price is more than total shipping price than Message looks like: <b>Shipping Method Name: Curreny Symbole like( $ ) -60.00 Price </b> and if shipping minus price is more than total price than it will set Total Price to Zero( 0 ).</p>', 'advanced-flat-rate-shipping-for-woocommerce' ) ),
					'warning_msg2'                     => esc_html__( 'Please fill some required field in advance pricing rule section', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'warning_msg3'                     => esc_html__( 'You need to select product specific option in Shipping Method Rules for product based option', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'attribute_list'                   => wp_json_encode( $this->afrsfwa_attribute_list() ),
					'note'                             => esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'click_here'                       => esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'weight_msg'                       => esc_html__(
						'Please make sure that when you add rules in Advanced Pricing > Cost per weight Section It contains in
                                                                        above entered weight, otherwise it may be not apply proper shipping charges. For more detail please view
                                                                        our documentation at ',
						'advanced-flat-rate-shipping-for-woocommerce'
					),
					'cart_contains_product_msg'        => esc_html__(
						'Please make sure that when you add rules in Advanced Pricing > Cost per product Section It contains in
                                                                        above selected product list, otherwise it may be not apply proper shipping charges. For more detail please view
                                                                        our documentation at ',
						'advanced-flat-rate-shipping-for-woocommerce'
					),
					'cart_contains_category_msg'       => esc_html__(
						'Please make sure that when you add rules in Advanced Pricing > Cost per category Section It contains in
                                                                        above selected category list, otherwise it may be not apply proper shipping charges. For more detail please view
                                                                        our documentation at ',
						'advanced-flat-rate-shipping-for-woocommerce'
					),
					'cart_subtotal_after_discount_msg' => esc_html__( 'This rule will apply when you would apply coupun in front side. ', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'payment_method_msg'               => esc_html__( 'This rule will work for Force All Shipping Method in master setting ', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'current_url'                      => $current_url,
					'doc_url'                          => 'https://docs.thedotstore.com/article/209-how-to-add-fee-based-on-after-discount-rule',
					'list_page_url'                    => add_query_arg( array( 'page' => 'afrsm-start-page' ), admin_url( 'admin.php' ) ),
					'product_qty_page_url'             => 'https://docs.thedotstore.com/article/104-product-specific-shipping-rule/',
				)
			);
		}
	}
	/**
	 * Shipping method Pro Menu
	 *
	 * @since 3.0.0
	 */
	public function afrsfwa_dot_store_menu_shipping_method_pro() {
		$get_hook   = add_submenu_page(
			'woocommerce',
			'Advanced Shipping',
			'Advanced Shipping',
			'manage_options',
			'afrsm-start-page',
			array(
				$this,
				'afrsfwa_start_page',
			)
		);
		self::$hook = $get_hook;
		add_action( "load-$get_hook", array( $this, 'afrsm_screen_options' ) );
	}
	/**
	 * Shipping List Page
	 *
	 * @since    1.0.0
	 */
	public function afrsfwa_start_page() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/afrsm-start-page.php';
	}
	/**
	 * Remove section from shipping settings because we have added new menu in woocommece section
	 *
	 * @param array $sections get sections from shipping tab.
	 *
	 * @return array $sections
	 *
	 * @since    4.0
	 */
	public function afrsfwa_remove_section( $sections ) {
		unset( $sections['advanced_flat_rate_shipping'] );
		unset( $sections['forceall'] );
		return $sections;
	}
	/**
	 * Screen option for shipping
	 *
	 * @since    4.0
	 */
	public function afrsm_screen_options() {
		$get_action = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		if ( isset( $get_action ) && ( 'advance_shipping_method' === $get_action || 'advance_shipping_zone' === $get_action ) ) {
			$args = array(
				'label'   => esc_html__( 'List Per Page', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'default' => 10,
				'option'  => 'afrsm_per_page',
			);
			add_screen_option( 'per_page', $args );
		}
	}
	/**
	 * Redirect to quick start guide after plugin activation
	 *
	 * @since    4.0
	 */
	public function afrsfwa_welcome_shipping_method_screen_do_activation_redirect() {
		$this->afrsmsmp_register_post_type();

		if ( ! get_transient( '_welcome_screen_afrsm__mode_activation_redirect_data' ) ) {
			return;
		}
		delete_transient( '_welcome_screen_afrsm__mode_activation_redirect_data' );
		$activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_STRING );
		if ( is_network_admin() || isset( $activate_multi ) ) {
			return;
		}
		wp_safe_redirect( add_query_arg( admin_url( 'plugins.php' ) ) );
		exit;
	}
	/**
	 * Register post type
	 *
	 * @since 3.5
	 */
	public static function afrsmsmp_register_post_type() {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'          => array(
					'name'          => __( 'Advance Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'singular_name' => __( 'Advance Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' ),
				),
				'rewrite'         => false,
				'query_var'       => false,
				'public'          => false,
				'capability_type' => 'page',
				'capabilities'    => array(
					'edit_post'          => 'edit_advance_shipping_method',
					'read_post'          => 'read_advance_shipping_method',
					'delete_post'        => 'delete_advance_shipping_method',
					'edit_posts'         => 'edit_advance_shippings_method',
					'edit_others_posts'  => 'edit_advance_shippings_method',
					'publish_posts'      => 'edit_advance_shippings_method',
					'read_private_posts' => 'edit_advance_shippings_method',
				),
			)
		);

		register_post_type( self::AFRSM_ZONE_POST_TYPE, array(
			'labels' => array(
				'name'          => __( 'Advance Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'singular_name' => __( 'Advance Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
			),
		) );
	}

	/**
	 * Match condition based on shipping list
	 *
	 * @param int          $sm_post_id Matched shipping method id.
	 * @param array|object $package    Get shipping package.
	 *
	 * @return bool True if $final_condition_flag is 1, false otherwise. if $sm_status is off then also return false.
	 * @since    4.0
	 *
	 * @uses     afrsfwa_get_default_langugae_with_sitpress()
	 * @uses     afrsfwa_get_woo_version_number()
	 * @uses     WC_Cart::get_cart()
	 * @uses     afrsfwa_match_country_rules()
	 * @uses     afrsfwa_match_state_rules__premium_only()
	 * @uses     afrsfwa_match_postcode_rules__premium_only()
	 * @uses     afrsfwa_match_zone_rules__premium_only()
	 * @uses     afrsfwa_match_variable_products_rule__premium_only()
	 * @uses     afrsfwa_match_simple_products_rule()
	 * @uses     afrsfwa_match_category_rule()
	 * @uses     afrsfwa_match_tag_rule()
	 * @uses     afrsfwa_match_sku_rule__premium_only()
	 * @uses     afrsfwa_match_user_rule()
	 * @uses     afrsfwa_match_user_role_rule__premium_only()
	 * @uses     afrsfwa_match_coupon_rule__premium_only()
	 * @uses     afrsfwa_match_cart_subtotal_before_discount_rule()
	 * @uses     afrsfwa_match_cart_subtotal_after_discount_rule__premium_only()
	 * @uses     afrsfwa_match_cart_total_cart_qty_rule()
	 * @uses     afrsfwa_match_cart_total_weight_rule__premium_only()
	 * @uses     afrsfwa_match_shipping_class_rule__premium_only()
	 */
	public function afrsfwa_condition_match_rules( $sm_post_id, $package = array() ) {
		if ( empty( $sm_post_id ) ) {
			return false;
		}
		global $sitepress;
		$default_lang = $this->afrsfwa_get_default_langugae_with_sitpress();
		if ( ! empty( $sitepress ) ) {
			$sm_post_id = apply_filters( 'wpml_object_id', $sm_post_id, 'wc_afrsm', true, $default_lang );
		} else {
			$sm_post_id = $sm_post_id;
		}
		$wc_curr_version               = $this->afrsfwa_get_woo_version_number();
		$is_passed                     = array();
		$final_is_passed_general_rule  = array();
		$new_is_passed                 = array();
		$final_condition_flag          = array();
		$cart_array                    = $this->afrsfwa_get_cart();
		$cart_main_product_ids_array   = $this->afrsfwa_get_main_prd_id( $sitepress, $default_lang );
		$cart_product_ids_array        = $this->afrsfwa_get_prd_var_id( $sitepress, $default_lang );
		$sm_status                     = get_post_status( $sm_post_id );
		$get_condition_array           = get_post_meta( $sm_post_id, 'sm_metabox', true );
		$variation_cart_products_array = $this->afrsfwa_get_var_name__premium_only( $sitepress, $default_lang );
		$sm_start_date                 = get_post_meta( $sm_post_id, 'sm_start_date', true );
		$sm_end_date                   = get_post_meta( $sm_post_id, 'sm_end_date', true );
		$sm_time_from                  = get_post_meta( $sm_post_id, 'sm_time_from', true );
		$sm_time_to                    = get_post_meta( $sm_post_id, 'sm_time_to', true );
		$sm_select_day_of_week         = get_post_meta( $sm_post_id, 'sm_select_day_of_week', true );
		$cost_rule_match               = get_post_meta( $sm_post_id, 'cost_rule_match', true );
		if ( ! empty( $cost_rule_match ) ) {
			if ( is_serialized( $cost_rule_match ) ) {
				$cost_rule_match = maybe_unserialize( $cost_rule_match );
			} else {
				$cost_rule_match = $cost_rule_match;
			}
			if ( array_key_exists( 'general_rule_match', $cost_rule_match ) ) {
				$general_rule_match = $cost_rule_match['general_rule_match'];
			} else {
				$general_rule_match = 'all';
			}
		} else {
			$general_rule_match = 'all';
		}
		if ( isset( $sm_status ) && 'off' === $sm_status ) {
			return false;
		}
		if ( ! empty( $get_condition_array ) || '' !== $get_condition_array || null !== $get_condition_array ) {
			$country_array         = array();
			$product_array         = array();
			$category_array        = array();
			$tag_array             = array();
			$user_array            = array();
			$cart_total_array      = array();
			$quantity_array        = array();
			$state_array           = array();
			$postcode_array        = array();
			$zone_array            = array();
			$variableproduct_array = array();
			$sku_array             = array();
			$product_qty_array     = array();
			$user_role_array       = array();
			$cart_totalafter_array = array();
			$weight_array          = array();
			$coupon_array          = array();
			$shipping_class_array  = array();
			$payment_methods_array = array();
			$attribute_taxonomies  = wc_get_attribute_taxonomies();
			$atta_name             = array();
			foreach ( $get_condition_array as $key => $value ) {
				if ( array_search( 'country', $value, true ) ) {
					$country_array[ $key ] = $value;
				}
				if ( array_search( 'product', $value, true ) ) {
					$product_array[ $key ] = $value;
				}
				if ( array_search( 'category', $value, true ) ) {
					$category_array[ $key ] = $value;
				}
				if ( array_search( 'tag', $value, true ) ) {
					$tag_array[ $key ] = $value;
				}
				if ( array_search( 'user', $value, true ) ) {
					$user_array[ $key ] = $value;
				}
				if ( array_search( 'cart_total', $value, true ) ) {
					$cart_total_array[ $key ] = $value;
				}
				if ( array_search( 'quantity', $value, true ) ) {
					$quantity_array[ $key ] = $value;
				}
				if ( array_search( 'state', $value, true ) ) {
					$state_array[ $key ] = $value;
				}
				if ( array_search( 'postcode', $value, true ) ) {
					$postcode_array[ $key ] = $value;
				}
				if ( array_search( 'zone', $value, true ) ) {
					$zone_array[ $key ] = $value;
				}
				if ( array_search( 'variableproduct', $value, true ) ) {
					$variableproduct_array[ $key ] = $value;
				}
				if ( array_search( 'sku', $value, true ) ) {
					$sku_array[ $key ] = $value;
				}
				if ( array_search( 'product_qty', $value, true ) ) {
					$product_qty_array[ $key ] = $value;
				}
				if ( $attribute_taxonomies ) {
					foreach ( $attribute_taxonomies as $attribute ) {
						$att_name = wc_attribute_taxonomy_name( $attribute->attribute_name );
						if ( array_search( $att_name, $value, true ) ) {
							$atta_name[ 'att_' . $att_name ] = $value;
						}
					}
				}
				if ( array_search( 'user_role', $value, true ) ) {
					$user_role_array[ $key ] = $value;
				}
				if ( array_search( 'cart_totalafter', $value, true ) ) {
					$cart_totalafter_array[ $key ] = $value;
				}
				if ( array_search( 'weight', $value, true ) ) {
					$weight_array[ $key ] = $value;
				}
				if ( array_search( 'coupon', $value, true ) ) {
					$coupon_array[ $key ] = $value;
				}
				if ( array_search( 'shipping_class', $value, true ) ) {
					$shipping_class_array[ $key ] = $value;
				}
				if ( array_search( 'payment_method', $value, true ) ) {
					$payment_methods_array[ $key ] = $value;
				}
				if ( is_array( $country_array ) && isset( $country_array ) && ! empty( $country_array ) && ! empty( $cart_product_ids_array ) ) {
					$country_passed = $this->afrsfwa_match_country_rules( $country_array, $general_rule_match );
					if ( 'yes' === $country_passed ) {
						$is_passed['has_fee_based_on_country'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_country'] = 'no';
					}
				}
				if ( is_array( $product_array ) && isset( $product_array ) && ! empty( $product_array ) && ! empty( $cart_product_ids_array ) ) {
					$product_passed = $this->afrsfwa_match_simple_products_rule( $cart_product_ids_array, $product_array, $general_rule_match );
					if ( 'yes' === $product_passed ) {
						$is_passed['has_fee_based_on_product'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_product'] = 'no';
					}
				}
				if ( is_array( $category_array ) && isset( $category_array ) && ! empty( $category_array ) && ! empty( $cart_main_product_ids_array ) ) {
					$category_passed = $this->afrsfwa_match_category_rule( $cart_main_product_ids_array, $category_array, $general_rule_match );
					if ( 'yes' === $category_passed ) {
						$is_passed['has_fee_based_on_category'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_category'] = 'no';
					}
				}
				if ( is_array( $tag_array ) && isset( $tag_array ) && ! empty( $tag_array ) && ! empty( $cart_main_product_ids_array ) ) {
					$tag_passed = $this->afrsfwa_match_tag_rule( $cart_main_product_ids_array, $tag_array, $general_rule_match );
					if ( 'yes' === $tag_passed ) {
						$is_passed['has_fee_based_on_tag'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_tag'] = 'no';
					}
				}
				if ( is_array( $user_array ) && isset( $user_array ) && ! empty( $user_array ) && ! empty( $cart_product_ids_array ) ) {
					$user_passed = $this->afrsfwa_match_user_rule( $user_array, $general_rule_match );
					if ( 'yes' === $user_passed ) {
						$is_passed['has_fee_based_on_user'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_user'] = 'no';
					}
				}
				if ( is_array( $cart_total_array ) && isset( $cart_total_array ) && ! empty( $cart_total_array ) && ! empty( $cart_product_ids_array ) ) {
					$cart_total_before_passed = $this->afrsfwa_match_cart_subtotal_before_discount_rule( $wc_curr_version, $cart_total_array, $general_rule_match );
					if ( 'yes' === $cart_total_before_passed ) {
						$is_passed['has_fee_based_on_cart_total_before'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_cart_total_before'] = 'no';
					}
				}
				if ( is_array( $quantity_array ) && isset( $quantity_array ) && ! empty( $quantity_array ) && ! empty( $cart_product_ids_array ) ) {
					$quantity_passed = $this->afrsfwa_match_cart_total_cart_qty_rule( $cart_array, $quantity_array, $general_rule_match );
					if ( 'yes' === $quantity_passed ) {
						$is_passed['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_quantity'] = 'no';
					}
				}
				if ( is_array( $state_array ) && isset( $state_array ) && ! empty( $state_array ) && ! empty( $cart_product_ids_array ) ) {
					$state_passed = $this->afrsfwa_match_state_rules__premium_only( $state_array, $general_rule_match );
					if ( 'yes' === $state_passed ) {
						$is_passed['has_fee_based_on_state'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_state'] = 'no';
					}
				}
				if ( is_array( $postcode_array ) && isset( $postcode_array ) && ! empty( $postcode_array ) && ! empty( $cart_product_ids_array ) ) {
					$postcode_passed = $this->afrsfwa_match_postcode_rules__premium_only( $postcode_array, $general_rule_match );
					if ( 'yes' === $postcode_passed ) {
						$is_passed['has_fee_based_on_postcode'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_postcode'] = 'no';
					}
				}
				if ( is_array( $zone_array ) && isset( $zone_array ) && ! empty( $zone_array ) && ! empty( $cart_product_ids_array ) ) {
					$zone_passed = $this->afrsfwa_match_zone_rules__premium_only( $zone_array, $package, $general_rule_match );
					if ( 'yes' === $zone_passed ) {
						$is_passed['has_fee_based_on_zone'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_zone'] = 'no';
					}
				}
				if ( is_array( $variableproduct_array ) && isset( $variableproduct_array ) && ! empty( $variableproduct_array ) && ! empty( $cart_product_ids_array ) ) {
					$variable_prd_passed = $this->afrsfwa_match_variable_products_rule__premium_only( $cart_product_ids_array, $variableproduct_array, $general_rule_match );
					if ( 'yes' === $variable_prd_passed ) {
						$is_passed['has_fee_based_on_variable_prd'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_variable_prd'] = 'no';
					}
				}
				if ( is_array( $sku_array ) && isset( $sku_array ) && ! empty( $sku_array ) && ! empty( $cart_product_ids_array ) ) {
					$sku_passed = $this->afrsfwa_match_sku_rule__premium_only( $cart_product_ids_array, $sku_array, $general_rule_match );
					if ( 'yes' === $sku_passed ) {
						$is_passed['has_fee_based_on_sku'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_sku'] = 'no';
					}
				}
				if ( is_array( $product_qty_array ) && isset( $product_qty_array ) && ! empty( $product_qty_array ) && ! empty( $cart_product_ids_array ) ) {
					$product_qty_passed = $this->afrsfwa_match_product_qty_rule__premium_only( $sm_post_id, $cart_array, $product_qty_array, $general_rule_match, $sitepress, $default_lang );
					if ( 'yes' === $product_qty_passed ) {
						$is_passed['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_product_qty'] = 'no';
					}
				}
				if ( ! empty( $attribute_taxonomies ) ) {
					if ( is_array( $atta_name ) && isset( $atta_name ) && ! empty( $atta_name ) && ! empty( $cart_product_ids_array ) ) {
						$attribute_passed = $this->afrsfwa_match_attribute_rule__premium_only( $variation_cart_products_array, $atta_name, $general_rule_match );
						if ( 'yes' === $attribute_passed ) {
							$is_passed['has_fee_based_on_product_att'] = 'yes';
						} else {
							$is_passed['has_fee_based_on_product_att'] = 'no';
						}
					}
				}
				if ( is_array( $user_role_array ) && isset( $user_role_array ) && ! empty( $user_role_array ) && ! empty( $cart_product_ids_array ) ) {
					$user_role_passed = $this->afrsfwa_match_user_role_rule__premium_only( $user_role_array, $general_rule_match );
					if ( 'yes' === $user_role_passed ) {
						$is_passed['has_fee_based_on_user_role'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_user_role'] = 'no';
					}
				}
				if ( is_array( $coupon_array ) && isset( $coupon_array ) && ! empty( $coupon_array ) && ! empty( $cart_product_ids_array ) ) {
					$coupon_passed = $this->afrsfwa_match_coupon_rule__premium_only( $wc_curr_version, $coupon_array, $general_rule_match );
					if ( 'yes' === $coupon_passed ) {
						$is_passed['has_fee_based_on_coupon'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_coupon'] = 'no';
					}
				}
				if ( is_array( $cart_totalafter_array ) && isset( $cart_totalafter_array ) && ! empty( $cart_totalafter_array ) && ! empty( $cart_product_ids_array ) ) {
					$cart_total_after_passed = $this->afrsfwa_match_cart_subtotal_after_discount_rule__premium_only( $wc_curr_version, $cart_totalafter_array, $general_rule_match );
					if ( 'yes' === $cart_total_after_passed ) {
						$is_passed['has_fee_based_on_cart_total_after'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_cart_total_after'] = 'no';
					}
				}
				if ( is_array( $weight_array ) && isset( $weight_array ) && ! empty( $weight_array ) && ! empty( $cart_product_ids_array ) ) {
					$weight_passed = $this->afrsfwa_match_cart_total_weight_rule__premium_only( $cart_array, $weight_array, $general_rule_match );
					if ( 'yes' === $weight_passed ) {
						$is_passed['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_weight'] = 'no';
					}
				}
				if ( is_array( $shipping_class_array ) && isset( $shipping_class_array ) && ! empty( $shipping_class_array ) && ! empty( $cart_array ) ) {
					$shipping_class_passed = $this->afrsfwa_match_shipping_class_rule__premium_only( $cart_array, $shipping_class_array, $general_rule_match );
					if ( 'yes' === $shipping_class_passed ) {
						$is_passed['has_fee_based_on_shipping_class'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_shipping_class'] = 'no';
					}
				}
				if ( is_array( $payment_methods_array ) && isset( $payment_methods_array ) && ! empty( $payment_methods_array ) ) {
					$payment_methods_passed = $this->afrsfwa_match_payment_gateway_rule__premium_only( $payment_methods_array, $general_rule_match );
					if ( 'yes' === $payment_methods_passed ) {
						$is_passed['has_fee_based_on_payment'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_payment'] = 'no';
					}
				}
			}
			if ( isset( $is_passed ) && ! empty( $is_passed ) && is_array( $is_passed ) ) {
				$fnispassed = array();
				foreach ( $is_passed as $val ) {
					if ( '' !== $val ) {
						$fnispassed[] = $val;
					}
				}
				if ( 'all' === $general_rule_match ) {
					if ( in_array( 'no', $fnispassed, true ) ) {
						$final_is_passed_general_rule['passed'] = 'no';
					} else {
						$final_is_passed_general_rule['passed'] = 'yes';
					}
				} else {
					if ( in_array( 'yes', $fnispassed, true ) ) {
						$final_is_passed_general_rule['passed'] = 'yes';
					} else {
						$final_is_passed_general_rule['passed'] = 'no';
					}
				}
			}
		}
		if ( empty( $final_is_passed_general_rule ) || '' === $final_is_passed_general_rule || null === $final_is_passed_general_rule ) {
			$new_is_passed['passed'] = 'no';
		} elseif ( ! empty( $final_is_passed_general_rule ) && in_array( 'no', $final_is_passed_general_rule, true ) ) {
			$new_is_passed['passed'] = 'no';
		} elseif ( empty( $final_is_passed_general_rule ) && in_array( '', $final_is_passed_general_rule, true ) ) {
			$new_is_passed['passed'] = 'no';
		} elseif ( ! empty( $final_is_passed_general_rule ) && in_array( 'yes', $final_is_passed_general_rule, true ) ) {
			$new_is_passed['passed'] = 'yes';
		}
		if ( isset( $new_is_passed ) && ! empty( $new_is_passed ) && is_array( $new_is_passed ) ) {
			if ( ! in_array( 'no', $new_is_passed, true ) ) {
				$current_date  = strtotime( gmdate( 'd-m-Y' ) );
				$sm_start_date = ( isset( $sm_start_date ) && ! empty( $sm_start_date ) ) ? strtotime( $sm_start_date ) : '';
				$sm_end_date   = ( isset( $sm_end_date ) && ! empty( $sm_end_date ) ) ? strtotime( $sm_end_date ) : '';
				/* Check for date */
				if ( ( $current_date >= $sm_start_date || '' === $sm_start_date ) && ( $current_date <= $sm_end_date || '' === $sm_end_date ) ) {
					$final_condition_flag['date'] = 'yes';
				} else {
					$final_condition_flag['date'] = 'no';
				}
				/* Check for time */
				$local_nowtimestamp = current_time( 'timestamp' ); // PHPCS: XSS ok.
				$sm_time_from       = ( isset( $sm_time_from ) && ! empty( $sm_time_from ) ) ? strtotime( $sm_time_from ) : '';
				$sm_time_to         = ( isset( $sm_time_to ) && ! empty( $sm_time_to ) ) ? strtotime( $sm_time_to ) : '';
				if ( ( $local_nowtimestamp >= $sm_time_from || '' === $sm_time_from ) && ( $local_nowtimestamp <= $sm_time_to || '' === $sm_time_to ) ) {
					$final_condition_flag['time'] = 'yes';
				} else {
					$final_condition_flag['time'] = 'no';
				}
				/* Check for day */
				$today = strtolower( gmdate( 'D' ) );
				if ( ! empty( $sm_select_day_of_week ) ) {
					if ( in_array( $today, $sm_select_day_of_week, true ) || '' === $sm_select_day_of_week ) {
						$final_condition_flag['day'] = 'yes';
					} else {
						$final_condition_flag['day'] = 'no';
					}
				}
			} else {
				$final_condition_flag[] = 'no';
			}
		}
		if ( empty( $final_condition_flag ) && '' === $final_condition_flag ) {
			return false;
		} elseif ( ! empty( $final_condition_flag ) && in_array( 'no', $final_condition_flag, true ) ) {
			return false;
		} elseif ( empty( $final_condition_flag ) && in_array( '', $final_condition_flag, true ) ) {
			return false;
		} elseif ( ! empty( $final_condition_flag ) && in_array( 'yes', $final_condition_flag, true ) ) {
			return true;
		}
	}
	/**
	 * Match country rules
	 *
	 * @param array  $country_array      List of all country array from rule.
	 * @param string $general_rule_match check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 *
	 * @uses     WC_Customer::get_shipping_country()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 *
	 * @since    4.0
	 */
	public function afrsfwa_match_country_rules( $country_array, $general_rule_match ) {
		$selected_country = WC()->customer->get_shipping_country();
		$is_passed        = array();
		foreach ( $country_array as $key => $country ) {
			if ( 'is_equal_to' === $country['product_fees_conditions_is'] ) {
				if ( ! empty( $country['product_fees_conditions_values'] ) ) {
					if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_country'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_country'] = 'no';
					}
				}
				if ( empty( $country['product_fees_conditions_values'] ) ) {
					$is_passed[ $key ]['has_fee_based_on_country'] = 'yes';
				}
			}
			if ( 'not_in' === $country['product_fees_conditions_is'] ) {
				if ( ! empty( $country['product_fees_conditions_values'] ) ) {
					if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) || in_array( 'all', $country['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_country'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_country'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_country', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match state rules
	 *
	 * @param array  $state_array        List of all states array from rule.
	 * @param string $general_rule_match check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 *
	 * @since    4.0
	 *
	 * @uses     WC_Customer::get_shipping_country()
	 * @uses     WC_Customer::get_shipping_state()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_state_rules__premium_only( $state_array, $general_rule_match ) {
		$country        = WC()->customer->get_shipping_country();
		$state          = WC()->customer->get_shipping_state();
		$selected_state = $country . ':' . $state;
		$is_passed      = array();
		foreach ( $state_array as $key => $get_state ) {
			if ( 'is_equal_to' === $get_state['product_fees_conditions_is'] ) {
				if ( ! empty( $get_state['product_fees_conditions_values'] ) ) {
					if ( in_array( $selected_state, $get_state['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_state'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_state'] = 'no';
					}
				}
			}
			if ( 'not_in' === $get_state['product_fees_conditions_is'] ) {
				if ( ! empty( $get_state['product_fees_conditions_values'] ) ) {
					if ( in_array( $selected_state, $get_state['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_state'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_state'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_state', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match postcode rules
	 *
	 * @param array  $postcode_array     List of all postcodes array from rule.
	 * @param string $general_rule_match check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @uses     WC_Customer::get_shipping_postcode()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 *
	 * @since    4.0
	 */
	public function afrsfwa_match_postcode_rules__premium_only( $postcode_array, $general_rule_match ) {
		$selected_postcode = WC()->customer->get_shipping_postcode();
		$is_passed         = array();
		foreach ( $postcode_array as $key => $postcode ) {
			if ( 'is_equal_to' === $postcode['product_fees_conditions_is'] ) {
				if ( ! empty( $postcode['product_fees_conditions_values'] ) ) {
					$postcodestr        = str_replace( PHP_EOL, '<br/>', trim( $postcode['product_fees_conditions_values'] ) );
					$postcode_val_array = explode( '<br/>', $postcodestr );
					$new_postcode_array = array();
					foreach ( $postcode_val_array as $value ) {
						$new_postcode_array[] = trim( $value );
					}
					if ( in_array( $selected_postcode, $new_postcode_array, true ) ) {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'no';
					}
				}
			}
			if ( 'not_in' === $postcode['product_fees_conditions_is'] ) {
				if ( ! empty( $postcode['product_fees_conditions_values'] ) ) {
					$postcodestr        = str_replace( PHP_EOL, '<br/>', $postcode['product_fees_conditions_values'] );
					$postcode_val_array = explode( '<br/>', $postcodestr );
					if ( in_array( $selected_postcode, $postcode_val_array, true ) ) {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_postcode', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match zone rules
	 *
	 * @param array        $zone_array         List of all zones array from rule.
	 * @param array|object $package            List of all shipping package.
	 * @param string       $general_rule_match check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @since    4.0
	 *
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_zone_rules__premium_only( $zone_array, $package, $general_rule_match ) {
		$is_passed = array();
		foreach ( $zone_array as $key => $zone ) {
			if ( 'is_equal_to' === $zone['product_fees_conditions_is'] ) {
				if ( ! empty( $zone['product_fees_conditions_values'] ) ) {
					$get_zonelist = $this->afrsfwa_check_zone_available__premium_only( $package, $zone['product_fees_conditions_values'] );
					if ( in_array( $get_zonelist, $zone['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_zone'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_zone'] = 'no';
					}
				}
			}
			if ( 'not_in' === $zone['product_fees_conditions_is'] ) {
				if ( ! empty( $zone['product_fees_conditions_values'] ) ) {
					$get_zonelist = $this->afrsfwa_check_zone_available__premium_only( $package, $zone['product_fees_conditions_values'] );
					if ( in_array( $get_zonelist, $zone['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_zone'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_zone'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_zone', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match variable products rules
	 *
	 * @param array  $cart_product_ids_array List of all products id from cart.
	 * @param array  $variableproduct_array  List of all variable products array from rule.
	 * @param string $general_rule_match     check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @since    4.0
	 *
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_variable_products_rule__premium_only( $cart_product_ids_array, $variableproduct_array, $general_rule_match ) {
		$is_passed      = array();
		$passed_product = array();
		foreach ( $variableproduct_array as $key => $product ) {
			if ( 'is_equal_to' === $product['product_fees_conditions_is'] ) {
				if ( ! empty( $product['product_fees_conditions_values'] ) ) {
					foreach ( $product['product_fees_conditions_values'] as $product_id ) {
						settype( $product_id, 'integer' );
						$passed_product[] = $product_id;
						if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $product['product_fees_conditions_is'] ) {
				if ( ! empty( $product['product_fees_conditions_values'] ) ) {
					foreach ( $product['product_fees_conditions_values'] as $product_id ) {
						settype( $product_id, 'integer' );
						if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_product', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match simple products rules
	 *
	 * @param array  $cart_product_ids_array List of all products id from cart.
	 * @param array  $product_array          List of all products array from rule.
	 * @param string $general_rule_match     check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @since    4.0
	 *
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_simple_products_rule( $cart_product_ids_array, $product_array, $general_rule_match ) {
		$is_passed = array();
		foreach ( $product_array as $key => $product ) {
			if ( 'is_equal_to' === $product['product_fees_conditions_is'] ) {
				if ( ! empty( $product['product_fees_conditions_values'] ) ) {
					foreach ( $product['product_fees_conditions_values'] as $product_id ) {
						settype( $product_id, 'integer' );
						if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $product['product_fees_conditions_is'] ) {
				if ( ! empty( $product['product_fees_conditions_values'] ) ) {
					foreach ( $product['product_fees_conditions_values'] as $product_id ) {
						settype( $product_id, 'integer' );
						if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_product', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match category rules
	 *
	 * @param array  $cart_product_ids_array List of all products id from cart.
	 * @param array  $category_array         List of all categories array from rule.
	 * @param string $general_rule_match     check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @since    4.0
	 *
	 * @uses     wp_get_post_terms()
	 * @uses     afrsfwa_array_flatten()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_category_rule( $cart_product_ids_array, $category_array, $general_rule_match ) {
		$is_passed              = array();
		$cart_category_id_array = array();
		foreach ( $cart_product_ids_array as $product ) {
			$cart_product_category = wp_get_post_terms( $product, 'product_cat', array( 'fields' => 'ids' ) );
			if ( isset( $cart_product_category ) && ! empty( $cart_product_category ) && is_array( $cart_product_category ) ) {
				$cart_category_id_array[] = $cart_product_category;
			}
		}
		$get_cat_all = array_unique( $this->afrsfwa_array_flatten( $cart_category_id_array ) );
		foreach ( $category_array as $key => $category ) {
			if ( 'is_equal_to' === $category['product_fees_conditions_is'] ) {
				if ( ! empty( $category['product_fees_conditions_values'] ) ) {
					foreach ( $category['product_fees_conditions_values'] as $category_id ) {
						settype( $category_id, 'integer' );
						if ( in_array( $category_id, $get_cat_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $category['product_fees_conditions_is'] ) {
				if ( ! empty( $category['product_fees_conditions_values'] ) ) {
					foreach ( $category['product_fees_conditions_values'] as $category_id ) {
						settype( $category_id, 'integer' );
						if ( in_array( $category_id, $get_cat_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_category', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match tag rules
	 *
	 * @param array  $cart_product_ids_array List of all products id from cart.
	 * @param array  $tag_array              List of all tag array from rule.
	 * @param string $general_rule_match     check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @since    4.0
	 *
	 * @uses     wp_get_post_terms()
	 * @uses     afrsfwa_array_flatten()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_tag_rule( $cart_product_ids_array, $tag_array, $general_rule_match ) {
		$tagid     = array();
		$is_passed = array();
		foreach ( $cart_product_ids_array as $product ) {
			$cart_product_tag = wp_get_post_terms( $product, 'product_tag', array( 'fields' => 'ids' ) );
			if ( isset( $cart_product_tag ) && ! empty( $cart_product_tag ) && is_array( $cart_product_tag ) ) {
				$tagid[] = $cart_product_tag;
			}
		}
		$get_tag_all = array_unique( $this->afrsfwa_array_flatten( $tagid ) );
		foreach ( $tag_array as $key => $tag ) {
			if ( 'is_equal_to' === $tag['product_fees_conditions_is'] ) {
				if ( ! empty( $tag['product_fees_conditions_values'] ) ) {
					foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
						settype( $tag_id, 'integer' );
						if ( in_array( $tag_id, $get_tag_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $tag['product_fees_conditions_is'] ) {
				if ( ! empty( $tag['product_fees_conditions_values'] ) ) {
					foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
						settype( $tag_id, 'integer' );
						if ( in_array( $tag_id, $get_tag_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_tag', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match sku rules
	 *
	 * @param array  $cart_product_ids_array List of all products id from cart.
	 * @param array  $sku_array              List of all sku array from rule.
	 * @param string $general_rule_match     check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @since    4.0
	 *
	 * @uses     afrsfwa_array_flatten()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_sku_rule__premium_only( $cart_product_ids_array, $sku_array, $general_rule_match ) {
		$sku_ids    = array();
		$is_passed  = array();
		$passed_sku = array();
		if ( ! empty( $cart_product_ids_array ) ) {
			foreach ( $cart_product_ids_array as $product_id ) {
				$product_sku = get_post_meta( $product_id, '_sku', true );
				if ( isset( $product_sku ) && ! empty( $product_sku ) ) {
					$sku_ids[] = $product_sku;
				}
			}
		}
		$get_all_unique_sku = array_unique( $this->afrsfwa_array_flatten( $sku_ids ) );
		foreach ( $sku_array as $key => $sku ) {
			if ( 'is_equal_to' === $sku['product_fees_conditions_is'] ) {
				if ( ! empty( $sku['product_fees_conditions_values'] ) ) {
					foreach ( $sku['product_fees_conditions_values'] as $sku_name ) {
						$passed_sku[] = $sku_name;
						if ( in_array( $sku_name, $get_all_unique_sku, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_sku'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_sku'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $sku['product_fees_conditions_is'] ) {
				if ( ! empty( $sku['product_fees_conditions_values'] ) ) {
					foreach ( $sku['product_fees_conditions_values'] as $sku_name ) {
						if ( in_array( $sku_name, $get_all_unique_sku, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_sku'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_sku'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_sku', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match specific product quantity rules
	 *
	 * @param int    $shipping_method_id_val shipping method ID.
	 * @param array  $cart_array             Cart Array.
	 * @param array  $product_qty_array      Product qty array.
	 * @param string $general_rule_match     General rule match.
	 * @param string $sitepress              Get sitepress.
	 * @param string $default_lang           Default language id.
	 *
	 * @return string $main_is_passed chekced rule is passed or not.
	 * @since    3.4
	 */
	public function afrsfwa_match_product_qty_rule__premium_only( $shipping_method_id_val, $cart_array, $product_qty_array, $general_rule_match, $sitepress, $default_lang ) {
		$products_based_qty = 0;
		/* Get all the quantity of specific selected products/categories/tag/sku */
		$products_based_qty = $this->afrsfwa_shipping_fees_get_per_product_qty__premium_only( $shipping_method_id_val, $cart_array, $products_based_qty, $sitepress, $default_lang );
		/** Check if selected product specific quantity is match with cart quantity or not */
		$main_is_passed = $this->afrsfwa_match_product_based_qty_rule( $products_based_qty, $product_qty_array, $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Count qty for product based and cart based when apply per qty option is on. This rule will apply when advance pricing rule will disable
	 *
	 * @param int    $shipping_method_id_val shipping method ID.
	 * @param array  $cart_array             Cart Array.
	 * @param array  $products_based_qty     Product qty array.
	 * @param string $sitepress              Get sitepress.
	 * @param string $default_lang           Default language id.
	 *
	 * @return int $total_products_based_qty Total product based qty.
	 * @uses  get_post_meta()
	 * @uses  get_post()
	 * @uses  get_terms()
	 *
	 * @since 3.4
	 */
	public function afrsfwa_shipping_fees_get_per_product_qty__premium_only( $shipping_method_id_val, $cart_array, $products_based_qty, $sitepress, $default_lang ) {
		$product_fees_array = get_post_meta( $shipping_method_id_val, 'sm_metabox', true );
		$all_rule_check     = array();
		if ( ! empty( $product_fees_array ) ) {
			foreach ( $product_fees_array as $condition ) {
				if ( array_search( 'product', $condition, true ) ) {
					$site_product_id           = '';
					$cart_final_products_array = array();
					// Product Condition Start.
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
					// Product Condition End.
				}
				if ( array_search( 'variableproduct', $condition, true ) ) {
					$site_product_id               = '';
					$cart_final_var_products_array = array();
					// Variable Product Condition Start.
					if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							foreach ( $condition['product_fees_conditions_values'] as $product_id ) {
								settype( $product_id, 'integer' );
								foreach ( $cart_array as $value ) {
									if ( ! ( $value['data']->is_virtual() ) && ( false === strpos($value['data']->get_type(), 'bundle') ) ) {
										if ( ! empty( $sitepress ) ) {
											$site_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', true, $default_lang );
										} else {
											$site_product_id = $value['variation_id'];
										}
										if ( $product_id === $site_product_id ) {
											if ( array_key_exists( $site_product_id, $cart_final_var_products_array ) ) {
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
									if ( ! ( $value['data']->is_virtual() ) && ( false === strpos($value['data']->get_type(), 'bundle') ) ) {
										if ( ! empty( $sitepress ) ) {
											$site_product_id = apply_filters( 'wpml_object_id', $product_id_lan, 'product', true, $default_lang );
										} else {
											$site_product_id = $product_id_lan;
										}
										if ( $product_id !== $site_product_id ) {
											if ( array_key_exists( $site_product_id, $cart_final_var_products_array ) ) {
												$cart_final_var_products_array[ $site_product_id ] += $value['quantity'];
											} else {
												$cart_final_var_products_array[ $site_product_id ] = $value['quantity'];
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
					// Variable Product Condition End.
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
							$id          = ( ! empty( $product['variation_id'] ) && 0 !== $woo_cart_item['variation_id'] ) ? $product['variation_id'] : $product['product_id'];
							$get_product = wc_get_product( $id );
							if ( ! ( $get_product->is_virtual( 'yes' ) ) && ( false === strpos($get_product->get_type(), 'bundle') ) ) {
								// if ( in_array( $cart_product_category[0], $final_cart_products_cats_ids, true ) ) {
								// 	if( array_key_exists($cart_product_category[0], $cart_final_cat_products_array) && array_key_exists($id, $cart_final_cat_products_array[ $cart_product_category[0] ]) ){
								// 		$cart_final_cat_products_array[ $cart_product_category[0] ][ $id ] += $product['quantity'];
								// 	} else {
								// 		$cart_final_cat_products_array[ $cart_product_category[0] ][ $id ] = $product['quantity'];
								// 	}
								// }
								if ( array_intersect( $cart_product_category, $final_cart_products_cats_ids, true ) ) {
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
				if ( array_search( 'sku', $condition, true ) ) {
					$cart_final_skus_array = array();
					// Product Condition Start.
					if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							foreach ( $condition['product_fees_conditions_values'] as $product_sku ) {
								settype( $product_sku, 'string' );
								foreach ( $cart_array as $value ) {
									/** Custom code here */
									if ( ! empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ) {
										$product_id_lan = $value['variation_id'];
									} else {
										$product_id_lan = $value['product_id'];
									}
									if ( ! ( $value['data']->is_virtual() ) && ( false === strpos($value['data']->get_type(), 'bundle') ) ) {
										$cart_product_sku = get_post_meta( $product_id_lan, '_sku', true );
										if ( $product_sku === $cart_product_sku ) {
											if ( array_key_exists( $product_id_lan, $cart_final_skus_array ) ) {
												$cart_final_skus_array[ $product_id_lan ] += $value;
											} else {
												$cart_final_skus_array[ $product_id_lan ] = $value;
											}
										}
									}
								}
							}
						}
					} elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							foreach ( $condition['product_fees_conditions_values'] as $product_sku ) {
								settype( $product_sku, 'string' );
								foreach ( $cart_array as $value ) {
									/** Custom code here. */
									if ( ! empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ) {
										$product_id_lan = $value['variation_id'];
									} else {
										$product_id_lan = $value['product_id'];
									}
									if ( ! ( $value['data']->is_virtual() ) && ( false === strpos($value['data']->get_type(), 'bundle') ) ) {
										$cart_product_sku = get_post_meta( $product_id_lan, '_sku', true );
										if ( $product_sku !== $cart_product_sku ) {
											if ( array_key_exists( $product_id_lan, $cart_final_skus_array ) ) {
												$cart_final_skus_array[ $product_id_lan ] += $value;
											} else {
												$cart_final_skus_array[ $product_id_lan ] = $value;
											}
										}
									}
								}
							}
						}
					}
					if ( ! empty( $cart_final_skus_array ) ) {
						foreach ( $cart_final_skus_array as $prd_id => $cart_item ) {
							$all_rule_check[ $prd_id ] = $cart_item['quantity'];
						}
					}
					// Product Condition End.
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
					/** Custom code here. */
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
	 * Match rule based on product qty
	 *
	 * @param int    $product_qty Product qty.
	 * @param array  $quantity_array Quantity array.
	 * @param string $general_rule_match General rule match.
	 *
	 * @return string $main_is_passed Check rule passed.
	 * @since    3.4
	 *
	 * @uses     WC_Cart::get_cart()
	 */
	public function afrsfwa_match_product_based_qty_rule( $product_qty, $quantity_array, $general_rule_match ) {
		$quantity_total = 0;
		if ( 0 < $product_qty ) {
			$quantity_total = $product_qty;
		}
		$is_passed = array();
		foreach ( $quantity_array as $key => $quantity ) {
			settype( $quantity['product_fees_conditions_values'], 'integer' );
			if ( 'is_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] >= $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'less_then' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] > $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] <= $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] < $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'not_in' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_product_qty', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match attribute rules
	 *
	 * @param array  $cart_product_ids_array List of all products id from cart.
	 * @param array  $att_name               List of all attribute array from rule.
	 * @param string $general_rule_match     check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @since    4.0
	 *
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_attribute_rule__premium_only( $cart_product_ids_array, $att_name, $general_rule_match ) {
		$is_passed      = array();
		$passed_product = array();
		foreach ( $att_name as $key => $product ) {
			if ( 'is_equal_to' === $product['product_fees_conditions_is'] ) {
				if ( ! empty( $product['product_fees_conditions_values'] ) ) {
					foreach ( $product['product_fees_conditions_values'] as $product_id ) {
						$passed_product[] = $product_id;
						if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product_att'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product_att'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $product['product_fees_conditions_is'] ) {
				if ( ! empty( $product['product_fees_conditions_values'] ) ) {
					foreach ( $product['product_fees_conditions_values'] as $product_id ) {
						if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product_att'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product_att'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_product_att', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match user rules
	 *
	 * @param array  $user_array         List of all user array from rule.
	 * @param string $general_rule_match check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @uses     get_current_user_id()
	 * @since    4.0
	 *
	 * @uses     is_user_logged_in()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_user_rule( $user_array, $general_rule_match ) {
		if ( ! is_user_logged_in() ) {
			return false;
		}
		$current_user_id = get_current_user_id();
		$is_passed       = array();
		foreach ( $user_array as $key => $user ) {
			$user['product_fees_conditions_values'] = array_map( 'intval', $user['product_fees_conditions_values'] );
			if ( 'is_equal_to' === $user['product_fees_conditions_is'] ) {
				if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_user'] = 'yes';
				} else {
					$is_passed[ $key ]['has_fee_based_on_user'] = 'no';
				}
			}
			if ( 'not_in' === $user['product_fees_conditions_is'] ) {
				if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_user'] = 'no';
				} else {
					$is_passed[ $key ]['has_fee_based_on_user'] = 'yes';
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_user', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match user role rules
	 *
	 * @param array  $user_role_array    List of all user roles array from rule.
	 * @param string $general_rule_match check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @uses     is_user_logged_in()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 *
	 * @since    4.0
	 */
	public function afrsfwa_match_user_role_rule__premium_only( $user_role_array, $general_rule_match ) {
		global $current_user;
		if ( is_user_logged_in() ) {
			$current_user_role = $current_user->roles[0];
		} else {
			$current_user_role = 'guest';
		}
		$is_passed = array();
		foreach ( $user_role_array as $key => $user_role ) {
			if ( 'is_equal_to' === $user_role['product_fees_conditions_is'] ) {
				if ( in_array( $current_user_role, $user_role['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_user_role'] = 'yes';
				} else {
					$is_passed[ $key ]['has_fee_based_on_user_role'] = 'no';
				}
			}
			if ( 'not_in' === $user_role['product_fees_conditions_is'] ) {
				if ( in_array( $current_user_role, $user_role['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_user_role'] = 'no';
				} else {
					$is_passed[ $key ]['has_fee_based_on_user_role'] = 'yes';
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_user_role', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match coupon role rules
	 *
	 * @param string $wc_curr_version    Get current WooCommerce version.
	 * @param array  $coupon_array       List of all coupon array from rule.
	 * @param string $general_rule_match check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @uses     WC_Cart::get_coupons()
	 * @uses     WC_Coupon::is_valid()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 *
	 * @since    4.0
	 */
	public function afrsfwa_match_coupon_rule__premium_only( $wc_curr_version, $coupon_array, $general_rule_match ) {
		global $woocommerce;
		if ( $wc_curr_version >= 3.0 ) {
			$cart_coupon = WC()->cart->get_coupons();
		} else {
			$cart_coupon = isset( $woocommerce->cart->coupons ) && ! empty( $woocommerce->cart->coupons ) ? $woocommerce->cart->coupons : array();
		}
		$coupon_id_array = array();
		$is_passed       = array();
		foreach ( $cart_coupon as $sub_cart_coupon ) {
			if ( $sub_cart_coupon->is_valid() && isset( $sub_cart_coupon ) && ! empty( $sub_cart_coupon ) ) {
				if ( $wc_curr_version >= 3.0 ) {
					$coupon_id_array[] = $sub_cart_coupon->get_id();
				} else {
					$coupon_id_array[] = $sub_cart_coupon->id;
				}
			}
		}
		foreach ( $coupon_array as $key => $coupon ) {
			if ( 'is_equal_to' === $coupon['product_fees_conditions_is'] ) {
				if ( ! empty( $coupon['product_fees_conditions_values'] ) ) {
					foreach ( $coupon['product_fees_conditions_values'] as $coupon_id ) {
						settype( $coupon_id, 'integer' );
						if ( in_array( $coupon_id, $coupon_id_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_coupon'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_coupon'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $coupon['product_fees_conditions_is'] ) {
				if ( ! empty( $coupon['product_fees_conditions_values'] ) ) {
					foreach ( $coupon['product_fees_conditions_values'] as $coupon_id ) {
						settype( $coupon_id, 'integer' );
						if ( in_array( $coupon_id, $coupon_id_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_coupon'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_coupon'] = 'yes';
						}
					}
				}
				if ( empty( $cart_coupon ) ) {
					return 'yes';
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_coupon', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match rule based on cart subtotal before discount
	 *
	 * @param string $wc_curr_version    Get current WooCommerce version.
	 * @param array  $cart_total_array   List of all cart's product id array from rule.
	 * @param string $general_rule_match check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 *
	 * @since    4.0
	 *
	 * @uses     afrsfwa_get_cart_subtotal()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_cart_subtotal_before_discount_rule( $wc_curr_version, $cart_total_array, $general_rule_match ) {
		global $woocommerce, $woocommerce_wpml;
		if ( $wc_curr_version >= 3.0 ) {
			$total = $this->afrsfwa_get_cart_subtotal();
		} else {
			$total = $woocommerce->cart->subtotal;
		}
		if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
			$new_total = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $total );
		} else {
			$new_total = $total;
		}
		$is_passed = array();
		foreach ( $cart_total_array as $key => $cart_total ) {
			settype( $cart_total['product_fees_conditions_values'], 'float' );
			if ( 'is_equal_to' === $cart_total['product_fees_conditions_is'] ) {
				if ( ! empty( $cart_total['product_fees_conditions_values'] ) ) {
					if ( $cart_total['product_fees_conditions_values'] === $new_total ) {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $cart_total['product_fees_conditions_is'] ) {
				if ( ! empty( $cart_total['product_fees_conditions_values'] ) ) {
					if ( $cart_total['product_fees_conditions_values'] >= $new_total ) {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
					}
				}
			}
			if ( 'less_then' === $cart_total['product_fees_conditions_is'] ) {
				if ( ! empty( $cart_total['product_fees_conditions_values'] ) ) {
					if ( $cart_total['product_fees_conditions_values'] > $new_total ) {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $cart_total['product_fees_conditions_is'] ) {
				if ( ! empty( $cart_total['product_fees_conditions_values'] ) ) {
					if ( $cart_total['product_fees_conditions_values'] <= $new_total ) {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $cart_total['product_fees_conditions_is'] ) {
				$cart_total['product_fees_conditions_values'];
				if ( ! empty( $cart_total['product_fees_conditions_values'] ) ) {
					if ( $cart_total['product_fees_conditions_values'] < $new_total ) {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
					}
				}
			}
			if ( 'not_in' === $cart_total['product_fees_conditions_is'] ) {
				if ( ! empty( $cart_total['product_fees_conditions_values'] ) ) {
					if ( $new_total === $cart_total['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_cart_total', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match rule based on cart subtotal after discount
	 *
	 * @param string $wc_curr_version       Get current WooCommerce version.
	 * @param array  $cart_totalafter_array List of all cart's product id array from rule.
	 * @param string $general_rule_match    check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @since    4.0
	 *
	 * @uses     afrsfwa_remove_currency_symbol()
	 * @uses     WC_Cart::get_total_discount()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 * @uses     afrsfwa_get_cart_subtotal()
	 */
	public function afrsfwa_match_cart_subtotal_after_discount_rule__premium_only( $wc_curr_version, $cart_totalafter_array, $general_rule_match ) {
		global $woocommerce, $woocommerce_wpml;
		if ( $wc_curr_version >= 3.0 ) {
			$totalprice = $this->afrsfwa_get_cart_subtotal();
		} else {
			$totalprice = $this->afrsfwa_remove_currency_symbol( $woocommerce->cart->get_cart_subtotal() );
		}
		if ( $wc_curr_version >= 3.0 ) {
			$totaldisc = $this->afrsfwa_remove_currency_symbol( WC()->cart->get_total_discount() );
		} else {
			$totaldisc = $this->afrsfwa_remove_currency_symbol( $woocommerce->cart->get_total_discount() );
		}
		$is_passed = array();
		if ( '' !== $totaldisc && 0.0 !== $totaldisc ) {
			$resultprice = $totalprice - $totaldisc;
			if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
				$new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $resultprice );
			} else {
				$new_resultprice = $resultprice;
			}
			foreach ( $cart_totalafter_array as $key => $cart_totalafter ) {
				settype( $cart_totalafter['product_fees_conditions_values'], 'float' );
				if ( 'is_equal_to' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] === $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'less_equal_to' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] >= $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'less_then' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] > $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'greater_equal_to' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] <= $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'greater_then' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] < $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'not_in' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $new_resultprice === $cart_totalafter['product_fees_conditions_values'] ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_cart_totalafter', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match rule based on total cart quantity
	 *
	 * @param array  $cart_array         all cart product's id array.
	 * @param array  $quantity_array     List of quantity array from rule.
	 * @param string $general_rule_match check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @since    4.0
	 *
	 * @uses     WC_Cart::get_cart()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_cart_total_cart_qty_rule( $cart_array, $quantity_array, $general_rule_match ) {
		$quantity_total = 0;
		foreach ( $cart_array as $woo_cart_item ) {
			if ( ! ( $woo_cart_item['data']->is_virtual() ) && ( false === strpos($woo_cart_item['data']->get_type(), 'bundle') ) ) {
				$quantity_total += $woo_cart_item['quantity'];
			}
		}
		$is_passed = array();
		foreach ( $quantity_array as $key => $quantity ) {
			settype( $quantity['product_fees_conditions_values'], 'integer' );
			if ( 'is_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] >= $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'less_then' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] > $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] <= $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] < $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'not_in' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_quantity', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match rule based on total cart weight
	 *
	 * @param array  $cart_array         all cart product's id array.
	 * @param array  $weight_array       List of weight array from rule.
	 * @param string $general_rule_match check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @since    4.0
	 *
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_cart_total_weight_rule__premium_only( $cart_array, $weight_array, $general_rule_match ) {
		$weight_total = 0;
		foreach ( $cart_array as $woo_cart_item ) {
			if ( ! ( $woo_cart_item['data']->is_virtual() ) && ( false === strpos($woo_cart_item['data']->get_type(), 'bundle') ) ) {
				if ( ! empty( $woo_cart_item['variation_id'] ) && 0 !== $woo_cart_item['variation_id'] ) {
					$product_id_lan = $woo_cart_item['variation_id'];
				} else {
					$product_id_lan = $woo_cart_item['product_id'];
				}
				$_product = wc_get_product( $product_id_lan );
				if ( ! ( $_product->is_virtual( 'yes' ) ) && ( false === strpos($_product->get_type(), 'bundle') ) ) {
					$weight_total += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
				}
			}
		}
		$is_passed = array();
		foreach ( $weight_array as $key => $weight ) {
			settype( $weight_total, 'float' );
			settype( $weight['product_fees_conditions_values'], 'float' );
			if ( 'is_equal_to' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight_total === $weight['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight['product_fees_conditions_values'] >= $weight_total ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'less_then' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight['product_fees_conditions_values'] > $weight_total ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight['product_fees_conditions_values'] <= $weight_total ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight_total > $weight['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'not_in' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight_total === $weight['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_weight', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match rule based on shipping class
	 *
	 * @param array  $cart_array All cart product's id array.
	 * @param array  $shipping_class_array   List of all shipping class's array.
	 * @param string $general_rule_match     check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @uses     afrsfwa_array_flatten()
	 *
	 * @since    4.0
	 *
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_shipping_class_rule__premium_only( $cart_array, $shipping_class_array, $general_rule_match ) {
		$shippingclass         = array();
		$passed_shipping_class = array();
		foreach ( $cart_array as $product ) {
			$id          = ( ! empty( $product['variation_id'] ) && 0 !== $woo_cart_item['variation_id'] ) ? $product['variation_id'] : $product['product_id'];
			$get_product = wc_get_product( $id );
			if ( ! ( $get_product->is_virtual( 'yes' ) ) ) {
				$products_shipping_class = $get_product->get_shipping_class();
				if ( ! empty( $products_shipping_class ) ) {
					$shippingclass[] = $products_shipping_class;
				}
			}
		}
		$shipping_class_id_array = array();
		if ( ! empty( $shippingclass ) ) {
			foreach ( $shippingclass as $shipping_slug ) {
				$product_shipping_class = get_term_by( 'slug', $shipping_slug, 'product_shipping_class' );
				if ( $product_shipping_class ) {
					$shipping_class_id_array[] = $product_shipping_class->term_id;
				}
			}
		}
		$get_shipping_class_all = array_unique( $this->afrsfwa_array_flatten( $shipping_class_id_array ) );
		$is_passed              = array();
		foreach ( $shipping_class_array as $key => $shipping_class ) {
			if ( 'is_equal_to' === $shipping_class['product_fees_conditions_is'] ) {
				if ( ! empty( $shipping_class['product_fees_conditions_values'] ) ) {
					foreach ( $shipping_class['product_fees_conditions_values'] as $shipping_class_slug ) {
						$shipping_class_term_id = get_term_by( 'slug', $shipping_class_slug, 'product_shipping_class' );
						$shipping_class_id      = $shipping_class_term_id->term_id;
						settype( $shipping_class_id, 'integer' );
						$passed_shipping_class[] = $shipping_class_id;
						if ( in_array( $shipping_class_id, $get_shipping_class_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $shipping_class['product_fees_conditions_is'] ) {
				if ( ! empty( $shipping_class['product_fees_conditions_values'] ) ) {
					foreach ( $shipping_class['product_fees_conditions_values'] as $shipping_class_slug ) {
						$shipping_class_term_id = get_term_by( 'slug', $shipping_class_slug, 'product_shipping_class' );
						$shipping_class_id      = $shipping_class_term_id->term_id;
						settype( $shipping_class_id, 'integer' );
						if ( in_array( $shipping_class_id, $get_shipping_class_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_shipping_class', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Match rule based on payment gateway
	 *
	 * @param array  $payment_methods_array List of payment method from rule.
	 * @param string $general_rule_match    check general rule for any or all rule.
	 *
	 * @return array $main_is_passed
	 * @since    4.0
	 *
	 * @uses     WC_Session::get()
	 * @uses     afrsfwa_check_all_passed_general_rule()
	 */
	public function afrsfwa_match_payment_gateway_rule__premium_only( $payment_methods_array, $general_rule_match ) {
		$is_passed             = array();
		$chosen_payment_method = WC()->session->get( 'chosen_payment_method' );
		foreach ( $payment_methods_array as $key => $payment ) {
			if ( 'is_equal_to' === $payment['product_fees_conditions_is'] ) {
				if ( in_array( $chosen_payment_method, $payment['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_payment'] = 'yes';
				} else {
					$is_passed[ $key ]['has_fee_based_on_payment'] = 'no';
				}
			}
			if ( 'not_in' === $payment['product_fees_conditions_is'] ) {
				if ( in_array( $chosen_payment_method, $payment['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_payment'] = 'no';
				} else {
					$is_passed[ $key ]['has_fee_based_on_payment'] = 'yes';
				}
			}
		}
		$main_is_passed = $this->afrsfwa_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_payment', $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Find unique id based on given array
	 *
	 * @param array  $is_passed          fetch all matched rule.
	 * @param string $has_fee_based      check matched key.
	 * @param string $general_rule_match check general rule for any or all rule.
	 *
	 * @return string $main_is_passed
	 * @since    3.6
	 */
	public function afrsfwa_check_all_passed_general_rule( $is_passed, $has_fee_based, $general_rule_match ) {
		$main_is_passed = 'no';
		$flag           = array();
		if ( ! empty( $is_passed ) ) {
			foreach ( $is_passed as $key => $is_passed_value ) {
				if ( 'yes' === $is_passed_value[ $has_fee_based ] ) {
					$flag[ $key ] = true;
				} else {
					$flag[ $key ] = false;
				}
			}
			if ( 'any' === $general_rule_match ) {
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
		return $main_is_passed;
	}
	/**
	 * Find unique id based on given array
	 *
	 * @param array $array Find unique value.
	 *
	 * @return array $result if $array is empty it will return false otherwise return array as $result
	 * @since    1.0.0
	 */
	public function afrsfwa_array_flatten( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}
		$result = array();
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$result = array_merge( $result, $this->afrsfwa_array_flatten( $value ) );
			} else {
				$result[ $key ] = $value;
			}
		}
		return $result;
	}
	/**
	 * Get cart subtotal
	 *
	 * @return float $subtotal
	 *
	 * @since    3.6
	 */
	public function afrsfwa_get_cart_subtotal() {
		$get_customer            = WC()->cart->get_customer();
		$get_customer_vat_exempt = WC()->customer->get_is_vat_exempt();
		$tax_display_cart        = WC()->cart->tax_display_cart;
		$wc_prices_include_tax   = wc_prices_include_tax();
		$tax_enable              = wc_tax_enabled();
		$cart_subtotal           = 0;
		if ( true === $tax_enable ) {
			if ( true === $wc_prices_include_tax ) {
				if ( 'incl' === $tax_display_cart && ! ( $get_customer && $get_customer_vat_exempt ) ) {
					$cart_subtotal += WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
				} else {
					$cart_subtotal += WC()->cart->get_subtotal();
				}
			} else {
				if ( 'incl' === $tax_display_cart && ! ( $get_customer && $get_customer_vat_exempt ) ) {
					$cart_subtotal += WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
				} else {
					$cart_subtotal += WC()->cart->get_subtotal();
				}
			}
		} else {
			$cart_subtotal += WC()->cart->get_subtotal();
		}
		return $cart_subtotal;
	}
	/**
	 * Find a matching zone for a given package.
	 *
	 * @param array|object $package                 Shipping package.
	 * @param array        $available_zone_id_array Matched available zone id.
	 *
	 * @return int $return_zone_id
	 * @uses   afrsfwa_wc_make_numeric_postcode__premium_only()
	 *
	 * @since  3.0.0
	 */
	public function afrsfwa_check_zone_available__premium_only( $package, $available_zone_id_array ) {
		$country = $package['destination']['country'];
		if ( ! empty( $package['destination']['state'] ) && '' !== $package['destination']['state'] ) {
			$state = $country . ':' . $package['destination']['state'];
		} else {
			$state = '';
		}
		$postcode          = strtoupper( $package['destination']['postcode'] );
		$valid_postcodes   = array( '*', $postcode );
		$postcode_length   = strlen( $postcode );
		$wildcard_postcode = $postcode;
		for ( $i = 0; $i < $postcode_length; $i ++ ) {
			$wildcard_postcode = substr( $wildcard_postcode, 0, - 1 );
			$valid_postcodes[] = $wildcard_postcode . '*';
		}
		$return_zone_id = '';
		foreach ( $available_zone_id_array as $available_zone_id ) {
			$postcode_ranges = new WP_Query(
				array(
					'post_type'      => self::AFRSFWA_ZONE_POST_TYPE,
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
					'post__in'       =>
						array(
							$available_zone_id,
						),
				)
			);
			$location_code   = array();
			foreach ( $postcode_ranges->posts as $postcode_ranges_value ) {
				$postcode_ranges_location_code               = get_post_meta( $postcode_ranges_value->ID, 'location_code', false );
				$zone_type                                   = get_post_meta( $postcode_ranges_value->ID, 'zone_type', true );
				$location_code[ $postcode_ranges_value->ID ] = $postcode_ranges_location_code;
				foreach ( $postcode_ranges_location_code as $location_code_sub_val ) {
					$country_array = array();
					$state_array   = array();
					foreach ( $location_code_sub_val as $location_country_state => $location_code_postcode_val ) {
						$location_code_postcode_val_ex = explode( PHP_EOL, $location_code_postcode_val[0] );
						if ( 'postcodes' === $zone_type ) {
							if ( false !== strpos( $location_country_state, ':' ) ) {
								$location_country_state_explode = explode( ':', $location_country_state );
							} else {
								$state_array = array();
							}
							if ( ! empty( $location_country_state_explode ) ) {
								if ( array_key_exists( '0', $location_country_state_explode ) ) {
									$country_array[] = $location_country_state_explode[0];
								}
							} else {
								$country_array[] = $location_country_state;
							}
							if ( ! empty( $location_country_state_explode ) ) {
								if ( array_key_exists( '1', $location_country_state_explode ) ) {
									$state_array[] = $location_country_state;
								}
							}
							foreach ( $location_code_postcode_val_ex as $location_code_val ) {
								if ( false !== strpos( $location_code_val, '=' ) ) {
									$location_code_val = str_replace( '=', ' ', $location_code_val );
								}
								if ( false !== strpos( $location_code_val, '-' ) ) {
									if ( $postcode_ranges->posts ) {
										$encoded_postcode     = $this->afrsfwa_wc_make_numeric_postcode__premium_only( $postcode );
										$encoded_postcode_len = strlen( $encoded_postcode );
										$range                = array_map( 'trim', explode( '-', $location_code_val ) );
										if ( 2 !== count( $range ) ) {
											continue;
										}
										if ( is_numeric( $range[0] ) && is_numeric( $range[1] ) ) {
											$encoded_postcode = $postcode;
											$min              = $range[0];
											$max              = $range[1];
										} else {
											$min = $this->afrsfwa_wc_make_numeric_postcode__premium_only( $range[0] );
											$max = $this->afrsfwa_wc_make_numeric_postcode__premium_only( $range[1] );
											$min = str_pad( $min, $encoded_postcode_len, '0' );
											$max = str_pad( $max, $encoded_postcode_len, '9' );
										}
										if ( $encoded_postcode >= $min && $encoded_postcode <= $max ) {
											$return_zone_id = $available_zone_id;
										}
									}
								} elseif ( false !== strpos( $location_code_val, '*' ) ) {
									if ( in_array( trim( $location_code_val ), $valid_postcodes, true ) ) {
										$return_zone_id = $available_zone_id;
									}
								} else {
									if ( in_array( $country, $country_array, true ) ) {
										if ( ! empty( $state_array ) ) {
											if ( in_array( $state, $state_array, true ) ) {
												if ( in_array( $postcode, $location_code_postcode_val_ex, true ) ) {
													$return_zone_id = $available_zone_id;
												}
											}
										} else {

											/** Adding postcode from space to = */
											if ( false !== strpos( $postcode, ' ' ) ) {
												$postcode = str_replace( ' ', '=', $postcode );
											}
											$location_code_postcode_val_ex = array_map( 'trim', $location_code_postcode_val_ex );
											if ( in_array( trim( $postcode ), $location_code_postcode_val_ex, true ) ) {
												$return_zone_id = $available_zone_id;
											}
										}
									}
								}
							}
						} elseif ( 'countries' === $zone_type ) {
							if ( ! empty( $country ) && in_array( $country, $location_code_postcode_val, true ) ) {
								$return_zone_id = $available_zone_id;
							}
						} elseif ( 'states' === $zone_type ) {
							if ( ! empty( $state ) && in_array( $state, $location_code_postcode_val, true ) ) {
								$return_zone_id = $available_zone_id;
							}
						}
					}
				}
			}
		}
		return $return_zone_id;
	}
	/**
	 * Make numeric postcode function.
	 *
	 * @param mixed $postcode Get postcode and checked here.
	 *
	 * @return integer $numeric_postcode
	 * @since  1.0.0
	 *
	 * Converts letters to numbers so we can do a simple range check on postcodes.
	 *
	 * E.g. PE30 becomes 16050300 ( P = 16, E = 05, 3 = 03, 0 = 00 )
	 */
	public function afrsfwa_wc_make_numeric_postcode__premium_only( $postcode ) {
		$postcode_length    = strlen( $postcode );
		$letters_to_numbers = array_merge( array( 0 ), range( 'A', 'Z' ) );
		$letters_to_numbers = array_flip( $letters_to_numbers );
		$numeric_postcode   = '';
		for ( $i = 0; $i < $postcode_length; $i ++ ) {
			if ( is_numeric( $postcode[ $i ] ) ) {
				$numeric_postcode .= str_pad( $postcode[ $i ], 2, '0', STR_PAD_LEFT );
			} elseif ( isset( $letters_to_numbers[ $postcode[ $i ] ] ) ) {
				$numeric_postcode .= str_pad( $letters_to_numbers[ $postcode[ $i ] ], 2, '0', STR_PAD_LEFT );
			} else {
				$numeric_postcode .= '00';
			}
		}
		return $numeric_postcode;
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
	 */
	public function afrsfwa_fee_array_column_admin( array $input, $column_key, $index_key = null ) {
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
	 * Remove WooCommerce currency symbol
	 *
	 * @param float $price Price.
	 *
	 * @return float $new_price2
	 * @since  1.0.0
	 *
	 * @uses   get_woocommerce_currency_symbol()
	 */
	public function afrsfwa_remove_currency_symbol( $price ) {
		$wc_currency_symbol = get_woocommerce_currency_symbol();
		$new_price          = str_replace( $wc_currency_symbol, '', $price );
		$new_price2         = (float) preg_replace( '/[^.\d]/', '', $new_price );
		return $new_price2;
	}
	/**
	 * Get WooCommerce version number
	 *
	 * @return string if file is not exists then it will return null
	 * @since  1.0.0
	 */
	public function afrsfwa_get_woo_version_number() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugin_folder = get_plugins( '/woocommerce' );
		$plugin_file   = 'woocommerce.php';
		if ( isset( $plugin_folder[ $plugin_file ]['Version'] ) ) {
			return $plugin_folder[ $plugin_file ]['Version'];
		} else {
			return null;
		}
	}
	/**
	 * Save shipping order in shipping list section
	 *
	 * @since 1.0.0
	 */
	public function afrsfwa_sm_sort_order() {
		$default_lang       = $this->afrsfwa_get_default_langugae_with_sitpress();
		$get_sm_order_array = filter_input( INPUT_GET, 'smOrderArray', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY );
		$sm_order_array     = ! empty( $get_sm_order_array ) ? array_map( 'sanitize_text_field', wp_unslash( $get_sm_order_array ) ) : '';
		if ( isset( $sm_order_array ) && ! empty( $sm_order_array ) ) {
			update_option( 'sm_sortable_order_' . $default_lang, $sm_order_array );
			echo esc_html( 'true' );
		}
		wp_die();
	}
	/**
	 * Display textfield and multiselect dropdown based on country, state, zone and etc.
	 *
	 * @since 1.0.0
	 *
	 * @uses  afrsfwa_get_country_list()
	 * @uses  afrsfwa_get_states_list__premium_only()
	 * @uses  afrsfwa_get_zones_list__premium_only()
	 * @uses  afrsfwa_get_product_list()
	 * @uses  afrsfwa_get_varible_product_list__premium_only()
	 * @uses  afrsfwa_get_category_list()
	 * @uses  afrsfwa_get_tag_list()
	 * @uses  afrsfwa_get_att_term_list__premium_only()
	 * @uses  afrsfwa_get_sku_list__premium_only()
	 * @uses  afrsfwa_get_user_list()
	 * @uses  afrsfwa_get_user_role_list__premium_only()
	 * @uses  afrsfwa_get_coupon_list__premium_only()
	 * @uses  afrsfwa_get_advance_flat_rate_class__premium_only()
	 * @uses  afrsmw_allowed_html_tags()
	 * @uses  afrsfwa_get_payment__premium_only()
	 */
	public function afrsfwa_product_fees_conditions_values_ajax() {
		$get_condition = filter_input( INPUT_GET, 'condition', FILTER_SANITIZE_STRING );
		$get_count     = filter_input( INPUT_GET, 'count', FILTER_SANITIZE_NUMBER_INT );
		$condition     = isset( $get_condition ) ? sanitize_text_field( $get_condition ) : '';
		$count         = isset( $get_count ) ? sanitize_text_field( $get_count ) : '';
		$att_taxonomy  = wc_get_attribute_taxonomy_names();
		$html          = '';
		if ( 'country' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_country_list( $count, array(), true ) );
		} elseif ( 'state' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_states_list__premium_only( $count, array(), true ) );
		} elseif ( 'postcode' === $condition ) {
			$html .= 'textarea';
		} elseif ( 'zone' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_zones_list__premium_only( $count, array(), true ) );
		} elseif ( 'product' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_product_list( $count, array(), true ) );
		} elseif ( 'variableproduct' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_varible_product_list__premium_only( $count, array(), true ) );
		} elseif ( 'category' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_category_list( $count, array(), true ) );
		} elseif ( 'tag' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_tag_list( $count, array(), true ) );
		} elseif ( in_array( $condition, $att_taxonomy, true ) ) {
			$html .= wp_json_encode( $this->afrsfwa_get_att_term_list__premium_only( $count, $condition, array(), true ) );
		} elseif ( 'sku' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_sku_list__premium_only( $count, array(), true ) );
		} elseif ( 'product_qty' === $condition ) {
			$html .= 'input';
		} elseif ( 'user' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_user_list( $count, array(), true ) );
		} elseif ( 'user_role' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_user_role_list__premium_only( $count, array(), true ) );
		} elseif ( 'cart_total' === $condition ) {
			$html .= 'input';
		} elseif ( 'cart_totalafter' === $condition ) {
			$html .= 'input';
		} elseif ( 'quantity' === $condition ) {
			$html .= 'input';
		} elseif ( 'weight' === $condition ) {
			$html .= 'input';
		} elseif ( 'coupon' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_coupon_list__premium_only( $count, array(), true ) );
		} elseif ( 'shipping_class' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_advance_flat_rate_class__premium_only( $count, array(), true ) );
		} elseif ( 'payment_method' === $condition ) {
			$html .= wp_json_encode( $this->afrsfwa_get_payment__premium_only( $count, array(), true ) );
		}
		echo wp_kses( $html, Advanced_Flat_Rate_Shipping_For_WooCommerce::afrsmw_allowed_html_tags() );
		wp_die();
	}
	/**
	 * Get country list
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @uses   WC_Countries() class
	 *
	 * @since  1.0.0
	 */
	public function afrsfwa_get_country_list( $count = '', $selected = array(), $json = false ) {
		$countries_obj = new WC_Countries();
		$get_countries = $countries_obj->__get( 'countries' );
		$html          = '<select name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_country" multiple="multiple">';
		if ( ! empty( $get_countries ) ) {
			foreach ( $get_countries as $code => $country ) {
				$selected_val = is_array( $selected ) && ! empty( $selected ) && in_array( $code, $selected, true ) ? 'selected=selected' : '';
				$html        .= '<option value="' . esc_attr( $code ) . '" ' . esc_attr( $selected_val ) . '>' . esc_html( $country ) . '</option>';
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsfwa_convert_array_to_json( $get_countries );
		}
		return $html;
	}
	/**
	 * Get the states for a country.
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @since  1.0.0
	 *
	 * @uses   WC_Countries::get_allowed_countries()
	 * @uses   WC_Countries::get_states()
	 */
	public function afrsfwa_get_states_list__premium_only( $count = '', $selected = array(), $json = false ) {
		$filter_states = array();
		$countries     = WC()->countries->get_allowed_countries();
		$html          = '<select name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_state" multiple="multiple">';
		if ( ! empty( $countries ) && is_array( $countries ) ) :
			foreach ( $countries as $key => $val ) {
				$states = WC()->countries->get_states( $key );
				if ( ! empty( $states ) ) {
					foreach ( $states as $state_key => $state_value ) {
						$selected_val                             = is_array( $selected ) && ! empty( $selected ) && in_array( esc_attr( $key . ':' . $state_key ), $selected, true ) ? 'selected=selected' : '';
						$html                                    .= '<option value="' . esc_attr( $key . ':' . $state_key ) . '" ' . esc_attr( $selected_val ) . '>' . esc_html( $val . ' -> ' . $state_value ) . '</option>';
						$filter_states[ $key . ':' . $state_key ] = $val . ' -> ' . $state_value;
					}
				}
			}
		endif;
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsfwa_convert_array_to_json( $filter_states );
		}
		return $html;
	}
	/**
	 * Get all zones list
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @uses   afrsfwa_get_default_langugae_with_sitpress()
	 *
	 * @since  1.0.0
	 */
	public function afrsfwa_get_zones_list__premium_only( $count = '', $selected = array(), $json = false ) {
		global $sitepress;
		$default_lang   = $this->afrsfwa_get_default_langugae_with_sitpress();
		$filter_zone    = array();
		$get_all_zones  = new WP_Query(
			array(
				'post_type'      => self::AFRSFWA_ZONE_POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			)
		);
		$get_zones_data = $get_all_zones->posts;
		$html           = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_zone" multiple="multiple">';
		if ( isset( $get_zones_data ) && ! empty( $get_zones_data ) ) {
			foreach ( $get_zones_data as $get_all_zone ) {
				if ( ! empty( $sitepress ) ) {
					$new_zone_id = apply_filters( 'wpml_object_id', $get_all_zone->ID, 'wc_afrsm_zone', true, $default_lang );
				} else {
					$new_zone_id = $get_all_zone->ID;
				}
				$selected     = array_map( 'intval', $selected );
				$selected_val = is_array( $selected ) && ! empty( $selected ) && in_array( $new_zone_id, $selected, true ) ? 'selected=selected' : '';
				ob_start();
				?>
				<option value='<?php echo esc_attr( $new_zone_id ); ?>' <?php echo esc_attr( $selected_val ); ?>><?php echo esc_html( get_the_title( $new_zone_id ) ); ?></option>
				<?php
				$html .= ob_get_contents();
				ob_end_clean();
				$filter_zone[ $new_zone_id ] = get_the_title( $new_zone_id );
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsfwa_convert_array_to_json( $filter_zone );
		}
		return $html;
	}
	/**
	 * Get product list
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @uses   afrsfwa_get_default_langugae_with_sitpress()
	 *
	 * @since  1.0.0
	 */
	public function afrsfwa_get_product_list( $count = '', $selected = array(), $json = false ) {
		global $sitepress;
		$default_lang     = $this->afrsfwa_get_default_langugae_with_sitpress();
		$get_all_products = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			)
		);
		$html             = '<select id="product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values product_fees_conditions_values_product product_fees_conditions_values_' . esc_attr( $count ) . '" multiple="multiple">';
		if ( isset( $get_all_products->posts ) && ! empty( $get_all_products->posts ) ) {
			foreach ( $get_all_products->posts as $get_all_product ) {
				$_product = wc_get_product( $get_all_product->ID );
				if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
					if ( $_product->is_type( 'simple' ) ) {
						if ( ! empty( $sitepress ) ) {
							$new_product_id = apply_filters( 'wpml_object_id', $get_all_product->ID, 'product', true, $default_lang );
						} else {
							$new_product_id = $get_all_product->ID;
						}
						$selected     = array_map( 'intval', $selected );
						$selected_val = is_array( $selected ) && ! empty( $selected ) && in_array( $new_product_id, $selected, true ) ? 'selected=selected' : '';
						if ( '' !== $selected_val ) {
							$option_title = '#' . esc_html( $new_product_id ) . '-' . esc_html( get_the_title( $new_product_id ) );
							ob_start();
							?>
							<option value='<?php echo esc_attr( $new_product_id ); ?>' <?php echo esc_attr( $selected_val ); ?>><?php echo wp_kses_post( $option_title ); ?></option>
							<?php
							$html .= ob_get_contents();
							ob_end_clean();
						}
					}
				}
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return array();
		}
		return $html;
	}
	/**
	 * Get variable product list in Advance pricing rules
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 *
	 * @return string $html
	 * @uses   WC_Product::is_type()
	 *
	 * @since  3.4
	 *
	 * @uses   afrsfwa_get_default_langugae_with_sitpress()
	 * @uses   wc_get_product()
	 */
	public function afrsfwa_get_product_options( $count = '', $selected = array() ) {
		global $sitepress;
		$default_lang                   = $this->afrsfwa_get_default_langugae_with_sitpress();
		$get_all_products               = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			)
		);
		$baselang_variation_product_ids = array();
		$defaultlang_simple_product_ids = array();
		$html                           = '';
		if ( isset( $get_all_products->posts ) && ! empty( $get_all_products->posts ) ) {
			foreach ( $get_all_products->posts as $get_all_product ) {
				$_product = wc_get_product( $get_all_product->ID );
				if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
					if ( $_product->is_type( 'variable' ) ) {
						$variations = $_product->get_available_variations();
						foreach ( $variations as $value ) {
							if ( ! empty( $sitepress ) ) {
								$defaultlang_variation_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', true, $default_lang );
							} else {
								$defaultlang_variation_product_id = $value['variation_id'];
							}
							$baselang_variation_product_ids[] = $defaultlang_variation_product_id;
						}
					}
					if ( $_product->is_type( 'simple' ) ) {
						if ( ! empty( $sitepress ) ) {
							$defaultlang_simple_product_id = apply_filters( 'wpml_object_id', $get_all_product->ID, 'product', true, $default_lang );
						} else {
							$defaultlang_simple_product_id = $get_all_product->ID;
						}
						$defaultlang_simple_product_ids[] = $defaultlang_simple_product_id;
					}
				}
			}
		}
		$baselang_product_ids = array_merge( $baselang_variation_product_ids, $defaultlang_simple_product_ids );
		if ( isset( $baselang_product_ids ) && ! empty( $baselang_product_ids ) ) {
			foreach ( $baselang_product_ids as $baselang_product_id ) {
				$selected     = array_map( 'intval', $selected );
				$selected_val = is_array( $selected ) && ! empty( $selected ) && in_array( $baselang_product_id, $selected, true ) ? 'selected=selected' : '';
				if ( '' !== $selected_val ) {
					$option_title = '#' . esc_html( $baselang_product_id ) . '-' . esc_html( get_the_title( $baselang_product_id ) );
					ob_start();
					?>
					<option value='<?php echo esc_attr( $baselang_product_id ); ?>' <?php echo esc_attr( $selected_val ); ?>><?php echo wp_kses_post( $option_title ); ?></option>
					<?php
					$html .= ob_get_contents();
					ob_end_clean();
				}
			}
		}
		return $html;
	}
	/**
	 * Get category list in Advance pricing rules
	 *
	 * @param array $selected selected id.
	 * @param bool  $json     If passed false then it will passed json data.
	 *
	 * @return string $html
	 * @since  3.4
	 *
	 * @uses   afrsfwa_get_default_langugae_with_sitpress()
	 */
	public function afrsfwa_get_category_options( $selected = array(), $json ) {
		global $sitepress;
		$filter_categories    = array();
		$default_lang         = $this->afrsfwa_get_default_langugae_with_sitpress();
		$filter_category_list = array();
		$taxonomy             = 'product_cat';
		$post_status          = 'publish';
		$orderby              = 'name';
		$hierarchical         = 1;
		$empty                = 0;
		$args                 = array(
			'post_type'      => 'product',
			'post_status'    => $post_status,
			'taxonomy'       => $taxonomy,
			'orderby'        => $orderby,
			'hierarchical'   => $hierarchical,
			'hide_empty'     => $empty,
			'posts_per_page' => - 1,
		);
		$get_all_categories   = get_categories( $args );
		$html                 = '';
		if ( isset( $get_all_categories ) && ! empty( $get_all_categories ) ) {
			foreach ( $get_all_categories as $get_all_category ) {
				if ( ! empty( $sitepress ) ) {
					$new_cat_id = apply_filters( 'wpml_object_id', $get_all_category->term_id, 'product_cat', true, $default_lang );
				} else {
					$new_cat_id = $get_all_category->term_id;
				}
				$category        = get_term_by( 'id', $new_cat_id, 'product_cat' );
				$parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
				if ( ! empty( $selected ) ) {
					$selected     = array_map( 'intval', $selected );
					$selected_val = is_array( $selected ) && ! empty( $selected ) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '';
					if ( $category->parent > 0 ) {
						$option_title = '#' . esc_html( $parent_category->name ) . '-' . esc_html( $category->name );
						ob_start();
						?>
						<option value='<?php echo esc_attr( $category->term_id ); ?>' <?php echo esc_attr( $selected_val ); ?>><?php echo wp_kses_post( $option_title ); ?></option>
						<?php
						$html .= ob_get_contents();
						ob_end_clean();
						$filter_categories[ $category->term_id ] = '#' . $parent_category->name . '->' . $category->name;
					} else {
						$option_title = esc_html( $category->name );
						ob_start();
						?>
						<option value='<?php echo esc_attr( $category->term_id ); ?>' <?php echo esc_attr( $selected_val ); ?>><?php echo wp_kses_post( $option_title ); ?></option>
						<?php
						$html .= ob_get_contents();
						ob_end_clean();
						$filter_categories[ $category->term_id ] = $category->name;
					}
				} else {
					if ( $category->parent > 0 ) {
						$filter_category_list[ $category->term_id ] = $parent_category->name . '->' . $category->name;
					} else {
						$filter_category_list[ $category->term_id ] = $category->name;
					}
				}
			}
		}
		if ( true === $json ) {
			return wp_json_encode( $this->afrsfwa_convert_array_to_json( $filter_category_list ) );
		} else {
			return $html;
		}
	}
	/**
	 * Get shipping class list
	 *
	 * @param array $selected selected id.
	 * @param bool  $json     false.
	 *
	 * @return string $html
	 * @since  1.0.0
	 *
	 * @uses   WC_Shipping::get_shipping_classes()
	 */
	public function afrsfwa_get_class_options__premium_only( $selected = array(), $json ) {
		$shipping_classes           = WC()->shipping->get_shipping_classes();
		$filter_shipping_class_list = array();
		$html                       = '';
		if ( isset( $shipping_classes ) && ! empty( $shipping_classes ) ) {
			foreach ( $shipping_classes as $shipping_classes_key ) {
				$selected_val = ! empty( $selected ) && in_array( $shipping_classes_key->slug, $selected, true ) ? 'selected=selected' : '';
				$html        .= '<option value="' . esc_attr( $shipping_classes_key->slug ) . '" ' . esc_attr( $selected_val ) . '>' . esc_html( $shipping_classes_key->name ) . '</option>';
				$filter_shipping_class_list[ $shipping_classes_key->slug ] = $shipping_classes_key->name;
			}
		}
		if ( true === $json ) {
			return wp_json_encode( $this->afrsfwa_convert_array_to_json( $filter_shipping_class_list ) );
		} else {
			return $html;
		}
	}
	/**
	 * Get variable product list in Shipping Method Rules
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @uses   WC_Product::is_type()
	 * @uses   get_available_variations()
	 *
	 * @since  1.0.0
	 *
	 * @uses   afrsfwa_get_default_langugae_with_sitpress()
	 * @uses   wc_get_product()
	 */
	public function afrsfwa_get_varible_product_list__premium_only( $count = '', $selected = array(), $json = false ) {
		global $sitepress;
		$default_lang     = $this->afrsfwa_get_default_langugae_with_sitpress();
		$get_all_products = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			)
		);
		$html             = '<select id="var-product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_var_product" multiple="multiple">';
		if ( ! empty( $get_all_products->posts ) ) {
			foreach ( $get_all_products->posts as $post ) {
				$_product = wc_get_product( $post->ID );
				if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
					if ( $_product->is_type( 'variable' ) ) {
						$variations = $_product->get_available_variations();
						foreach ( $variations as $value ) {
							if ( ! empty( $sitepress ) ) {
								$new_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', true, $default_lang );
							} else {
								$new_product_id = $value['variation_id'];
							}
							$selected     = array_map( 'intval', $selected );
							$selected_val = is_array( $selected ) && ! empty( $selected ) && in_array( $new_product_id, $selected, true ) ? 'selected=selected' : '';
							if ( '' !== $selected_val ) {
								$option_title = '#' . esc_html( $new_product_id ) . '-' . esc_html( get_the_title( $new_product_id ) );
								ob_start();
								?>
								<option value='<?php echo esc_attr( $new_product_id ); ?>' <?php echo esc_attr( $selected_val ); ?>><?php echo wp_kses_post( $option_title ); ?></option>
								<?php
								$html .= ob_get_contents();
								ob_end_clean();
							}
						}
					}
				}
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return array();
		}
		return $html;
	}
	/**
	 * Get category list in Shipping Method Rules
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @uses   get_term_by()
	 *
	 * @since  1.0.0
	 *
	 * @uses   afrsfwa_get_default_langugae_with_sitpress()
	 * @uses   get_categories()
	 */
	public function afrsfwa_get_category_list( $count = '', $selected = array(), $json = false ) {
		global $sitepress;
		$default_lang       = $this->afrsfwa_get_default_langugae_with_sitpress();
		$filter_categories  = array();
		$taxonomy           = 'product_cat';
		$post_status        = 'publish';
		$orderby            = 'name';
		$hierarchical       = 1;
		$empty              = 0;
		$args               = array(
			'post_type'      => 'product',
			'post_status'    => $post_status,
			'taxonomy'       => $taxonomy,
			'orderby'        => $orderby,
			'hierarchical'   => $hierarchical,
			'hide_empty'     => $empty,
			'posts_per_page' => - 1,
		);
		$get_all_categories = get_categories( $args );
		$html               = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_cat_product" multiple="multiple">';
		if ( isset( $get_all_categories ) && ! empty( $get_all_categories ) ) {
			foreach ( $get_all_categories as $get_all_category ) {
				if ( ! empty( $sitepress ) ) {
					$new_cat_id = apply_filters( 'wpml_object_id', $get_all_category->term_id, 'product_cat', true, $default_lang );
				} else {
					$new_cat_id = $get_all_category->term_id;
				}
				$selected        = array_map( 'intval', $selected );
				$selected_val    = is_array( $selected ) && ! empty( $selected ) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '';
				$category        = get_term_by( 'id', $new_cat_id, 'product_cat' );
				$parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
				if ( $category->parent > 0 ) {
					$option_title = '#' . esc_html( $parent_category->name ) . '-' . esc_html( $category->name );
					ob_start();
					?>
					<option value='<?php echo esc_attr( $category->term_id ); ?>' <?php echo esc_attr( $selected_val ); ?>><?php echo wp_kses_post( $option_title ); ?></option>
					<?php
					$html .= ob_get_contents();
					ob_end_clean();
					$filter_categories[ $category->term_id ] = '#' . $parent_category->name . '->' . $category->name;
				} else {
					$option_title = esc_html( $category->name );
					ob_start();
					?>
					<option value='<?php echo esc_attr( $category->term_id ); ?>' <?php echo esc_attr( $selected_val ); ?>><?php echo wp_kses_post( $option_title ); ?></option>
					<?php
					$html .= ob_get_contents();
					ob_end_clean();
					$filter_categories[ $category->term_id ] = $category->name;
				}
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsfwa_convert_array_to_json( $filter_categories );
		}
		return $html;
	}
	/**
	 * Get tag list in Shipping Method Rules
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @since  1.0.0
	 *
	 * @uses   afrsfwa_get_default_langugae_with_sitpress()
	 * @uses   get_term_by()
	 */
	public function afrsfwa_get_tag_list( $count = '', $selected = array(), $json = false ) {
		global $sitepress;
		$default_lang = $this->afrsfwa_get_default_langugae_with_sitpress();
		$filter_tags  = array();
		$taxonomy     = 'product_tag';
		$orderby      = 'name';
		$hierarchical = 1;
		$empty        = 0;
		$args         = array(
			'post_type'        => 'product',
			'post_status'      => 'publish',
			'taxonomy'         => $taxonomy,
			'orderby'          => $orderby,
			'hierarchical'     => $hierarchical,
			'hide_empty'       => $empty,
			'posts_per_page'   => - 1,
			'suppress_filters' => false,
		);
		$get_all_tags = get_categories( $args );
		$html         = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_tag_product" multiple="multiple">';
		if ( isset( $get_all_tags ) && ! empty( $get_all_tags ) ) {
			foreach ( $get_all_tags as $get_all_tag ) {
				if ( ! empty( $sitepress ) ) {
					$new_tag_id = apply_filters( 'wpml_object_id', $get_all_tag->term_id, 'product_tag', true, $default_lang );
				} else {
					$new_tag_id = $get_all_tag->term_id;
				}
				$selected                     = array_map( 'intval', $selected );
				$selected_val                 = is_array( $selected ) && ! empty( $selected ) && in_array( $new_tag_id, $selected, true ) ? 'selected=selected' : '';
				$tag                          = get_term_by( 'id', $new_tag_id, 'product_tag' );
				$html                        .= '<option value="' . esc_attr( $tag->term_id ) . '" ' . esc_attr( $selected_val ) . '>' . esc_html( $tag->name ) . '</option>';
				$filter_tags[ $tag->term_id ] = $tag->name;
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsfwa_convert_array_to_json( $filter_tags );
		}
		return $html;
	}
	/**
	 * Get sku list in Shipping Method Rules
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @uses   get_post_meta()
	 *
	 * @since  1.0.0
	 */
	public function afrsfwa_get_sku_list__premium_only( $count = '', $selected = array(), $json = false ) {
		global $sitepress;
		$default_lang                   = $this->afrsfwa_get_default_langugae_with_sitpress();
		$get_products_array             = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			)
		);
		$filter_skus                    = array();
		$products_array                 = $get_products_array->posts;
		$baselang_simple_product_ids    = array();
		$baselang_variation_product_ids = array();
		if ( ! empty( $products_array ) ) {
			foreach ( $products_array as $get_product ) {
				$_product = wc_get_product( $get_product->ID );
				if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
					if ( $_product->is_type( 'variable' ) ) {
						$variations = $_product->get_available_variations();
						foreach ( $variations as $value ) {
							if ( ! empty( $sitepress ) ) {
								$defaultlang_variation_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', true, $default_lang );
							} else {
								$defaultlang_variation_product_id = $value['variation_id'];
							}
							$baselang_variation_product_ids[] = $defaultlang_variation_product_id;
						}
					}
					if ( $_product->is_type( 'simple' ) ) {
						if ( ! empty( $sitepress ) ) {
							$defaultlang_simple_product_id = apply_filters( 'wpml_object_id', $get_product->ID, 'product', true, $default_lang );
						} else {
							$defaultlang_simple_product_id = $get_product->ID;
						}
						$baselang_simple_product_ids[] = $defaultlang_simple_product_id;
					}
				}
			}
		}
		$baselang_product_ids = array_merge( $baselang_variation_product_ids, $baselang_simple_product_ids );
		$html                 = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_sku_product" multiple="multiple">';
		if ( isset( $baselang_product_ids ) && ! empty( $baselang_product_ids ) ) {
			foreach ( $baselang_product_ids as $baselang_product_id ) {
				if ( ! empty( $baselang_product_id ) ) {
					$product_sku = get_post_meta( $baselang_product_id, '_sku', true );
				}
				settype( $product_sku, 'string' );
				$selected_val = is_array( $selected ) && ! empty( $selected ) && in_array( $product_sku, $selected, true ) ? 'selected=selected' : '';
				if ( ! empty( $product_sku ) || '' !== $product_sku ) {
					$html .= '<option value="' . esc_attr( $product_sku ) . '" ' . esc_attr( $selected_val ) . '>' . esc_html( $product_sku ) . '</option>';
				}
				$filter_skus[ $product_sku ] = $product_sku;
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsfwa_convert_array_to_json( $filter_skus );
		}
		return $html;
	}
	/**
	 * Get attribute list in Shipping Method Rules
	 *
	 * @param string $count     blank.
	 * @param string $condition taxonomy.
	 * @param array  $selected  selected id.
	 * @param bool   $json      false.
	 *
	 * @return string $html
	 * @since  1.0.0
	 */
	public function afrsfwa_get_att_term_list__premium_only( $count = '', $condition = '', $selected = array(), $json = false ) {
		$att_terms         = get_terms(
			array(
				'taxonomy'   => $condition,
				'parent'     => 0,
				'hide_empty' => false,
			)
		);
		$filter_attributes = array();
		$html              = '<select rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_att_term" multiple="multiple">';
		if ( ! empty( $att_terms ) ) {
			foreach ( $att_terms as $term ) {
				$term_name                       = $term->name;
				$term_slug                       = $term->slug;
				$selected_val                    = is_array( $selected ) && ! empty( $selected ) && in_array( $term_slug, $selected, true ) ? 'selected=selected' : '';
				$html                           .= '<option value="' . $term_slug . '" ' . $selected_val . '>' . $term_name . '</option>';
				$filter_attributes[ $term_slug ] = $term_name;
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsfwa_convert_array_to_json( $filter_attributes );
		}
		return $html;
	}
	/**
	 * Get user list in Shipping Method Rules
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @since  1.0.0
	 */
	public function afrsfwa_get_user_list( $count = '', $selected = array(), $json = false ) {
		$filter_users  = array();
		$get_all_users = get_users();
		$html          = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_user" multiple="multiple">';
		if ( isset( $get_all_users ) && ! empty( $get_all_users ) ) {
			foreach ( $get_all_users as $get_all_user ) {
				$selected_val                            = is_array( $selected ) && ! empty( $selected ) && in_array( $get_all_user->data->ID, $selected, true ) ? 'selected=selected' : '';
				$html                                   .= '<option value="' . esc_attr( $get_all_user->data->ID ) . '" ' . esc_attr( $selected_val ) . '>' . esc_html( $get_all_user->data->user_login ) . '</option>';
				$filter_users[ $get_all_user->data->ID ] = $get_all_user->data->user_login;
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsfwa_convert_array_to_json( $filter_users );
		}
		return $html;
	}
	/**
	 * Get role user list in Shipping Method Rules
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @since  1.0.0
	 */
	public function afrsfwa_get_user_role_list__premium_only( $count = '', $selected = array(), $json = false ) {
		$filter_user_roles = array();
		global $wp_roles;
		$html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_role" multiple="multiple">';
		if ( isset( $wp_roles->roles ) && ! empty( $wp_roles->roles ) ) {
			$default_sel                = ! empty( $selected ) && in_array( 'guest', $selected, true ) ? 'selected=selected' : '';
			$html                      .= '<option value="guest" ' . esc_attr( $default_sel ) . '>' . esc_html__( 'Guest', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</option>';
			$filter_user_roles['guest'] = esc_html__( 'Guest', 'advanced-flat-rate-shipping-for-woocommerce' );
			foreach ( $wp_roles->roles as $user_role_key => $get_all_role ) {
				$selected_val                        = is_array( $selected ) && ! empty( $selected ) && in_array( $user_role_key, $selected, true ) ? 'selected=selected' : '';
				$html                               .= '<option value="' . esc_attr( $user_role_key ) . '" ' . esc_attr( $selected_val ) . '>' . esc_html( $get_all_role['name'] ) . '</option>';
				$filter_user_roles[ $user_role_key ] = $get_all_role['name'];
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsfwa_convert_array_to_json( $filter_user_roles );
		}
		return $html;
	}
	/**
	 * Get coupon list in Shipping Method Rules
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @since  1.0.0
	 */
	public function afrsfwa_get_coupon_list__premium_only( $count = '', $selected = array(), $json = false ) {
		$filter_coupon_list = array();
		$get_all_coupon     = new WP_Query(
			array(
				'post_type'      => 'shop_coupon',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			)
		);
		$html               = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_coupon" multiple="multiple">';
		if ( isset( $get_all_coupon->posts ) && ! empty( $get_all_coupon->posts ) ) {
			foreach ( $get_all_coupon->posts as $get_all_coupon ) {
				$selected                                  = array_map( 'intval', $selected );
				$selected_val                              = is_array( $selected ) && ! empty( $selected ) && in_array( $get_all_coupon->ID, $selected, true ) ? 'selected=selected' : '';
				$html                                     .= '<option value="' . esc_attr( $get_all_coupon->ID ) . '" ' . esc_attr( $selected_val ) . '>' . esc_html( $get_all_coupon->post_title ) . '</option>';
				$filter_coupon_list[ $get_all_coupon->ID ] = $get_all_coupon->post_title;
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsfwa_convert_array_to_json( $filter_coupon_list );
		}
		return $html;
	}
	/**
	 * Get shipping class list in Shipping Method Rules
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @uses   WC_Shipping::get_shipping_classes()
	 *
	 * @since  1.0.0
	 */
	public function afrsfwa_get_advance_flat_rate_class__premium_only( $count = '', $selected = array(), $json = false ) {
		$filter_rate_class = array();
		$shipping_classes  = WC()->shipping->get_shipping_classes();
		$html              = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_class" multiple="multiple">';
		if ( isset( $shipping_classes ) && ! empty( $shipping_classes ) ) {
			foreach ( $shipping_classes as $shipping_classes_key ) {
				$selected_val = ! empty( $selected ) && in_array( $shipping_classes_key->slug, $selected, true ) ? 'selected=selected' : '';
				$html        .= '<option value="' . esc_attr( $shipping_classes_key->slug ) . '" ' . esc_attr( $selected_val ) . '>' . esc_html( $shipping_classes_key->name ) . '</option>';
				$filter_rate_class[ $shipping_classes_key->slug ] = $shipping_classes_key->name;
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsfwa_convert_array_to_json( $filter_rate_class );
		}
		return $html;
	}
	/**
	 * Get payment method in Shipping Method Rules
	 *
	 * @param string $count    blank.
	 * @param array  $selected selected id.
	 * @param bool   $json     false.
	 *
	 * @return string $html
	 * @uses   WC_Payment_Gateways::get_available_payment_gateways()
	 *
	 * @since  1.0.0
	 */
	public function afrsfwa_get_payment__premium_only( $count = '', $selected = array(), $json = false ) {
		$filter_rate_payment = array();
		$gateways            = WC()->payment_gateways->get_available_payment_gateways();
		$html                = '<select rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_payment" multiple="multiple">';
		if ( isset( $gateways ) && ! empty( $gateways ) ) {
			foreach ( $gateways as $gateway ) {
				if ( 'yes' === $gateway->enabled ) {
					$selected_val                        = ! empty( $selected ) && in_array( $gateway->id, $selected, true ) ? 'selected=selected' : '';
					$html                               .= '<option value="' . esc_attr( $gateway->id ) . '" ' . esc_attr( $selected_val ) . '>' . esc_html( $gateway->title ) . '</option>';
					$filter_rate_payment[ $gateway->id ] = esc_html( $gateway->title );
				}
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsfwa_convert_array_to_json( $filter_rate_payment );
		}
		return $html;
	}
	/**
	 * Display product list based product specific option
	 *
	 * @return string $html
	 * @uses   afrsfwa_get_default_langugae_with_sitpress()
	 * @uses   Advanced_Flat_Rate_Shipping_For_WooCommerce::afrsmw_allowed_html_tags()
	 * @uses   wcpfc_posts_wheres()
	 *
	 * @since  1.0.0
	 */
	public function afrsfwa_product_fees_conditions_values_product_ajax() {
		global $sitepress;
		$json                 = true;
		$filter_product_list  = array();
		$default_lang         = $this->afrsfwa_get_default_langugae_with_sitpress();
		$request_value        = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_STRING );
		$post_value           = isset( $request_value ) ? sanitize_text_field( $request_value ) : '';
		$baselang_product_ids = array();
		/**
		 * Query for the post with where
		 *
		 * @param string $where    Where condition for SQL.
		 * @param mixed  $wp_query $sql query.
		 *
		 * @return string $where Where condition.
		 */
		function afrsfwa_posts_where( $where, $wp_query ) {
			global $wpdb;
			$search_term = $wp_query->get( 'search_pro_title' );
			if ( ! empty( $search_term ) ) {
				$search_term_like = $wpdb->esc_like( $search_term );
				$where           .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
			}
			return $where;
		}
		$product_args = array(
			'post_type'        => 'product',
			'posts_per_page'   => - 1,
			'search_pro_title' => $post_value,
			'post_status'      => 'publish',
			'orderby'          => 'title',
			'order'            => 'ASC',
		);
		add_filter( 'posts_where', 'afrsfwa_posts_where', 10, 2 );
		$get_wp_query = new WP_Query( $product_args );
		remove_filter( 'posts_where', 'afrsfwa_posts_where', 10, 2 );
		$get_all_products = $get_wp_query->posts;
		if ( isset( $get_all_products ) && ! empty( $get_all_products ) ) {
			foreach ( $get_all_products as $get_all_product ) {
				$_product = wc_get_product( $get_all_product->ID );
				if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
					if ( $_product->is_type( 'simple' ) ) {
						if ( ! empty( $sitepress ) ) {
							$defaultlang_product_id = apply_filters( 'wpml_object_id', $get_all_product->ID, 'product', true, $default_lang );
						} else {
							$defaultlang_product_id = $get_all_product->ID;
						}
						$baselang_product_ids[] = $defaultlang_product_id;
					}
				}
			}
		}
		$html = '';
		if ( isset( $baselang_product_ids ) && ! empty( $baselang_product_ids ) ) {
			foreach ( $baselang_product_ids as $baselang_product_id ) {
				ob_start();
				$option_title = '#' . esc_html( $baselang_product_id ) . '-' . esc_html( get_the_title( $baselang_product_id ) );
				?>
				<option value='<?php echo esc_attr( $baselang_product_id ); ?>'><?php echo wp_kses_post( $option_title ); ?></option>
				<?php
				$html .= ob_get_contents();
				ob_end_clean();
				$filter_product_list[] = array( $baselang_product_id, get_the_title( $baselang_product_id ) );
			}
		}
		if ( $json ) {
			echo wp_json_encode( $filter_product_list );
			wp_die();
		}
		echo wp_kses( $html, Advanced_Flat_Rate_Shipping_For_WooCommerce::afrsmw_allowed_html_tags() );
		wp_die();
	}
	/**
	 * Display variable product list based product specific option
	 *
	 * @return string $html
	 * @uses   afrsfwa_get_default_langugae_with_sitpress()
	 * @uses   wc_get_product()
	 * @uses   WC_Product::is_type()
	 * @uses   Advanced_Flat_Rate_Shipping_For_WooCommerce::afrsmw_allowed_html_tags()
	 * @uses   wcpfc_posts_wheres()
	 *
	 * @since  1.0.0
	 */
	public function afrsfwa_product_fees_conditions_varible_values_product_ajax__premium_only() {
		global $sitepress;
		$default_lang                 = $this->afrsfwa_get_default_langugae_with_sitpress();
		$json                         = true;
		$filter_variable_product_list = array();
		$request_value                = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_STRING );
		$post_value                   = isset( $request_value ) ? sanitize_text_field( $request_value ) : '';
		$baselang_product_ids         = array();
		/**
		 * Query for the post with where
		 *
		 * @param string $where    Where condition for SQL.
		 * @param mixed  $wp_query $sql query.
		 *
		 * @return string $where Where condition.
		 */
		function wcpfc_posts_wheres( $where, &$wp_query ) {
			global $wpdb;
			$search_term = $wp_query->get( 'search_pro_title' );
			if ( ! empty( $search_term ) ) {
				$search_term_like = $wpdb->esc_like( $search_term );
				$where           .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
			}
			return $where;
		}
		$product_args     = array(
			'post_type'        => 'product',
			'posts_per_page'   => - 1,
			'search_pro_title' => $post_value,
			'post_status'      => 'publish',
			'orderby'          => 'title',
			'order'            => 'ASC',
		);
		$get_all_products = new WP_Query( $product_args );
		if ( ! empty( $get_all_products ) ) {
			foreach ( $get_all_products->posts as $get_all_product ) {
				$_product = wc_get_product( $get_all_product->ID );
				if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
					if ( $_product->is_type( 'variable' ) ) {
						$variations = $_product->get_available_variations();
						foreach ( $variations as $value ) {
							if ( ! empty( $sitepress ) ) {
								$defaultlang_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', true, $default_lang );
							} else {
								$defaultlang_product_id = $value['variation_id'];
							}
							$baselang_product_ids[] = $defaultlang_product_id;
						}
					}
				}
			}
		}
		$html = '';
		if ( isset( $baselang_product_ids ) && ! empty( $baselang_product_ids ) ) {
			foreach ( $baselang_product_ids as $baselang_product_id ) {
				ob_start();
				$option_title = '#' . esc_html( $baselang_product_id ) . '-' . esc_html( get_the_title( $baselang_product_id ) );
				?>
				<option value='<?php echo esc_attr( $baselang_product_id ); ?>'><?php echo wp_kses_post( $option_title ); ?></option>
				<?php
				$html .= ob_get_contents();
				ob_end_clean();
				$filter_variable_product_list[] = array( $baselang_product_id, get_the_title( $baselang_product_id ) );
			}
		}
		if ( $json ) {
			echo wp_json_encode( $filter_variable_product_list );
			wp_die();
		}
		echo wp_kses( $html, Advanced_Flat_Rate_Shipping_For_WooCommerce::afrsmw_allowed_html_tags() );
		wp_die();
	}
	/**
	 * Display simple and variable product list based product specific option in Advance Pricing Rules
	 *
	 * @return string $html
	 * @uses   afrsfwa_get_default_langugae_with_sitpress()
	 * @uses   wc_get_product()
	 * @uses   WC_Product::is_type()
	 * @uses   get_available_variations()
	 * @uses   Advanced_Flat_Rate_Shipping_For_WooCommerce::afrsmw_allowed_html_tags()
	 * @uses   afrsfwa_posts_where()
	 * @since  3.4
	 */
	public function afrsfwa_simple_and_variation_product_list_ajax__premium_only() {
		global $sitepress;
		$default_lang                   = $this->afrsfwa_get_default_langugae_with_sitpress();
		$json                           = true;
		$filter_product_list            = array();
		$request_value                  = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_STRING );
		$post_value                     = isset( $request_value ) ? sanitize_text_field( $request_value ) : '';
		$baselang_simple_product_ids    = array();
		$baselang_variation_product_ids = array();
		/**
		 * Query for the post with where
		 *
		 * @param string $where    Where condition for SQL.
		 * @param mixed  $wp_query $sql query.
		 *
		 * @return string $where Where condition.
		 */
		function afrsfwa_posts_where( $where, $wp_query ) {
			global $wpdb;
			$search_term = $wp_query->get( 'search_pro_title' );
			if ( ! empty( $search_term ) ) {
				$search_term_like = $wpdb->esc_like( $search_term );
				$where           .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
			}
			return $where;
		}
		$product_args = array(
			'post_type'        => 'product',
			'posts_per_page'   => - 1,
			'search_pro_title' => $post_value,
			'post_status'      => 'publish',
			'orderby'          => 'title',
			'order'            => 'ASC',
		);
		add_filter( 'posts_where', 'afrsfwa_posts_where', 10, 2 );
		$get_wp_query = new WP_Query( $product_args );
		remove_filter( 'posts_where', 'afrsfwa_posts_where', 10, 2 );
		$get_all_products = $get_wp_query->posts;
		if ( isset( $get_all_products ) && ! empty( $get_all_products ) ) {
			foreach ( $get_all_products as $get_all_product ) {
				$_product = wc_get_product( $get_all_product->ID );
				if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
					if ( $_product->is_type( 'variable' ) ) {
						$variations = $_product->get_available_variations();
						foreach ( $variations as $value ) {
							if ( ! empty( $sitepress ) ) {
								$defaultlang_variation_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', true, $default_lang );
							} else {
								$defaultlang_variation_product_id = $value['variation_id'];
							}
							$baselang_variation_product_ids[] = $defaultlang_variation_product_id;
						}
					}
					if ( $_product->is_type( 'simple' ) ) {
						if ( ! empty( $sitepress ) ) {
							$defaultlang_simple_product_id = apply_filters( 'wpml_object_id', $get_all_product->ID, 'product', true, $default_lang );
						} else {
							$defaultlang_simple_product_id = $get_all_product->ID;
						}
						$baselang_simple_product_ids[] = $defaultlang_simple_product_id;
					}
				}
			}
		}
		$baselang_product_ids = array_merge( $baselang_variation_product_ids, $baselang_simple_product_ids );
		$html                 = '';
		if ( isset( $baselang_product_ids ) && ! empty( $baselang_product_ids ) ) {
			foreach ( $baselang_product_ids as $baselang_product_id ) {
				ob_start();
				$option_title = '#' . esc_html( $baselang_product_id ) . '-' . esc_html( get_the_title( $baselang_product_id ) );
				?>
				<option value='<?php echo esc_attr( $baselang_product_id ); ?>'><?php echo wp_kses_post( $option_title ); ?></option>
				<?php
				$html .= ob_get_contents();
				ob_end_clean();
				$filter_product_list[] = array( $baselang_product_id, get_the_title( $baselang_product_id ) );
			}
		}
		if ( $json ) {
			echo wp_json_encode( $filter_product_list );
			wp_die();
		}
		echo wp_kses( $html, Advanced_Flat_Rate_Shipping_For_WooCommerce::afrsmw_allowed_html_tags() );
		wp_die();
	}
	/**
	 * Get all shipping method
	 *
	 * @return array|object $get_all_shipping
	 *
	 * @since  4.0
	 */
	public static function afrsfwa_get_shipping_method() {
		$sm_args          = array(
			'post_type'      => self::AFRSFWA_SHIPPING_POST_TYPE,
			'posts_per_page' => - 1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		);
		$get_all_shipping = new WP_Query( $sm_args );
		$get_all_shipping = $get_all_shipping->get_posts();
		return $get_all_shipping;
	}
	/**
	 * Convert array to json
	 *
	 * @param array $arr Fetch array from all admin data.
	 *
	 * @return array $filter_data
	 * @since 1.0.0
	 */
	public function afrsfwa_convert_array_to_json( $arr ) {
		$filter_data = array();
		foreach ( $arr as $key => $value ) {
			$option                        = array();
			$option['name']                = $value;
			$option['attributes']['value'] = $key;
			$filter_data[]                 = $option;
		}
		return $filter_data;
	}
	/**
	 * Convert array to json
	 *
	 * @return array $filter_data
	 * @since 1.0.0
	 */
	public function afrsfwa_attribute_list() {
		$filter_attr_data     = array();
		$filter_attr_json     = array();
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		if ( $attribute_taxonomies ) {
			foreach ( $attribute_taxonomies as $attribute ) {
				$att_label                               = $attribute->attribute_label;
				$att_name                                = wc_attribute_taxonomy_name( $attribute->attribute_name );
				$filter_attr_json['name']                = $att_label;
				$filter_attr_json['attributes']['value'] = esc_html( $att_name );
				$filter_attr_data[]                      = $filter_attr_json;
			}
		}
		return $filter_attr_data;
	}
	/**
	 * Add screen option for per page
	 *
	 * @param bool   $status Return status of the page.
	 * @param string $option Get page name and check.
	 * @param int    $value  How many page you want to display.
	 *
	 * @return int $value
	 * @since 1.0.0
	 */
	public function afrsfwa_set_screen_options( $status, $option, $value ) {
		$afrsm_screens = array(
			'afrsm_per_page',
		);
		if ( in_array( $option, $afrsm_screens, true ) ) {
			return $value;
		}
		return $status;
	}
	/**
	 * Tab array
	 *
	 * @return array $tab_array
	 *
	 * @since 1.0.0
	 */
	public static function afrsfwa_tab_array() {
		$tab_array = array(
			'master_setting'          => esc_html__( 'Master Setting', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'advance_shipping_method' => esc_html__( 'Advance Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'advance_shipping_zone'   => esc_html__( 'Advance Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'import_export'           => esc_html__( 'Import / Export', 'advanced-flat-rate-shipping-for-woocommerce' ),
		);
		return $tab_array;
	}
	/**
	 * Display message in admin side
	 *
	 * @param string $message        Display message in admin side.
	 * @param string $tab            Get tab from current page.
	 * @param string $validation_msg Display validation message in admin side.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function afrsfwa_updated_message( $message, $tab, $validation_msg ) {
		if ( empty( $message ) ) {
			return false;
		}
		if ( 'advance_shipping_method' === $tab ) {
			if ( 'created' === $message ) {
				$updated_message = esc_html__( 'Shipping method created.', 'advanced-flat-rate-shipping-for-woocommerce' );
			} elseif ( 'saved' === $message ) {
				$updated_message = esc_html__( 'Shipping method updated.', 'advanced-flat-rate-shipping-for-woocommerce' );
			} elseif ( 'deleted' === $message ) {
				$updated_message = esc_html__( 'Shipping method deleted.', 'advanced-flat-rate-shipping-for-woocommerce' );
			} elseif ( 'duplicated' === $message ) {
				$updated_message = esc_html__( 'Shipping method duplicated.', 'advanced-flat-rate-shipping-for-woocommerce' );
			} elseif ( 'disabled' === $message ) {
				$updated_message = esc_html__( 'Shipping method disabled.', 'advanced-flat-rate-shipping-for-woocommerce' );
			} elseif ( 'enabled' === $message ) {
				$updated_message = esc_html__( 'Shipping method enabled.', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
			if ( 'failed' === $message ) {
				$failed_messsage = esc_html__( 'There was an error with saving data.', 'advanced-flat-rate-shipping-for-woocommerce' );
			} elseif ( 'nonce_check' === $message ) {
				$failed_messsage = esc_html__( 'There was an error with security check.', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
			if ( 'validated' === $message ) {
				$validated_messsage = esc_html( $validation_msg );
			}
		} elseif ( 'advance_shipping_zone' === $tab ) {
			if ( 'created' === $message ) {
				$updated_message = esc_html__( 'Shipping Zone created.', 'advanced-flat-rate-shipping-for-woocommerce' );
			} elseif ( 'saved' === $message ) {
				$updated_message = esc_html__( 'Shipping Zone updated.', 'advanced-flat-rate-shipping-for-woocommerce' );
			} elseif ( 'deleted' === $message ) {
				$updated_message = esc_html__( 'Shipping Zone deleted.', 'advanced-flat-rate-shipping-for-woocommerce' );
			} elseif ( 'duplicated' === $message ) {
				$updated_message = esc_html__( 'Shipping Zone duplicated.', 'advanced-flat-rate-shipping-for-woocommerce' );
			} elseif ( 'disabled' === $message ) {
				$updated_message = esc_html__( 'Shipping Zone disabled.', 'advanced-flat-rate-shipping-for-woocommerce' );
			} elseif ( 'enabled' === $message ) {
				$updated_message = esc_html__( 'Shipping Zone enabled.', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
			if ( 'failed' === $message ) {
				$failed_messsage = esc_html__( 'There was an error with saving data.', 'advanced-flat-rate-shipping-for-woocommerce' );
			} elseif ( 'nonce_check' === $message ) {
				$failed_messsage = esc_html__( 'There was an error with security check.', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
			if ( 'validated' === $message ) {
				$validated_messsage = esc_html( $validation_msg );
			}
		} elseif ( 'import_export' === $tab ) {
			if ( 'import' === $message ) {
				$updated_message = esc_html__( 'File import successfully.', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
		} else {
			if ( 'saved' === $message ) {
				$updated_message = esc_html__( 'Settings save successfully', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
			if ( 'nonce_check' === $message ) {
				$failed_messsage = esc_html__( 'There was an error with security check.', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
			if ( 'validated' === $message ) {
				$validated_messsage = esc_html( $validation_msg );
			}
		}
		if ( ! empty( $updated_message ) ) {
			echo sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );
			return false;
		}
		if ( ! empty( $failed_messsage ) ) {
			echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $failed_messsage ) );
			return false;
		}
		if ( ! empty( $validated_messsage ) ) {
			echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $validated_messsage ) );
			return false;
		}
	}
	/**
	 * Add class into body
	 *
	 * @param string $classes find all classes from default WordPress.
	 *
	 * @return string $classes
	 * @since 1.0.0
	 */
	public function afrsfwa_admin_body_class( $classes ) {
		$get_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$get_tab  = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		if ( isset( $get_page ) && 'afrsm-start-page' === $get_page ) {
			if ( isset( $get_tab ) && 'advance_shipping_method' === $get_tab ) {
				$classes = 'shipping-method-class';
			}
			if ( isset( $get_tab ) && 'advance_shipping_zone' === $get_tab ) {
				$classes = 'shipping-zone-class';
			}
		}
		return $classes;
	}
	/**
	 * Get product id and variation id from cart
	 *
	 * @param string $sitepress    sitepress is use for multilanguage.
	 * @param string $default_lang get default language.
	 *
	 * @return array $cart_main_product_ids_array
	 * @uses  afrsfwa_get_cart();
	 *
	 * @since 1.0.0
	 */
	public function afrsfwa_get_main_prd_id( $sitepress, $default_lang ) {
		$cart_array                  = $this->afrsfwa_get_cart();
		$cart_main_product_ids_array = array();
		foreach ( $cart_array as $woo_cart_item ) {
			$_product = wc_get_product( $woo_cart_item['product_id'] );
			if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
				if ( ! empty( $sitepress ) ) {
					$cart_main_product_ids_array[] = apply_filters( 'wpml_object_id', $woo_cart_item['product_id'], 'product', true, $default_lang );
				} else {
					$cart_main_product_ids_array[] = $woo_cart_item['product_id'];
				}
			}
		}
		return $cart_main_product_ids_array;
	}
	/**
	 * Get product id and variation id from cart
	 *
	 * @param string $sitepress    sitepress is use for multilanguage.
	 * @param string $default_lang get default language.
	 *
	 * @return array $cart_product_ids_array
	 * @uses  afrsfwa_get_cart();
	 *
	 * @since 1.0.0
	 */
	public function afrsfwa_get_prd_var_id( $sitepress, $default_lang ) {
		$cart_array             = $this->afrsfwa_get_cart();
		$cart_product_ids_array = array();
		foreach ( $cart_array as $woo_cart_item ) {
			$_product = wc_get_product( $woo_cart_item['product_id'] );
			if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
				if ( $_product->is_type( 'variable' ) ) {
					if ( ! empty( $sitepress ) ) {
						$cart_product_ids_array[] = apply_filters( 'wpml_object_id', $woo_cart_item['variation_id'], 'product', true, $default_lang );
					} else {
						$cart_product_ids_array[] = $woo_cart_item['variation_id'];
					}
				}
				if ( $_product->is_type( 'simple' ) ) {
					if ( ! empty( $sitepress ) ) {
						$cart_product_ids_array[] = apply_filters( 'wpml_object_id', $woo_cart_item['product_id'], 'product', true, $default_lang );
					} else {
						$cart_product_ids_array[] = $woo_cart_item['product_id'];
					}
				}
			}
		}
		return $cart_product_ids_array;
	}
	/**
	 * Get variation name from cart
	 *
	 * @param string $sitepress    sitepress is use for multilanguage.
	 * @param string $default_lang get default language.
	 *
	 * @return array $cart_product_ids_array
	 * @uses  afrsfwa_get_cart();
	 *
	 * @since 1.0.0
	 */
	public function afrsfwa_get_var_name__premium_only( $sitepress, $default_lang ) {
		$cart_array             = $this->afrsfwa_get_cart();
		$cart_product_ids_array = array();
		$variation_cart_products_array = array();
		foreach ( $cart_array as $woo_cart_item ) {
			$_product = wc_get_product( $woo_cart_item['product_id'] );
			if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
				if ( $_product->is_type( 'variable' ) ) {
					if ( ! empty( $sitepress ) ) {
						$cart_product_ids_array[] = apply_filters( 'wpml_object_id', $woo_cart_item['variation_id'], 'product', true, $default_lang );
					} else {
						$cart_product_ids_array[] = $woo_cart_item['variation_id'];
					}
					foreach ( $cart_product_ids_array as $variation_id ) {
						$variation                = new WC_Product_Variation( $variation_id );
						$variation_cart_product[] = $variation->get_variation_attributes();
					}
					
					if ( isset( $variation_cart_product ) && ! empty( $variation_cart_product ) ) {
						foreach ( $variation_cart_product as $cart_product_id ) {
							if ( isset( $cart_product_id ) && ! empty( $cart_product_id ) ) {
								foreach ( $cart_product_id as $v ) {
									$variation_cart_products_array[] = $v;
								}
							}
						}
					}
				} else if ( $_product->is_type( 'simple' ) ) {
					if ( ! empty( $sitepress ) ) {
						$cart_product_id = apply_filters( 'wpml_object_id', $woo_cart_item['product_id'], 'product', true, $default_lang );
					} else {
						$cart_product_id = $woo_cart_item['product_id'];
					}
					$product = wc_get_product( $cart_product_id );
					$variation_cart_product[] = $product->get_attributes();
					if ( isset( $variation_cart_product ) && ! empty( $variation_cart_product ) ) {
						foreach ( $variation_cart_product as $cart_product_id ) {
							if ( isset( $cart_product_id ) && ! empty( $cart_product_id ) ) {
								if ( isset( $cart_product_id ) && ! empty( $cart_product_id ) ) {
									foreach ( $cart_product_id as $v ) {
										if ( is_a( $v, 'WC_Product_Attribute' ) ) {     
											$slug_array = $v->get_slugs();
											if ( isset( $slug_array ) && is_array( $slug_array ) ) {
												foreach ( $slug_array as $attr_slug ) {
													$variation_cart_products_array[] = $attr_slug; // The terms Ids
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return $variation_cart_products_array;
	}
	/**
	 * Get product id and variation id from cart
	 *
	 * @return array $cart_array
	 * @since 1.0.0
	 */
	public function afrsfwa_get_cart() {
		$cart_array = WC()->cart->get_cart();
		return $cart_array;
	}
	/**
	 * Get current site langugae
	 *
	 * @return string $default_lang
	 * @since 1.0.0
	 */
	public function afrsfwa_get_current_site_language() {
		$get_site_language = get_bloginfo( 'language' );
		if ( false !== strpos( $get_site_language, '-' ) ) {
			$get_site_language_explode = explode( '-', $get_site_language );
			$default_lang              = $get_site_language_explode[0];
		} else {
			$default_lang = $get_site_language;
		}
		return $default_lang;
	}
	/**
	 * Get default site language
	 *
	 * @return string $default_lang
	 *
	 * @since  4.0
	 */
	public function afrsfwa_get_default_langugae_with_sitpress() {
		global $sitepress;
		if ( ! empty( $sitepress ) ) {
			$default_lang = $sitepress->get_current_language();
		} else {
			$default_lang = $this->afrsfwa_get_current_site_language();
		}
		return $default_lang;
	}
	/**
	 * Allowed html tags used for wp_kses function
	 *
	 * @return array
	 * @since     1.0.0
	 */
	public static function afrsfwa_allowed_html_tags() {
		$allowed_tags = array(
			'a'        => array(
				'href'         => array(),
				'title'        => array(),
				'class'        => array(),
				'target'       => array(),
				'data-tooltip' => array(),
			),
			'ul'       => array(
				'class' => array(),
			),
			'li'       => array(
				'class' => array(),
			),
			'div'      => array(
				'class' => array(),
				'id'    => array(),
			),
			'select'   => array(
				'rel-id'   => array(),
				'id'       => array(),
				'name'     => array(),
				'class'    => array(),
				'multiple' => array(),
				'style'    => array(),
			),
			'input'    => array(
				'id'         => array(),
				'value'      => array(),
				'name'       => array(),
				'class'      => array(),
				'type'       => array(),
				'data-index' => array(),
			),
			'textarea' => array(
				'id'    => array(),
				'name'  => array(),
				'class' => array(),
			),
			'option'   => array(
				'id'       => array(),
				'selected' => array(),
				'name'     => array(),
				'value'    => array(),
			),
			'br'       => array(),
			'p'        => array(),
			'b'        => array(
				'style' => array(),
			),
			'em'       => array(),
			'strong'   => array(),
			'i'        => array(
				'class' => array(),
			),
			'span'     => array(
				'class' => array(),
			),
			'small'    => array(
				'class' => array(),
			),
			'label'    => array(
				'class' => array(),
				'id'    => array(),
				'for'   => array(),
			),
		);
		return $allowed_tags;
	}
	/**
	 * Fetch slug based on id
	 *
	 * @param array  $id_array  Array of id for wc-afrsm posttype.
	 * @param string $condition Using condition for product or etc.
	 *
	 * @return array $return_array Return array with post name.
	 *
	 * @since    4.1
	 */
	public function afrsfwa_fetch_slug( $id_array, $condition ) {
		$return_array = array();
		if ( ! empty( $id_array ) ) {
			foreach ( $id_array as $key => $ids ) {
				if ( ! empty( $ids ) ) {
					if ( 'product' === $condition || 'variableproduct' === $condition || 'cpp' === $condition || 'zone' === $condition ) {
						$get_posts = get_post( $ids );
						if ( ! empty( $get_posts ) ) {
							$return_array[] = $get_posts->post_name;
						}
					} elseif ( 'category' === $condition || 'cpc' === $condition ) {
						$term = get_term( $ids, 'product_cat' );
						if ( ! empty( $term ) ) {
							$return_array[] = $term->slug;
						}
					} elseif ( 'tag' === $condition ) {
						$tag = get_term( $ids, 'product_tag' );
						if ( ! empty( $tag ) ) {
							$return_array[] = $tag->slug;
						}
					} elseif ( 'shipping_class' === $condition ) {
						$shipping_class = get_term( $key, 'product_shipping_class' );
						if ( ! empty( $shipping_class ) ) {
							$return_array[ $shipping_class->slug ] = $ids;
						}
					} elseif ( 'cpsc' === $condition ) {
						$return_array[] = $ids;
					} elseif ( 'cpp' === $condition ) {
						$cpp_posts = get_post( $ids );
						if ( ! empty( $cpp_posts ) ) {
							$return_array[] = $cpp_posts->post_name;
						}
					} else {
						$return_array[] = $ids;
					}
				}
			}
		}
		return $return_array;
	}
	/**
	 * Fetch id based on slug
	 *
	 * @param array  $slug_array Array of slug for wc-afrsm posttype.
	 * @param string $condition  Using condition for product or etc.
	 *
	 * @return array $return_array Return array with post name.
	 *
	 * @since    4.1
	 */
	public function afrsfwa_fetch_id( $slug_array, $condition ) {
		$return_array = array();
		if ( ! empty( $slug_array ) ) {
			foreach ( $slug_array as $key => $slugs ) {
				if ( ! empty( $slugs ) ) {
					if ( 'product' === $condition ) {
						$post = get_page_by_path( $slugs, OBJECT, 'product' );
						if ( ! empty( $post ) ) {
							$id             = $post->ID;
							$return_array[] = $id;
						}
					} elseif ( 'variableproduct' === $condition ) {
						$args           = array(
							'post_type' => 'product_variation',
							'fields'    => 'ids',
							'name'      => $slugs,
						);
						$variable_posts = get_posts( $args );
						if ( ! empty( $variable_posts ) ) {
							foreach ( $variable_posts as $val ) {
								$return_array[] = $val;
							}
						}
					} elseif ( 'category' === $condition || 'cpc' === $condition ) {
						$term = get_term_by( 'slug', $slugs, 'product_cat' );
						if ( ! empty( $term ) ) {
							$return_array[] = $term->term_id;
						}
					} elseif ( 'tag' === $condition ) {
						$term_tag = get_term_by( 'slug', $slugs, 'product_tag' );
						if ( ! empty( $term_tag ) ) {
							$return_array[] = $term_tag->term_id;
						}
					} elseif ( 'shipping_class' === $condition
							|| 'cpsc' === $condition ) {
						$shipping_class = get_term_by( 'slug', $key, 'product_shipping_class' );
						if ( ! empty( $shipping_class ) ) {
							$return_array[ $shipping_class->term_id ] = $slugs;
						}
					} elseif ( 'cpp' === $condition ) {
						$args           = array(
							'post_type' => array( 'product_variation', 'product' ),
							'name'      => $slugs,
						);
						$variable_posts = get_posts( $args );
						if ( ! empty( $variable_posts ) ) {
							foreach ( $variable_posts as $val ) {
								$return_array[] = $val->ID;
							}
						}
					} elseif ( 'zone' === $condition ) {
						$post = get_page_by_path( $slugs, OBJECT, 'wc_afrsm_zone' );
						if ( ! empty( $post ) ) {
							$id             = $post->ID;
							$return_array[] = $id;
						}
					} else {
						$return_array[] = $slugs;
					}
				}
			}
		}
		return $return_array;
	}
	/**
	 * Export Shipping Method
	 *
	 * @since 4.1
	 */
	public function afrsfwa_import_export_shipping_method__premium_only() {
		$tab           = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$page          = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$export_action = filter_input( INPUT_POST, 'afrsm_export_action', FILTER_SANITIZE_STRING );
		$import_action = filter_input( INPUT_POST, 'afrsm_import_action', FILTER_SANITIZE_STRING );
		$default_lang  = $this->afrsfwa_get_default_langugae_with_sitpress();
		if ( ! empty( $export_action ) || 'export_settings' === $export_action ) {
			$get_all_fees_args  = array(
				'post_type'      => self::AFRSFWA_SHIPPING_POST_TYPE,
				'order'          => 'DESC',
				'posts_per_page' => - 1,
				'orderby'        => 'ID',
			);
			$get_all_fees_query = new WP_Query( $get_all_fees_args );
			$get_all_fees       = $get_all_fees_query->get_posts();
			$get_all_fees_count = $get_all_fees_query->found_posts;
			$get_sort_order     = get_option( 'sm_sortable_order_' . $default_lang );
			$sort_order         = array();
			if ( isset( $get_sort_order ) && ! empty( $get_sort_order ) ) {
				foreach ( $get_sort_order as $sort ) {
					$sort_order[ $sort ] = array();
				}
			}
			foreach ( $get_all_fees as $carrier_id => $carrier ) {
				$carrier_name = $carrier->ID;
				if ( array_key_exists( $carrier_name, $sort_order ) ) {
					$sort_order[ $carrier_name ][ $carrier_id ] = $get_all_fees[ $carrier_id ];
					unset( $get_all_fees[ $carrier_id ] );
				}
			}
			foreach ( $sort_order as $carriers ) {
				$get_all_fees = array_merge( $get_all_fees, $carriers );
			}
			$fees_data = array();
			$main_data = array();
			if ( $get_all_fees_count > 0 ) {
				foreach ( $get_all_fees as $fees ) {
					$request_post_id                        = $fees->ID;
					$sm_status                              = get_post_status( $request_post_id );
					$sm_title                               = get_the_title( $request_post_id );
					$sm_cost                                = get_post_meta( $request_post_id, 'sm_product_cost', true );
					$sm_tooltip_desc                        = get_post_meta( $request_post_id, 'sm_tooltip_desc', true );
					$sm_is_taxable                          = get_post_meta( $request_post_id, 'sm_select_taxable', true );
					$sm_metabox                             = get_post_meta( $request_post_id, 'sm_metabox', true );
					$sm_extra_cost                          = get_post_meta( $request_post_id, 'sm_extra_cost', true );
					$sm_extra_cost_calc_type                = get_post_meta( $request_post_id, 'sm_extra_cost_calculation_type', true );
					$ap_rule_status                         = get_post_meta( $request_post_id, 'ap_rule_status', true );
					$fee_settings_unique_shipping_title     = get_post_meta( $request_post_id, 'fee_settings_unique_shipping_title', true );
					$get_fees_per_qty_flag                  = get_post_meta( $request_post_id, 'how_to_apply', true );
					$get_fees_per_qty                       = get_post_meta( $request_post_id, 'sm_fee_per_qty', true );
					$extra_product_cost                     = get_post_meta( $request_post_id, 'sm_extra_product_cost', true );
					$sm_estimation_delivery                 = get_post_meta( $request_post_id, 'sm_estimation_delivery', true );
					$sm_start_date                          = get_post_meta( $request_post_id, 'sm_start_date', true );
					$sm_end_date                            = get_post_meta( $request_post_id, 'sm_end_date', true );
					$sm_time_from                           = get_post_meta( $request_post_id, 'sm_time_from', true );
					$sm_time_to                             = get_post_meta( $request_post_id, 'sm_time_to', true );
					$sm_select_day_of_week                  = get_post_meta( $request_post_id, 'sm_select_day_of_week', true );
					$cost_on_product_status                 = get_post_meta( $request_post_id, 'cost_on_product_status', true );
					$cost_on_product_weight_status          = get_post_meta( $request_post_id, 'cost_on_product_weight_status', true );
					$cost_on_product_subtotal_status        = get_post_meta( $request_post_id, 'cost_on_product_subtotal_status', true );
					$cost_on_category_status                = get_post_meta( $request_post_id, 'cost_on_category_status', true );
					$cost_on_category_weight_status         = get_post_meta( $request_post_id, 'cost_on_category_weight_status', true );
					$cost_on_category_subtotal_status       = get_post_meta( $request_post_id, 'cost_on_category_subtotal_status', true );
					$cost_on_total_cart_qty_status          = get_post_meta( $request_post_id, 'cost_on_total_cart_qty_status', true );
					$cost_on_total_cart_weight_status       = get_post_meta( $request_post_id, 'cost_on_total_cart_weight_status', true );
					$cost_on_total_cart_subtotal_status     = get_post_meta( $request_post_id, 'cost_on_total_cart_subtotal_status', true );
					$cost_on_shipping_class_subtotal_status = get_post_meta( $request_post_id, 'cost_on_shipping_class_subtotal_status', true );
					$sm_metabox_ap_product                  = get_post_meta( $request_post_id, 'sm_metabox_ap_product', true );
					$sm_metabox_ap_product_subtotal         = get_post_meta( $request_post_id, 'sm_metabox_ap_product_subtotal', true );
					$sm_metabox_ap_product_weight           = get_post_meta( $request_post_id, 'sm_metabox_ap_product_weight', true );
					$sm_metabox_ap_category                 = get_post_meta( $request_post_id, 'sm_metabox_ap_category', true );
					$sm_metabox_ap_category_subtotal        = get_post_meta( $request_post_id, 'sm_metabox_ap_category_subtotal', true );
					$sm_metabox_ap_category_weight          = get_post_meta( $request_post_id, 'sm_metabox_ap_category_weight', true );
					$sm_metabox_ap_total_cart_qty           = get_post_meta( $request_post_id, 'sm_metabox_ap_total_cart_qty', true );
					$sm_metabox_ap_total_cart_weight        = get_post_meta( $request_post_id, 'sm_metabox_ap_total_cart_weight', true );
					$sm_metabox_ap_total_cart_subtotal      = get_post_meta( $request_post_id, 'sm_metabox_ap_total_cart_subtotal', true );
					$sm_metabox_ap_shipping_class_subtotal  = get_post_meta( $request_post_id, 'sm_metabox_ap_shipping_class_subtotal', true );
					$cost_rule_match                        = get_post_meta( $request_post_id, 'cost_rule_match', true );
					$sm_metabox_customize                   = array();
					if ( ! empty( $sm_metabox ) ) {
						foreach ( $sm_metabox as $key => $val ) {
							if ( 'product' === $val['product_fees_conditions_condition']
								|| 'variableproduct' === $val['product_fees_conditions_condition']
								|| 'category' === $val['product_fees_conditions_condition']
								|| 'tag' === $val['product_fees_conditions_condition']
								|| 'zone' === $val['product_fees_conditions_condition']
							) {
								$product_fees_conditions_values = $this->afrsfwa_fetch_slug( $val['product_fees_conditions_values'], $val['product_fees_conditions_condition'] );
								$sm_metabox_customize[ $key ]   = array(
									'product_fees_conditions_condition' => $val['product_fees_conditions_condition'],
									'product_fees_conditions_is'        => $val['product_fees_conditions_is'],
									'product_fees_conditions_values'    => $product_fees_conditions_values,
								);
							} else {
								$sm_metabox_customize[ $key ] = array(
									'product_fees_conditions_condition' => $val['product_fees_conditions_condition'],
									'product_fees_conditions_is'        => $val['product_fees_conditions_is'],
									'product_fees_conditions_values'    => $val['product_fees_conditions_values'],
								);
							}
						}
					}
					if ( ! empty( $sm_extra_cost ) ) {
						foreach ( $sm_extra_cost as $key => $val ) {
							$shipping_class = $this->afrsfwa_fetch_slug( $sm_extra_cost, 'shipping_class' );
						}
					} else {
						$shipping_class = array();
					}
					$sm_metabox_ap_product_customize = array();
					if ( ! empty( $sm_metabox_ap_product ) ) {
						foreach ( $sm_metabox_ap_product as $key => $val ) {
							$ap_fees_products_values                 = $this->afrsfwa_fetch_slug( $val['ap_fees_products'], 'cpp' );
							$sm_metabox_ap_product_customize[ $key ] = array(
								'ap_fees_products'         => $ap_fees_products_values,
								'ap_fees_ap_prd_min_qty'   => $val['ap_fees_ap_prd_min_qty'],
								'ap_fees_ap_prd_max_qty'   => $val['ap_fees_ap_prd_max_qty'],
								'ap_fees_ap_price_product' => $val['ap_fees_ap_price_product'],
							);
						}
					}
					$sm_metabox_ap_product_subtotal_customize = array();
					if ( ! empty( $sm_metabox_ap_product_subtotal ) ) {
						foreach ( $sm_metabox_ap_product_subtotal as $key => $val ) {
							$ap_fees_product_subtotal_values                  = $this->afrsfwa_fetch_slug( $val['ap_fees_product_subtotal'], 'cpp' );
							$sm_metabox_ap_product_subtotal_customize[ $key ] = array(
								'ap_fees_product_subtotal' => $ap_fees_product_subtotal_values,
								'ap_fees_ap_product_subtotal_min_subtotal' => $val['ap_fees_ap_product_subtotal_min_subtotal'],
								'ap_fees_ap_product_subtotal_max_subtotal' => $val['ap_fees_ap_product_subtotal_max_subtotal'],
								'ap_fees_ap_price_product_subtotal' => $val['ap_fees_ap_price_product_subtotal'],
							);
						}
					}
					$sm_metabox_ap_product_weight_customize = array();
					if ( ! empty( $sm_metabox_ap_product_weight ) ) {
						foreach ( $sm_metabox_ap_product_weight as $key => $val ) {
							$ap_fees_product_weight_values                  = $this->afrsfwa_fetch_slug( $val['ap_fees_product_weight'], 'cpp' );
							$sm_metabox_ap_product_weight_customize[ $key ] = array(
								'ap_fees_product_weight' => $ap_fees_product_weight_values,
								'ap_fees_ap_product_weight_min_qty' => $val['ap_fees_ap_product_weight_min_qty'],
								'ap_fees_ap_product_weight_max_qty' => $val['ap_fees_ap_product_weight_max_qty'],
								'ap_fees_ap_price_product_weight' => $val['ap_fees_ap_price_product_weight'],
							);
						}
					}
					$sm_metabox_ap_category_customize = array();
					if ( ! empty( $sm_metabox_ap_category ) ) {
						foreach ( $sm_metabox_ap_category as $key => $val ) {
							$ap_fees_category_values                  = $this->afrsfwa_fetch_slug( $val['ap_fees_categories'], 'cpc' );
							$sm_metabox_ap_category_customize[ $key ] = array(
								'ap_fees_categories'     => $ap_fees_category_values,
								'ap_fees_ap_cat_min_qty' => $val['ap_fees_ap_cat_min_qty'],
								'ap_fees_ap_cat_max_qty' => $val['ap_fees_ap_cat_max_qty'],
								'ap_fees_ap_price_category' => $val['ap_fees_ap_price_category'],
							);
						}
					}
					$sm_metabox_ap_category_subtotal_customize = array();
					if ( ! empty( $sm_metabox_ap_category_subtotal ) ) {
						foreach ( $sm_metabox_ap_category_subtotal as $key => $val ) {
							$ap_fees_category_subtotal_values                  = $this->afrsfwa_fetch_slug( $val['ap_fees_category_subtotal'], 'cpc' );
							$sm_metabox_ap_category_subtotal_customize[ $key ] = array(
								'ap_fees_category_subtotal'                 => $ap_fees_category_subtotal_values,
								'ap_fees_ap_category_subtotal_min_subtotal' => $val['ap_fees_ap_category_subtotal_min_subtotal'],
								'ap_fees_ap_category_subtotal_max_subtotal' => $val['ap_fees_ap_category_subtotal_max_subtotal'],
								'ap_fees_ap_price_category_subtotal'        => $val['ap_fees_ap_price_category_subtotal'],
							);
						}
					}
					$sm_metabox_ap_category_weight_customize = array();
					if ( ! empty( $sm_metabox_ap_category_weight ) ) {
						foreach ( $sm_metabox_ap_category_weight as $key => $val ) {
							$ap_fees_category_weight_values                  = $this->afrsfwa_fetch_slug( $val['ap_fees_categories_weight'], 'cpc' );
							$sm_metabox_ap_category_weight_customize[ $key ] = array(
								'ap_fees_categories_weight'          => $ap_fees_category_weight_values,
								'ap_fees_ap_category_weight_min_qty' => $val['ap_fees_ap_category_weight_min_qty'],
								'ap_fees_ap_category_weight_max_qty' => $val['ap_fees_ap_category_weight_max_qty'],
								'ap_fees_ap_price_category_weight'   => $val['ap_fees_ap_price_category_weight'],
							);
						}
					}
					$sm_metabox_ap_total_cart_qty_customize = array();
					if ( ! empty( $sm_metabox_ap_total_cart_qty ) ) {
						foreach ( $sm_metabox_ap_total_cart_qty as $key => $val ) {
							$ap_fees_total_cart_qty_values                  = $this->afrsfwa_fetch_slug( $val['ap_fees_total_cart_qty'], '' );
							$sm_metabox_ap_total_cart_qty_customize[ $key ] = array(
								'ap_fees_total_cart_qty' => $ap_fees_total_cart_qty_values,
								'ap_fees_ap_total_cart_qty_min_qty' => $val['ap_fees_ap_total_cart_qty_min_qty'],
								'ap_fees_ap_total_cart_qty_max_qty' => $val['ap_fees_ap_total_cart_qty_max_qty'],
								'ap_fees_ap_price_total_cart_qty' => $val['ap_fees_ap_price_total_cart_qty'],
							);
						}
					}
					$sm_metabox_ap_total_cart_weight_customize = array();
					if ( ! empty( $sm_metabox_ap_total_cart_weight ) ) {
						foreach ( $sm_metabox_ap_total_cart_weight as $key => $val ) {
							$ap_fees_total_cart_weight_values                  = $this->afrsfwa_fetch_slug( $val['ap_fees_total_cart_weight'], '' );
							$sm_metabox_ap_total_cart_weight_customize[ $key ] = array(
								'ap_fees_total_cart_weight'               => $ap_fees_total_cart_weight_values,
								'ap_fees_ap_total_cart_weight_min_weight' => $val['ap_fees_ap_total_cart_weight_min_weight'],
								'ap_fees_ap_total_cart_weight_max_weight' => $val['ap_fees_ap_total_cart_weight_max_weight'],
								'ap_fees_ap_price_total_cart_weight'      => $val['ap_fees_ap_price_total_cart_weight'],
							);
						}
					}
					$sm_metabox_ap_total_cart_subtotal_customize = array();
					if ( ! empty( $sm_metabox_ap_total_cart_subtotal ) ) {
						foreach ( $sm_metabox_ap_total_cart_subtotal as $key => $val ) {
							$ap_fees_total_cart_subtotal_values                  = $this->afrsfwa_fetch_slug( $val['ap_fees_total_cart_subtotal'], '' );
							$sm_metabox_ap_total_cart_subtotal_customize[ $key ] = array(
								'ap_fees_total_cart_subtotal'                 => $ap_fees_total_cart_subtotal_values,
								'ap_fees_ap_total_cart_subtotal_min_subtotal' => $val['ap_fees_ap_total_cart_subtotal_min_subtotal'],
								'ap_fees_ap_total_cart_subtotal_max_subtotal' => $val['ap_fees_ap_total_cart_subtotal_max_subtotal'],
								'ap_fees_ap_price_total_cart_subtotal'        => $val['ap_fees_ap_price_total_cart_subtotal'],
							);
						}
					}
					$sm_metabox_ap_shipping_class_subtotal_customize = array();
					if ( ! empty( $sm_metabox_ap_shipping_class_subtotal ) ) {
						foreach ( $sm_metabox_ap_shipping_class_subtotal as $key => $val ) {
							$ap_fees_shipping_class_subtotal_values                  = $this->afrsfwa_fetch_slug( $val['ap_fees_shipping_class_subtotals'], 'cpsc' );
							$sm_metabox_ap_shipping_class_subtotal_customize[ $key ] = array(
								'ap_fees_shipping_class_subtotals'                => $ap_fees_shipping_class_subtotal_values,
								'ap_fees_ap_shipping_class_subtotal_min_subtotal' => $val['ap_fees_ap_shipping_class_subtotal_min_subtotal'],
								'ap_fees_ap_shipping_class_subtotal_max_subtotal' => $val['ap_fees_ap_shipping_class_subtotal_max_subtotal'],
								'ap_fees_ap_price_shipping_class_subtotal'        => $val['ap_fees_ap_price_shipping_class_subtotal'],
							);
						}
					}
					$fees_data[ $request_post_id ] = array(
						'sm_title'                         => $sm_title,
						'fee_settings_unique_shipping_title' => $fee_settings_unique_shipping_title,
						'sm_cost'                          => $sm_cost,
						'sm_tooltip_desc'                  => $sm_tooltip_desc,
						'sm_start_date'                    => $sm_start_date,
						'sm_end_date'                      => $sm_end_date,
						'sm_start_time'                    => $sm_time_from,
						'sm_end_time'                      => $sm_time_to,
						'sm_select_day_of_week'            => $sm_select_day_of_week,
						'sm_estimation_delivery'           => $sm_estimation_delivery,
						'sm_select_taxable'                => $sm_is_taxable,
						'status'                           => $sm_status,
						'product_fees_metabox'             => $sm_metabox_customize,
						'sm_extra_cost'                    => $shipping_class,
						'sm_extra_cost_calc_type'          => $sm_extra_cost_calc_type,
						'how_to_apply'                     => $get_fees_per_qty_flag,
						'sm_fee_per_qty'                   => $get_fees_per_qty,
						'sm_extra_product_cost'            => $extra_product_cost,
						'ap_rule_status'                   => $ap_rule_status,
						'cost_on_product_status'           => $cost_on_product_status,
						'cost_on_product_weight_status'    => $cost_on_product_weight_status,
						'cost_on_product_subtotal_status'  => $cost_on_product_subtotal_status,
						'cost_on_category_status'          => $cost_on_category_status,
						'cost_on_category_weight_status'   => $cost_on_category_weight_status,
						'cost_on_category_subtotal_status' => $cost_on_category_subtotal_status,
						'cost_on_total_cart_qty_status'    => $cost_on_total_cart_qty_status,
						'cost_on_total_cart_weight_status' => $cost_on_total_cart_weight_status,
						'cost_on_total_cart_subtotal_status' => $cost_on_total_cart_subtotal_status,
						'cost_on_shipping_class_subtotal_status' => $cost_on_shipping_class_subtotal_status,
						'sm_metabox_ap_product'            => $sm_metabox_ap_product_customize,
						'sm_metabox_ap_product_subtotal'   => $sm_metabox_ap_product_subtotal_customize,
						'sm_metabox_ap_product_weight'     => $sm_metabox_ap_product_weight_customize,
						'sm_metabox_ap_category'           => $sm_metabox_ap_category_customize,
						'sm_metabox_ap_category_subtotal'  => $sm_metabox_ap_category_subtotal_customize,
						'sm_metabox_ap_category_weight'    => $sm_metabox_ap_category_weight_customize,
						'sm_metabox_ap_total_cart_qty'     => $sm_metabox_ap_total_cart_qty_customize,
						'sm_metabox_ap_total_cart_weight'  => $sm_metabox_ap_total_cart_weight_customize,
						'sm_metabox_ap_total_cart_subtotal' => $sm_metabox_ap_total_cart_subtotal_customize,
						'sm_metabox_ap_shipping_class_subtotal' => $sm_metabox_ap_shipping_class_subtotal_customize,
						'cost_rule_match'                  => $cost_rule_match,
					);
				}
				$get_sort_order = get_option( 'sm_sortable_order_' . $default_lang );
				$main_data      = array(
					'fees_data'      => $fees_data,
					'shipping_order' => $get_sort_order,
				);
			}
			$afrsm_export_action_nonce = filter_input( INPUT_POST, 'afrsm_export_action_nonce', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $afrsm_export_action_nonce, 'afrsm_export_save_action_nonce' ) ) {
				return;
			}
			ignore_user_abort( true );
			nocache_headers();
			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=afrsw-settings-export-' . gmdate( 'm-d-Y' ) . '.json' );
			header( 'Expires: 0' );
			echo wp_json_encode( $main_data );
			exit;
		}
		if ( ! empty( $import_action ) || 'import_settings' === $import_action ) {
			$afrsm_import_action_nonce = filter_input( INPUT_POST, 'afrsm_import_action_nonce', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $afrsm_import_action_nonce, 'afrsm_import_action_nonce' ) ) {
				return;
			}
			$file_import_file_args              = array(
				'import_file' => array(
					'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
					'flags'  => FILTER_FORCE_ARRAY,
				),
			);
			$attached_import_files__arr         = filter_var_array( $_FILES, $file_import_file_args );
			$attached_import_files__arr_explode = explode( '.', $attached_import_files__arr['import_file']['name'] );
			$extension                          = end( $attached_import_files__arr_explode );
			if ( 'json' !== $extension ) {
				wp_die(
					esc_html__(
						'Please upload a valid .json file',
						'advanced-flat-rate-shipping-for-woocommerce'
					)
				);
			}
			$import_file = $attached_import_files__arr['import_file']['tmp_name'];
			if ( empty( $import_file ) ) {
				wp_die(
					esc_html__(
						'Please upload a file to import',
						'advanced-flat-rate-shipping-for-woocommerce'
					)
				);
			}
			WP_Filesystem();
			global $wp_filesystem;
			$file_data = $wp_filesystem->get_contents( $import_file );
			if ( ! empty( $file_data ) ) {
				$file_data_decode = json_decode( $file_data, true );
				$new_sorting_id   = array();
				if ( ! empty( $file_data_decode['fees_data'] ) ) {
					foreach ( $file_data_decode['fees_data'] as $fees_val ) {
						$fee_post    = array(
							'post_title'  => $fees_val['sm_title'],
							'post_status' => $fees_val['status'],
							'post_type'   => self::AFRSFWA_SHIPPING_POST_TYPE,
						);
						$get_post_id = wp_insert_post( $fee_post );
						if ( '' !== $get_post_id && 0 !== $get_post_id ) {
							if ( $get_post_id > 0 ) {
								$new_sorting_id[]     = $get_post_id;
								$sm_metabox_customize = array();
								if ( ! empty( $fees_val['product_fees_metabox'] ) ) {
									foreach ( $fees_val['product_fees_metabox'] as $key => $val ) {
										if ( 'product' === $val['product_fees_conditions_condition']
												|| 'variableproduct' === $val['product_fees_conditions_condition']
												|| 'category' === $val['product_fees_conditions_condition']
												|| 'tag' === $val['product_fees_conditions_condition']
												|| 'zone' === $val['product_fees_conditions_condition']
										) {
											$product_fees_conditions_values = $this->afrsfwa_fetch_id( $val['product_fees_conditions_values'], $val['product_fees_conditions_condition'] );
											$sm_metabox_customize[ $key ]   = array(
												'product_fees_conditions_condition' => $val['product_fees_conditions_condition'],
												'product_fees_conditions_is'        => $val['product_fees_conditions_is'],
												'product_fees_conditions_values'    => $product_fees_conditions_values,
											);
										} else {
											$sm_metabox_customize[ $key ] = array(
												'product_fees_conditions_condition' => $val['product_fees_conditions_condition'],
												'product_fees_conditions_is'        => $val['product_fees_conditions_is'],
												'product_fees_conditions_values'    => $val['product_fees_conditions_values'],
											);
										}
									}
								}
								if ( ! empty( $fees_val['sm_extra_cost'] ) ) {
									foreach ( $fees_val['sm_extra_cost'] as $key => $val ) {
										$shipping_class = $this->afrsfwa_fetch_id( $fees_val['sm_extra_cost'], 'shipping_class' );
									}
								} else {
									$shipping_class = array();
								}
								$sm_metabox_product_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_product'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_product'] as $key => $val ) {
										$ap_fees_products_values              = $this->afrsfwa_fetch_id( $val['ap_fees_products'], 'cpp' );
										$sm_metabox_product_customize[ $key ] = array(
											'ap_fees_products'         => $ap_fees_products_values,
											'ap_fees_ap_prd_min_qty'   => $val['ap_fees_ap_prd_min_qty'],
											'ap_fees_ap_prd_max_qty'   => $val['ap_fees_ap_prd_max_qty'],
											'ap_fees_ap_price_product' => $val['ap_fees_ap_price_product'],
										);
									}
								}
								$sm_metabox_ap_product_subtotal_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_product_subtotal'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_product_subtotal'] as $key => $val ) {
										$ap_fees_products_subtotal_values                 = $this->afrsfwa_fetch_id( $val['ap_fees_product_subtotal'], 'cpp' );
										$sm_metabox_ap_product_subtotal_customize[ $key ] = array(
											'ap_fees_product_subtotal'                 => $ap_fees_products_subtotal_values,
											'ap_fees_ap_product_subtotal_min_subtotal' => $val['ap_fees_ap_product_subtotal_min_subtotal'],
											'ap_fees_ap_product_subtotal_max_subtotal' => $val['ap_fees_ap_product_subtotal_max_subtotal'],
											'ap_fees_ap_price_product_subtotal'        => $val['ap_fees_ap_price_product_subtotal'],
										);
									}
								}
								$sm_metabox_ap_product_weight_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_product_weight'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_product_weight'] as $key => $val ) {
										$ap_fees_products_weight_values                 = $this->afrsfwa_fetch_id( $val['ap_fees_product_weight'], 'cpp' );
										$sm_metabox_ap_product_weight_customize[ $key ] = array(
											'ap_fees_product_weight'            => $ap_fees_products_weight_values,
											'ap_fees_ap_product_weight_min_qty' => $val['ap_fees_ap_product_weight_min_qty'],
											'ap_fees_ap_product_weight_max_qty' => $val['ap_fees_ap_product_weight_max_qty'],
											'ap_fees_ap_price_product_weight'   => $val['ap_fees_ap_price_product_weight'],
										);
									}
								}
								$sm_metabox_ap_category_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_category'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_category'] as $key => $val ) {
										$ap_fees_category_values                  = $this->afrsfwa_fetch_id( $val['ap_fees_categories'], 'cpc' );
										$sm_metabox_ap_category_customize[ $key ] = array(
											'ap_fees_categories'        => $ap_fees_category_values,
											'ap_fees_ap_cat_min_qty'    => $val['ap_fees_ap_cat_min_qty'],
											'ap_fees_ap_cat_max_qty'    => $val['ap_fees_ap_cat_max_qty'],
											'ap_fees_ap_price_category' => $val['ap_fees_ap_price_category'],
										);
									}
								}
								$sm_metabox_ap_category_subtotal_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_category_subtotal'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_category_subtotal'] as $key => $val ) {
										$ap_fees_ap_category_subtotal_values               = $this->afrsfwa_fetch_id( $val['ap_fees_category_subtotal'], 'cpc' );
										$sm_metabox_ap_category_subtotal_customize[ $key ] = array(
											'ap_fees_category_subtotal'                 => $ap_fees_ap_category_subtotal_values,
											'ap_fees_ap_category_subtotal_min_subtotal' => $val['ap_fees_ap_category_subtotal_min_subtotal'],
											'ap_fees_ap_category_subtotal_max_subtotal' => $val['ap_fees_ap_category_subtotal_max_subtotal'],
											'ap_fees_ap_price_category_subtotal'        => $val['ap_fees_ap_price_category_subtotal'],
										);
									}
								}
								$sm_metabox_ap_category_weight_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_category_weight'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_category_weight'] as $key => $val ) {
										$ap_fees_ap_category_weight_values               = $this->afrsfwa_fetch_id( $val['ap_fees_categories_weight'], 'cpc' );
										$sm_metabox_ap_category_weight_customize[ $key ] = array(
											'ap_fees_categories_weight'          => $ap_fees_ap_category_weight_values,
											'ap_fees_ap_category_weight_min_qty' => $val['ap_fees_ap_category_weight_min_qty'],
											'ap_fees_ap_category_weight_max_qty' => $val['ap_fees_ap_category_weight_max_qty'],
											'ap_fees_ap_price_category_weight'   => $val['ap_fees_ap_price_category_weight'],
										);
									}
								}
								$sm_metabox_ap_total_cart_qty_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_total_cart_qty'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_total_cart_qty'] as $key => $val ) {
										$ap_fees_ap_total_cart_qty_values               = $this->afrsfwa_fetch_id( $val['ap_fees_total_cart_qty'], '' );
										$sm_metabox_ap_total_cart_qty_customize[ $key ] = array(
											'ap_fees_total_cart_qty'            => $ap_fees_ap_total_cart_qty_values,
											'ap_fees_ap_total_cart_qty_min_qty' => $val['ap_fees_ap_total_cart_qty_min_qty'],
											'ap_fees_ap_total_cart_qty_max_qty' => $val['ap_fees_ap_total_cart_qty_max_qty'],
											'ap_fees_ap_price_total_cart_qty'   => $val['ap_fees_ap_price_total_cart_qty'],
										);
									}
								}
								$sm_metabox_ap_total_cart_weight_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_total_cart_weight'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_total_cart_weight'] as $key => $val ) {
										$ap_fees_ap_total_cart_weight_values               = $this->afrsfwa_fetch_id( $val['ap_fees_total_cart_weight'], '' );
										$sm_metabox_ap_total_cart_weight_customize[ $key ] = array(
											'ap_fees_total_cart_weight'               => $ap_fees_ap_total_cart_weight_values,
											'ap_fees_ap_total_cart_weight_min_weight' => $val['ap_fees_ap_total_cart_weight_min_weight'],
											'ap_fees_ap_total_cart_weight_max_weight' => $val['ap_fees_ap_total_cart_weight_max_weight'],
											'ap_fees_ap_price_total_cart_weight'      => $val['ap_fees_ap_price_total_cart_weight'],
										);
									}
								}
								$sm_metabox_ap_total_cart_subtotal_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_total_cart_subtotal'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_total_cart_subtotal'] as $key => $val ) {
										$ap_fees_ap_total_cart_subtotal_values               = $this->afrsfwa_fetch_id( $val['ap_fees_total_cart_subtotal'], '' );
										$sm_metabox_ap_total_cart_subtotal_customize[ $key ] = array(
											'ap_fees_total_cart_subtotal'                 => $ap_fees_ap_total_cart_subtotal_values,
											'ap_fees_ap_total_cart_subtotal_min_subtotal' => $val['ap_fees_ap_total_cart_subtotal_min_subtotal'],
											'ap_fees_ap_total_cart_subtotal_max_subtotal' => $val['ap_fees_ap_total_cart_subtotal_max_subtotal'],
											'ap_fees_ap_price_total_cart_subtotal'        => $val['ap_fees_ap_price_total_cart_subtotal'],
										);
									}
								}
								$sm_metabox_ap_shipping_class_subtotal_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_shipping_class_subtotal'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_shipping_class_subtotal'] as $key => $val ) {
										$ap_fees_ap_shipping_class_subtotal_values               = $this->afrsfwa_fetch_id( $val['ap_fees_shipping_class_subtotals'], 'cpsc' );
										$sm_metabox_ap_shipping_class_subtotal_customize[ $key ] = array(
											'ap_fees_shipping_class_subtotals'                => $ap_fees_ap_shipping_class_subtotal_values,
											'ap_fees_ap_shipping_class_subtotal_min_subtotal' => $val['ap_fees_ap_shipping_class_subtotal_min_subtotal'],
											'ap_fees_ap_shipping_class_subtotal_max_subtotal' => $val['ap_fees_ap_shipping_class_subtotal_max_subtotal'],
											'ap_fees_ap_price_shipping_class_subtotal'        => $val['ap_fees_ap_price_shipping_class_subtotal'],
										);
									}
								}
								update_post_meta( $get_post_id, 'fee_settings_unique_shipping_title', $fees_val['fee_settings_unique_shipping_title'] );
								update_post_meta( $get_post_id, 'sm_product_cost', $fees_val['sm_cost'] );
								update_post_meta( $get_post_id, 'sm_tooltip_desc', $fees_val['sm_tooltip_desc'] );
								update_post_meta( $get_post_id, 'sm_start_date', $fees_val['sm_start_date'] );
								update_post_meta( $get_post_id, 'sm_end_date', $fees_val['sm_end_date'] );
								update_post_meta( $get_post_id, 'sm_time_from', $fees_val['sm_start_time'] );
								update_post_meta( $get_post_id, 'sm_time_to', $fees_val['sm_end_time'] );
								update_post_meta( $get_post_id, 'sm_select_day_of_week', $fees_val['sm_select_day_of_week'] );
								update_post_meta( $get_post_id, 'sm_estimation_delivery', $fees_val['sm_estimation_delivery'] );
								update_post_meta( $get_post_id, 'sm_select_taxable', $fees_val['sm_select_taxable'] );
								update_post_meta( $get_post_id, 'sm_metabox', $sm_metabox_customize );
								update_post_meta( $get_post_id, 'sm_extra_cost', $shipping_class );
								update_post_meta( $get_post_id, 'sm_extra_cost_calculation_type', $fees_val['sm_extra_cost_calc_type'] );
								update_post_meta( $get_post_id, 'how_to_apply', $fees_val['how_to_apply'] );
								update_post_meta( $get_post_id, 'sm_fee_per_qty', $fees_val['sm_fee_per_qty'] );
								update_post_meta( $get_post_id, 'sm_extra_product_cost', $fees_val['sm_extra_product_cost'] );
								update_post_meta( $get_post_id, 'ap_rule_status', $fees_val['ap_rule_status'] );
								update_post_meta( $get_post_id, 'cost_on_product_status', $fees_val['cost_on_product_status'] );
								update_post_meta( $get_post_id, 'cost_on_product_weight_status', $fees_val['cost_on_product_weight_status'] );
								update_post_meta( $get_post_id, 'cost_on_product_subtotal_status', $fees_val['cost_on_product_subtotal_status'] );
								update_post_meta( $get_post_id, 'cost_on_category_status', $fees_val['cost_on_category_status'] );
								update_post_meta( $get_post_id, 'cost_on_category_weight_status', $fees_val['cost_on_category_weight_status'] );
								update_post_meta( $get_post_id, 'cost_on_category_subtotal_status', $fees_val['cost_on_category_subtotal_status'] );
								update_post_meta( $get_post_id, 'cost_on_total_cart_qty_status', $fees_val['cost_on_total_cart_qty_status'] );
								update_post_meta( $get_post_id, 'cost_on_total_cart_weight_status', $fees_val['cost_on_total_cart_weight_status'] );
								update_post_meta( $get_post_id, 'cost_on_total_cart_subtotal_status', $fees_val['cost_on_total_cart_subtotal_status'] );
								update_post_meta( $get_post_id, 'cost_on_shipping_class_subtotal_status', $fees_val['cost_on_shipping_class_subtotal_status'] );
								update_post_meta( $get_post_id, 'sm_metabox_ap_product', $sm_metabox_product_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_product_subtotal', $sm_metabox_ap_product_subtotal_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_product_weight', $sm_metabox_ap_product_weight_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_category', $sm_metabox_ap_category_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_category_subtotal', $sm_metabox_ap_category_subtotal_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_category_weight', $sm_metabox_ap_category_weight_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_qty', $sm_metabox_ap_total_cart_qty_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_weight', $sm_metabox_ap_total_cart_weight_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_subtotal', $sm_metabox_ap_total_cart_subtotal_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_shipping_class_subtotal', $sm_metabox_ap_shipping_class_subtotal_customize );
								update_post_meta( $get_post_id, 'cost_rule_match', $fees_val['cost_rule_match'] );
							}
						}
					}
					update_option( 'sm_sortable_order_' . $default_lang, $new_sorting_id );
				}
			}
			wp_safe_redirect(
				add_query_arg(
					array(
						'page'   => $page,
						'tab'    => $tab,
						'status' => 'success',
					),
					admin_url( 'admin.php' )
				)
			);
			exit();
		}
	}
	/**
	 * Export Zone
	 *
	 * @since 4.1
	 */
	public function afrsfwa_import_export_zone__premium_only() {
		$tab                = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$page               = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$get_all_fees_args  = array(
			'post_type'      => self::AFRSFWA_ZONE_POST_TYPE,
			'order'          => 'DESC',
			'posts_per_page' => - 1,
			'orderby'        => 'ID',
		);
		$get_all_fees_query = new WP_Query( $get_all_fees_args );
		$get_all_fees       = $get_all_fees_query->get_posts();
		$get_all_fees_count = $get_all_fees_query->found_posts;
		$fees_data          = array();
		if ( $get_all_fees_count > 0 ) {
			foreach ( $get_all_fees as $fees ) {
				$request_post_id               = $fees->ID;
				$sm_status                     = get_post_status( $request_post_id );
				$sm_title                      = get_the_title( $request_post_id );
				$location_type                 = get_post_meta( $request_post_id, 'location_type', true );
				$zone_type                     = get_post_meta( $request_post_id, 'zone_type', true );
				$location_code                 = get_post_meta( $request_post_id, 'location_code', true );
				$fees_data[ $request_post_id ] = array(
					'sm_title'      => $sm_title,
					'status'        => $sm_status,
					'location_type' => $location_type,
					'zone_type'     => $zone_type,
					'location_code' => $location_code,
				);
			}
		}
		$export_action = filter_input( INPUT_POST, 'afrsm_zone_export_action', FILTER_SANITIZE_STRING );
		$import_action = filter_input( INPUT_POST, 'afrsm_zone_import_action', FILTER_SANITIZE_STRING );
		if ( ! empty( $export_action ) || 'zone_export_settings' === $export_action ) {
			$afrsm_export_action_nonce = filter_input( INPUT_POST, 'afrsm_zone_export_action_nonce', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $afrsm_export_action_nonce, 'afrsm_zone_export_save_action_nonce' ) ) {
				return;
			}
			ignore_user_abort( true );
			nocache_headers();
			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=afrsw-zone-export-' . gmdate( 'm-d-Y' ) . '.json' );
			header( 'Expires: 0' );
			echo wp_json_encode( $fees_data );
			exit;
		}
		if ( ! empty( $import_action ) || 'zone_import_settings' === $import_action ) {
			$afrsm_import_action_nonce = filter_input( INPUT_POST, 'afrsm_zone_import_action_nonce', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $afrsm_import_action_nonce, 'afrsm_zone_import_action_nonce' ) ) {
				return;
			}
			$file_import_file_args              = array(
				'zone_import_file' => array(
					'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
					'flags'  => FILTER_FORCE_ARRAY,
				),
			);
			$attached_import_files__arr         = filter_var_array( $_FILES, $file_import_file_args );
			$attached_import_files__arr_explode = explode( '.', $attached_import_files__arr['zone_import_file']['name'] );
			$extension                          = end( $attached_import_files__arr_explode );
			if ( 'json' !== $extension ) {
				wp_die(
					esc_html__(
						'Please upload a valid .json file',
						'advanced-flat-rate-shipping-for-woocommerce'
					)
				);
			}
			$import_file = $attached_import_files__arr['zone_import_file']['tmp_name'];
			if ( empty( $import_file ) ) {
				wp_die(
					esc_html__(
						'Please upload a file to import',
						'advanced-flat-rate-shipping-for-woocommerce'
					)
				);
			}
			WP_Filesystem();
			global $wp_filesystem;
			$fees_data = $wp_filesystem->get_contents( $import_file );
			if ( ! empty( $fees_data ) ) {
				$fees_data_decode = json_decode( $fees_data, true );
				if ( ! empty( $fees_data_decode ) ) {
					foreach ( $fees_data_decode as $fees_val ) {
						if ( ! empty( $fees_val['sm_title'] ) ) {
							$fee_post    = array(
								'post_title'  => $fees_val['sm_title'],
								'post_status' => $fees_val['status'],
								'post_type'   => self::AFRSFWA_ZONE_POST_TYPE,
							);
							$get_post_id = wp_insert_post( $fee_post );
							if ( '' !== $get_post_id && 0 !== $get_post_id ) {
								if ( $get_post_id > 0 ) {
									update_post_meta( $get_post_id, 'location_type', $fees_val['location_type'] );
									update_post_meta( $get_post_id, 'zone_type', $fees_val['zone_type'] );
									update_post_meta( $get_post_id, 'location_code', $fees_val['location_code'] );
								}
							}
						}
					}
				}
			}
			wp_safe_redirect(
				add_query_arg(
					array(
						'page'   => $page,
						'tab'    => $tab,
						'status' => 'success',
					),
					admin_url( 'admin.php' )
				)
			);
			exit();
		}
	}
}
