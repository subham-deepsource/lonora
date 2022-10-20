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
 * WC_Shipping_Methods_Table class.
 *
 * @extends WP_List_Table
 */
if ( ! class_exists( 'WC_Shipping_Methods_Table' ) ) {
	/**
	 * WC_Shipping_Methods_Table class.
	 */
	class WC_Shipping_Methods_Table extends WP_List_Table {
		/**
		 * Output the Admin UI
		 *
		 * @since 3.5
		 */
		const POST_TYPE = 'wc_afrsm';
		/**
		 * Count total items
		 *
		 * @since    3.5
		 * @var      string $wc_afrsm_found_items The class of external plugin.
		 */
		private static $wc_afrsm_found_items = 0;
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
		 * @since 3.5
		 */
		public function get_columns() {
			return array(
				'cb'     => '<input type="checkbox" />',
				'title'  => esc_html__( 'Shipping name', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'amount' => esc_html__( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'status' => esc_html__( 'Status', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'date'   => esc_html__( 'Date', 'advanced-flat-rate-shipping-for-woocommerce' ),
			);
		}
		/**
		 * Get_sortable_columns function.
		 *
		 * @return array
		 * @since 3.5
		 */
		protected function get_sortable_columns() {
			$columns = array(
				'title'  => array( 'title', true ),
				'amount' => array( 'amount', false ),
				'date'   => array( 'date', false ),
			);
			return $columns;
		}
		/**
		 * Constructor
		 *
		 * @since 3.5
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
		 * @since 3.5
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
				} elseif ( 'amount' === $get_orderby ) {
					$args['meta_key'] = 'sm_product_cost';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$args['orderby']  = 'meta_value_num';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
			$this->items = $this->afrsm_find( $args, $get_orderby );
			$total_items = $this->afrsm_count();
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
		 * If no listing found then display message.
		 *
		 * @since 3.5
		 */
		public function no_items() {
			if ( isset( $this->error ) ) {
				echo esc_html( $this->error->get_error_message() );
			} else {
				esc_html_e( 'No shipping method found.', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
		}
		/**
		 * Checkbox column
		 *
		 * @param string $item Get shipping method id.
		 *
		 * @return mixed
		 * @since 3.5
		 */
		public function column_cb( $item ) {
			if ( ! $item->ID ) {
				return;
			}
			return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', 'method_id_cb', esc_attr( $item->ID ) );
		}
		/**
		 * Output the shipping name column.
		 *
		 * @param object $item Get shipping method id.
		 *
		 * @since 3.5
		 */
		public function column_title( $item ) {
			$edit_method_url = add_query_arg(
				array(
					'page'   => 'afrsm-start-page',
					'tab'    => 'advance_shipping_method',
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
		 * @since 3.5
		 */
		protected function handle_row_actions( $item, $column_name, $primary ) {
			if ( $primary !== $column_name ) {
				return '';
			}
			$edit_method_url      = add_query_arg(
				array(
					'page'   => 'afrsm-start-page',
					'tab'    => 'advance_shipping_method',
					'action' => 'edit',
					'post'   => $item->ID,
				),
				admin_url( 'admin.php' )
			);
			$editurl              = $edit_method_url;
			$delete_method_url    = add_query_arg(
				array(
					'page'   => 'afrsm-start-page',
					'tab'    => 'advance_shipping_method',
					'action' => 'delete',
					'post'   => $item->ID,
				),
				admin_url( 'admin.php' )
			);
			$delurl               = $delete_method_url;
			$duplicate_method_url = add_query_arg(
				array(
					'page'   => 'afrsm-start-page',
					'tab'    => 'advance_shipping_method',
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
		 * Output the method amount column.
		 *
		 * @param object $item Get shipping method id.
		 *
		 * @return int|float
		 * @since 3.5
		 */
		public function column_amount( $item ) {
			if ( 0 === $item->ID ) {
				return esc_html__( 'Everywhere', 'advanced-flat-rate-shipping-for-woocommerce' );
			}
			$amount = get_post_meta( $item->ID, 'sm_product_cost', true );
			if ( false !== strpos( $amount, '[' ) || false !== strpos( $amount, '*' ) ) {
				return $amount;
			} else {
				return wc_price( $amount );
			}
		}
		/**
		 * Output the method enabled column.
		 *
		 * @param object $item Get shipping method id.
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
		 * @param object $item Get shipping method id.
		 *
		 * @return mixed $item->post_date;
		 * @since 3.5
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
		 * @since 3.5
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
		 * @since 3.5
		 */
		public function process_bulk_action() {
			$delete_nonce     = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
			$get_method_id_cb = filter_input( INPUT_POST, 'method_id_cb', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY );
			$method_id_cb     = ! empty( $get_method_id_cb ) ? array_map( 'sanitize_text_field', wp_unslash( $get_method_id_cb ) ) : array();
			$get_tab          = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$action           = $this->current_action();
			if ( ! isset( $method_id_cb ) ) {
				return;
			}
			$deletenonce = wp_verify_nonce( $delete_nonce, 'bulk-shippingmethods' );
			if ( ! isset( $deletenonce ) && 1 !== $deletenonce ) {
				return;
			}
			$items = array_filter( array_map( 'absint', $method_id_cb ) );
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
		 * @param mixed  $args pass query args.
		 * @param string $get_orderby pass order by for listing.
		 *
		 * @return array $posts
		 * @since 3.5
		 */
		public static function afrsm_find( $args = '', $get_orderby ) {
			$defaults          = array(
				'post_status'    => 'any',
				'posts_per_page' => - 1,
				'offset'         => 0,
				'orderby'        => 'ID',
				'order'          => 'ASC',
			);
			$args              = wp_parse_args( $args, $defaults );
			$args['post_type'] = self::POST_TYPE;
			$wc_afrsm_query    = new WP_Query( $args );
			$posts             = $wc_afrsm_query->query( $args );
			if ( ! isset( $get_orderby ) && empty( $get_orderby ) ) {
				$sort_order     = array();
				$get_sort_order = get_option( 'sm_sortable_order' );
				if ( isset( $get_sort_order ) && ! empty( $get_sort_order ) ) {
					foreach ( $get_sort_order as $sort ) {
						$sort_order[ $sort ] = array();
					}
				}
				foreach ( $posts as $carrier_id => $carrier ) {
					$carrier_name = $carrier->ID;
					if ( array_key_exists( $carrier_name, $sort_order ) ) {
						$sort_order[ $carrier_name ][ $carrier_id ] = $posts[ $carrier_id ];
						unset( $posts[ $carrier_id ] );
					}
				}
				foreach ( $sort_order as $carriers ) {
					$posts = array_merge( $posts, $carriers );
				}
			}
			self::$wc_afrsm_found_items = $wc_afrsm_query->found_posts;
			return $posts;
		}
		/**
		 * Count post data
		 *
		 * @return string
		 * @since 3.5
		 */
		public static function afrsm_count() {
			return self::$wc_afrsm_found_items;
		}
		/**
		 * Set column_headers property for table list
		 *
		 * @since 3.5
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
