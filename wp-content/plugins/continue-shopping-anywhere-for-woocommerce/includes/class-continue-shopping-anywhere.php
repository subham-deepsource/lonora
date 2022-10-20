<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://businessupwebsite.com
 * @since      1.0.0
 *
 * @package    Continue_Shopping_Anywhere
 * @subpackage Continue_Shopping_Anywhere/includes
 */

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
 * @package    Continue_Shopping_Anywhere
 * @subpackage Continue_Shopping_Anywhere/includes
 * @author     Ivan Chernyakov <admin@businessupwebsite.com>
 */
class Continue_Shopping_Anywhere {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Continue_Shopping_Anywhere_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
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
		if ( defined( 'CONTINUE_SHOPPING_ANYWHERE_VERSION' ) ) {
			$this->version = CONTINUE_SHOPPING_ANYWHERE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'continue-shopping-anywhere';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Continue_Shopping_Anywhere_Loader. Orchestrates the hooks of the plugin.
	 * - Continue_Shopping_Anywhere_i18n. Defines internationalization functionality.
	 * - Continue_Shopping_Anywhere_Admin. Defines all hooks for the admin area.
	 * - Continue_Shopping_Anywhere_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-continue-shopping-anywhere-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-continue-shopping-anywhere-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-continue-shopping-anywhere-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-continue-shopping-anywhere-public.php';

		$this->loader = new Continue_Shopping_Anywhere_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Continue_Shopping_Anywhere_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Continue_Shopping_Anywhere_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Continue_Shopping_Anywhere_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add woocommerce settings
		$this->loader->add_filter( 'woocommerce_get_settings_pages', $plugin_admin, 'init_after' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @since    1.1.0 - Added Single Position
	 * @since    1.3.0 - Added Recent Category Save
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Continue_Shopping_Anywhere_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// save recent category
		$this->loader->add_action( 'template_redirect', $plugin_public, 'save_category' );

		// cart continue shopping link (after redirect)
		if ( get_option('woocsa_cart_apply') == 'yes' && ( get_option('woocsa_cart_type_after_redirect') != 'default' ) ) {
			$this->loader->add_filter( 'woocommerce_continue_shopping_redirect', $plugin_public, 'custom_cart_redirect' );
		}

		// cart continue shopping link (always)
		$this->loader->add_action( 'woocommerce_before_cart_table', $plugin_public, 'custom_cart_redirect_always_shown' );

		// checkout continue shopping link (always)
		$this->loader->add_action( 'woocommerce_checkout_before_customer_details', $plugin_public, 'custom_checkout_redirect_always_shown' );

		// single continue shopping link (always)
		$single_position = get_option( 'woocsa_single_position' );
		if ($single_position == '' ){
			$single_position  = 'woocommerce_before_single_product_summary';
		}
		$this->loader->add_action( $single_position, $plugin_public, 'custom_single_redirect_always_shown' );

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
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Continue_Shopping_Anywhere_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
