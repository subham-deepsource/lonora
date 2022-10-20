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
$get_tab     = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
$current_tab = ! empty( $get_tab ) ? sanitize_title( $get_tab ) : 'master_setting';
$afrsm_tab   = Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin::afrsfwa_tab_array();
require_once dirname( __FILE__ ) . '/class-afrsm-shipping-method-page.php';
require_once dirname( __FILE__ ) . '/class-afrsm-shipping-zone-page.php';
?>
<div class="wrap woocommerce">
	<form method="post" enctype="multipart/form-data">
		<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
			<?php
			foreach ( $afrsm_tab as $name => $label ) {
				$afrsm_url = add_query_arg(
					array(
						'page' => 'afrsm-start-page',
						'tab'  => esc_attr( $name ),
					),
					admin_url( 'admin.php' )
				);
				echo '<a href="' . esc_url( $afrsm_url ) . '" class="nav-tab ';
				if ( $current_tab === $name ) {
					echo 'nav-tab-active';
				}
				echo '">' . esc_html( $label ) . '</a>';
			}
			?>
		</nav>
		<?php
		switch ( $current_tab ) {
			case 'import_export':
				require_once dirname( __FILE__ ) . '/afrsm-imp-exp-setting.php';
				break;
			case 'advance_shipping_method':
				$shipping_method_obj = new AFRSM_Shipping_Method_Page();
				$shipping_method_obj->afrsmsmp_sz_output();
				break;
			case 'advance_shipping_zone':
				$shipping_zone_obj = new AFRSM_Shipping_Zone_Page();
				$shipping_zone_obj->afrsmsz_sz_output();
				break;
			default:
				require_once dirname( __FILE__ ) . '/afrsm-master-setting.php';
				break;
		}
		?>
	</form>
</div>
