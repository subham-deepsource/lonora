<?php
/**
 * Plugin Name:         Advanced Flat Rate Shipping For WooCommerce
 * Plugin URI:          https://www.thedotstore.com/advanced-flat-rate-shipping-method-for-woocommerce
 * Description:         Using Advanced Flat Rate Shipping plugin, you can create multiple flat rate shipping methods. Using this plugin you can configure different parameters on which a particular Flat Rate Shipping method becomes available to the customers at the time of checkout.
 * Version:             4.6
 * Author:              theDotstore
 * Author URI:          https://www.thedotstore.com/
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         advanced-flat-rate-shipping-for-woocommerce
 * Domain Path:         /languages
 *
 * Woo: 4887237:f2eeb8a45e8f1f3ced05cf97d99f4bf0
 * WC requires at least: 4.0
 * WC tested up to: 5.3
 *
 * @package Advanced Flat Rate Shipping For WooCommerce
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'AFRSM_PLUGIN_VERSION' ) ) {
	define( 'AFRSM_PLUGIN_VERSION', '4.6' );
}
if ( ! defined( 'AFRSM_PLUGIN_URL' ) ) {
	define( 'AFRSM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'AFRSM_PLUGIN_DIR' ) ) {
	define( 'AFRSM_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( ! defined( 'AFRSM_PLUGIN_DIR_PATH' ) ) {
	define( 'AFRSM_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'AFRSM_SLUG' ) ) {
	define( 'AFRSM_SLUG', 'advanced-flat-rate-shipping-for-woocommerce' );
}
if ( ! defined( 'AFRSM_PLUGIN_BASENAME' ) ) {
	define( 'AFRSM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'AFRSM_PLUGIN_NAME' ) ) {
	define( 'AFRSM_PLUGIN_NAME', 'Advanced Flat Rate Shipping For WooCommerce' );
}
if ( ! defined( 'AFRSM_TEXT_DOMAIN' ) ) {
	define( 'AFRSM_TEXT_DOMAIN', 'advanced-flat-rate-shipping-for-woocommerce' );
}
if ( ! defined( 'AFRSM_FEE_AMOUNT_NOTICE' ) ) {
	define( 'AFRSM_FEE_AMOUNT_NOTICE', 'If entered fee amount is less than cart subtotal it will reflect with minus sign ( EX: $ -10.00 ) <b>OR</b> If entered fee amount is more than cart subtotal then the total amount shown as zero ( EX: Total: 0 )' );
}
if ( ! defined( 'AFRSM_PERTICULAR_FEE_AMOUNT_NOTICE' ) ) {
	define( 'AFRSM_PERTICULAR_FEE_AMOUNT_NOTICE', 'Enable or Disable this shipping rule using this checkbox.' );
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-advanced-flat-rate-shipping-for-woocommerce-activator.php
 */
