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
$get_tab    = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
$get_status = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );
?>
<h1 class="wp-heading-inline">
	<?php
	echo esc_html( __( 'Import Export Setting', 'advanced-flat-rate-shipping-for-woocommerce' ) );
	?>
</h1>
<?php
$admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin( '', '' );
if ( 'success' === $get_status ) {
	echo wp_kses_post( $admin_object->afrsfwa_updated_message( 'import', $get_tab, '' ) );
}
?>
<div class="sub-title screen-reeader-title">
	<h2><?php echo esc_html__( 'Step 1 - Import &amp; Export Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
</div>
<table class="table-impexpsetting table-outer form-table" cellpadding="0" cellspacing="0">
	<tbody>
	<tr>
		<th scope="row" class="titledesc"><label
				for="blogname"><?php echo esc_html__( 'Export Zone Data', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
		</th>
		<td>
			<form method="post">
				<div class="afrsm_main_container">
					<p class="afrsm_button_container"><?php submit_button( esc_html__( 'Export', 'advanced-flat-rate-shipping-for-woocommerce' ), 'secondary', 'submit', false ); ?></p>
					<p class="afrsm_content_container">
						<?php wp_nonce_field( 'afrsm_zone_export_save_action_nonce', 'afrsm_zone_export_action_nonce' ); ?>
						<input type="hidden" name="afrsm_zone_export_action" value="zone_export_settings"/>
						<strong><?php esc_html_e( 'Export the zone settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></strong>
					</p>
				</div>
			</form>
		</td>
	</tr>
	<tr>
		<th scope="row" class="titledesc"><label
				for="blogname"><?php echo esc_html__( 'Import Zone Data', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
		</th>
		<td>
			<form method="post" enctype="multipart/form-data">
				<div class="afrsm_main_container">
					<p>
						<input type="file" name="zone_import_file"/>
					</p>
					<p class="afrsm_button_container">
						<input type="hidden" name="afrsm_zone_import_action" value="zone_import_settings"/>
						<?php wp_nonce_field( 'afrsm_zone_import_action_nonce', 'afrsm_zone_import_action_nonce' ); ?>
						<?php
						$other_attributes = array( 'id' => 'afrsm_zone_import_setting' );
						?>
						<?php submit_button( esc_html__( 'Import', 'advanced-flat-rate-shipping-for-woocommerce' ), 'secondary', 'submit', false, $other_attributes ); ?>
						<strong><?php esc_html_e( 'Import the zone settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.' ); ?></strong>
					</p>
				</div>
			</form>
		</td>
	</tr>
	</tbody>
</table>
<div class="sub-title screen-reeader-title">
	<h2><?php echo esc_html__( 'Step 2 - Import &amp; Export Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
</div>
<table class="table-impexpsetting table-outer form-table" cellpadding="0" cellspacing="0">
	<tbody>
	<tr>
		<th scope="row" class="titledesc"><label
				for="blogname"><?php echo esc_html__( 'Export Shipping Method Data', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
		</th>
		<td>
			<form method="post">
				<div class="afrsm_main_container">
					<p class="afrsm_button_container"><?php submit_button( esc_html__( 'Export', 'advanced-flat-rate-shipping-for-woocommerce' ), 'secondary', 'submit', false ); ?></p>
					<p class="afrsm_content_container">
						<?php wp_nonce_field( 'afrsm_export_save_action_nonce', 'afrsm_export_action_nonce' ); ?>
						<input type="hidden" name="afrsm_export_action" value="export_settings"/>
						<strong><?php esc_html_e( 'Export the shipping method settings for this site as a .json file. This allows you to easily import the configuration into another site. Please make sure simple product and variation products slugs must be unique.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></strong>
					</p>
				</div>
			</form>
		</td>
	</tr>
	<tr>
		<th scope="row" class="titledesc"><label
				for="blogname"><?php echo esc_html__( 'Import Shipping Method Data', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
		</th>
		<td>
			<form method="post" enctype="multipart/form-data">
				<div class="afrsm_main_container">
					<p>
						<input type="file" name="import_file"/>
					</p>
					<p class="afrsm_button_container">
						<input type="hidden" name="afrsm_import_action" value="import_settings"/>
						<?php wp_nonce_field( 'afrsm_import_action_nonce', 'afrsm_import_action_nonce' ); ?>
						<?php
						$other_attributes = array( 'id' => 'afrsm_import_setting' );
						?>
						<?php submit_button( esc_html__( 'Import', 'advanced-flat-rate-shipping-for-woocommerce' ), 'secondary', 'submit', false, $other_attributes ); ?>
						<strong><?php esc_html_e( 'Import the shipping method settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.' ); ?></strong>
					</p>
				</div>
			</form>
		</td>
	</tr>
	</tbody>
</table>
<p class="submit">
	<input type="submit" class="button button-primary" name="save_master_setting" value="<?php esc_html_e( 'Save Settings', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
</p>
