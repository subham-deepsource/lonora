<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce/admin/list-tables
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
/**
 * WC_Shipping_Zones_Table class.
 *
 * @extends WP_List_Table
 */
if ( ! class_exists( 'WC_Shipping_Zones_Table' ) ) {
	/**
	 * WC_Shipping_Zones_Table class.
	 */
	class WC_Shipping_Zones_Table extends WP_List_Table {
		/**
		 * Output the Admin UI
		 *
		 * @since 3.5
		 */
		const POST_TYPE = 'wc_afrsm_zone';
		/**
		 * Count total items
		 *
		 * @since    3.5
		 * @var      string $wc_afrsm_found_items The class of external plugin.
		 */
		private static $wc_afrsm_zone_found_items = 0;
		/**
		 * Admin object call
		 *
		 * @since    3.5
		 * @var      string $admin_object The class of external plugin.
		 */
		private static $admin_object = null;
		/**
		 * Get_columns function.
		 *
		 * @return  array
		 * @since 4.0
		 */
		public function get_columns() {
			return array(
				'cb'        => '<input type="checkbox" />',
				'title'     => esc_html__( 'Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'zone_type' => esc_html__( 'Shipping Zone type', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'status'    => esc_html__( 'Status', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'date'      => esc_html__( 'Date', 'advanced-flat-rate-shipping-for-woocommerce' ),
			);
		}
		/**
		 * Get_sortable_columns function.
		 *
		 * @return array
		 * @since 4.0
		 */
		protected function get_sortable_columns() {
			$columns = array(
				'title' => array(
					'title',
					true,
				),
				'date'  => array(
					'date',
					false,
				),
			);
			return $columns;
		}
		/**
		 * Constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {
			parent::__construct(
				array(
					'singular' => 'post',
					'plural'   => 'posts',
					'ajax'     => false,
				)
			);
			self::$admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin( '', '' );
		}
		/**
		 * Get Methods to display
		 *
		 * @since 4.0
		 */
		public function prepare_items() {
			$this->prepare_column_headers();
			$per_page    = $this->get_items_per_page( 'afrsm_per_page' );
			$get_search  = filter_input( INPUT_POST, 's', FILTER_SANITIZE_STRING );
			$get_orderby = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_STRING );
			$get_order   = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_STRING );
			$args        = array(
				'posts_per_page' => $per_page,
				'orderby'        => 'ID',
				'order'          => 'DESC',
				'offset'         => ( $this->get_pagenum() - 1 ) * $per_page,
			);
			if ( isset( $get_search ) && ! empty( $get_search ) ) {
				$args['s'] = trim( wp_unslash( $get_search ) );
			}
			if ( isset( $get_orderby ) && ! empty( $get_orderby ) ) {
				if ( 'title' === $get_orderby ) {
					$args['orderby'] = 'title';
				} elseif ( 'date' === $get_orderby ) {
					$args['orderby'] = 'date';
				}
			}
			if ( isset( $get_order ) && ! empty( $get_order ) ) {
				if ( 'asc' === strtolower( $get_order ) ) {
					$args['order'] = 'ASC';
				} elseif ( 'desc' === strtolower( $get_order ) ) {
					$args['order'] = 'DESC';
				}
			}
			$this->items = $this->afrsm_zone_find( $args );
			$total_items = $this->afrsm_zone_count();
			$total_pages = ceil( $total_items / $per_page );
			$this->set_pagination_args(
				array(
					'total_items' => $total_items,
					'total_pages' => $total_pages,
					'per_page'    => $per_page,
				)
			);
		}
		/**
		 * No items found
		 *
		 * @since 4.0
		 */
		public function no_items() {
			if ( isset( $this->error ) ) {
				echo esc_html( $this->error->get_error_message() );
			} else {
				esc_html_e( 'No shipping zone found.', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
		}
		/**
		 * Checkbox column
		 *
		 * @param string $item Get shipping zone id.
		 *
		 * @return mixed
		 * @since 4.0
		 */
		public function column_cb( $item ) {
			if ( ! $item->ID ) {
				return;
			}
			return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', 'zone_id_cb', esc_attr( $item->ID ) );
		}
		/**
		 * Output the zone name column.
		 *
		 * @param object $item Get shipping zone id.
		 *
		 * @since 4.0
		 */
		public function column_title( $item ) {
			$edit_method_url = add_query_arg(
				array(
					'page'   => 'afrsm-start-page',
					'tab'    => 'advance_shipping_zone',
					'action' => 'edit',
					'post'   => $item->ID,
				),
				admin_url( 'admin.php' )
			);
			$editurl         = $edit_method_url;
			$method_name     = '<strong>
                            <a href="' . wp_nonce_url( $editurl, 'edit_' . $item->ID, 'cust_nonce' ) . '" class="row-title">' . esc_html( $item->post_title ) . '</a>
                        </strong>';
			echo wp_kses( $method_name, self::$admin_object->afrsfwa_allowed_html_tags() );
		}
		/**
		 * Generates and displays row action links.
		 *
		 * @param object $item        Link being acted upon.
		 * @param string $column_name Current column name.
		 * @param string $primary     Primary column name.
		 *
		 * @return string Row action output for links.
		 * @since 4.0
		 */
		protected function handle_row_actions( $item, $column_name, $primary ) {
			if ( $primary !== $column_name ) {
				return '';
			}
			$edit_method_url      = add_query_arg(
				array(
					'page'   => 'afrsm-start-page',
					'tab'    => 'advance_shipping_zone',
					'action' => 'edit',
					'post'   => $item->ID,
				),
				admin_url( 'admin.php' )
			);
			$editurl              = $edit_method_url;
			$delete_method_url    = add_query_arg(
				array(
					'page'   => 'afrsm-start-page',
					'tab'    => 'advance_shipping_zone',
					'action' => 'delete',
					'post'   => $item->ID,
				),
				admin_url( 'admin.php' )
			);
			$delurl               = $delete_method_url;
			$duplicate_method_url = add_query_arg(
				array(
					'page'   => 'afrsm-start-page',
					'tab'    => 'advance_shipping_zone',
					'action' => 'duplicate',
					'post'   => $item->ID,
				),
				admin_url( 'admin.php' )
			);
			$duplicateurl         = $duplicate_method_url;
			$actions              = array();
			$actions['edit']      = '<a href="' . wp_nonce_url( $editurl, 'edit_' . esc_attr( $item->ID ), 'cust_nonce' ) . '">' . __( 'Edit', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</a>';
			$actions['delete']    = '<a href="' . wp_nonce_url( $delurl, 'del_' . esc_attr( $item->ID ), 'cust_nonce' ) . '">' . __( 'Delete', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</a>';
			$actions['duplicate'] = '<a href="' . wp_nonce_url( $duplicateurl, 'duplicate_' . esc_attr( $item->ID ), 'cust_nonce' ) . '">' . __( 'Duplicate', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</a>';
			return $this->row_actions( $actions );
		}
		/**
		 * Output the zone type column.
		 *
		 * @param object $item Get shipping zone id.
		 *
		 * @return int|float
		 * @since 4.0
		 */
		public function column_zone_type( $item ) {
			if ( 0 === $item->ID ) {
				return esc_html__( 'Everywhere', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
			$postcode_state    = array();
			$postcode_list     = array();
			$locations_prepend = '';
			$locations_append  = '';
			$locations_list    = array();
			$location_type     = get_post_meta( $item->ID, 'location_type', true );
			$zone_type         = get_post_meta( $item->ID, 'zone_type', true );
			if ( 'postcode' !== $location_type ) {
				$location_code_arr_new = get_post_meta( $item->ID, 'location_code', true );
				$location_code_arr     = array();
				if ( ! empty( $location_code_arr_new ) ) {
					foreach ( $location_code_arr_new as $location_code_key => $location_code_arr_val ) {
						$count = count( $location_code_arr_val );
						foreach ( $location_code_arr_val as $location_code_arr_val_key => $location_code_sub_arr_val ) {
							if ( $location_code_arr_val_key >= 8 ) {
								$locations_append = ' ' . esc_html__( 'and ', 'advanced-flat-rate-shipping-for-woocommerce' ) . esc_html( $count ) . esc_html__( ' others', 'advanced-flat-rate-shipping-for-woocommerce' );
								break;
							}
							$location_code_arr[] = $location_code_sub_arr_val;
						}
					}
				}
			} else {
				$postcode_code_arr = get_post_meta( $item->ID, 'location_code', true );
				if ( ! empty( $postcode_code_arr ) ) {
					foreach ( $postcode_code_arr as $location_code_key => $location_code_val ) {
						$postcode_state[]  = $location_code_key;
						$postcode_list_arr = $location_code_val;
					}
				}
				$count = count( $postcode_list_arr );
				foreach ( $postcode_list_arr as $postcode_list_arr_key => $postcode_list_arr_key_val ) {
					if ( $postcode_list_arr_key >= 8 ) {
						$locations_append = ' ' . esc_html__( 'and ', 'advanced-flat-rate-shipping-for-woocommerce' ) . esc_html( $count ) . esc_html__( ' others', 'advanced-flat-rate-shipping-for-woocommerce' );
						break;
					}
					$postcode_list[] = $postcode_list_arr_key_val;
				}
			}
			switch ( $location_type ) {
				case 'country':
				case 'state':
					foreach ( $location_code_arr as $location_code_key => $location_code ) {
						if ( strstr( $location_code, ':' ) ) {
							$split_code = explode( ':', $location_code );
							if ( ! isset( WC()->countries->states[ $split_code[0] ][ $split_code[1] ] ) ) {
								continue;
							}
							$location_name = WC()->countries->states[ $split_code[0] ][ $split_code[1] ];
						} else {
							if ( ! isset( WC()->countries->countries[ $location_code ] ) ) {
								continue;
							}
							$location_name = WC()->countries->countries[ $location_code ];
						}
						$locations_list[] = $location_name;
					}
					break;
				case 'postcode':
					if ( strstr( $postcode_state[0], ':' ) ) {
						$split_code = explode( ':', $postcode_state[0] );
						if ( ! isset( WC()->countries->states[ $split_code[0] ][ $split_code[1] ] ) ) {
							break;
						}
						$location_name = WC()->countries->states[ $split_code[0] ][ $split_code[1] ];
					} else {
						if ( ! isset( WC()->countries->countries[ $postcode_state[0] ] ) ) {
							break;
						}
						$location_name = WC()->countries->countries[ $postcode_state[0] ];
					}
					$locations_prepend = esc_html__( 'Within ', 'advanced-flat-rate-shipping-for-woocommerce' ) . esc_html( $location_name ) . ': ';
					$locations_list    = $postcode_list;
			}
			switch ( $zone_type ) {
				case 'countries':
					return '<strong>' . esc_html__( 'Countries', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</strong><br/>' . $locations_prepend . implode( ', ', $locations_list ) . $locations_append;
				case 'states':
					return '<strong>' . esc_html__( 'Countries and states', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</strong><br/>' . $locations_prepend . implode( ', ', $locations_list ) . $locations_append;
				case 'postcodes':
					return '<strong>' . esc_html__( 'Postcodes', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</strong><br/>' . $locations_prepend . implode( ', ', $locations_list ) . $locations_append;
			}
		}
		/**
		 * Output the method enabled column.
		 *
		 * @param object $item Get shipping zone id.
		 *
		 * @return string
		 */
		public function column_status( $item ) {
			if ( 0 === $item->ID ) {
				return esc_html__( 'Everywhere', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
			if ( 'publish' === $item->post_status ) {
				$status = 'Enable';
			} else {
				$status = 'Disable';
			}
			return $status;
		}
		/**
		 * Output the method amount column.
		 *
		 * @param object $item Get shipping zone id.
		 *
		 * @return mixed $item->post_date;
		 * @since 4.0
		 */
		public function column_date( $item ) {
			if ( 0 === $item->ID ) {
				return esc_html__( 'Everywhere', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
			return $item->post_date;
		}
		/**
		 * Display bulk action in filter
		 *
		 * @return array $actions
		 * @since 4.0
		 */
		public function get_bulk_actions() {
			$actions = array(
				'disable' => esc_html__( 'Disable', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'enable'  => esc_html__( 'Enable', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'delete'  => esc_html__( 'Delete', 'advanced-flat-rate-shipping-for-woocommerce' ),
			);
			return $actions;
		}
		/**
		 * Process bulk actions
		 *
		 * @since 4.0
		 */
		public function process_bulk_action() {
			$delete_nonce   = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
			$get_zone_id_cb = filter_input( INPUT_POST, 'zone_id_cb', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY );
			$zone_id_cb     = ! empty( $get_zone_id_cb ) ? array_map( 'sanitize_text_field', wp_unslash( $get_zone_id_cb ) ) : array();
			$get_tab        = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$action         = $this->current_action();
			if ( ! isset( $zone_id_cb ) ) {
				return;
			}
			$deletenonce = wp_verify_nonce( $delete_nonce, 'bulk-shippingzones' );
			if ( ! isset( $deletenonce ) && 1 !== $deletenonce ) {
				return;
			}
			$items = array_filter( array_map( 'absint', $zone_id_cb ) );
			if ( ! $items ) {
				return;
			}
			if ( 'delete' === $action ) {
				foreach ( $items as $id ) {
					wp_delete_post( $id );
				}
				self::$admin_object->afrsfwa_updated_message( 'deleted', $get_tab, '' );
			} elseif ( 'enable' === $action ) {
				foreach ( $items as $id ) {
					$enable_post = array(
						'post_type'   => self::POST_TYPE,
						'ID'          => $id,
						'post_status' => 'publish',
					);
					wp_update_post( $enable_post );
				}
				self::$admin_object->afrsfwa_updated_message( 'enabled', $get_tab, '' );
			} elseif ( 'disable' === $action ) {
				foreach ( $items as $id ) {
					$disable_post = array(
						'post_type'   => self::POST_TYPE,
						'ID'          => $id,
						'post_status' => 'draft',
					);
					wp_update_post( $disable_post );
				}
				self::$admin_object->afrsfwa_updated_message( 'disabled', $get_tab, '' );
			}
		}
		/**
		 * Find post data
		 *
		 * @param mixed $args Get query args.
		 *
		 * @return array $posts
		 * @since 4.0
		 */
		public static function afrsm_zone_find( $args = '' ) {
			$defaults                        = array(
				'post_status'    => 'any',
				'posts_per_page' => - 1,
				'offset'         => 0,
				'orderby'        => 'ID',
				'order'          => 'ASC',
			);
			$args                            = wp_parse_args( $args, $defaults );
			$args['post_type']               = self::POST_TYPE;
			$wc_afrsm_query                  = new WP_Query( $args );
			$posts                           = $wc_afrsm_query->query( $args );
			self::$wc_afrsm_zone_found_items = $wc_afrsm_query->found_posts;
			return $posts;
		}
		/**
		 * Count post data
		 *
		 * @return string
		 * @since 4.0
		 */
		public static function afrsm_zone_count() {
			return self::$wc_afrsm_zone_found_items;
		}
		/**
		 * Set column_headers property for table list
		 *
		 * @since 4.0
		 */
		protected function prepare_column_headers() {
			$this->_column_headers = array(
				$this->get_columns(),
				array(),
				$this->get_sortable_columns(),
			);
		}
	}
}