function activate_advanced_flat_rate_shipping_for_woocommerce() {
	set_transient( 'afrsm-admin-notice', true );
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-advanced-flat-rate-shipping-for-woocommerce-activator.php';
	Advanced_Flat_Rate_Shipping_For_WooCommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-advanced-flat-rate-shipping-for-woocommerce-deactivator.php
 */
function deactivate_advanced_flat_rate_shipping_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-advanced-flat-rate-shipping-for-woocommerce-deactivator.php';
	Advanced_Flat_Rate_Shipping_For_WooCommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_advanced_flat_rate_shipping_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_advanced_flat_rate_shipping_for_woocommerce' );

add_action( 'admin_init', 'afrsm_deactivate_plugin' );
/**
 * Deactivate plugin automatically when woocommerce will deactivate
 */
function afrsm_deactivate_plugin() {
	/*Check WooCommerce Active or not*/
	if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
		deactivate_plugins( '/advanced-flat-rate-shipping-method-for-woocommerce/advanced-flat-rate-shipping-for-woocommerce.php', true );
	}
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-advanced-flat-rate-shipping-for-woocommerce.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_advanced_flat_rate_shipping_for_woocommerce() {
	$plugin = new Advanced_Flat_Rate_Shipping_For_WooCommerce();
	$plugin->run();
}

run_advanced_flat_rate_shipping_for_woocommerce();
/**
 * Woocommerce plugin path
 */
function woo_advanced_flat_rate_shipping_for_woocommerce_pro_plugin_path() {
	return untrailingslashit( plugin_dir_path( __FILE__ ) );
}

/**
 * Helper function for logging
 *
 * For valid levels, see `WC_Log_Levels` class
 *
 * Description of levels:
 *     'emergency': System is unusable.
 *     'alert': Action must be taken immediately.
 *     'critical': Critical conditions.
 *     'error': Error conditions.
 *     'warning': Warning conditions.
 *     'notice': Normal but significant condition.
 *     'info': Informational messages.
 *     'debug': Debug-level messages.
 *
 * @param string $message error msg will display.
 * @param string $level what you want to do.
 *
 * @return mixed log
 * @since    1.0.0
 */
function afrsm_main_log( $message, $level = 'debug' ) {
	$chk_enable_logging = get_option( 'chk_enable_logging' );
	if ( 'off' === $chk_enable_logging ) {
		return;
	}
	$logger  = wc_get_logger();
	$context = array( 'source' => 'advanced-flat-rate-shipping-for-woocommerce' );
	return $logger->log( $level, $message, $context );
}
add_action( 'admin_notices', 'afrsm_main_notice_function' );
/**
 * Notice function: When activate plugin then notice will display.
 *
 * @since    1.0.0
 */
function afrsm_main_notice_function() {
	$screen    = get_current_screen();
	$screen_id = $screen ? $screen->id : '';
	$screens   = array(
		'plugins',
		'woocommerce_page_afrsm-start-page',
	);
	if ( ! in_array( $screen_id, $screens, true ) ) {
		return;
	}
	$afrsm_admin     = filter_input( INPUT_GET, 'afrsm-hide-notice', FILTER_SANITIZE_STRING );
	$wc_notice_nonce = filter_input( INPUT_GET, '_afrsm_notice_nonce', FILTER_SANITIZE_STRING );
	if ( isset( $afrsm_admin ) && 'afrsm_admin' === $afrsm_admin && wp_verify_nonce( sanitize_text_field( $wc_notice_nonce ), 'afrsm_hide_notices_nonce' ) ) {
		delete_transient( 'afrsm-admin-notice' );
	}
	/* Check transient, if available display notice */
	if ( get_transient( 'afrsm-admin-notice' ) ) {
		?>
	<div id="message"
	class="updated woocommerce-message woocommerce-admin-promo-messages welcome-panel afrsm-panel">
			<a class="woocommerce-message-close notice-dismiss"
			href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'afrsm-hide-notice', 'afrsm_admin' ), 'afrsm_hide_notices_nonce', '_afrsm_notice_nonce' ) ); ?>"></a>
			<p>
				<?php
				$html = sprintf(
					'<strong>%s</strong>',
					esc_html__( 'Advanced Flat Rate Shipping For WooCommerce is successfully installed and ready to go.', 'advanced-flat-rate-shipping-for-woocommerce' )
				);
				echo wp_kses_post( $html );
				?>
			</p>
			<p>
				<?php echo wp_kses_post( __( 'Click on settings button and create your shipping method with multiple rules', 'advanced-flat-rate-shipping-for-woocommerce' ) ); ?>
			</p>
			<?php
			$url = add_query_arg( array( 'page' => 'afrsm-start-page' ), admin_url( 'admin.php' ) );
			?>
			<p>
				<a href="<?php echo esc_url( $url ); ?>"
				class="button button-primary"><?php esc_html_e( 'Settings', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
			</p>
	</div>
		<?php
	}
}
