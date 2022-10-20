<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
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
 * Advanced_Flat_Rate_Shipping_For_WooCommerce class.
 */
class Advanced_Flat_Rate_Shipping_For_WooCommerce {
	/**
	 * Plugin's version
	 *
	 * @since 3.5
	 */
	const WCPFC_VERSION = AFRSM_PLUGIN_VERSION;
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @var      Advanced_Flat_Rate_Shipping_For_WooCommerce_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'advanced-flat-rate-shipping-for-woocommerce';
		$this->version     = AFRSM_PLUGIN_VERSION;
		$this->load_dependencies();
		$this->set_locale();
		$this->init();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$prefix = is_network_admin() ? 'network_admin_' : '';
		add_filter(
			"{$prefix}plugin_action_links_" . AFRSM_PLUGIN_BASENAME,
			array(
				$this,
				'plugin_action_links',
			),
			10
		);
	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Advanced_Flat_Rate_Shipping_For_WooCommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Advanced_Flat_Rate_Shipping_For_WooCommerce_I18n. Defines internationalization functionality.
	 * - Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin. Defines all hooks for the admin area.
	 * - Advanced_Flat_Rate_Shipping_For_WooCommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advanced-flat-rate-shipping-for-woocommerce-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advanced-flat-rate-shipping-for-woocommerce-i18n.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-advanced-flat-rate-shipping-for-woocommerce-admin.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-advanced-flat-rate-shipping-for-woocommerce-public.php';
		/**
		 * Admin user review block
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advanced-flat-rate-shipping-for-woocommerce-user-feedback.php';
		$this->loader = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Loader();
	}
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Advanced_Flat_Rate_Shipping_For_WooCommerce_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function set_locale() {
		$plugin_i18n = new Advanced_Flat_Rate_Shipping_For_WooCommerce_I18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	/**
	 * Init.
	 *
	 * Initialize plugin parts.
	 *
	 * @since 3.0.0
	 */
	public function init() {
		add_action( 'woocommerce_shipping_init', array( $this, 'afrsm_init_shipping_method' ) );
		add_action( 'woocommerce_shipping_methods', array( $this, 'afrsm_register_shipping_method_class' ) );
	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_admin_hooks() {
		$page         = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$tab          = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$plugin_admin = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'afrsfwa_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'afrsfwa_enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'afrsfwa_dot_store_menu_shipping_method_pro' );
		$this->loader->add_action( 'afrsm_condition_match_rules', $plugin_admin, 'afrsfwa_condition_match_rules', 10, 2 );
		$this->loader->add_action( 'wp_ajax_afrsfwa_product_fees_conditions_values_ajax', $plugin_admin, 'afrsfwa_product_fees_conditions_values_ajax' );
		$this->loader->add_action( 'wp_ajax_afrsfwa_product_fees_conditions_varible_values_product_ajax__premium_only', $plugin_admin, 'afrsfwa_product_fees_conditions_varible_values_product_ajax__premium_only' );
		$this->loader->add_action( 'wp_ajax_afrsfwa_product_fees_conditions_values_product_ajax', $plugin_admin, 'afrsfwa_product_fees_conditions_values_product_ajax' );
		$this->loader->add_action( 'wp_ajax_afrsfwa_simple_and_variation_product_list_ajax__premium_only', $plugin_admin, 'afrsfwa_simple_and_variation_product_list_ajax__premium_only' );
		$this->loader->add_filter( 'set-screen-option', $plugin_admin, 'afrsfwa_set_screen_options', 10, 3 );
		$this->loader->add_action( 'wp_ajax_afrsfwa_sm_sort_order', $plugin_admin, 'afrsfwa_sm_sort_order' );
		$this->loader->add_filter( 'woocommerce_get_sections_shipping', $plugin_admin, 'afrsfwa_remove_section' );
		$this->loader->add_filter( 'admin_body_class', $plugin_admin, 'afrsfwa_admin_body_class' );
		if ( ! empty( $page ) && ( false !== strpos( $page, 'afrsm-start-page' ) ) && ! empty( $tab ) && ( false !== strpos( $tab, 'import_export' ) ) ) {
			$this->loader->add_action( 'admin_init', $plugin_admin, 'afrsfwa_import_export_shipping_method__premium_only' );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'afrsfwa_import_export_zone__premium_only' );
		}
		$this->loader->add_action( 'admin_init', $plugin_admin, 'afrsfwa_welcome_shipping_method_screen_do_activation_redirect' );
		
	}
	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_public_hooks() {
		$plugin_public = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'afrsfwp_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'afrsfwp_enqueue_scripts' );
		$this->loader->add_filter( 'woocommerce_locate_template', $plugin_public, 'afrsfwp_wc_locate_template_sm_conditions', 1, 3 );
		$this->loader->add_action( 'woocommerce_checkout_update_order_review', $plugin_public, 'afrsfwp_woocommerce_checkout_update_order_review__premium_only' );
		$this->loader->add_filter( 'woocommerce_package_rates', $plugin_public, 'afrsfwp_remove_shipping_method__premium_only', 10, 2 );
	}
	/**
	 * Allowed html tags used for wp_kses function
	 *
	 * @return array
	 * @since     1.0.0
	 */
	public static function afrsmw_allowed_html_tags() {
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
	 * Initialize shipping method.
	 *
	 * Configure and add all the shipping methods available.
	 *
	 * @since 3.0.0
	 *
	 * @uses  AFRSM_Init_Shipping_Methods class
	 * @uses  AFRSM_Forceall_Shipping_Method
	 */
	public function afrsm_init_shipping_method() {
		require_once plugin_dir_path( __DIR__ ) . '/admin/partials/class-afrsm-init-shipping-methods.php';
		$this->afrsm_method = new AFRSM_Init_Shipping_Methods();
		require_once plugin_dir_path( __DIR__ ) . '/admin/partials/class-afrsm-forceall-shipping-method.php';
		$this->afrsm_forceall_method = new AFRSM_Forceall_Shipping_Method();
	}
	/**
	 * Add shipping method.
	 *
	 * Add configured methods to available shipping methods.
	 *
	 * @param array $methods register shipping method name.
	 *
	 * @return array $methods
	 * @since 3.0.0
	 */
	public function afrsm_register_shipping_method_class( $methods ) {
		if ( class_exists( 'AFRSM_Init_Shipping_Methods' ) ) {
			$methods[] = 'AFRSM_Init_Shipping_Methods';
		}
		if ( class_exists( 'AFRSM_Forceall_Shipping_Method' ) ) {
			$methods[] = 'AFRSM_Forceall_Shipping_Method';
		}
		return $methods;
	}
	/**
	 * Return the plugin action links.  This will only be called if the plugin
	 * is active.
	 *
	 * @param array $actions associative array of action names to anchor tags.
	 *
	 * @return array associative array of plugin action links
	 * @since 1.0.0
	 */
	public function plugin_action_links( $actions ) {
		$custom_actions = array(
			'configure' => sprintf(
				'<a href="%s">%s</a>',
				esc_url(
					add_query_arg(
						array(
							'page' => 'afrsm-start-page',
						),
						admin_url( 'admin.php' )
					)
				),
				__( 'Settings', 'advanced-flat-rate-shipping-for-woocommerce' )
			),
			'docs'      => sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( 'https://www.thedotstore.com/docs/plugin/advanced-flat-rate-shipping-method-for-woocommerce' ),
				__( 'Docs', 'advanced-flat-rate-shipping-for-woocommerce' )
			),
			'support'   => sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( 'https://www.thedotstore.com/support' ),
				__( 'Support', 'advanced-flat-rate-shipping-for-woocommerce' )
			),
		);
		return array_merge( $custom_actions, $actions );
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Advanced_Flat_Rate_Shipping_For_WooCommerce_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}
}

