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
/**
 * AFRSM_Shipping_Method_Page class.
 */
if ( ! class_exists( 'AFRSM_Shipping_Method_Page' ) ) {
	/**
	 * AFRSM_Shipping_Method_Page class.
	 */
	class AFRSM_Shipping_Method_Page {
		/**
		 * Output the Admin UI
		 *
		 * @since 3.5
		 */
		const POST_TYPE = 'wc_afrsm';
		/**
		 * Admin object call
		 *
		 * @since    3.5
		 * @var      string $admin_object The class of external plugin.
		 */
		private static $admin_object = null;
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
		}
		/**
		 * Display output
		 *
		 * @since    3.5
		 *
		 * @uses     Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin
		 * @uses     afrsmsmp_sz_save_method
		 * @uses     afrsmsmp_sz_add_shipping_method_form
		 * @uses     afrsmsmp_sz_edit_method_screen
		 * @uses     afrsmsmp_sz_delete_method
		 * @uses     afrsmsmp_sz_duplicate_method
		 * @uses     afrsmsmp_sz_list_methods_screen
		 * @uses     Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin::afrsfwa_updated_message()
		 */
		public static function afrsmsmp_sz_output() {
			self::$admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin( '', '' );
			$action             = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
			$post_id_request    = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
			$cust_nonce         = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_STRING );
			$get_afrsm_add      = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
			$get_tab            = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$message            = filter_input( INPUT_GET, 'message', FILTER_SANITIZE_STRING );
			if ( isset( $action ) && ! empty( $action ) ) {
				if ( 'add' === $action ) {
					self::afrsmsmp_sz_save_method();
					self::afrsmsmp_sz_add_shipping_method_form();
				} elseif ( 'edit' === $action ) {
					if ( isset( $cust_nonce ) && ! empty( $cust_nonce ) ) {
						$getnonce = wp_verify_nonce( $cust_nonce, 'edit_' . $post_id_request );
						if ( isset( $getnonce ) && 1 === $getnonce ) {
							self::afrsmsmp_sz_edit_method_screen( $post_id_request );
						} else {
							wp_safe_redirect(
								add_query_arg(
									array(
										'page' => 'afrsm-start-page',
										'tab'  => 'advance_shipping_method',
									),
									admin_url( 'admin.php' )
								)
							);
							exit;
						}
					} elseif ( isset( $get_afrsm_add ) && ! empty( $get_afrsm_add ) ) {
						if ( ! wp_verify_nonce( $get_afrsm_add, 'afrsm_add' ) ) {
							$message = 'nonce_check';
						} else {
							self::afrsmsmp_sz_edit_method_screen( $post_id_request );
						}
					}
				} elseif ( 'delete' === $action ) {
					self::afrsmsmp_sz_delete_method( $post_id_request );
				} elseif ( 'duplicate' === $action ) {
					self::afrsmsmp_sz_duplicate_method( $post_id_request );
				} else {
					self::afrsmsmp_sz_list_methods_screen();
				}
			} else {
				self::afrsmsmp_sz_list_methods_screen();
			}
			if ( isset( $message ) && ! empty( $message ) ) {
				self::$admin_object->afrsfwa_updated_message( $message, $get_tab, '' );
			}
		}
		/**
		 * Delete shipping method
		 *
		 * @param int $id Get shipping method id.
		 *
		 * @uses     Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin::afrsfwa_updated_message()
		 *
		 * @since    3.5
		 */
		public function afrsmsmp_sz_delete_method( $id ) {
			$cust_nonce = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_STRING );
			$get_tab    = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$getnonce   = wp_verify_nonce( $cust_nonce, 'del_' . $id );
			if ( isset( $getnonce ) && 1 === $getnonce ) {
				wp_delete_post( $id );
				wp_safe_redirect(
					add_query_arg(
						array(
							'page'    => 'afrsm-start-page',
							'tab'     => 'advance_shipping_method',
							'message' => 'deleted',
						),
						admin_url( 'admin.php' )
					)
				);
				exit;
			} else {
				self::$admin_object->afrsfwa_updated_message( 'nonce_check', $get_tab, '' );
			}
		}
		/**
		 * Duplicate shipping method
		 *
		 * @param int $id Get shipping method id.
		 *
		 * @uses     Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin::afrsfwa_updated_message()
		 *
		 * @since    1.0.0
		 */
		public function afrsmsmp_sz_duplicate_method( $id ) {
			$cust_nonce  = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_STRING );
			$get_tab     = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$getnonce    = wp_verify_nonce( $cust_nonce, 'duplicate_' . $id );
			$afrsm_add   = wp_create_nonce( 'afrsm_add' );
			$post_id     = isset( $id ) ? absint( $id ) : '';
			$new_post_id = '';
			if ( isset( $getnonce ) && 1 === $getnonce ) {
				if ( ! empty( $post_id ) || '' !== $post_id ) {
					$post            = get_post( $post_id );
					$current_user    = wp_get_current_user();
					$new_post_author = $current_user->ID;
					if ( isset( $post ) && null !== $post ) {
						$args           = array(
							'comment_status' => $post->comment_status,
							'ping_status'    => $post->ping_status,
							'post_author'    => $new_post_author,
							'post_content'   => $post->post_content,
							'post_excerpt'   => $post->post_excerpt,
							'post_name'      => $post->post_name,
							'post_parent'    => $post->post_parent,
							'post_password'  => $post->post_password,
							'post_status'    => 'draft',
							'post_title'     => $post->post_title . '-duplicate',
							'post_type'      => self::POST_TYPE,
							'to_ping'        => $post->to_ping,
							'menu_order'     => $post->menu_order,
						);
						$new_post_id    = wp_insert_post( $args );
						$post_meta_data = get_post_meta( $post_id );
						if ( 0 !== count( $post_meta_data ) ) {
							foreach ( $post_meta_data as $meta_key => $meta_data ) {
								if ( '_wp_old_slug' === $meta_key ) {
									continue;
								}
								if ( is_array( $meta_data[0] ) ) {
									$meta_value = maybe_unserialize( $meta_data[0] );
								} else {
									$meta_value = $meta_data[0];
								}
								update_post_meta( $new_post_id, $meta_key, $meta_value );
							}
						}
					}
					wp_safe_redirect(
						add_query_arg(
							array(
								'page'     => 'afrsm-start-page',
								'tab'      => 'advance_shipping_method',
								'action'   => 'edit',
								'post'     => $new_post_id,
								'_wpnonce' => esc_attr( $afrsm_add ),
								'message'  => 'duplicated',
							),
							admin_url( 'admin.php' )
						)
					);
					exit();
				} else {
					wp_safe_redirect(
						add_query_arg(
							array(
								'page'    => 'afrsm-start-page',
								'tab'     => 'advance_shipping_method',
								'message' => 'failed',
							),
							admin_url( 'admin.php' )
						)
					);
					exit();
				}
			} else {
				self::$admin_object->afrsfwa_updated_message( 'nonce_check', $get_tab, '' );
			}
		}
		/**
		 * Count total shipping method
		 *
		 * @return int $count_method Count total shipping method ID.
		 * @since    3.5
		 */
		public static function afrsmsmp_sm_count_method() {
			$shipping_method_args = array(
				'post_type'      => self::POST_TYPE,
				'post_status'    => array( 'publish', 'draft' ),
				'posts_per_page' => - 1,
				'orderby'        => 'ID',
				'order'          => 'DESC',
			);
			$sm_post_query        = new WP_Query( $shipping_method_args );
			$shipping_method_list = $sm_post_query->posts;
			return count( $shipping_method_list );
		}
		/**
		 * Save shipping method when add or edit
		 *
		 * @param int $method_id Shipping method id.
		 *
		 * @return bool false when nonce is not verified, $zone id, $zone_type is blank, Country also blank, Postcode field also blank, saving error when form submit.
		 * @uses     afrsmsmp_sm_count_method()
		 *
		 * @since    3.5
		 *
		 * @uses     Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin::afrsfwa_updated_message()
		 */
		private static function afrsmsmp_sz_save_method( $method_id = 0 ) {
			global $sitepress;
			$default_lang                  = self::$admin_object->afrsfwa_get_default_langugae_with_sitpress();
			$action                        = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
			$get_tab                       = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$afrsm_save                    = filter_input( INPUT_POST, 'afrsm_save', FILTER_SANITIZE_STRING );
			$woocommerce_save_method_nonce = filter_input( INPUT_POST, 'woocommerce_save_method_nonce', FILTER_SANITIZE_STRING );
			if ( ( isset( $action ) && ! empty( $action ) ) ) {
				if ( isset( $afrsm_save ) ) {
					if ( empty( $woocommerce_save_method_nonce ) || ! wp_verify_nonce( sanitize_text_field( $woocommerce_save_method_nonce ), 'woocommerce_save_method' ) ) {
						self::$admin_object->afrsfwa_updated_message( 'nonce_check', $get_tab, '' );
					}
					$fees                                       = filter_input( INPUT_POST, 'fees', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
					$sm_status                                  = filter_input( INPUT_POST, 'sm_status', FILTER_SANITIZE_STRING );
					$fee_settings_product_fee_title             = filter_input( INPUT_POST, 'fee_settings_product_fee_title', FILTER_SANITIZE_STRING );
					$get_condition_key                          = filter_input( INPUT_POST, 'condition_key', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
					$get_how_to_apply                           = filter_input( INPUT_POST, 'how_to_apply', FILTER_SANITIZE_STRING );
					$get_sm_product_cost                        = filter_input( INPUT_POST, 'sm_product_cost', FILTER_SANITIZE_STRING );
					$get_sm_fee_chk_qty_price                   = filter_input( INPUT_POST, 'sm_fee_chk_qty_price', FILTER_SANITIZE_STRING );
					$get_sm_fee_per_qty                         = filter_input( INPUT_POST, 'sm_fee_per_qty', FILTER_SANITIZE_STRING );
					$get_sm_extra_product_cost                  = filter_input( INPUT_POST, 'sm_extra_product_cost', FILTER_SANITIZE_STRING );
					$get_sm_tooltip_desc                        = filter_input( INPUT_POST, 'sm_tooltip_desc', FILTER_SANITIZE_STRING );
					$get_sm_select_taxable                      = filter_input( INPUT_POST, 'sm_select_taxable', FILTER_SANITIZE_STRING );
					$get_sm_estimation_delivery                 = filter_input( INPUT_POST, 'sm_estimation_delivery', FILTER_SANITIZE_STRING );
					$get_sm_start_date                          = filter_input( INPUT_POST, 'sm_start_date', FILTER_SANITIZE_STRING );
					$get_sm_end_date                            = filter_input( INPUT_POST, 'sm_end_date', FILTER_SANITIZE_STRING );
					$get_sm_extra_cost                          = filter_input( INPUT_POST, 'sm_extra_cost', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
					$get_sm_extra_cost_calculation_type         = filter_input( INPUT_POST, 'sm_extra_cost_calculation_type', FILTER_SANITIZE_STRING );
					$get_cost_on_product_status                 = filter_input( INPUT_POST, 'cost_on_product_status', FILTER_SANITIZE_STRING );
					$get_cost_on_product_weight_status          = filter_input( INPUT_POST, 'cost_on_product_weight_status', FILTER_SANITIZE_STRING );
					$get_cost_on_product_subtotal_status        = filter_input( INPUT_POST, 'cost_on_product_subtotal_status', FILTER_SANITIZE_STRING );
					$get_cost_on_category_status                = filter_input( INPUT_POST, 'cost_on_category_status', FILTER_SANITIZE_STRING );
					$get_cost_on_category_weight_status         = filter_input( INPUT_POST, 'cost_on_category_weight_status', FILTER_SANITIZE_STRING );
					$get_cost_on_category_subtotal_status       = filter_input( INPUT_POST, 'cost_on_category_subtotal_status', FILTER_SANITIZE_STRING );
					$get_cost_on_total_cart_qty_status          = filter_input( INPUT_POST, 'cost_on_total_cart_qty_status', FILTER_SANITIZE_STRING );
					$get_cost_on_total_cart_weight_status       = filter_input( INPUT_POST, 'cost_on_total_cart_weight_status', FILTER_SANITIZE_STRING );
					$get_cost_on_total_cart_subtotal_status     = filter_input( INPUT_POST, 'cost_on_total_cart_subtotal_status', FILTER_SANITIZE_STRING );
					$get_cost_on_shipping_class_subtotal_status = filter_input( INPUT_POST, 'cost_on_shipping_class_subtotal_status', FILTER_SANITIZE_STRING );
					$get_sm_select_day_of_week                  = filter_input( INPUT_POST, 'sm_select_day_of_week', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
					$get_sm_time_from                           = filter_input( INPUT_POST, 'sm_time_from', FILTER_SANITIZE_STRING );
					$get_sm_time_to                             = filter_input( INPUT_POST, 'sm_time_to', FILTER_SANITIZE_STRING );
					$get_cost_rule_match                        = filter_input( INPUT_POST, 'cost_rule_match', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
					$how_to_apply                               = isset( $get_how_to_apply ) ? sanitize_text_field( $get_how_to_apply ) : '';
					$sm_product_cost                            = isset( $get_sm_product_cost ) ? sanitize_text_field( $get_sm_product_cost ) : '';
					$sm_fee_chk_qty_price                       = isset( $get_sm_fee_chk_qty_price ) ? sanitize_text_field( $get_sm_fee_chk_qty_price ) : '';
					$sm_fee_per_qty                             = isset( $get_sm_fee_per_qty ) ? sanitize_text_field( $get_sm_fee_per_qty ) : '';
					$sm_extra_product_cost                      = isset( $get_sm_extra_product_cost ) ? sanitize_text_field( $get_sm_extra_product_cost ) : '';
					$sm_tooltip_desc                            = isset( $get_sm_tooltip_desc ) ? sanitize_textarea_field( $get_sm_tooltip_desc ) : '';
					$sm_select_taxable                          = isset( $get_sm_select_taxable ) ? sanitize_text_field( $get_sm_select_taxable ) : '';
					$sm_estimation_delivery                     = isset( $get_sm_estimation_delivery ) ? sanitize_text_field( $get_sm_estimation_delivery ) : '';
					$sm_start_date                              = isset( $get_sm_start_date ) ? sanitize_text_field( $get_sm_start_date ) : '';
					$sm_end_date                                = isset( $get_sm_end_date ) ? sanitize_text_field( $get_sm_end_date ) : '';
					$sm_extra_cost                              = isset( $get_sm_extra_cost ) ? array_map( 'sanitize_text_field', $get_sm_extra_cost ) : array();
					$sm_extra_cost_calculation_type             = isset( $get_sm_extra_cost_calculation_type ) ? sanitize_text_field( $get_sm_extra_cost_calculation_type ) : '';
					$cost_on_product_status                     = isset( $get_cost_on_product_status ) ? sanitize_text_field( $get_cost_on_product_status ) : 'off';
					$cost_on_product_weight_status              = isset( $get_cost_on_product_weight_status ) ? sanitize_text_field( $get_cost_on_product_weight_status ) : 'off';
					$cost_on_product_subtotal_status            = isset( $get_cost_on_product_subtotal_status ) ? sanitize_text_field( $get_cost_on_product_subtotal_status ) : 'off';
					$cost_on_category_status                    = isset( $get_cost_on_category_status ) ? sanitize_text_field( $get_cost_on_category_status ) : 'off';
					$cost_on_category_weight_status             = isset( $get_cost_on_category_weight_status ) ? sanitize_text_field( $get_cost_on_category_weight_status ) : 'off';
					$cost_on_category_subtotal_status           = isset( $get_cost_on_category_subtotal_status ) ? sanitize_text_field( $get_cost_on_category_subtotal_status ) : 'off';
					$cost_on_total_cart_qty_status              = isset( $get_cost_on_total_cart_qty_status ) ? sanitize_text_field( $get_cost_on_total_cart_qty_status ) : 'off';
					$cost_on_total_cart_weight_status           = isset( $get_cost_on_total_cart_weight_status ) ? sanitize_text_field( $get_cost_on_total_cart_weight_status ) : 'off';
					$cost_on_total_cart_subtotal_status         = isset( $get_cost_on_total_cart_subtotal_status ) ? sanitize_text_field( $get_cost_on_total_cart_subtotal_status ) : 'off';
					$cost_on_shipping_class_subtotal_status     = isset( $get_cost_on_shipping_class_subtotal_status ) ? sanitize_text_field( $get_cost_on_shipping_class_subtotal_status ) : 'off';
					$sm_select_day_of_week                      = isset( $get_sm_select_day_of_week ) ? array_map( 'sanitize_text_field', $get_sm_select_day_of_week ) : array();
					$sm_time_from                               = isset( $get_sm_time_from ) ? sanitize_text_field( $get_sm_time_from ) : '';
					$sm_time_to                                 = isset( $get_sm_time_to ) ? sanitize_text_field( $get_sm_time_to ) : '';
					$cost_rule_match                            = isset( $get_cost_rule_match ) ? array_map( 'sanitize_text_field', $get_cost_rule_match ) : array();
					$shipping_method_count                      = self::afrsmsmp_sm_count_method();
					settype( $method_id, 'integer' );
					if ( isset( $sm_status ) ) {
						$post_status = 'publish';
					} else {
						$post_status = 'draft';
					}
					if ( '' !== $method_id && 0 !== $method_id ) {
						$fee_post  = array(
							'ID'          => $method_id,
							'post_title'  => sanitize_text_field( $fee_settings_product_fee_title ),
							'post_status' => $post_status,
							'menu_order'  => $shipping_method_count + 1,
							'post_type'   => self::POST_TYPE,
						);
						$method_id = wp_update_post( $fee_post );
					} else {
						$fee_post  = array(
							'post_title'  => sanitize_text_field( $fee_settings_product_fee_title ),
							'post_status' => $post_status,
							'menu_order'  => $shipping_method_count + 1,
							'post_type'   => self::POST_TYPE,
						);
						$method_id = wp_insert_post( $fee_post );
					}
					if ( '' !== $method_id && 0 !== $method_id ) {
						if ( $method_id > 0 ) {
							$fees_array                     = array();
							$ap_product_arr                 = array();
							$ap_category_arr                = array();
							$ap_total_cart_qty_arr          = array();
							$ap_product_weight_arr          = array();
							$ap_category_weight_arr         = array();
							$ap_total_cart_weight_arr       = array();
							$ap_total_cart_subtotal_arr     = array();
							$ap_product_subtotal_arr        = array();
							$ap_category_subtotal_arr       = array();
							$ap_shipping_class_subtotal_arr = array();
							$ap_class_arr                   = array();
							$conditions_values_array        = array();
							$condition_key                  = isset( $get_condition_key ) ? $get_condition_key : array();
							$fees_conditions                = $fees['product_fees_conditions_condition'];
							$conditions_is                  = $fees['product_fees_conditions_is'];
							$conditions_values              = isset( $fees['product_fees_conditions_values'] ) && ! empty( $fees['product_fees_conditions_values'] ) ? $fees['product_fees_conditions_values'] : array();
							$size                           = count( $fees_conditions );
							foreach ( array_keys( $condition_key ) as $key ) {
								if ( ! array_key_exists( $key, $conditions_values ) ) {
									$conditions_values[ $key ] = array();
								}
							}
							uksort( $conditions_values, 'strnatcmp' );
							foreach ( $conditions_values as $v ) {
								$conditions_values_array[] = $v;
							}
							for ( $i = 0; $i < $size; $i ++ ) {
								$fees_array[] = array(
									'product_fees_conditions_condition' => $fees_conditions[ $i ],
									'product_fees_conditions_is'        => $conditions_is[ $i ],
									'product_fees_conditions_values'    => $conditions_values_array[ $i ],
								);
							}
							if ( isset( $fees['ap_product_fees_conditions_condition'] ) ) {
								$fees_products         = $fees['ap_product_fees_conditions_condition'];
								$fees_ap_prd_min_qty   = $fees['ap_fees_ap_prd_min_qty'];
								$fees_ap_prd_max_qty   = $fees['ap_fees_ap_prd_max_qty'];
								$fees_ap_price_product = $fees['ap_fees_ap_price_product'];
								$prd_arr               = array();
								foreach ( $fees_products as $fees_prd_val ) {
									$prd_arr[] = $fees_prd_val;
								}
								$size_product_cond = count( $fees_products );
								if ( ! empty( $size_product_cond ) && $size_product_cond > 0 ) :
									for ( $product_cnt = 0; $product_cnt < $size_product_cond; $product_cnt ++ ) {
										foreach ( $prd_arr as $prd_key => $prd_val ) {
											if ( $prd_key === $product_cnt ) {
												$ap_product_arr[] = array(
													'ap_fees_products'         => $prd_val,
													'ap_fees_ap_prd_min_qty'   => $fees_ap_prd_min_qty[ $product_cnt ],
													'ap_fees_ap_prd_max_qty'   => $fees_ap_prd_max_qty[ $product_cnt ],
													'ap_fees_ap_price_product' => $fees_ap_price_product[ $product_cnt ],
												);
											}
										}
									}
								endif;
							}
							if ( isset( $fees['ap_product_subtotal_fees_conditions_condition'] ) ) {
								$fees_product_subtotal            = $fees['ap_product_subtotal_fees_conditions_condition'];
								$fees_ap_product_subtotal_min_qty = $fees['ap_fees_ap_product_subtotal_min_subtotal'];
								$fees_ap_product_subtotal_max_qty = $fees['ap_fees_ap_product_subtotal_max_subtotal'];
								$fees_ap_product_subtotal_price   = $fees['ap_fees_ap_price_product_subtotal'];
								$product_subtotal_arr             = array();
								foreach ( $fees_product_subtotal as $fees_product_subtotal_val ) {
									$product_subtotal_arr[] = $fees_product_subtotal_val;
								}
								$size_product_subtotal_cond = count( $fees_product_subtotal );
								if ( ! empty( $size_product_subtotal_cond ) && $size_product_subtotal_cond > 0 ) :
									for ( $product_subtotal_cnt = 0; $product_subtotal_cnt < $size_product_subtotal_cond; $product_subtotal_cnt ++ ) {
										if ( ! empty( $product_subtotal_arr ) && '' !== $product_subtotal_arr ) {
											foreach ( $product_subtotal_arr as $product_subtotal_key => $product_subtotal_val ) {
												if ( $product_subtotal_key === $product_subtotal_cnt ) {
													$ap_product_subtotal_arr[] = array(
														'ap_fees_product_subtotal'                 => $product_subtotal_val,
														'ap_fees_ap_product_subtotal_min_subtotal' => $fees_ap_product_subtotal_min_qty[ $product_subtotal_cnt ],
														'ap_fees_ap_product_subtotal_max_subtotal' => $fees_ap_product_subtotal_max_qty[ $product_subtotal_cnt ],
														'ap_fees_ap_price_product_subtotal'        => $fees_ap_product_subtotal_price[ $product_subtotal_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							if ( isset( $fees['ap_product_weight_fees_conditions_condition'] ) ) {
								$fees_product_weight            = $fees['ap_product_weight_fees_conditions_condition'];
								$fees_ap_product_weight_min_qty = $fees['ap_fees_ap_product_weight_min_weight'];
								$fees_ap_product_weight_max_qty = $fees['ap_fees_ap_product_weight_max_weight'];
								$fees_ap_price_product_weight   = $fees['ap_fees_ap_price_product_weight'];
								$product_weight_arr             = array();
								foreach ( $fees_product_weight as $fees_product_weight_val ) {
									$product_weight_arr[] = $fees_product_weight_val;
								}
								$size_product_weight_cond = count( $fees_product_weight );
								if ( ! empty( $size_product_weight_cond ) && $size_product_weight_cond > 0 ) :
									for ( $product_weight_cnt = 0; $product_weight_cnt < $size_product_weight_cond; $product_weight_cnt ++ ) {
										if ( ! empty( $product_weight_arr ) && '' !== $product_weight_arr ) {
											foreach ( $product_weight_arr as $product_weight_key => $product_weight_val ) {
												if ( $product_weight_key === $product_weight_cnt ) {
													$ap_product_weight_arr[] = array(
														'ap_fees_product_weight'            => $product_weight_val,
														'ap_fees_ap_product_weight_min_qty' => $fees_ap_product_weight_min_qty[ $product_weight_cnt ],
														'ap_fees_ap_product_weight_max_qty' => $fees_ap_product_weight_max_qty[ $product_weight_cnt ],
														'ap_fees_ap_price_product_weight'   => $fees_ap_price_product_weight[ $product_weight_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							if ( isset( $fees['ap_category_fees_conditions_condition'] ) ) {
								$fees_categories        = $fees['ap_category_fees_conditions_condition'];
								$fees_ap_cat_min_qty    = $fees['ap_fees_ap_cat_min_qty'];
								$fees_ap_cat_max_qty    = $fees['ap_fees_ap_cat_max_qty'];
								$fees_ap_price_category = $fees['ap_fees_ap_price_category'];
								$cat_arr                = array();
								foreach ( $fees_categories as $fees_cat_val ) {
									$cat_arr[] = $fees_cat_val;
								}
								$size_category_cond = count( $fees_categories );
								if ( ! empty( $size_category_cond ) && $size_category_cond > 0 ) :
									for ( $category_cnt = 0; $category_cnt < $size_category_cond; $category_cnt ++ ) {
										if ( ! empty( $cat_arr ) && '' !== $cat_arr ) {
											foreach ( $cat_arr as $cat_key => $cat_val ) {
												if ( $cat_key === $category_cnt ) {
													$ap_category_arr[] = array(
														'ap_fees_categories'        => $cat_val,
														'ap_fees_ap_cat_min_qty'    => $fees_ap_cat_min_qty[ $category_cnt ],
														'ap_fees_ap_cat_max_qty'    => $fees_ap_cat_max_qty[ $category_cnt ],
														'ap_fees_ap_price_category' => $fees_ap_price_category[ $category_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							if ( isset( $fees['ap_category_subtotal_fees_conditions_condition'] ) ) {
								$fees_category_subtotal            = $fees['ap_category_subtotal_fees_conditions_condition'];
								$fees_ap_category_subtotal_min_qty = $fees['ap_fees_ap_category_subtotal_min_subtotal'];
								$fees_ap_category_subtotal_max_qty = $fees['ap_fees_ap_category_subtotal_max_subtotal'];
								$fees_ap_price_category_subtotal   = $fees['ap_fees_ap_price_category_subtotal'];
								$category_subtotal_arr             = array();
								foreach ( $fees_category_subtotal as $fees_category_subtotal_val ) {
									$category_subtotal_arr[] = $fees_category_subtotal_val;
								}
								$size_category_subtotal_cond = count( $fees_category_subtotal );
								if ( ! empty( $size_category_subtotal_cond ) && $size_category_subtotal_cond > 0 ) :
									for ( $category_subtotal_cnt = 0; $category_subtotal_cnt < $size_category_subtotal_cond; $category_subtotal_cnt ++ ) {
										if ( ! empty( $category_subtotal_arr ) && '' !== $category_subtotal_arr ) {
											foreach ( $category_subtotal_arr as $category_subtotal_key => $category_subtotal_val ) {
												if ( $category_subtotal_key === $category_subtotal_cnt ) {
													$ap_category_subtotal_arr[] = array(
														'ap_fees_category_subtotal'                 => $category_subtotal_val,
														'ap_fees_ap_category_subtotal_min_subtotal' => $fees_ap_category_subtotal_min_qty[ $category_subtotal_cnt ],
														'ap_fees_ap_category_subtotal_max_subtotal' => $fees_ap_category_subtotal_max_qty[ $category_subtotal_cnt ],
														'ap_fees_ap_price_category_subtotal'        => $fees_ap_price_category_subtotal[ $category_subtotal_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							if ( isset( $fees['ap_category_weight_fees_conditions_condition'] ) ) {
								$fees_category_weight            = $fees['ap_category_weight_fees_conditions_condition'];
								$fees_ap_category_weight_min_qty = $fees['ap_fees_ap_category_weight_min_weight'];
								$fees_ap_category_weight_max_qty = $fees['ap_fees_ap_category_weight_max_weight'];
								$fees_ap_price_category_weight   = $fees['ap_fees_ap_price_category_weight'];
								$category_weight_arr             = array();
								foreach ( $fees_category_weight as $fees_category_weight_val ) {
									$category_weight_arr[] = $fees_category_weight_val;
								}
								$size_category_weight_cond = count( $fees_category_weight );
								if ( ! empty( $size_category_weight_cond ) && $size_category_weight_cond > 0 ) :
									for ( $category_weight_cnt = 0; $category_weight_cnt < $size_category_weight_cond; $category_weight_cnt ++ ) {
										if ( ! empty( $category_weight_arr ) && '' !== $category_weight_arr ) {
											foreach ( $category_weight_arr as $category_weight_key => $category_weight_val ) {
												if ( $category_weight_key === $category_weight_cnt ) {
													$ap_category_weight_arr[] = array(
														'ap_fees_categories_weight'          => $category_weight_val,
														'ap_fees_ap_category_weight_min_qty' => $fees_ap_category_weight_min_qty[ $category_weight_cnt ],
														'ap_fees_ap_category_weight_max_qty' => $fees_ap_category_weight_max_qty[ $category_weight_cnt ],
														'ap_fees_ap_price_category_weight'   => $fees_ap_price_category_weight[ $category_weight_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							if ( isset( $fees['ap_total_cart_qty_fees_conditions_condition'] ) ) {
								$fees_total_cart_qty            = $fees['ap_total_cart_qty_fees_conditions_condition'];
								$fees_ap_total_cart_qty_min_qty = $fees['ap_fees_ap_total_cart_qty_min_qty'];
								$fees_ap_total_cart_qty_max_qty = $fees['ap_fees_ap_total_cart_qty_max_qty'];
								$fees_ap_price_total_cart_qty   = $fees['ap_fees_ap_price_total_cart_qty'];
								$total_cart_qty_arr             = array();
								foreach ( $fees_total_cart_qty as $fees_total_cart_qty_val ) {
									$total_cart_qty_arr[] = $fees_total_cart_qty_val;
								}
								$size_total_cart_qty_cond = count( $fees_total_cart_qty );
								if ( ! empty( $size_total_cart_qty_cond ) && $size_total_cart_qty_cond > 0 ) :
									for ( $total_cart_qty_cnt = 0; $total_cart_qty_cnt < $size_total_cart_qty_cond; $total_cart_qty_cnt ++ ) {
										if ( ! empty( $total_cart_qty_arr ) && '' !== $total_cart_qty_arr ) {
											foreach ( $total_cart_qty_arr as $total_cart_qty_key => $total_cart_qty_val ) {
												if ( $total_cart_qty_key === $total_cart_qty_cnt ) {
													$ap_total_cart_qty_arr[] = array(
														'ap_fees_total_cart_qty'            => $total_cart_qty_val,
														'ap_fees_ap_total_cart_qty_min_qty' => $fees_ap_total_cart_qty_min_qty[ $total_cart_qty_cnt ],
														'ap_fees_ap_total_cart_qty_max_qty' => $fees_ap_total_cart_qty_max_qty[ $total_cart_qty_cnt ],
														'ap_fees_ap_price_total_cart_qty'   => $fees_ap_price_total_cart_qty[ $total_cart_qty_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							if ( isset( $fees['ap_total_cart_weight_fees_conditions_condition'] ) ) {
								$fees_total_cart_weight               = $fees['ap_total_cart_weight_fees_conditions_condition'];
								$fees_ap_total_cart_weight_min_weight = $fees['ap_fees_ap_total_cart_weight_min_weight'];
								$fees_ap_total_cart_weight_max_weight = $fees['ap_fees_ap_total_cart_weight_max_weight'];
								$fees_ap_price_total_cart_weight      = $fees['ap_fees_ap_price_total_cart_weight'];
								$total_cart_weight_arr                = array();
								foreach ( $fees_total_cart_weight as $fees_total_cart_weight_val ) {
									$total_cart_weight_arr[] = $fees_total_cart_weight_val;
								}
								$size_total_cart_weight_cond = count( $fees_total_cart_weight );
								if ( ! empty( $size_total_cart_weight_cond ) && $size_total_cart_weight_cond > 0 ) :
									for ( $total_cart_weight_cnt = 0; $total_cart_weight_cnt < $size_total_cart_weight_cond; $total_cart_weight_cnt ++ ) {
										if ( ! empty( $total_cart_weight_arr ) && '' !== $total_cart_weight_arr ) {
											foreach ( $total_cart_weight_arr as $total_cart_weight_key => $total_cart_weight_val ) {
												if ( $total_cart_weight_key === $total_cart_weight_cnt ) {
													$ap_total_cart_weight_arr[] = array(
														'ap_fees_total_cart_weight'               => $total_cart_weight_val,
														'ap_fees_ap_total_cart_weight_min_weight' => $fees_ap_total_cart_weight_min_weight[ $total_cart_weight_cnt ],
														'ap_fees_ap_total_cart_weight_max_weight' => $fees_ap_total_cart_weight_max_weight[ $total_cart_weight_cnt ],
														'ap_fees_ap_price_total_cart_weight'      => $fees_ap_price_total_cart_weight[ $total_cart_weight_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							if ( isset( $fees['ap_total_cart_subtotal_fees_conditions_condition'] ) ) {
								$fees_total_cart_subtotal                 = $fees['ap_total_cart_subtotal_fees_conditions_condition'];
								$fees_ap_total_cart_subtotal_min_subtotal = $fees['ap_fees_ap_total_cart_subtotal_min_subtotal'];
								$fees_ap_total_cart_subtotal_max_subtotal = $fees['ap_fees_ap_total_cart_subtotal_max_subtotal'];
								$fees_ap_price_total_cart_subtotal        = $fees['ap_fees_ap_price_total_cart_subtotal'];
								$total_cart_subtotal_arr                  = array();
								foreach ( $fees_total_cart_subtotal as $total_cart_subtotal_key => $total_cart_subtotal_val ) {
									$total_cart_subtotal_arr[] = $total_cart_subtotal_val;
								}
								$size_total_cart_subtotal_cond = count( $fees_total_cart_subtotal );
								if ( ! empty( $size_total_cart_subtotal_cond ) && $size_total_cart_subtotal_cond > 0 ) :
									for ( $total_cart_subtotal_cnt = 0; $total_cart_subtotal_cnt < $size_total_cart_subtotal_cond; $total_cart_subtotal_cnt ++ ) {
										if ( ! empty( $total_cart_subtotal_arr ) && '' !== $total_cart_subtotal_arr ) {
											foreach ( $total_cart_subtotal_arr as $total_cart_subtotal_key => $total_cart_subtotal_val ) {
												if ( $total_cart_subtotal_key === $total_cart_subtotal_cnt ) {
													$ap_total_cart_subtotal_arr[] = array(
														'ap_fees_total_cart_subtotal'                 => $total_cart_subtotal_val,
														'ap_fees_ap_total_cart_subtotal_min_subtotal' => $fees_ap_total_cart_subtotal_min_subtotal[ $total_cart_subtotal_cnt ],
														'ap_fees_ap_total_cart_subtotal_max_subtotal' => $fees_ap_total_cart_subtotal_max_subtotal[ $total_cart_subtotal_cnt ],
														'ap_fees_ap_price_total_cart_subtotal'        => $fees_ap_price_total_cart_subtotal[ $total_cart_subtotal_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							if ( isset( $fees['ap_shipping_class_subtotal_fees_conditions_condition'] ) ) {
								$fees_shipping_class_subtotal                 = $fees['ap_shipping_class_subtotal_fees_conditions_condition'];
								$fees_ap_shipping_class_subtotal_min_subtotal = $fees['ap_fees_ap_shipping_class_subtotal_min_subtotal'];
								$fees_ap_shipping_class_subtotal_max_subtotal = $fees['ap_fees_ap_shipping_class_subtotal_max_subtotal'];
								$fees_ap_price_shipping_class_subtotal        = $fees['ap_fees_ap_price_shipping_class_subtotal'];
								$shipping_class_subtotal_arr                  = array();
								foreach ( $fees_shipping_class_subtotal as $shipping_class_subtotal_key => $shipping_class_subtotal_val ) {
									$shipping_class_subtotal_arr[] = $shipping_class_subtotal_val;
								}
								$size_shipping_class_subtotal_cond = count( $fees_shipping_class_subtotal );
								if ( ! empty( $size_shipping_class_subtotal_cond ) && $size_shipping_class_subtotal_cond > 0 ) :
									for ( $shipping_class_subtotal_cnt = 0; $shipping_class_subtotal_cnt < $size_shipping_class_subtotal_cond; $shipping_class_subtotal_cnt ++ ) {
										if ( ! empty( $shipping_class_subtotal_arr ) && '' !== $shipping_class_subtotal_arr ) {
											foreach ( $shipping_class_subtotal_arr as $shipping_class_subtotal_key => $shipping_class_subtotal_val ) {
												if ( $shipping_class_subtotal_key === $shipping_class_subtotal_cnt ) {
													$ap_shipping_class_subtotal_arr[] = array(
														'ap_fees_shipping_class_subtotals'                => $shipping_class_subtotal_val,
														'ap_fees_ap_shipping_class_subtotal_min_subtotal' => $fees_ap_shipping_class_subtotal_min_subtotal[ $shipping_class_subtotal_cnt ],
														'ap_fees_ap_shipping_class_subtotal_max_subtotal' => $fees_ap_shipping_class_subtotal_max_subtotal[ $shipping_class_subtotal_cnt ],
														'ap_fees_ap_price_shipping_class_subtotal'        => $fees_ap_price_shipping_class_subtotal[ $shipping_class_subtotal_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							update_post_meta( $method_id, 'cost_rule_match', $cost_rule_match );
							update_post_meta( $method_id, 'sm_product_cost', $sm_product_cost );
							update_post_meta( $method_id, 'how_to_apply', $how_to_apply );
							update_post_meta( $method_id, 'sm_fee_chk_qty_price', $sm_fee_chk_qty_price );
							update_post_meta( $method_id, 'sm_fee_per_qty', $sm_fee_per_qty );
							update_post_meta( $method_id, 'sm_extra_product_cost', $sm_extra_product_cost );
							update_post_meta( $method_id, 'sm_tooltip_desc', $sm_tooltip_desc );
							update_post_meta( $method_id, 'sm_select_taxable', $sm_select_taxable );
							update_post_meta( $method_id, 'sm_estimation_delivery', $sm_estimation_delivery );
							update_post_meta( $method_id, 'sm_start_date', $sm_start_date );
							update_post_meta( $method_id, 'sm_end_date', $sm_end_date );
							update_post_meta( $method_id, 'sm_extra_cost', $sm_extra_cost );
							update_post_meta( $method_id, 'sm_extra_cost_calculation_type', $sm_extra_cost_calculation_type );
							update_post_meta( $method_id, 'cost_on_product_status', $cost_on_product_status );
							update_post_meta( $method_id, 'cost_on_product_weight_status', $cost_on_product_weight_status );
							update_post_meta( $method_id, 'cost_on_product_subtotal_status', $cost_on_product_subtotal_status );
							update_post_meta( $method_id, 'cost_on_category_status', $cost_on_category_status );
							update_post_meta( $method_id, 'cost_on_category_weight_status', $cost_on_category_weight_status );
							update_post_meta( $method_id, 'cost_on_category_subtotal_status', $cost_on_category_subtotal_status );
							update_post_meta( $method_id, 'cost_on_total_cart_qty_status', $cost_on_total_cart_qty_status );
							update_post_meta( $method_id, 'cost_on_total_cart_weight_status', $cost_on_total_cart_weight_status );
							update_post_meta( $method_id, 'cost_on_total_cart_subtotal_status', $cost_on_total_cart_subtotal_status );
							update_post_meta( $method_id, 'cost_on_shipping_class_subtotal_status', $cost_on_shipping_class_subtotal_status );
							if ( isset( $sm_select_day_of_week ) ) {
								update_post_meta( $method_id, 'sm_select_day_of_week', $sm_select_day_of_week );
							}
							if ( isset( $sm_time_from ) ) {
								update_post_meta( $method_id, 'sm_time_from', $sm_time_from );
							}
							if ( isset( $sm_time_to ) ) {
								update_post_meta( $method_id, 'sm_time_to', $sm_time_to );
							}
							update_post_meta( $method_id, 'sm_metabox', $fees_array );
							update_post_meta( $method_id, 'sm_metabox_ap_product', $ap_product_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_product_weight', $ap_product_weight_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_product_subtotal', $ap_product_subtotal_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_category', $ap_category_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_category_weight', $ap_category_weight_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_category_subtotal', $ap_category_subtotal_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_total_cart_qty', $ap_total_cart_qty_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_total_cart_weight', $ap_total_cart_weight_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_total_cart_subtotal', $ap_total_cart_subtotal_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_shipping_class_subtotal', $ap_shipping_class_subtotal_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_class', $ap_class_arr );
							if ( ! empty( $sitepress ) ) {
								do_action(
									'wpml_register_single_string',
									'advanced-flat-rate-shipping-for-woocommerce',
									sanitize_text_field( $fee_settings_product_fee_title ),
									sanitize_text_field( $fee_settings_product_fee_title )
								);
							}
							if ( 'edit' !== $action ) {
								$get_sort_order = get_option( 'sm_sortable_order_' . $default_lang );
								if ( ! empty( $get_sort_order ) ) {
									foreach ( $get_sort_order as $get_sort_order_id ) {
										settype( $get_sort_order_id, 'integer' );
									}
									array_unshift( $get_sort_order, $method_id );
								}
								update_option( 'sm_sortable_order_' . $default_lang, $get_sort_order );
							}
						}
					} else {
						echo '<div class="updated error"><p>' . esc_html__( 'Error saving shipping method.', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</p></div>';
						return false;
					}
					$afrsm_add = wp_create_nonce( 'afrsm_add' );
					if ( 'add' === $action ) {
						wp_safe_redirect(
							add_query_arg(
								array(
									'page'     => 'afrsm-start-page',
									'tab'      => 'advance_shipping_method',
									'action'   => 'edit',
									'post'     => $method_id,
									'_wpnonce' => esc_attr( $afrsm_add ),
									'message'  => 'created',
								),
								admin_url( 'admin.php' )
							)
						);
						exit();
					}
					if ( 'edit' === $action ) {
						wp_safe_redirect(
							add_query_arg(
								array(
									'page'     => 'afrsm-start-page',
									'tab'      => 'advance_shipping_method',
									'action'   => 'edit',
									'post'     => $method_id,
									'_wpnonce' => esc_attr( $afrsm_add ),
									'message'  => 'saved',
								),
								admin_url( 'admin.php' )
							)
						);
						exit();
					}
				}
			}
		}
		/**
		 * Edit shipping method screen
		 *
		 * @param string $id Get shipping method id.
		 *
		 * @uses     afrsmsmp_sz_save_method()
		 * @uses     afrsmsmp_sz_edit_method()
		 *
		 * @since    3.5
		 */
		public static function afrsmsmp_sz_edit_method_screen( $id ) {
			self::afrsmsmp_sz_save_method( $id );
			self::afrsmsmp_sz_edit_method();
		}
		/**
		 * Edit shipping method
		 *
		 * @since    3.5
		 */
		public static function afrsmsmp_sz_edit_method() {
			include plugin_dir_path( __FILE__ ) . 'form-shipping-method.php';
		}
		/**
		 * List_shipping_methods function.
		 *
		 * @since    3.5
		 *
		 * @uses     WC_Shipping_Methods_Table class
		 * @uses     WC_Shipping_Methods_Table::process_bulk_action()
		 * @uses     WC_Shipping_Methods_Table::prepare_items()
		 * @uses     WC_Shipping_Methods_Table::search_box()
		 * @uses     WC_Shipping_Methods_Table::display()
		 */
		public static function afrsmsmp_sz_list_methods_screen() {
			if ( ! class_exists( 'WC_Shipping_Methods_Table' ) ) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'list-tables/class-wc-shipping-methods-table.php';
			}
			$link = add_query_arg(
				array(
					'page'   => 'afrsm-start-page',
					'tab'    => 'advance_shipping_method',
					'action' => 'add',
				),
				admin_url( 'admin.php' )
			);
			?>
			<h1 class="wp-heading-inline">
				<?php
				echo esc_html( __( 'Advance Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' ) );
				?>
			</h1>
			<a href="<?php echo esc_url( $link ); ?>" class="page-title-action"><?php echo esc_html__( 'Add New', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
			<?php
			$request_s = filter_input( INPUT_POST, 's', FILTER_SANITIZE_STRING );
			if ( isset( $request_s ) && ! empty( $request_s ) ) {
				?>
				<span class="subtitle">
					<?php
					echo esc_html__( 'Search results for ', 'advanced-flat-rate-shipping-for-woocommerce' ) . '&#8220;' . esc_html( $request_s ) . '&#8221;';
					?>
				</span>
				<?php
			}
			?>
			<hr class="wp-header-end">
			<?php
			$wc_shipping_methods_table = new WC_Shipping_Methods_Table();
			$wc_shipping_methods_table->process_bulk_action();
			$wc_shipping_methods_table->prepare_items();
			$wc_shipping_methods_table->search_box(
				esc_html__(
					'Search Shipping Method',
					'advanced-flat-rate-shipping-for-woocommerce'
				),
				'afrsm-shipping'
			);
			$wc_shipping_methods_table->display();
		}
		/**
		 * Add_shipping_method_form function.
		 *
		 * @since    3.5
		 */
		public static function afrsmsmp_sz_add_shipping_method_form() {
			include plugin_dir_path( __FILE__ ) . 'form-shipping-method.php';
		}
	}
}
