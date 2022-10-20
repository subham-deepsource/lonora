<?php
/**
 * If this file is called directly, abort.
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin( '', '' );
if ( isset( $_POST['save_master_setting'] ) && ! empty( $_POST['save_master_setting'] ) ) {
	$post_wpnonce         = filter_input( INPUT_POST, 'afrsm_save_master_setting', FILTER_SANITIZE_STRING );
	$post_retrieved_nonce = isset( $post_wpnonce ) ? sanitize_text_field( wp_unslash( $post_wpnonce ) ) : '';
	if ( ! wp_verify_nonce( $post_retrieved_nonce, 'afrsm_save_master_setting_action' ) ) {
		$admin_object->afrsfwa_updated_message( 'nonce_check', '', '' );
	} else {
		$get_what_to_do                             = filter_input( INPUT_POST, 'what_to_do_method', FILTER_SANITIZE_STRING );
		$get_shipping_display_mode                  = filter_input( INPUT_POST, 'shipping_display_mode', FILTER_SANITIZE_STRING );
		$get_combine_default_shipping_with_forceall = filter_input( INPUT_POST, 'combine_default_shipping_with_forceall', FILTER_SANITIZE_STRING );
		$get_chk_enable_logging                     = filter_input( INPUT_POST, 'chk_enable_logging', FILTER_SANITIZE_STRING );
		$get_forceall_label                         = filter_input( INPUT_POST, 'forceall_label', FILTER_SANITIZE_STRING );
		$what_to_do                                 = ! empty( $get_what_to_do ) ? sanitize_text_field( wp_unslash( $get_what_to_do ) ) : '';
		$shipping_display_mode                      = ! empty( $get_shipping_display_mode ) ? sanitize_text_field( wp_unslash( $get_shipping_display_mode ) ) : '';
		$combine_default_shipping_with_forceall     = ! empty( $get_combine_default_shipping_with_forceall ) ? sanitize_text_field( wp_unslash( $get_combine_default_shipping_with_forceall ) ) : '';
		$forceall_label                             = ! empty( $get_forceall_label ) ? sanitize_text_field( wp_unslash( $get_forceall_label ) ) : '';
		if ( isset( $what_to_do ) && ! empty( $what_to_do ) ) {
			update_option( 'what_to_do_method', $what_to_do );
		}
		if ( 'allow_customer' === $what_to_do ) {
			if ( isset( $shipping_display_mode ) && ! empty( $shipping_display_mode ) ) {
				update_option( 'md_woocommerce_shipping_method_format', $shipping_display_mode );
			}
		} else {
			update_option( 'md_woocommerce_shipping_method_format', 'radio_button_mode' );
		}
		if ( isset( $combine_default_shipping_with_forceall ) && ! empty( $combine_default_shipping_with_forceall ) ) {
			update_option( 'combine_default_shipping_with_forceall', $combine_default_shipping_with_forceall );
		}
		if ( isset( $get_chk_enable_logging ) ) {
			update_option( 'chk_enable_logging', 'on' );
		} else {
			update_option( 'chk_enable_logging', 'off' );
		}
		if ( isset( $forceall_label ) && ! empty( $forceall_label ) ) {
			update_option( 'forceall_label', $forceall_label );
		} else {
			update_option( 'forceall_label', '' );
		}
		$admin_object->afrsfwa_updated_message( 'saved', '', '' );
	}
}
?>
<h1 class="wp-heading-inline">
	<?php
	echo esc_html( __( 'Master Setting', 'advanced-flat-rate-shipping-for-woocommerce' ) );
	?>
</h1>
<?php
wp_nonce_field( 'afrsm_save_master_setting_action', 'afrsm_save_master_setting' );
$what_to_do_method                      = get_option( 'what_to_do_method' );
$shipping_method_format                 = get_option( 'md_woocommerce_shipping_method_format' );
$combine_default_shipping_with_forceall = get_option( 'combine_default_shipping_with_forceall' );
$chk_enable_logging                     = get_option( 'chk_enable_logging' );
$chk_enable_logging_checked             = ( ( ! empty( $chk_enable_logging ) && 'on' === $chk_enable_logging ) || empty( $chk_enable_logging ) ) ? 'checked' : '';
$forceall_label                         = get_option( 'forceall_label' );
?>
<table class="table-mastersettings table-outer form-table" cellpadding="0" cellspacing="0">
	<tbody>
	<tr id="table-whattodo">
		<th scope="row">
			<label
				for="table-whattodo"><?php esc_html_e( 'What to do when multiple shipping methods are available', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
		</th>
		<td>
			<select name="what_to_do_method" id="what_to_do_method">
				<option
					value="allow_customer"<?php echo ( isset( $what_to_do_method ) && 'allow_customer' === $what_to_do_method ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Allow customer to choose', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
				<option
					value="apply_highest"<?php echo ( isset( $what_to_do_method ) && 'apply_highest' === $what_to_do_method ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Apply Highest', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
				<option
					value="apply_smallest"<?php echo ( isset( $what_to_do_method ) && 'apply_smallest' === $what_to_do_method ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Apply Smallest', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
				<option
					value="force_all"<?php echo ( isset( $what_to_do_method ) && 'force_all' === $what_to_do_method ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Force all shipping methods', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
			</select>
		</td>
	</tr>
	<tr valign="top" id="display_mode">
		<th scope="row">
			<label for="table-whattodo">
				<?php esc_html_e( 'Shipping Display Mode', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
			</label>
		</th>
		<td>
			<select name="shipping_display_mode" id="shipping_display_mode">
				<option
					value="radio_button_mode"<?php echo ( isset( $shipping_method_format ) && 'radio_button_mode' === $shipping_method_format ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Display shipping methods with radio buttons', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
				<option
					value="dropdown_mode"<?php echo ( isset( $shipping_method_format ) && 'dropdown_mode' === $shipping_method_format ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Display shipping methods in a dropdown', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
			</select>
		</td>
	</tr>
	<tr valign="top" id="combine_default_shipping_with_forceall_td">
		<th scope="row">
			<label for="table-whattodo">
				<?php esc_html_e( 'Are you want to allow to include default wooCommerce shipping method in forceall', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
			</label>
		</th>
		<td>
			<select name="combine_default_shipping_with_forceall"
			id="combine_default_shipping_with_forceall">
				<option
					value="woo_our"<?php echo ( isset( $combine_default_shipping_with_forceall ) && 'woo_our' === $combine_default_shipping_with_forceall ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Combine both shipping method. ( Default WooCommerce and Our plugin\'s shipping method. )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
				<option
					value="our"<?php echo ( isset( $combine_default_shipping_with_forceall ) && 'our' === $combine_default_shipping_with_forceall ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Separate shipping method ( only combine our plugin\'s shipping method. )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
				<option
					value="all"<?php echo ( isset( $combine_default_shipping_with_forceall ) && 'all' === $combine_default_shipping_with_forceall ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Combine all shipping', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
			</select>
		</td>
	</tr>
	<tr valign="top" id="forceall_text">
		<th scope="row">
			<label for="table-whattodo">
				<?php esc_html_e( 'Forceall Label', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
			</label>
		</th>
		<td>
			<input type="text" name="forceall_label" id="forceall_label_id"
			value="<?php echo esc_attr( $forceall_label ); ?>"/>
		</td>
	</tr>
	<tr valign="top" id="enable_logging">
		<th scope="row">
			<label for="table-whattodo">
				<?php esc_html_e( 'Enable Logging', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
			</label>
		</th>
		<td>
			<input type="checkbox" name="chk_enable_logging" id="chk_enable_logging"
			value="on" <?php echo esc_attr( $chk_enable_logging_checked ); ?>>
		</td>
	</tr>
	</tbody>
</table>
<p class="submit">
	<input type="submit" class="button button-primary" name="save_master_setting"
	value="<?php esc_html_e( 'Save Settings', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
</p>
