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
 * AFRSM_Shipping_Zone_Page class.
 */
if ( ! class_exists( 'AFRSM_Shipping_Zone_Page' ) ) {
	/**
	 * AFRSM_Shipping_Zone_Page class.
	 */
	class AFRSM_Shipping_Zone_Page {
		/**
		 * Output the Admin UI
		 *
		 * @since 4.0
		 */
		const POST_TYPE = 'wc_afrsm_zone';
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
		 * @since 4.0
		 */
		public static function afrsmsz_register_post_type() {
			register_post_type(
				self::POST_TYPE,
				array(
					'labels'          => array(
						'name'          => __( 'Advance Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'singular_name' => __( 'Advance Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
					),
					'rewrite'         => false,
					'query_var'       => false,
					'public'          => false,
					'capability_type' => 'page',
					'capabilities'    =>
						array(
							'edit_post'          => 'edit_advance_shipping_zone',
							'read_post'          => 'read_advance_shipping_zone',
							'delete_post'        => 'delete_advance_shipping_zone',
							'edit_posts'         => 'edit_advance_shippings_zone',
							'edit_others_posts'  => 'edit_advance_shippings_zone',
							'publish_posts'      => 'edit_advance_shippings_zone',
							'read_private_posts' => 'edit_advance_shippings_zone',
						),
				)
			);
		}
		/**
		 * Display output
		 *
		 * @since    4.0
		 *
		 * @uses     Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin
		 * @uses     afrsmsz_sz_save_zone
		 * @uses     afrsmsz_sz_add_shipping_zone_form
		 * @uses     afrsmsz_sz_edit_zone_screen
		 * @uses     afrsmsz_sz_delete_zone
		 * @uses     afrsmsz_sz_duplicate_zone
		 * @uses     afrsmsz_sz_list_zones_screen
		 * @uses     Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin::afrsfwa_updated_message()
		 */
		public static function afrsmsz_sz_output() {
			self::$admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin( '', '' );
			$action             = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
			$post_id_request    = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
			$cust_nonce         = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_STRING );
			$get_afrsm_zone_add = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
			$get_tab            = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$message            = filter_input( INPUT_GET, 'message', FILTER_SANITIZE_STRING );
			if ( isset( $action ) && ! empty( $action ) ) {
				if ( 'add' === $action ) {
					self::afrsmsz_sz_save_zone();
					self::afrsmsz_sz_add_shipping_zone_form();
				} elseif ( 'edit' === $action ) {
					if ( isset( $cust_nonce ) && ! empty( $cust_nonce ) ) {
						$getnonce = wp_verify_nonce( $cust_nonce, 'edit_' . $post_id_request );
						if ( isset( $getnonce ) && 1 === $getnonce ) {
							self::afrsmsz_sz_edit_zone_screen( $post_id_request );
						} else {
							wp_safe_redirect(
								add_query_arg(
									array(
										'page' => 'afrsm-start-page',
										'tab'  => 'advance_shipping_zone',
									),
									admin_url( 'admin.php' )
								)
							);
							exit;
						}
					} elseif ( isset( $get_afrsm_zone_add ) && ! empty( $get_afrsm_zone_add ) ) {
						if ( ! wp_verify_nonce( $get_afrsm_zone_add, 'afrsm_zone_add' ) ) {
							$message = 'nonce_check';
						} else {
							self::afrsmsz_sz_edit_zone_screen( $post_id_request );
						}
					}
				} elseif ( 'delete' === $action ) {
					self::afrsmsz_sz_delete_zone( $post_id_request );
				} elseif ( 'duplicate' === $action ) {
					self::afrsmsz_sz_duplicate_zone( $post_id_request );
				} else {
					self::afrsmsz_sz_list_zones_screen();
				}
			} else {
				self::afrsmsz_sz_list_zones_screen();
			}
			if ( isset( $message ) && ! empty( $message ) ) {
				self::$admin_object->afrsfwa_updated_message( $message, $get_tab, '' );
			}
		}
		/**
		 * Delete zone
		 *
		 * @param int $id Get shipping method id.
		 *
		 * @uses     Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin::afrsfwa_updated_message()
		 *
		 * @since    4.0
		 */
		public function afrsmsz_sz_delete_zone( $id ) {
			$cust_nonce = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_STRING );
			$get_tab    = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$getnonce   = wp_verify_nonce( $cust_nonce, 'del_' . $id );
			if ( isset( $getnonce ) && 1 === $getnonce ) {
				wp_delete_post( $id );
				wp_safe_redirect(
					add_query_arg(
						array(
							'page'    => 'afrsm-start-page',
							'tab'     => 'advance_shipping_zone',
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
		 * Duplicate shipping zone
		 *
		 * @param int $id Get shipping method id.
		 *
		 * @uses     Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin::afrsfwa_updated_message()
		 *
		 * @since    1.0.0
		 */
		public function afrsmsz_sz_duplicate_zone( $id ) {
			$cust_nonce     = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_STRING );
			$get_tab        = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$getnonce       = wp_verify_nonce( $cust_nonce, 'duplicate_' . $id );
			$afrsm_zone_add = wp_create_nonce( 'afrsm_zone_add' );
			$post_id        = isset( $id ) ? absint( $id ) : '';
			$new_post_id    = '';
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
								'tab'      => 'advance_shipping_zone',
								'action'   => 'edit',
								'post'     => $new_post_id,
								'_wpnonce' => esc_attr( $afrsm_zone_add ),
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
								'tab'     => 'advance_shipping_zone',
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
		 * Count total shipping zone
		 *
		 * @return int $count_zone
		 * @since    4.0
		 */
		public static function afrsmsz_sz_count_zone() {
			$zone_args       = array(
				'post_type'      => self::POST_TYPE,
				'post_status'    => array( 'publish', 'draft' ),
				'posts_per_page' => - 1,
				'orderby'        => 'ID',
				'order'          => 'DESC',
			);
			$zone_post_query = new WP_Query( $zone_args );
			$zone_list       = $zone_post_query->posts;
			return count( $zone_list );
		}
		/**
		 * Save shipping method when add or edit
		 *
		 * @param int $method_id Get shipping method id.
		 *
		 * @return bool false when nonce is not verified, $zone id, $zone_type is blank, Country also blank, Postcode field also blank, saving error when form submit
		 * @uses     afrsmsz_sz_count_zone()
		 *
		 * @since    4.0
		 *
		 * @uses     Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin::afrsfwa_updated_message()
		 */
		private static function afrsmsz_sz_save_zone( $method_id = 0 ) {
			$action                      = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
			$get_tab                     = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$afrsm_save_zone             = filter_input( INPUT_POST, 'afrsm_save_zone', FILTER_SANITIZE_STRING );
			$woocommerce_save_zone_nonce = filter_input( INPUT_POST, 'woocommerce_save_zone_nonce', FILTER_SANITIZE_STRING );
			if ( ( isset( $action ) && ! empty( $action ) ) ) {
				if ( isset( $afrsm_save_zone ) ) {
					if ( empty( $woocommerce_save_zone_nonce ) || ! wp_verify_nonce( sanitize_text_field( $woocommerce_save_zone_nonce ), 'woocommerce_save_zone' ) ) {
						self::$admin_object->afrsfwa_updated_message( 'nonce_check', $get_tab, '' );
					}
					$zone_enabled            = filter_input( INPUT_POST, 'zone_enabled', FILTER_SANITIZE_STRING );
					$get_zone_name           = filter_input( INPUT_POST, 'zone_name', FILTER_SANITIZE_STRING );
					$get_zone_type           = filter_input( INPUT_POST, 'zone_type', FILTER_SANITIZE_STRING );
					$get_zone_type_countries = filter_input( INPUT_POST, 'zone_type_countries', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
					$get_zone_type_states    = filter_input( INPUT_POST, 'zone_type_states', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
					$get_zone_type_postcodes = filter_input( INPUT_POST, 'zone_type_postcodes', FILTER_SANITIZE_STRING );
					$get_postcodes           = filter_input( INPUT_POST, 'postcodes', FILTER_SANITIZE_STRING );
					$get_postcodes_explode   = explode( '\n', $get_postcodes );
					$zone_name               = isset( $get_zone_name ) ? sanitize_text_field( $get_zone_name ) : '';
					$zone_type               = isset( $get_zone_type ) ? sanitize_text_field( $get_zone_type ) : '';
					if ( empty( $zone_type ) ) {
						self::$admin_object->afrsfwa_updated_message(
							'validated',
							$get_tab,
							esc_html__( 'Please select at least one zone type', 'advanced-flat-rate-shipping-for-woocommerce' )
						);
						return false;
					}
					if ( ! empty( $zone_type ) && 'countries' === $zone_type ) {
						if ( empty( $get_zone_type_countries ) || '' === $get_zone_type_countries ) {
							self::$admin_object->afrsfwa_updated_message(
								'validated',
								$get_tab,
								esc_html__( 'Please select at least one country', 'advanced-flat-rate-shipping-for-woocommerce' )
							);
							return false;
						}
					}
					if ( ! empty( $zone_type ) && 'states' === $zone_type ) {
						if ( empty( $get_zone_type_states ) || '' === $get_zone_type_states ) {
							self::$admin_object->afrsfwa_updated_message(
								'validated',
								$get_tab,
								esc_html__( 'Please select at least one state', 'advanced-flat-rate-shipping-for-woocommerce' )
							);
							return false;
						}
					}
					if ( ! empty( $zone_type ) && 'postcodes' === $zone_type ) {
						if ( empty( $get_zone_type_postcodes ) || '' === $get_zone_type_postcodes ) {
							self::$admin_object->afrsfwa_updated_message(
								'validated',
								$get_tab,
								esc_html__( 'Please select at least one state or country for postcode', 'advanced-flat-rate-shipping-for-woocommerce' )
							);
							return false;
						}
						if ( empty( $get_postcodes ) || '' === $get_postcodes ) {
							self::$admin_object->afrsfwa_updated_message(
								'validated',
								$get_tab,
								esc_html__( 'Please enter at least one postcode', 'advanced-flat-rate-shipping-for-woocommerce' )
							);
							return false;
						}
					}
					$zone_count = self::afrsmsz_sz_count_zone();
					settype( $method_id, 'integer' );
					if ( isset( $zone_enabled ) ) {
						$post_status = 'publish';
					} else {
						$post_status = 'draft';
					}
					if ( '' !== $method_id && 0 !== $method_id ) {
						$fee_post  = array(
							'ID'          => $method_id,
							'post_title'  => $zone_name,
							'post_status' => $post_status,
							'menu_order'  => $zone_count + 1,
							'post_type'   => self::POST_TYPE,
						);
						$method_id = wp_update_post( $fee_post );
					} else {
						$fee_post  = array(
							'post_title'  => $zone_name,
							'post_status' => $post_status,
							'menu_order'  => $zone_count + 1,
							'post_type'   => self::POST_TYPE,
						);
						$method_id = wp_insert_post( $fee_post );
					}
					if ( '' !== $method_id && 0 !== $method_id ) {
						if ( $method_id > 0 ) {
							$location_code = array();
							if ( 'postcodes' === $zone_type ) {
								$location_code[ $get_zone_type_postcodes ] = $get_postcodes_explode;
								$location_type                             = 'postcode';
							} elseif ( 'countries' === $zone_type ) {
								$location_type   = 'country';
								$location_code[] = $get_zone_type_countries;
							} else {
								$location_type   = 'state';
								$location_code[] = $get_zone_type_states;
							}
							update_post_meta( $method_id, 'zone_type', $zone_type );
							update_post_meta( $method_id, 'location_type', $location_type );
							update_post_meta( $method_id, 'location_code', $location_code );
						}
					}
					$afrsm_zone_add = wp_create_nonce( 'afrsm_zone_add' );
					if ( 'add' === $action ) {
						wp_safe_redirect(
							add_query_arg(
								array(
									'page'     => 'afrsm-start-page',
									'tab'      => 'advance_shipping_zone',
									'action'   => 'edit',
									'post'     => $method_id,
									'_wpnonce' => esc_attr( $afrsm_zone_add ),
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
									'tab'      => 'advance_shipping_zone',
									'action'   => 'edit',
									'post'     => $method_id,
									'_wpnonce' => esc_attr( $afrsm_zone_add ),
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
		 * @uses     afrsmsz_sz_save_zone()
		 * @uses     afrsmsz_sz_edit_zone()
		 *
		 * @since    4.0
		 */
		public static function afrsmsz_sz_edit_zone_screen( $id ) {
			self::afrsmsz_sz_save_zone( $id );
			self::afrsmsz_sz_edit_zone();
		}
		/**
		 * Edit shipping method
		 *
		 * @since    4.0
		 */
		private static function afrsmsz_sz_edit_zone() {
			include plugin_dir_path( __FILE__ ) . 'form-shipping-zone.php';
		}
		/**
		 * List_shipping_zones function.
		 *
		 * @since    4.0
		 *
		 * @uses     WC_Shipping_Zones_Table class
		 * @uses     WC_Shipping_Zones_Table::process_bulk_action()
		 * @uses     WC_Shipping_Zones_Table::prepare_items()
		 * @uses     WC_Shipping_Zones_Table::search_box()
		 * @uses     WC_Shipping_Zones_Table::display()
		 */
		public static function afrsmsz_sz_list_zones_screen() {
			if ( ! class_exists( 'WC_Shipping_Methods_Table' ) ) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'list-tables/class-wc-shipping-zones-table.php';
			}
			$link = add_query_arg(
				array(
					'page'   => 'afrsm-start-page',
					'tab'    => 'advance_shipping_zone',
					'action' => 'add',
				),
				admin_url( 'admin.php' )
			);
			?>
			<h1 class="wp-heading-inline">
				<?php
				echo esc_html__( 'Advance Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' );
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
			$wc_shipping_zones_table = new WC_Shipping_Zones_Table();
			$wc_shipping_zones_table->process_bulk_action();
			$wc_shipping_zones_table->prepare_items();
			$wc_shipping_zones_table->search_box(
				esc_html__(
					'Search Shipping Zone',
					'advanced-flat-rate-shipping-for-woocommerce'
				),
				'afrsm-shipping-zone'
			);
			$wc_shipping_zones_table->display();
		}
		/**
		 * Add_shipping_zone_form function.
		 *
		 * @since    4.0
		 */
		public static function afrsmsz_sz_add_shipping_zone_form() {
			include plugin_dir_path( __FILE__ ) . 'form-shipping-zone.php';
		}
	}
}
