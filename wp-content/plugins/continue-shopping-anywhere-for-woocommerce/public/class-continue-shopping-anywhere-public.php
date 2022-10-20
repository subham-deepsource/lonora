<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://businessupwebsite.com
 * @since      1.0.0
 *
 * @package    Continue_Shopping_Anywhere
 * @subpackage Continue_Shopping_Anywhere/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Continue_Shopping_Anywhere
 * @subpackage Continue_Shopping_Anywhere/public
 * @author     Ivan Chernyakov <admin@businessupwebsite.com>
 */
class Continue_Shopping_Anywhere_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Continue_Shopping_Anywhere_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Continue_Shopping_Anywhere_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/continue-shopping-anywhere-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Continue_Shopping_Anywhere_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Continue_Shopping_Anywhere_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/continue-shopping-anywhere-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Custom Cart redirect (after add to cart button)
	 *
	 * @since    1.0.0
	 */
	public function custom_cart_redirect() {
		$continue = get_option( 'woocsa_cart_type_after_redirect' );
		$custom_link = get_option( 'woocsa_cart_custom_link' );
		$is_redirect_to_cart = get_option( 'woocommerce_cart_redirect_after_add' );	
		$site_url = get_home_url();

		if ( $is_redirect_to_cart == 'yes' ) {
			switch( $continue ) {

				case "home" :
				$link = $site_url;
				break;

				case "shop" :
				$shop_id = get_option( 'woocommerce_shop_page_id' );
				$link = get_permalink( $shop_id );
				break;

				case "custom_link" :
				if ( isset( $custom_link ) ) {
					$link = $custom_link;
				} else {
					$shop_id = get_option( 'woocommerce_shop_page_id' );
					$link = get_permalink( $shop_id );
				}
				break;

				default :
				$link = $site_url;
				break;
			}
		}

		return esc_url( $link );
	}

	/**
	 * Custom Cart redirect
	 *
	 * @since    1.0.0
	 */
	public function custom_cart_redirect_always_shown() {
		$is_redirect_to_cart = get_option( 'woocommerce_cart_redirect_after_add' );
		if ( $is_redirect_to_cart == 'no' ) {
			$this->custom_redirect_always_shown( 'cart' );
		}
	}

	/**
	 * Custom Checkout
	 *
	 * @since    1.0.0
	 */
	public function custom_checkout_redirect_always_shown() {
		$this->custom_redirect_always_shown( 'checkout' );
	}

	/**
	 * Custom Single Page
	 *
	 * @since    1.0.0
	 */
	public function custom_single_redirect_always_shown() {
		$this->custom_redirect_always_shown( 'single' );
	}

	/**
	 * Save Category Function
	 *
	 * @since    1.3.0
	 */
	public function save_category() {
		if (class_exists( 'WooCommerce' ) && is_product_category() && ! is_cart() )	
		{
			global $wp_query;
			$category_id = $wp_query->get_queried_object_id();
			$arr_cookie_options = array (
                'expires' => time() + 60*60*24,
                'path' => '/',
                'secure' => true,  
                'httponly' => true,  
                'samesite' => 'None' 
                );
			setcookie("CSARecentCategory", $category_id, $arr_cookie_options);  
			wp_reset_query(); 
		}
	}

	/**
	 * Custom redirect function (always shown)
	 *
	 * @since    1.0.0
	 * @since    1.2.0 - Single page out of stock check
	 */
	public function custom_redirect_always_shown( $page_name ) {
		if ( get_option('woocsa_'.$page_name.'_apply') == 'no' || ! get_option('woocsa_'.$page_name.'_apply') ){
			return;
		}

		if ($page_name == 'single' && get_option('woocsa_single_condition') == 'out_of_stock'){
			global $product;
			if ( $product->get_manage_stock() && $product->get_stock_quantity() <= 0){
			}
			else
				return;
		}

		$continue = get_option( 'woocsa_'.$page_name.'_type_always' );
		$custom_link = get_option( 'woocsa_'.$page_name.'_custom_link' );
		$site_url = get_home_url();		
		$shop_id = get_option( 'woocommerce_shop_page_id' );

		switch( $continue ) {

			case "home" :
				$link = $site_url;
				break;

			case "shop" :
				$link = get_permalink( $shop_id );
				break;

			case "custom_link" :
				if ( isset( $custom_link ) ) {
					$link = $custom_link;
				} else {
					$link = get_permalink( $shop_id );
				}
				break;

			case "prev_page" :
				$link = wp_get_referer();
				break;

			case "recent_category" :
				if (isset($_COOKIE['CSARecentCategory'])) {
					$link = get_term_link( (int)$_COOKIE['CSARecentCategory'], 'product_cat' );
				}
				else {
					$link = get_permalink( $shop_id );
				}
				break;

			default :
				$link = $site_url;
				break;
		}
		if ( get_option('woocsa_'.$page_name.'_custom_text') ){
			$added_text = get_option('woocsa_'.$page_name.'_custom_text');
		}
		else {
			$added_text = __( 'Maybe you want to continue shopping?', 'continue-shopping-anywhere' );
		}

		$message   = sprintf( '<a href="%s" tabindex="1" class="button wc-forward">%s</a> %s', esc_url( $link ), esc_html__( 'Continue shopping', 'woocommerce' ), esc_html( $added_text ) );
		echo '<div class="woocommerce-message">';
		echo $message;
		echo '</div>';
		
	}

}