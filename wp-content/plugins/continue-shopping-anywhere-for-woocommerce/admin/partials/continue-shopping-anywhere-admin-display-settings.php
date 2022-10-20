<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://businessupwebsite.com
 * @since      1.0.0
 * @since      1.1.0 - Added position for single page
 * @since      1.2.0 - Single page - when to show and new position
 *
 * @package    Continue_Shopping_Anywhere
 * @subpackage Continue_Shopping_Anywhere/admin/partials
 */

if ( ! class_exists( 'Woo_Continue_Shopping_Anywhere_Settings' , false) ) {
	/**
	 * Woo_Continue_Shopping_Anywhere_Settings class
	 */
	class Woo_Continue_Shopping_Anywhere_Settings extends WC_Settings_Page {

			/**
			 * Constructor.
			 */
			public function __construct() {
				$this->id    = 'woocsa';
				$this->label = _x( 'Continue Shopping Anywhere', 'continue-shopping-anywhere' );
				parent::__construct();
			}

			/**
			 * Get sections.
			 *
			 * @return array
			 */
			public function get_sections() {
				$sections = array(
					''                => __( 'Cart', 'continue-shopping-anywhere' ),
					'checkout'        => __( 'Checkout', 'continue-shopping-anywhere' ),
					'single'        => __( 'Single', 'continue-shopping-anywhere' ),
				);

				return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
			}

			/**
			 * Get settings array.
			 *
			 * @return array
			 */
			public function get_settings( $current_section = '' ) {
				if ( '' === $current_section ) {
					$cart_custom_text = array();
					$is_redirect_to_cart = get_option( 'woocommerce_cart_redirect_after_add' );		
					if ( $is_redirect_to_cart == 'yes' ) {
						$dynamic_description = __( 'Settings for the link after being redirected to the cart.', 'continue-shopping-anywhere' ) ;
						$cart_options = array(
							'default'	=>	'Default (Previous Product)',
							'home'	=>	'Home',
							'shop'	=>	'Shop',
							'custom_link' =>	'Custom Link',
							'prev_page' =>	'Previous Page',
							'recent_category' =>	'Recent Category',
						);
						$default_cart_option = 'default';
						$cart_type = array(
							'title' => 'Redirect to',
							'type'  => 'radio',
							'default'   => $default_cart_option,
							'desc'     => '',
							'options' => $cart_options,
							'id'    => 'woocsa_cart_type_after_redirect',
						);
					} else {
						$dynamic_description = __( 'The link "Return Shopping" will always be shown. <i>If you want to be redirected to the cart after add to cart button, then set the "Redirect to the cart page after successful addition" checkbox <a href="'.get_admin_url('','/admin.php?page=wc-settings&tab=products').'">here</a></i>', 'continue-shopping-anywhere' ) ;
						$cart_options = array(
							'home'	=>	'Home',
							'shop'	=>	'Shop',
							'custom_link' =>	'Custom Link',
							'prev_page' =>	'Previous Page',
							'recent_category' =>	'Recent Category',
						);
						$default_cart_option = 'home';
						$cart_type = array(
							'title' => 'Redirect to',
							'type'  => 'radio',
							'default'   => $default_cart_option,
							'desc'     => '',
							'options' => $cart_options,
							'id'    => 'woocsa_cart_type_always',
						);
						$cart_custom_text = array(
							'title' => 'Text',
							'type'  => 'text',
							'default'   => __( 'Maybe you want to continue shopping?', 'continue-shopping-anywhere' ),
							'desc'     => '',
							'id'    => 'woocsa_cart_custom_text',
						);
					}

					$account_settings = array(
						array(
							'title' => 'Cart',
							'type'  => 'title',
							'desc'     => $dynamic_description,
							'id'    => 'woocsa_main',
						),
						array(
							'title' => 'Apply',
							'type'  => 'checkbox',
							'desc'     => '',
							'id'    => 'woocsa_cart_apply',
						),
						$cart_type,
						$cart_custom_text,
						array(
							'title' => 'Custom Link',
							'type'  => 'text',
							'default'   => '',
							'desc'     => '',
							'id'    => 'woocsa_cart_custom_link',
						),
					);				

					$sectioned=array(
						array(
							'type' => 'sectionend',
							'id'   => 'personal_data_retention',
						),
					);

					$account_settings = array_merge($account_settings, $sectioned);
				} elseif ( 'checkout' == $current_section ) {

					$checkout_options = array(
						'home'	=>	'Home',
						'shop'	=>	'Shop',
						'custom_link' =>	'Custom Link',
						'prev_page' =>	'Previous Page',
						'recent_category' =>	'Recent Category',
					);
					$default_checkout_option = 'home';
					$checkout_type = array(
						'title' => 'Redirect to',
						'type'  => 'radio',
						'default'   => $default_checkout_option,
						'desc'     => '',
						'options' => $checkout_options,
						'id'    => 'woocsa_checkout_type_always',
					);
					$account_settings = array(
						array(
							'title' => 'Checkout',
							'type'  => 'title',
							'desc'  => __( 'Checkout Settings', 'continue-shopping-anywhere' ),
							'id'    => 'woocsa_main',
						),
						array(
							'title' => 'Apply',
							'type'  => 'checkbox',
							'desc'     => '',
							'id'    => 'woocsa_checkout_apply',
						),
						$checkout_type,
						array(
							'title' => 'Text',
							'type'  => 'text',
							'default'   => __( 'Maybe you want to continue shopping?', 'continue-shopping-anywhere' ),
							'desc'     => '',
							'id'    => 'woocsa_checkout_custom_text',
						),
						array(
							'title' => 'Custom Link',
							'type'  => 'text',
							'default'   => '',
							'desc'     => '',
							'id'    => 'woocsa_checkout_custom_link',
						),
					);				

					$sectioned=array(
						array(
							'type' => 'sectionend',
							'id'   => 'woocsa_checkout',
						),
					);
					$account_settings = array_merge($account_settings, $sectioned);
				} elseif ( 'single' == $current_section ) {

					$single_options = array(
						'home'	=>	'Home',
						'shop'	=>	'Shop',
						'custom_link' =>	'Custom Link',
						'prev_page' =>	'Previous Page',
						'recent_category' =>	'Recent Category',
					);
					$default_single_option = 'home';
					$single_type = array(
						'title' => 'Redirect to',
						'type'  => 'radio',
						'default'   => $default_single_option,
						'desc'     => '',
						'options' => $single_options,
						'id'    => 'woocsa_single_type_always',
					);

					// position
					$single_positions = array(
						'woocommerce_before_single_product_summary'	=>	'Before Product Summary',
						'woocommerce_before_single_product'	=>	'Before Product',
						'woocommerce_after_single_product_summary'	=>	'After Product Summary',
						'woocommerce_after_single_product'	=>	'After Product',
					);
					$default_position_option = 'woocommerce_before_single_product_summary';
					$single_position = array(
						'title' => 'Position of the Message',
						'type'  => 'radio',
						'default'   => $default_position_option,
						'desc'     => '',
						'options' => $single_positions,
						'id'    => 'woocsa_single_position',
					);

					// when to show
					$single_conditions = array(
						'always'	=>	'Always',
						'out_of_stock'	=>	'Out of Stock only',
					);
					$default_condition_option = 'always';
					$single_condition = array(
						'title' => 'When to show?',
						'type'  => 'radio',
						'default'   => $default_condition_option,
						'desc'     => '',
						'options' => $single_conditions,
						'id'    => 'woocsa_single_condition',
					);

					// all options
					$account_settings = array(
						array(
							'title' => 'Single',
							'type'  => 'title',
							'desc'  => __( 'Single Settings', 'continue-shopping-anywhere' ),
							'id'    => 'woocsa_main',
						),
						array(
							'title' => 'Apply',
							'type'  => 'checkbox',
							'desc'     => '',
							'id'    => 'woocsa_single_apply',
						),
						$single_condition,
						$single_position,
						$single_type,
						array(
							'title' => 'Text',
							'type'  => 'text',
							'default'   => __( 'Maybe you want to continue shopping?', 'continue-shopping-anywhere' ),
							'desc'     => '',
							'id'    => 'woocsa_single_custom_text',
						),
						array(
							'title' => 'Custom Link',
							'type'  => 'text',
							'default'   => '',
							'desc'     => '',
							'id'    => 'woocsa_single_custom_link',
						),
					);				

					$sectioned=array(
						array(
							'type' => 'sectionend',
							'id'   => 'woocsa_single',
						),
					);
					$account_settings = array_merge($account_settings, $sectioned);
				}
				$settings = apply_filters(
					'woocsa_' . $this->id . '_settings',
					$account_settings
				);
				return apply_filters( 'woocsa_get_settings_' . $this->id, $settings, $current_section );
			}

			/**
			* Output the settings.
			*/
			public function output() {
				global $current_section;
				$settings = $this->get_settings( $current_section );
				WC_Admin_Settings::output_fields( $settings );		
			}

			/**
			 * Save settings.
			 */
			public function save() {
				global $current_section;

				$settings = $this->get_settings( $current_section );

				WC_Admin_Settings::save_fields( $settings );

				if ( $current_section ) {
					do_action( 'woocommerce_update_options_' . $this->id . '_' . $current_section );
				}
			}

		}
	}


	if ( class_exists( 'Woo_Continue_Shopping_Anywhere_Settings', false ) ) {
		return new Woo_Continue_Shopping_Anywhere_Settings();
	}
