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
$get_action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
$get_id     = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
if ( 'edit' === $get_action ) {
	if ( ! empty( $get_id ) && '' !== $get_id ) {
		$get_post_id            = isset( $get_id ) ? sanitize_text_field( wp_unslash( $get_id ) ) : '';
		$sm_status              = get_post_status( $get_post_id );
		$sm_title               = get_the_title( $get_post_id );
		$sm_cost                = get_post_meta( $get_post_id, 'sm_product_cost', true );
		$how_to_apply           = get_post_meta( $get_post_id, 'how_to_apply', true );
		$get_fees_per_qty       = get_post_meta( $get_post_id, 'sm_fee_per_qty', true );
		$extra_product_cost     = get_post_meta( $get_post_id, 'sm_extra_product_cost', true );
		$sm_tooltip_desc        = get_post_meta( $get_post_id, 'sm_tooltip_desc', true );
		$sm_is_taxable          = get_post_meta( $get_post_id, 'sm_select_taxable', true );
		$sm_estimation_delivery = get_post_meta( $get_post_id, 'sm_estimation_delivery', true );
		$sm_start_date          = get_post_meta( $get_post_id, 'sm_start_date', true );
		$sm_end_date            = get_post_meta( $get_post_id, 'sm_end_date', true );
		$sm_time_from           = get_post_meta( $get_post_id, 'sm_time_from', true );
		$sm_time_to             = get_post_meta( $get_post_id, 'sm_time_to', true );
		$sm_select_day_of_week  = get_post_meta( $get_post_id, 'sm_select_day_of_week', true );
		if ( is_serialized( $sm_select_day_of_week ) ) {
			$sm_select_day_of_week = maybe_unserialize( $sm_select_day_of_week );
		} else {
			$sm_select_day_of_week = $sm_select_day_of_week;
		}
		$sm_extra_cost = get_post_meta( $get_post_id, 'sm_extra_cost', true );
		if ( is_serialized( $sm_extra_cost ) ) {
			$sm_extra_cost = maybe_unserialize( $sm_extra_cost );
		} else {
			$sm_extra_cost = $sm_extra_cost;
		}
		$sm_extra_cost_calc_type = get_post_meta( $get_post_id, 'sm_extra_cost_calculation_type', true );
		$sm_metabox              = get_post_meta( $get_post_id, 'sm_metabox', true );
		if ( is_serialized( $sm_metabox ) ) {
			$sm_metabox = maybe_unserialize( $sm_metabox );
		} else {
			$sm_metabox = $sm_metabox;
		}
		$cost_on_product_status                 = get_post_meta( $get_post_id, 'cost_on_product_status', true );
		$cost_on_product_weight_status          = get_post_meta( $get_post_id, 'cost_on_product_weight_status', true );
		$cost_on_product_subtotal_status        = get_post_meta( $get_post_id, 'cost_on_product_subtotal_status', true );
		$cost_on_category_status                = get_post_meta( $get_post_id, 'cost_on_category_status', true );
		$cost_on_category_weight_status         = get_post_meta( $get_post_id, 'cost_on_category_weight_status', true );
		$cost_on_category_subtotal_status       = get_post_meta( $get_post_id, 'cost_on_category_subtotal_status', true );
		$cost_on_total_cart_qty_status          = get_post_meta( $get_post_id, 'cost_on_total_cart_qty_status', true );
		$cost_on_total_cart_weight_status       = get_post_meta( $get_post_id, 'cost_on_total_cart_weight_status', true );
		$cost_on_total_cart_subtotal_status     = get_post_meta( $get_post_id, 'cost_on_total_cart_subtotal_status', true );
		$cost_on_shipping_class_subtotal_status = get_post_meta( $get_post_id, 'cost_on_shipping_class_subtotal_status', true );
		$sm_metabox_ap_product                  = get_post_meta( $get_post_id, 'sm_metabox_ap_product', true );
		if ( is_serialized( $sm_metabox_ap_product ) ) {
			$sm_metabox_ap_product = maybe_unserialize( $sm_metabox_ap_product );
		} else {
			$sm_metabox_ap_product = $sm_metabox_ap_product;
		}
		$sm_metabox_ap_product_subtotal = get_post_meta( $get_post_id, 'sm_metabox_ap_product_subtotal', true );
		if ( is_serialized( $sm_metabox_ap_product_subtotal ) ) {
			$sm_metabox_ap_product_subtotal = maybe_unserialize( $sm_metabox_ap_product_subtotal );
		} else {
			$sm_metabox_ap_product_subtotal = $sm_metabox_ap_product_subtotal;
		}
		$sm_metabox_ap_product_weight = get_post_meta( $get_post_id, 'sm_metabox_ap_product_weight', true );
		if ( is_serialized( $sm_metabox_ap_product_weight ) ) {
			$sm_metabox_ap_product_weight = maybe_unserialize( $sm_metabox_ap_product_weight );
		} else {
			$sm_metabox_ap_product_weight = $sm_metabox_ap_product_weight;
		}
		$sm_metabox_ap_category = get_post_meta( $get_post_id, 'sm_metabox_ap_category', true );
		if ( is_serialized( $sm_metabox_ap_category ) ) {
			$sm_metabox_ap_category = maybe_unserialize( $sm_metabox_ap_category );
		} else {
			$sm_metabox_ap_category = $sm_metabox_ap_category;
		}
		$sm_metabox_ap_category_subtotal = get_post_meta( $get_post_id, 'sm_metabox_ap_category_subtotal', true );
		if ( is_serialized( $sm_metabox_ap_category_subtotal ) ) {
			$sm_metabox_ap_category_subtotal = maybe_unserialize( $sm_metabox_ap_category_subtotal );
		} else {
			$sm_metabox_ap_category_subtotal = $sm_metabox_ap_category_subtotal;
		}
		$sm_metabox_ap_category_weight = get_post_meta( $get_post_id, 'sm_metabox_ap_category_weight', true );
		if ( is_serialized( $sm_metabox_ap_category_weight ) ) {
			$sm_metabox_ap_category_weight = maybe_unserialize( $sm_metabox_ap_category_weight );
		} else {
			$sm_metabox_ap_category_weight = $sm_metabox_ap_category_weight;
		}
		$sm_metabox_ap_total_cart_qty = get_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_qty', true );
		if ( is_serialized( $sm_metabox_ap_total_cart_qty ) ) {
			$sm_metabox_ap_total_cart_qty = maybe_unserialize( $sm_metabox_ap_total_cart_qty );
		} else {
			$sm_metabox_ap_total_cart_qty = $sm_metabox_ap_total_cart_qty;
		}
		$sm_metabox_ap_total_cart_weight = get_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_weight', true );
		if ( is_serialized( $sm_metabox_ap_total_cart_weight ) ) {
			$sm_metabox_ap_total_cart_weight = maybe_unserialize( $sm_metabox_ap_total_cart_weight );
		} else {
			$sm_metabox_ap_total_cart_weight = $sm_metabox_ap_total_cart_weight;
		}
		$sm_metabox_ap_total_cart_subtotal = get_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_subtotal', true );
		if ( is_serialized( $sm_metabox_ap_total_cart_subtotal ) ) {
			$sm_metabox_ap_total_cart_subtotal = maybe_unserialize( $sm_metabox_ap_total_cart_subtotal );
		} else {
			$sm_metabox_ap_total_cart_subtotal = $sm_metabox_ap_total_cart_subtotal;
		}
		$sm_metabox_ap_shipping_class_subtotal = get_post_meta( $get_post_id, 'sm_metabox_ap_shipping_class_subtotal', true );
		if ( is_serialized( $sm_metabox_ap_shipping_class_subtotal ) ) {
			$sm_metabox_ap_shipping_class_subtotal = maybe_unserialize( $sm_metabox_ap_shipping_class_subtotal );
		} else {
			$sm_metabox_ap_shipping_class_subtotal = $sm_metabox_ap_shipping_class_subtotal;
		}
		$cost_rule_match = get_post_meta( $get_post_id, 'cost_rule_match', true );
		if ( ! empty( $cost_rule_match ) ) {
			if ( is_serialized( $cost_rule_match ) ) {
				$cost_rule_match = maybe_unserialize( $cost_rule_match );
			} else {
				$cost_rule_match = $cost_rule_match;
			}
			if ( array_key_exists( 'general_rule_match', $cost_rule_match ) ) {
				$general_rule_match = $cost_rule_match['general_rule_match'];
			} else {
				$general_rule_match = 'all';
			}
			if ( array_key_exists( 'advance_rule_match', $cost_rule_match ) ) {
				$advance_rule_match = $cost_rule_match['advance_rule_match'];
			} else {
				$advance_rule_match = 'any';
			}
			if ( array_key_exists( 'cost_on_product_rule_match', $cost_rule_match ) ) {
				$cost_on_product_rule_match = $cost_rule_match['cost_on_product_rule_match'];
			} else {
				$cost_on_product_rule_match = 'any';
			}
			if ( array_key_exists( 'cost_on_product_weight_rule_match', $cost_rule_match ) ) {
				$cost_on_product_weight_rule_match = $cost_rule_match['cost_on_product_weight_rule_match'];
			} else {
				$cost_on_product_weight_rule_match = 'any';
			}
			if ( array_key_exists( 'cost_on_product_subtotal_rule_match', $cost_rule_match ) ) {
				$cost_on_product_subtotal_rule_match = $cost_rule_match['cost_on_product_subtotal_rule_match'];
			} else {
				$cost_on_product_subtotal_rule_match = 'any';
			}
			if ( array_key_exists( 'cost_on_category_rule_match', $cost_rule_match ) ) {
				$cost_on_category_rule_match = $cost_rule_match['cost_on_category_rule_match'];
			} else {
				$cost_on_category_rule_match = 'any';
			}
			if ( array_key_exists( 'cost_on_category_weight_rule_match', $cost_rule_match ) ) {
				$cost_on_category_weight_rule_match = $cost_rule_match['cost_on_category_weight_rule_match'];
			} else {
				$cost_on_category_weight_rule_match = 'any';
			}
			if ( array_key_exists( 'cost_on_category_subtotal_rule_match', $cost_rule_match ) ) {
				$cost_on_category_subtotal_rule_match = $cost_rule_match['cost_on_category_subtotal_rule_match'];
			} else {
				$cost_on_category_subtotal_rule_match = 'any';
			}
			if ( array_key_exists( 'cost_on_total_cart_qty_rule_match', $cost_rule_match ) ) {
				$cost_on_total_cart_qty_rule_match = $cost_rule_match['cost_on_total_cart_qty_rule_match'];
			} else {
				$cost_on_total_cart_qty_rule_match = 'any';
			}
			if ( array_key_exists( 'cost_on_total_cart_weight_rule_match', $cost_rule_match ) ) {
				$cost_on_total_cart_weight_rule_match = $cost_rule_match['cost_on_total_cart_weight_rule_match'];
			} else {
				$cost_on_total_cart_weight_rule_match = 'any';
			}
			if ( array_key_exists( 'cost_on_total_cart_subtotal_rule_match', $cost_rule_match ) ) {
				$cost_on_total_cart_subtotal_rule_match = $cost_rule_match['cost_on_total_cart_subtotal_rule_match'];
			} else {
				$cost_on_total_cart_subtotal_rule_match = 'any';
			}
			if ( array_key_exists( 'cost_on_shipping_class_subtotal_rule_match', $cost_rule_match ) ) {
				$cost_on_shipping_class_subtotal_rule_match = $cost_rule_match['cost_on_shipping_class_subtotal_rule_match'];
			} else {
				$cost_on_shipping_class_subtotal_rule_match = 'any';
			}
		} else {
			$general_rule_match                         = 'all';
			$advance_rule_match                         = 'any';
			$cost_on_product_rule_match                 = 'any';
			$cost_on_product_weight_rule_match          = 'any';
			$cost_on_product_subtotal_rule_match        = 'any';
			$cost_on_category_rule_match                = 'any';
			$cost_on_category_weight_rule_match         = 'any';
			$cost_on_category_subtotal_rule_match       = 'any';
			$cost_on_total_cart_qty_rule_match          = 'any';
			$cost_on_total_cart_weight_rule_match       = 'any';
			$cost_on_total_cart_subtotal_rule_match     = 'any';
			$cost_on_shipping_class_subtotal_rule_match = 'any';
		}
		$title_text = esc_html__( 'Edit Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' );
	}
} else {
	$get_post_id                                = '';
	$sm_status                                  = '';
	$sm_title                                   = '';
	$sm_cost                                    = '';
	$how_to_apply                               = '';
	$get_fees_per_qty                           = '';
	$extra_product_cost                         = '';
	$sm_tooltip_desc                            = '';
	$sm_is_taxable                              = '';
	$sm_estimation_delivery                     = '';
	$sm_start_date                              = '';
	$sm_end_date                                = '';
	$sm_extra_cost                              = array();
	$sm_extra_cost_calc_type                    = '';
	$sm_metabox                                 = array();
	$cost_on_product_status                     = '';
	$cost_on_category_status                    = '';
	$cost_on_total_cart_qty_status              = '';
	$cost_on_product_weight_status              = '';
	$cost_on_category_weight_status             = '';
	$cost_on_total_cart_weight_status           = '';
	$cost_on_total_cart_subtotal_status         = '';
	$cost_on_shipping_class_subtotal_status     = '';
	$sm_metabox_ap_product                      = array();
	$sm_metabox_ap_category                     = array();
	$sm_metabox_ap_total_cart_qty               = array();
	$sm_metabox_ap_product_weight               = array();
	$sm_metabox_ap_category_weight              = array();
	$sm_metabox_ap_total_cart_weight            = array();
	$sm_metabox_ap_total_cart_subtotal          = array();
	$sm_metabox_ap_shipping_class_subtotal      = array();
	$general_rule_match                         = 'all';
	$advance_rule_match                         = 'any';
	$cost_on_product_rule_match                 = 'any';
	$cost_on_product_weight_rule_match          = 'any';
	$cost_on_product_subtotal_rule_match        = 'any';
	$cost_on_category_rule_match                = 'any';
	$cost_on_category_weight_rule_match         = 'any';
	$cost_on_category_subtotal_rule_match       = 'any';
	$cost_on_total_cart_qty_rule_match          = 'any';
	$cost_on_total_cart_weight_rule_match       = 'any';
	$cost_on_total_cart_subtotal_rule_match     = 'any';
	$cost_on_shipping_class_subtotal_rule_match = 'any';
	$title_text                                 = esc_html__( 'Add Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' );
}
$sm_status                              = ( ( ! empty( $sm_status ) && 'publish' === $sm_status ) || empty( $sm_status ) ) ? 'checked' : '';
$cost_on_product_status                 = ( ! empty( $cost_on_product_status ) && 'on' === $cost_on_product_status && '' !== $cost_on_product_status ) ? 'checked' : '';
$cost_on_product_weight_status          = ( ! empty( $cost_on_product_weight_status ) && 'on' === $cost_on_product_weight_status && '' !== $cost_on_product_weight_status ) ? 'checked' : '';
$cost_on_product_subtotal_status        = ( ! empty( $cost_on_product_subtotal_status ) && 'on' === $cost_on_product_subtotal_status && '' !== $cost_on_product_subtotal_status ) ? 'checked' : '';
$cost_on_category_status                = ( ! empty( $cost_on_category_status ) && 'on' === $cost_on_category_status && '' !== $cost_on_category_status ) ? 'checked' : '';
$cost_on_category_weight_status         = ( ! empty( $cost_on_category_weight_status ) && 'on' === $cost_on_category_weight_status && '' !== $cost_on_category_weight_status ) ? 'checked' : '';
$cost_on_category_subtotal_status       = ( ! empty( $cost_on_category_subtotal_status ) && 'on' === $cost_on_category_subtotal_status && '' !== $cost_on_category_subtotal_status ) ? 'checked' : '';
$cost_on_total_cart_qty_status          = ( ! empty( $cost_on_total_cart_qty_status ) && 'on' === $cost_on_total_cart_qty_status && '' !== $cost_on_total_cart_qty_status ) ? 'checked' : '';
$cost_on_total_cart_weight_status       = ( ! empty( $cost_on_total_cart_weight_status ) && 'on' === $cost_on_total_cart_weight_status && '' !== $cost_on_total_cart_weight_status ) ? 'checked' : '';
$cost_on_total_cart_subtotal_status     = ( ! empty( $cost_on_total_cart_subtotal_status ) && 'on' === $cost_on_total_cart_subtotal_status && '' !== $cost_on_total_cart_subtotal_status ) ? 'checked' : '';
$cost_on_shipping_class_subtotal_status = ( ! empty( $cost_on_shipping_class_subtotal_status ) && 'on' === $cost_on_shipping_class_subtotal_status && '' !== $cost_on_shipping_class_subtotal_status ) ? 'checked' : '';
$sm_title                               = ! empty( $sm_title ) ? esc_attr( stripslashes( $sm_title ) ) : '';
$sm_cost                                = ( '' !== $sm_cost ) ? esc_attr( stripslashes( $sm_cost ) ) : '';
$sm_tooltip_desc                        = ! empty( $sm_tooltip_desc ) ? $sm_tooltip_desc : '';
$sm_estimation_delivery                 = ! empty( $sm_estimation_delivery ) ? esc_attr( stripslashes( $sm_estimation_delivery ) ) : '';
$sm_start_date                          = ! empty( $sm_start_date ) ? esc_attr( stripslashes( $sm_start_date ) ) : '';
$sm_end_date                            = ! empty( $sm_end_date ) ? esc_attr( stripslashes( $sm_end_date ) ) : '';
$sm_time_from                           = ! empty( $sm_time_from ) ? esc_attr( stripslashes( $sm_time_from ) ) : '';
$sm_time_to                             = ! empty( $sm_time_to ) ? esc_attr( stripslashes( $sm_time_to ) ) : '';
$sm_select_day_of_week                  = ! empty( $sm_select_day_of_week ) ? $sm_select_day_of_week : array();
$submit_text                            = __( 'Save changes', 'advanced-flat-rate-shipping-for-woocommerce' );
$afrsm_admin_object                     = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Admin( '', '' );
$afrsm_object                           = new Advanced_Flat_Rate_Shipping_For_WooCommerce( '', '' );
?>
	<h1 class="wp-heading-inline"><?php echo esc_html( $title_text ); ?></h1>
	<hr class="wp-header-end">
	<table class="form-table table-outer shipping-method-table main-shipping-conf">
		<tbody>
		<tr valign="top">
			<th class="titledesc" scope="row">
				<label for="onoffswitch"><?php esc_html_e( 'Status', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
				<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
				<p class="description" style="display:none;">
					<?php esc_html_e( 'Enable or Disable this shipping method using this button ( This method will be visible to customers only if it is enabled ).', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
				</p>
			</th>
			<td class="forminp">
				<label class="switch">
					<input type="checkbox" name="sm_status" value="on" <?php echo esc_attr( $sm_status ); ?>>
					<div class="slider round"></div>
				</label>
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc" scope="row">
				<label for="fee_settings_product_fee_title"><?php esc_html_e( 'Shipping Method Name', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
					<span class="required-star">*</span>
				</label>
				<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
				<p class="description" style="display:none;">
					<?php esc_html_e( 'This name will be visible to the customer at the time of checkout. This should convey the purpose of the charges you are applying to the order. For example "Ground Shipping", "Express Shipping Flat Rate", "Christmas Next Day Shipping" etc', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
				</p>
			</th>
			<td class="forminp">
				<input type="text" name="fee_settings_product_fee_title" class="text-class" id="fee_settings_product_fee_title" value="<?php echo esc_attr( $sm_title ); ?>" required="1" placeholder="<?php esc_html_e( 'Enter product fees title', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc" scope="row">
				<label for="sm_product_cost">
					<?php esc_html_e( 'Shipping Charge', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
					( <?php echo esc_html( get_woocommerce_currency_symbol() ); ?> )
					<span class="required-star">*</span>
				</label>
				<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
				<p class="description" style="display:none;">
					<?php
					$html = sprintf(
						'%s<br>%s<br>%s<br>%s<br>%s<br><br>%s<br>%s<br>%s<br>%s<br>%s',
						esc_html__( 'When customer select this shipping method the amount will be added to the cart subtotal. You can enter fixed amount or make it dynamic using below parameters:', 'advanced-flat-rate-shipping-for-woocommerce' ),
						esc_html__( '[qty] -> total number of items in cart,', 'advanced-flat-rate-shipping-for-woocommerce' ),
						esc_html__( '[cost] -> cost of items,', 'advanced-flat-rate-shipping-for-woocommerce' ),
						esc_html__( '[fee percent=10 min_fee=20] -> Percentage based fee,', 'advanced-flat-rate-shipping-for-woocommerce' ),
						esc_html__( '[fee percent=10 max_fee=20] -> Percentage based fee.', 'advanced-flat-rate-shipping-for-woocommerce' ),
						esc_html__( 'Below are some examples: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
						esc_html__( 'i. 10.00  -> To add flat 10.00 shipping charge.', 'advanced-flat-rate-shipping-for-woocommerce' ),
						esc_html__( 'ii. 10.00 * [qty] -> To charge 10.00 per quantity in the cart. It will be 50.00 if the cart has 5 quantity.', 'advanced-flat-rate-shipping-for-woocommerce' ),
						esc_html__( 'iii. [fee percent=10 min_fee=20] -> This means charge 10 percent of cart subtotal, minimum 20 charge will be applicable.', 'advanced-flat-rate-shipping-for-woocommerce' ),
						esc_html__( 'iv. [fee percent=10 max_fee=20] -> This means charge 10 percent of cart subtotal greater than max_fee then maximum 20 charge will be applicable.', 'advanced-flat-rate-shipping-for-woocommerce' )
					);
					echo wp_kses_post( $html );
					?>
				</p>
			</th>
			<td class="forminp">
				<input type="text" name="sm_product_cost" required="1" class="text-class" id="sm_product_cost" value="<?php echo esc_attr( $sm_cost ); ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc" scope="row">
				<label for="fee_settings_apply_per_qty"><?php esc_html_e( 'Apply Extra Charges', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
				<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
				<p class="description" style="display:none;">
					<?php esc_html_e( 'Apply this fee per quantity of products.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
				</p>
			</th>
			<td class="forminp">
				<select name="how_to_apply" id="how_to_apply">
					<option value="" <?php selected( $how_to_apply, '' ); ?>><?php esc_html_e( 'No Extra Charges', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
					<option value="advance_shipping_rules" <?php selected( $how_to_apply, 'advance_shipping_rules' ); ?>><?php esc_html_e( 'Advanced Shipping Price Rules', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
					<option value="apply_per_qty" <?php selected( $how_to_apply, 'apply_per_qty' ); ?>><?php esc_html_e( 'Apply Per Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc" scope="row">
				<label for="sm_tooltip_desc"><?php esc_html_e( 'Tooltip Description', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
			</th>
			<td class="forminp">
				<textarea name="sm_tooltip_desc" rows="3" cols="70" id="sm_tooltip_desc" placeholder="<?php esc_html_e( 'Enter tooltip short description', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"><?php echo wp_kses_post( $sm_tooltip_desc ); ?></textarea>
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc" scope="row">
				<label for="sm_select_taxable"><?php esc_html_e( 'Is Amount Taxable?', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
			</th>
			<td class="forminp">
				<select name="sm_select_taxable" id="sm_select_taxable" class="">
					<option value="no" <?php echo isset( $sm_is_taxable ) && 'no' === $sm_is_taxable ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'No', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
					<option value="yes" <?php echo isset( $sm_is_taxable ) && 'yes' === $sm_is_taxable ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Yes', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc" scope="row">
				<label for="sm_estimation_delivery"><?php esc_html_e( 'Estimated Delivery Time', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
				<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
				<p class="description" style="display:none;">
					<?php esc_html_e( 'With this feature, you can specify approximately days or time to deliver the order to the customers. It will increase your conversion ratio.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
				</p>
			</th>
			<td class="forminp">
				<input type="text" name="sm_estimation_delivery" class="text-class" id="sm_estimation_delivery" placeholder="<?php esc_html_e( 'e.g. ( 2-5 days )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $sm_estimation_delivery ); ?>">
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc" scope="row">
				<label for="sm_start_date"><?php esc_html_e( 'Start Date', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
				<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
				<p class="description" style="display:none;">
					<?php esc_html_e( 'Select start date on which date shipping method will enable on the website.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
				</p>
			</th>
			<td class="forminp">
				<input type="text" name="sm_start_date" class="text-class" id="sm_start_date" value="<?php echo esc_attr( $sm_start_date ); ?>" placeholder="<?php esc_html_e( 'Select start date', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc" scope="row">
				<label for="sm_end_date"><?php esc_html_e( 'End Date', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
				<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
				<p class="description" style="display:none;">
					<?php esc_html_e( 'Select end date on which date shipping method will disable on the website.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
				</p>
			</th>
			<td class="forminp">
				<input type="text" name="sm_end_date" class="text-class" id="sm_end_date" value="<?php echo esc_attr( $sm_end_date ); ?>" placeholder="<?php esc_html_e( 'Select end date', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc" scope="row">
				<label for="sm_select_day_of_week"><?php esc_html_e( 'Days of the Week', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
				<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
				<p class="description" style="display:none;">
					<?php
					$html = sprintf(
						'%s<a href=%s target="_blank">%s</a>',
						esc_html__( 'Select days on which day shipping method will enable on the website. This rule match with current day which is set by WordPress', 'advanced-flat-rate-shipping-for-woocommerce' ),
						esc_url( admin_url( 'options-general.php' ) ),
						esc_html__( 'Timezone', 'advanced-flat-rate-shipping-for-woocommerce' )
					);
					echo wp_kses_post( $html );
					?>
				</p>
			</th>
			<td class="forminp">
				<?php
				$select_day_week_array = array(
					'sun' => esc_html__( 'Sunday', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'mon' => esc_html__( 'Monday', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tue' => esc_html__( 'Tuesday', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'wed' => esc_html__( 'Wednesday', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'thu' => esc_html__( 'Thursday', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'fri' => esc_html__( 'Friday', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'sat' => esc_html__( 'Saturday', 'advanced-flat-rate-shipping-for-woocommerce' ),
				);
				?>
				<select name="sm_select_day_of_week[]" id="sm_select_day_of_week" class="sm_select_day_of_week multiselect2 afrsm_select" multiple="multiple">
					<?php
					foreach ( $select_day_week_array as $value => $name ) {
						?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php echo ! empty( $sm_select_day_of_week ) && in_array( $value, $sm_select_day_of_week, true ) ? 'selected="selected"' : ''; ?>><?php echo esc_html( $name ); ?></option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th class="titledesc" scope="row">
				<label for="sm_time"><?php esc_html_e( 'Time', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
				<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
				<p class="description" style="display:none;">
					<?php
					$html = sprintf(
						'%s<a href=%s target="_blank">%s</a>',
						esc_html__( 'Select time on which time shipping method will enable on the website. This rule match with current time which is set by WordPress', 'advanced-flat-rate-shipping-for-woocommerce' ),
						esc_url( admin_url( 'options-general.php' ) ),
						esc_html__( 'Timezone', 'advanced-flat-rate-shipping-for-woocommerce' )
					);
					echo wp_kses_post( $html );
					?>
				</p>
			</th>
			<td class="forminp">
				<span class="sm_time_from"><?php esc_html_e( 'From:', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
				<input type="text" name="sm_time_from" class="text-class" id="sm_time_from" value="<?php echo esc_attr( $sm_time_from ); ?>">
				<span class="sm_time_to"><?php esc_html_e( 'To:', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
				<input type="text" name="sm_time_to" class="text-class" id="sm_time_to" value="<?php echo esc_attr( $sm_time_to ); ?>">
			</td>
		</tr>
		</tbody>
	</table>
<?php
$all_shipping_classes = WC()->shipping->get_shipping_classes();
if ( ! empty( $all_shipping_classes ) ) {
	?>
	<div class="sub-title screen-reeader-title">
		<h2><?php esc_html_e( 'Additional Shipping Charges Based on Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
		<div id="add-new-shipping-description">
			<?php
			$html = sprintf(
				'%s<a href=%s>%s</a>.',
				esc_html__( 'These costs can optionally be added based on the ', 'advanced-flat-rate-shipping-for-woocommerce' ),
				esc_url(
					add_query_arg(
						array(
							'page'    => 'wc-settings',
							'tab'     => 'shipping',
							'section' => 'classes',
						),
						admin_url( 'admin.php' )
					)
				),
				esc_html__( 'product shipping class', 'advanced-flat-rate-shipping-for-woocommerce' )
			);
			echo wp_kses_post( $html );
			?>
		</div>
	</div>
	<div class="tap">
		<table class="form-table table-outer shipping-method-table shipping-class-table">
			<tbody>
			<?php
			foreach ( $all_shipping_classes as $key => $shipping_class ) {
				$shipping_extra_cost = isset( $sm_extra_cost[ "$shipping_class->term_id" ] ) && ( '' !== $sm_extra_cost[ "$shipping_class->term_id" ] ) ? $sm_extra_cost[ "$shipping_class->term_id" ] : '';
				?>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="extra_cost_<?php echo esc_attr( $shipping_class->term_id ); ?>">
							<?php
							echo esc_html( $shipping_class->name ) . ' ' . esc_html__( 'shipping class cost', 'advanced-flat-rate-shipping-for-woocommerce' );
							?>
						</label>
					</th>
					<td class="forminp">
						<input type="text" name="sm_extra_cost[<?php echo esc_attr( $shipping_class->term_id ); ?>]" class="text-class price-field" id="extra_cost_<?php echo esc_attr( $shipping_class->term_id ); ?>" value="<?php echo esc_attr( $shipping_extra_cost ); ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
					</td>
				</tr>
			<?php } ?>
			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="sm_extra_cost_calculation_type"><?php esc_html_e( 'Calculation type', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
				</th>
				<td class="forminp">
					<select name="sm_extra_cost_calculation_type" id="sm_extra_cost_calculation_type">
						<option value="per_class" <?php selected( $sm_extra_cost_calc_type, 'per_class' ); ?>>
							<?php esc_html_e( 'Per class: Charge shipping for each shipping class individually', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
						</option>
						<option value="per_order" <?php selected( $sm_extra_cost_calc_type, 'per_order' ); ?>>
							<?php esc_html_e( 'Per order: Charge shipping for the most expensive shipping class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
						</option>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
<?php } ?>
	<div id="apply_per_qty_div" class="adv-pricing-rules apply-per-qty">
		<div class="sub-title screen-reeader-title">
			<h2><?php esc_html_e( 'Additional Charge based on apply per qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
			<div id="add-new-shipping-description">
				<?php
				$html = sprintf(
					'<p class="description" style="display:none;"><b style="color: red;">%s</b>%s</b></p>',
					esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
					esc_html__( 'You will have to set rules for products specific option in shipping method rules. Ex: Cart contains product/variable product/category/tag/sku : equal to : Test Product', 'advanced-flat-rate-shipping-for-woocommerce' )
				);
				echo wp_kses_post( $html );
				?>
			</div>
		</div>
		<div class="tap">
			<table class="form-table table-outer apply-per-qty-table">
				<tbody>
				<tr valign="top" id="apply_per_qty_tr">
					<th class="titledesc" scope="row">
						<label for="apply_per_qty_type"><?php esc_html_e( 'Calculate Quantity Based On', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
						<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
						<p class="description" style="display:none;">
							<?php
							$html = sprintf(
								'%s( <b>%s</b> )<br>%s',
								esc_html__(
									'1. If you want to apply the fee for each quantity - where quantity should calculated based on
                                        product/category/tag conditions, then select the "Product Based" option. ',
									'advanced-flat-rate-shipping-for-woocommerce'
								),
								esc_html__(
									'Note: You will have to set rules for products
                                        if you select product based option. Ex: Cart contains product : equal to : Test Product',
									'advanced-flat-rate-shipping-for-woocommerce'
								),
								esc_html__(
									'2. If you want to apply the fee for each quantity in the customer\'s cart,
                                        then select the "Cart Based" option.',
									'advanced-flat-rate-shipping-for-woocommerce'
								)
							);
							echo wp_kses_post( $html );
							?>
						</p>
					</th>
					<td class="forminp">
						<select name="sm_fee_per_qty" id="price_cartqty_based" class="chk_qty_price_class" id="apply_per_qty_type">
							<option value="qty_cart_based" <?php selected( $get_fees_per_qty, 'qty_cart_based' ); ?>><?php esc_html_e( 'Cart Based', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
							<option value="qty_product_based" <?php selected( $get_fees_per_qty, 'qty_product_based' ); ?>><?php esc_html_e( 'Product Based', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
						</select>
					</td>
				</tr>

				<tr valign="top" id="apply_per_qty_tr">
					<th class="titledesc" scope="row">
						<label for="extra_product_cost">
							<?php echo esc_html__( 'Fee per Additional Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>&nbsp; <?php echo ' ( ' . esc_html( get_woocommerce_currency_symbol() ) . ' ) '; ?>
							<span class="required-star">*</span>
						</label>
						<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
						<p class="description" style="display:none;">
							<?php
							$html = sprintf(
								'%s<br>%s',
								esc_html__(
									'You can add fee here to be charged for each additional quantity. E.g. if user has added 3 quantities
                                    and you have set fee=$10 and fee per additional quantity=$5, then total extra fee=$10+$5+$5=$20.',
									'advanced-flat-rate-shipping-for-woocommerce'
								),
								esc_html__(
									'The quantity will be calculated
                                    based on the option chosen in the "Calculate Quantity Based On" above dropdown. That means, if you have chosen "Product Based"
                                    option then quantities will be calculated based on the products which are meeting the conditions set for this fee, and if
                                    they are more than 1, fee will be calculated considering only its additional quantities. e.g. 5 items in cart, and
                                    3 are meeting the condition set, then additional fee of $5 will be charged on 2 quantities only,
                                    and not on 4 quantities.',
									'advanced-flat-rate-shipping-for-woocommerce'
								)
							);
							echo wp_kses_post( $html );
							?>
						</p>
					</th>
					<td class="forminp">
						<input type="text" name="sm_extra_product_cost" class="text-class" id="extra_product_cost" required value="<?php echo isset( $extra_product_cost ) ? esc_attr( $extra_product_cost ) : ''; ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="shipping-method-rules">
		<div class="sub-title">
			<h2><?php esc_html_e( 'Shipping Method Rules', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
			<div class="tap">
				<a id="fee-add-field" class="button button-primary button-large" href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
			</div>
			<div class="advance_rule_condition_match_type">
				<p class="switch_in_pricing_rules_description_left">
					<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
				</p>
				<select name="cost_rule_match[general_rule_match]" id="general_rule_match" class="arcmt_select">
					<option value="any" <?php selected( $general_rule_match, 'any' ); ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
					<option value="all" <?php selected( $general_rule_match, 'all' ); ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
				</select>
				<p class="switch_in_pricing_rules_description">
					<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
				</p>
			</div>
		</div>
		<div class="tap">
			<table id="tbl-shipping-method" class="tbl_product_fee table-outer tap-cas form-table shipping-method-table">
				<tbody>
				<?php
				$attribute_taxonomies      = wc_get_attribute_taxonomies();
				$attribute_taxonomies_name = wc_get_attribute_taxonomy_names();
				if ( isset( $sm_metabox ) && ! empty( $sm_metabox ) ) {
					$i = 2;
					foreach ( $sm_metabox as $key => $productfees ) {
						$fees_conditions = isset( $productfees['product_fees_conditions_condition'] ) ? $productfees['product_fees_conditions_condition'] : '';
						$condition_is    = isset( $productfees['product_fees_conditions_is'] ) ? $productfees['product_fees_conditions_is'] : '';
						$condtion_value  = isset( $productfees['product_fees_conditions_values'] ) ? $productfees['product_fees_conditions_values'] : array();
						?>
						<tr id="row_<?php echo esc_attr( $i ); ?>" valign="top">
							<th class="titledesc th_product_fees_conditions_condition" scope="row">
								<select rel-id="<?php echo esc_attr( $i ); ?>" id="product_fees_conditions_condition_<?php echo esc_attr( $i ); ?>" name="fees[product_fees_conditions_condition][]" id="product_fees_conditions_condition" class="product_fees_conditions_condition">
									<optgroup label="<?php esc_html_e( 'Location Specific', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<option value="country" <?php echo ( 'country' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="state" <?php echo ( 'state' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'State', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="postcode" <?php echo ( 'postcode' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Postcode', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="zone" <?php echo ( 'zone' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Zone', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									</optgroup>
									<optgroup label="<?php esc_html_e( 'Product Specific', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<option value="product" <?php echo ( 'product' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Cart contains product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="variableproduct" <?php echo ( 'variableproduct' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Cart contains variable product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="category" <?php echo ( 'category' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Cart contains category\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="tag" <?php echo ( 'tag' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Cart contains tag\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="sku" <?php echo ( 'sku' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Cart contains SKU\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="product_qty" <?php echo ( 'product_qty' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Cart contains product\'s quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									</optgroup>
									<optgroup label="<?php esc_html_e( 'Attribute Specific', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<?php
										if ( $attribute_taxonomies ) {
											foreach ( $attribute_taxonomies as $attribute ) {
												$att_label = $attribute->attribute_label;
												$att_name  = wc_attribute_taxonomy_name( $attribute->attribute_name );
												$selected  = ( $fees_conditions === $att_name ) ? 'selected' : '';
												echo '<option value="' . esc_attr( $att_name ) . '" ' . esc_attr( $selected ) . ' >' . esc_html( $att_label ) . '</option>';
											}
										} else {
											echo '<option value="" disabled="disabled">' . esc_html__( 'Not Found Attribute', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</option>';
										}
										?>
									</optgroup>
									<optgroup label="<?php esc_html_e( 'User Specific', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<option value="user" <?php echo ( 'user' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'User', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="user_role" <?php echo ( 'user_role' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'User Role', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									</optgroup>
									<optgroup label="<?php esc_html_e( 'Cart Specific', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<?php
										$currency_symbol = get_woocommerce_currency_symbol();
										$currency_symbol = ! empty( $currency_symbol ) ? '( ' . $currency_symbol . ' )' : '';
										$weight_unit     = get_option( 'woocommerce_weight_unit' );
										$weight_unit     = ! empty( $weight_unit ) ? '( ' . $weight_unit . ' )' : '';
										?>
										<option value="cart_total" <?php echo ( 'cart_total' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Cart Subtotal ( Before Discount ) ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?><?php echo esc_html( $currency_symbol ); ?></option>
										<option value="cart_totalafter" <?php echo ( 'cart_totalafter' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Cart Subtotal ( After Discount ) ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?><?php echo esc_html( $currency_symbol ); ?></option>
										<option value="quantity" <?php echo ( 'quantity' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="weight" <?php echo ( 'weight' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Weight ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?><?php echo wp_kses_post( $weight_unit ); ?></option>
										<option value="coupon" <?php echo ( 'coupon' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Coupon', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="shipping_class" <?php echo ( 'shipping_class' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									</optgroup>
									<optgroup label="<?php esc_html_e( 'Checkout Specific', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<option value="payment_method" <?php echo ( 'payment_method' === $fees_conditions ) ? 'selected' : ''; ?>><?php esc_html_e( 'Payment Method', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									</optgroup>
								</select>
							</th>
							<td class="select_condition_for_in_notin">
								<?php if ( 'product_qty' === $fees_conditions || 'cart_total' === $fees_conditions || 'cart_totalafter' === $fees_conditions || 'quantity' === $fees_conditions || 'weight' === $fees_conditions ) { ?>
									<select name="fees[product_fees_conditions_is][]" class="product_fees_conditions_is_<?php echo esc_attr( $i ); ?>">
										<option value="is_equal_to" <?php echo ( 'is_equal_to' === $condition_is ) ? 'selected' : ''; ?>><?php esc_html_e( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="less_equal_to" <?php echo ( 'less_equal_to' === $condition_is ) ? 'selected' : ''; ?>><?php esc_html_e( 'Less or Equal to ( <= )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="less_then" <?php echo ( 'less_then' === $condition_is ) ? 'selected' : ''; ?>><?php esc_html_e( 'Less than ( < )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="greater_equal_to" <?php echo ( 'greater_equal_to' === $condition_is ) ? 'selected' : ''; ?>><?php esc_html_e( 'Greater or Equal to ( >= )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="greater_then" <?php echo ( 'greater_then' === $condition_is ) ? 'selected' : ''; ?>><?php esc_html_e( 'Greater than ( > )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="not_in" <?php echo ( 'not_in' === $condition_is ) ? 'selected' : ''; ?>><?php esc_html_e( 'Not Equal to ( ! = )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									</select>
								<?php } else { ?>
									<select name="fees[product_fees_conditions_is][]" class="product_fees_conditions_is_<?php echo esc_attr( $i ); ?>">
										<option value="is_equal_to" <?php echo ( 'is_equal_to' === $condition_is ) ? 'selected' : ''; ?>><?php esc_html_e( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="not_in" <?php echo ( 'not_in' === $condition_is ) ? 'selected' : ''; ?>><?php esc_html_e( 'Not Equal to ( ! = )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?> </option>
									</select>
								<?php } ?>
							</td>
							<td class="condition-value" id="column_<?php echo esc_attr( $i ); ?>">
								<?php
								$html = '';
								if ( 'country' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_country_list( $i, $condtion_value );
								} elseif ( 'state' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_states_list__premium_only( $i, $condtion_value );
								} elseif ( 'postcode' === $fees_conditions ) {
									$html .= '<textarea name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']">' . wp_kses_post( $condtion_value ) . '</textarea>';
								} elseif ( 'zone' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_zones_list__premium_only( $i, $condtion_value );
								} elseif ( 'product' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_product_list( $i, $condtion_value );
									$html .= wp_kses_post(
										sprintf(
											'<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
                                            </p>',
											esc_html__(
												'Note: ',
												'advanced-flat-rate-shipping-for-woocommerce'
											),
											esc_html__(
												'Please make sure that when you add rules in
                                                 Advanced Pricing > Cost per Product Section It contains in above selected product list,
                                                 otherwise it may be not apply proper shipping charges. For more detail please view
                                                 our documentation at ',
												'advanced-flat-rate-shipping-for-woocommerce'
											),
											esc_url( 'https://www.thedotstore.com/docs/plugin/advanced-flat-rate-shipping-method-for-woocommerce' ),
											esc_html__(
												'Click Here',
												'advanced-flat-rate-shipping-for-woocommerce'
											)
										)
									);
								} elseif ( 'variableproduct' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_varible_product_list__premium_only( $i, $condtion_value );
								} elseif ( 'category' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_category_list( $i, $condtion_value );
									$html .= wp_kses_post(
										sprintf(
											'<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
                                            </p>',
											esc_html__(
												'Note: ',
												'advanced-flat-rate-shipping-for-woocommerce'
											),
											esc_html__(
												'Please make sure that when you add rules in
                                                 Advanced Pricing > Cost per Category Section It contains in above selected category list,
                                                 otherwise it may be not apply proper shipping charges. For more detail please view
                                                 our documentation at ',
												'advanced-flat-rate-shipping-for-woocommerce'
											),
											esc_url( 'https://www.thedotstore.com/docs/plugin/advanced-flat-rate-shipping-method-for-woocommerce' ),
											esc_html__(
												'Click Here',
												'advanced-flat-rate-shipping-for-woocommerce'
											)
										)
									);
								} elseif ( 'tag' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_tag_list( $i, $condtion_value );
								} elseif ( 'sku' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_sku_list__premium_only( $i, $condtion_value );
								} elseif ( 'product_qty' === $fees_conditions ) {
									$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values qty-class" class = "product_fees_conditions_values qty-class" value = "' . esc_attr( $condtion_value ) . '">';
									$html .= wp_kses_post(
										sprintf(
											'<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.</p>',
											esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
											esc_html__( 'This rule will only work if you have selected any one Product Specific option. ', 'advanced-flat-rate-shipping-for-woocommerce' ),
											esc_url( 'https://docs.thedotstore.com/article/104-product-specific-shipping-rule/' ),
											esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' )
										)
									);
								} elseif ( in_array( $fees_conditions, $attribute_taxonomies_name, true ) ) {
									$html .= $afrsm_admin_object->afrsfwa_get_att_term_list__premium_only( $i, $fees_conditions, $condtion_value );
								} elseif ( 'user' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_user_list( $i, $condtion_value );
								} elseif ( 'user_role' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_user_role_list__premium_only( $i, $condtion_value );
								} elseif ( 'cart_total' === $fees_conditions ) {
									$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values" value = "' . esc_attr( $condtion_value ) . '">';
								} elseif ( 'cart_totalafter' === $fees_conditions ) {
									$html .= '<input type="text" name="fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id="product_fees_conditions_values" class="product_fees_conditions_values" value="' . esc_attr( $condtion_value ) . '">';
									$html .= wp_kses_post(
										sprintf(
											'<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
                                            </p>',
											esc_html__(
												'Note: ',
												'advanced-flat-rate-shipping-for-woocommerce'
											),
											esc_html__(
												'This rule will apply when you would apply coupun in front side. ',
												'advanced-flat-rate-shipping-for-woocommerce'
											),
											esc_url( 'https://www.thedotstore.com/docs/plugin/advanced-flat-rate-shipping-method-for-woocommerce' ),
											esc_html__(
												'Click Here',
												'advanced-flat-rate-shipping-for-woocommerce'
											)
										)
									);
								} elseif ( 'quantity' === $fees_conditions ) {
									$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values" value = "' . esc_attr( $condtion_value ) . '">';
								} elseif ( 'weight' === $fees_conditions ) {
									$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values" value = "' . esc_attr( $condtion_value ) . '">';
									$html .= wp_kses_post(
										sprintf(
											'<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
                                            </p>',
											esc_html__(
												'Note: ',
												'advanced-flat-rate-shipping-for-woocommerce'
											),
											esc_html__(
												'Please make sure that when you add rules in
                                                      Advanced Pricing > Cost per weight Section It contains in above entered weight,
                                                      otherwise it may be not apply proper shipping charges. For more detail please view
                                                      our documentation at ',
												'advanced-flat-rate-shipping-for-woocommerce'
											),
											esc_url( 'https://www.thedotstore.com/docs/plugin/advanced-flat-rate-shipping-method-for-woocommerce' ),
											esc_html__(
												'Click Here',
												'advanced-flat-rate-shipping-for-woocommerce'
											)
										)
									);
								} elseif ( 'coupon' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_coupon_list__premium_only( $i, $condtion_value );
								} elseif ( 'shipping_class' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_advance_flat_rate_class__premium_only( $i, $condtion_value );
								} elseif ( 'payment_method' === $fees_conditions ) {
									$html .= $afrsm_admin_object->afrsfwa_get_payment__premium_only( $i, $condtion_value );
									$html .= wp_kses_post(
										sprintf(
											'<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
                                            </p>',
											esc_html__(
												'Note: ',
												'advanced-flat-rate-shipping-for-woocommerce'
											),
											esc_html__(
												'This rule will work for Force All Shipping Method in master setting ',
												'advanced-flat-rate-shipping-for-woocommerce'
											),
											esc_url(
												add_query_arg(
													array(
														'page' => 'afrsm-start-page',
													),
													admin_url( 'admin.php' )
												)
											),
											esc_html__(
												'Click Here',
												'advanced-flat-rate-shipping-for-woocommerce'
											)
										)
									);
								}
								echo wp_kses( $html, $afrsm_object::afrsmw_allowed_html_tags() );
								?>
								<input type="hidden" name="condition_key[value_<?php echo esc_attr( $i ); ?>]" value="">
							</td>
							<td>
								<a id="fee-delete-field" rel-id="<?php echo esc_attr( $i ); ?>" class="delete-row" href="javascript:;" title="Delete">
									<i class="fa fa-trash"></i>
								</a>
							</td>
						</tr>
						<?php
						$i ++;
					}
					?>
					<?php
				} else {
					$i = 1;
					?>
					<tr id="row_1" valign="top">
						<th class="titledesc th_product_fees_conditions_condition" scope="row">
							<select rel-id="1" id="product_fees_conditions_condition_1" name="fees[product_fees_conditions_condition][]" id="product_fees_conditions_condition" class="product_fees_conditions_condition">
								<optgroup label="<?php esc_html_e( 'Location Specific', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
									<option value="country"><?php esc_html_e( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<option value="state"><?php esc_html_e( 'State', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<option value="postcode"><?php esc_html_e( 'Postcode', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<option value="zone"><?php esc_html_e( 'Zone', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
								</optgroup>
								<optgroup label="<?php esc_html_e( 'Product Specific', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
									<option value="product"><?php esc_html_e( 'Cart contains product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<option value="variableproduct"><?php esc_html_e( 'Cart contains variable product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<option value="category"><?php esc_html_e( 'Cart contains category\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<option value="tag"><?php esc_html_e( 'Cart contains tag\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<option value="sku"><?php esc_html_e( 'Cart contains SKU\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<option value="product_qty" ><?php esc_html_e( 'Cart contains product\'s quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
								</optgroup>
								<optgroup label="<?php esc_html_e( 'Attribute Specific', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
									<?php
									if ( $attribute_taxonomies ) {
										foreach ( $attribute_taxonomies as $attribute ) {
											$att_label = $attribute->attribute_label;
											$att_name  = wc_attribute_taxonomy_name( $attribute->attribute_name );
											echo '<option value="' . esc_attr( $att_name ) . '">' . esc_html( $att_label ) . '</option>';
										}
									} else {
										echo '<option value="" disabled="disabled">' . esc_html__( 'Not Found Attribute', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</option>';
									}
									?>
								</optgroup>
								<optgroup label="<?php esc_html_e( 'User Specific', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
									<option value="user"><?php esc_html_e( 'User', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<option value="user_role"><?php esc_html_e( 'User Role', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
								</optgroup>
								<optgroup label="<?php esc_html_e( 'Cart Specific', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
									<?php
									$get_woocommerce_currency_symbol = get_woocommerce_currency_symbol();
									$woocommerce_weight_unit         = get_option( 'woocommerce_weight_unit' );
									$currency_symbol                 = ! empty( $get_woocommerce_currency_symbol ) ? '( ' . $get_woocommerce_currency_symbol . ' )' : '';
									$weight_unit                     = ! empty( $woocommerce_weight_unit ) ? '( ' . $woocommerce_weight_unit . ' )' : '';
									?>
									<option value="cart_total"><?php esc_html_e( 'Cart Subtotal ( Before Discount ) ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?><?php echo esc_html( $currency_symbol ); ?></option>
									<option value="cart_totalafter"><?php esc_html_e( 'Cart Subtotal ( After Discount ) ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?><?php echo esc_html( $currency_symbol ); ?></option>
									<option value="quantity"><?php esc_html_e( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<option value="weight"><?php esc_html_e( 'Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?><?php echo wp_kses_post( $weight_unit ); ?></option>
									<option value="coupon"><?php esc_html_e( 'Coupon', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<option value="shipping_class"><?php esc_html_e( 'Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
								</optgroup>
								<optgroup label="<?php esc_html_e( 'Checkout Specific', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
									<option value="payment_method"><?php esc_html_e( 'Payment Method', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
								</optgroup>
							</select>
						<td class="select_condition_for_in_notin">
							<select name="fees[product_fees_conditions_is][]" class="product_fees_conditions_is product_fees_conditions_is_1">
								<option value="is_equal_to"><?php esc_html_e( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
								<option value="not_in"><?php esc_html_e( 'Not Equal to ( ! = )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
							</select>
						</td>
						<td id="column_1" class="condition-value">
							<?php
							echo wp_kses( $afrsm_admin_object->afrsfwa_get_country_list( 1 ), $afrsm_object::afrsmw_allowed_html_tags() );
							?>
							<input type="hidden" name="condition_key[value_1][]" value="">
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<input type="hidden" name="total_row" id="total_row" value="<?php echo esc_attr( $i ); ?>">
		</div>
	</div>
	<div id="apm_wrap" class="adv-pricing-rules">
		<div class="ap_title">
			<h2><?php esc_html_e( 'Advanced Shipping Price Rules', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
		</div>

		<div class="pricing_rules">
			<div class="pricing_rules_tab">
				<ul class="tabs">
					<?php
					$tab_array = array(
						'tab-1'  => esc_html__( 'Cost on Product', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'tab-2'  => esc_html__( 'Cost on Product Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'tab-3'  => esc_html__( 'Cost on Product Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'tab-4'  => esc_html__( 'Cost on Category', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'tab-5'  => esc_html__( 'Cost on Category Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'tab-6'  => esc_html__( 'Cost on Category Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'tab-7'  => esc_html__( 'Cost on Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'tab-8'  => esc_html__( 'Cost on Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'tab-9'  => esc_html__( 'Cost on Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'tab-10' => esc_html__( 'Cost on Shipping Class Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
					);
					if ( ! empty( $tab_array ) ) {
						foreach ( $tab_array as $data_tab => $tab_title ) {
							if ( 'tab-1' === $data_tab ) {
								$class = ' current';
							} else {
								$class = '';
							}
							?>
							<li class="tab-link<?php echo esc_attr( $class ); ?>" data-tab="<?php echo esc_attr( $data_tab ); ?>">
								<?php echo esc_html( $tab_title ); ?>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
			<div class="pricing_rules_tab_content">
				<div class="ap_product_container advance_pricing_rule_box tab-content current" id="tab-1" data-title="<?php esc_html_e( 'Cost on Product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
					<div class="tap-class">
						<div class="predefined_elements">
							<div id="all_product_list"></div>
						</div>
						<div class="sub-title">
							<div class="title-tip">
								<h2><?php esc_html_e( 'Cost on Product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
							</div>
							<div class="tap">
								<a id="ap-product-add-field" class="button button-primary button-large" href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
								<div class="switch_status_div">
									<label class="switch switch_in_pricing_rules">
										<input type="checkbox" name="cost_on_product_status" value="on" <?php echo esc_attr( $cost_on_product_status ); ?>>
										<div class="slider round"></div>
									</label>
								</div>
								<p class="switch_in_pricing_rules_description">
									<?php echo esc_html( AFRSM_PERTICULAR_FEE_AMOUNT_NOTICE ); ?>
								</p>
							</div>
							<div class="advance_rule_condition_match_type">
								<p class="switch_in_pricing_rules_description_left">
									<?php esc_html_e( 'below', 'woo-hide-shipping-methods' ); ?>
								</p>
								<select name="cost_rule_match[cost_on_product_rule_match]" id="cost_on_product_rule_match" class="arcmt_select">
									<option value="any" <?php selected( $cost_on_product_rule_match, 'any' ); ?>><?php esc_html_e( 'Any One', 'woo-hide-shipping-methods' ); ?></option>
									<option value="all" <?php selected( $cost_on_product_rule_match, 'all' ); ?>><?php esc_html_e( 'All', 'woo-hide-shipping-methods' ); ?></option>
								</select>
								<p class="switch_in_pricing_rules_description">
									<?php esc_html_e( 'rule match', 'woo-hide-shipping-methods' ); ?>
								</p>
							</div>
						</div>
						<table id="tbl_ap_product_method" class="tbl_product_fee table-outer tap-cas form-table advance-shipping-method-table">
							<tbody>
							<tr class="heading">
								<th class="titledesc th_product_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'Select a product to apply the fee amount to when the min/max quantity match.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
								</th>
								<th class="titledesc th_product_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Min Quantity *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a minimum product quantity per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_product_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Max Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a maximum product quantity per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
										<br/><?php esc_html_e( 'Leave empty then will set with maximum 999999999999999999999999999', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_product_fees_conditions_condition" scope="row" colspan="2"><?php esc_html_e( 'Fee amount *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'A fixed amount ( e.g. 5 / -5 ), percentage ( e.g. 5% / -5% ) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
							</tr>
							<?php
							$filled_arr = array();
							if ( ! empty( $sm_metabox_ap_product ) && is_array( $sm_metabox_ap_product ) ) :
								foreach ( $sm_metabox_ap_product as $app_arr ) {
									if ( ! empty( $app_arr ) || '' !== $app_arr ) {
										if ( ( '' !== $app_arr['ap_fees_products'] && '' !== $app_arr['ap_fees_ap_price_product'] ) && ( '' !== $app_arr['ap_fees_ap_prd_min_qty'] || '' !== $app_arr['ap_fees_ap_prd_max_qty'] ) ) {
											$filled_arr[] = $app_arr;
										}
									}
								}
							endif;
							if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
								$cnt_product = 2;
								foreach ( $filled_arr as $key => $productfees ) {
									$fees_ap_fees_products    = isset( $productfees['ap_fees_products'] ) ? $productfees['ap_fees_products'] : '';
									$ap_fees_ap_min_qty       = isset( $productfees['ap_fees_ap_prd_min_qty'] ) ? $productfees['ap_fees_ap_prd_min_qty'] : '';
									$ap_fees_ap_max_qty       = isset( $productfees['ap_fees_ap_prd_max_qty'] ) ? $productfees['ap_fees_ap_prd_max_qty'] : '';
									$ap_fees_ap_price_product = isset( $productfees['ap_fees_ap_price_product'] ) ? $productfees['ap_fees_ap_price_product'] : '';
									?>
									<tr id="ap_product_row_<?php echo esc_attr( $cnt_product ); ?>" valign="top" class="ap_product_row_tr">
										<td class="titledesc" scope="row">
											<select rel-id="<?php echo esc_attr( $cnt_product ); ?>" id="ap_product_fees_conditions_condition_<?php echo esc_attr( $cnt_product ); ?>" name="fees[ap_product_fees_conditions_condition][<?php echo esc_attr( $cnt_product ); ?>][]" id="ap_product_fees_conditions_condition" class="ap_product product_fees_conditions_values multiselect2 afrsm_select" multiple="multiple">
												<?php
												echo wp_kses( $afrsm_admin_object->afrsfwa_get_product_options( $cnt_product, $fees_ap_fees_products ), $afrsm_object::afrsmw_allowed_html_tags() );
												?>
											</select>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_product ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_prd_min_qty][]" class="text-class qty-class" id="ap_fees_ap_prd_min_qty[]" placeholder="<?php esc_html_e( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_min_qty ); ?>" min="1">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_product ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_prd_max_qty][]" class="text-class qty-class qty-class" id="ap_fees_ap_prd_max_qty[]" placeholder="<?php esc_html_e( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_max_qty ); ?>" min="1">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_product ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_price_product][]" class="text-class number-field" id="ap_fees_ap_price_product[]" placeholder="<?php esc_html_e( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_price_product ); ?>">
											<?php
											$first_char = substr( $ap_fees_ap_price_product, 0, 1 );
											if ( '-' === $first_char ) {
												$html = sprintf(
													'<p><b style="color: red;">%s</b>%s',
													esc_html__(
														'Note: ',
														'advanced-flat-rate-shipping-for-woocommerce'
													),
													esc_html__(
														'If entered fee amount is less than cart subtotal it will reflect with minus sign ( EX: $ -10.00 ) OR If entered fee amount is more than cart subtotal then the total amount shown as zero ( EX: Total: 0 ): ',
														'advanced-flat-rate-shipping-for-woocommerce'
													)
												);
												echo wp_kses_post( $html );
											}
											?>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_product ); ?> condition-value">
											<a id="ap-product-delete-field" rel-id="<?php echo esc_attr( $cnt_product ); ?>" title="Delete" class="delete-row" href="javascript:;">
												<i class="fa fa-trash"></i>
											</a>
										</td>
									</tr>
									<?php
									$cnt_product ++;
								}
								?>
								<?php
							} else {
								$cnt_product = 1;
							}
							?>
							</tbody>
						</table>
						<input type="hidden" name="total_row_product" id="total_row_product" value="<?php echo esc_attr( $cnt_product ); ?>">
					</div>
				</div>
				<div class="ap_product_subtotal_container advance_pricing_rule_box tab-content" id="tab-2" data-title="<?php esc_html_e( 'Cost on Product Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
					<div class="tap-class">
						<div class="predefined_elements">
							<div id="all_cart_subtotal">
								<option value="product_subtotal"><?php esc_html_e( 'Product Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
							</div>
						</div>
						<div class="sub-title">
							<div class="title-tip">
								<h2><?php esc_html_e( 'Cost on Product Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
							</div>
							<div class="tap">
								<a id="ap-product-subtotal-add-field" class="button button-primary button-large" href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
								<div class="switch_status_div">
									<label class="switch switch_in_pricing_rules">
										<input type="checkbox" name="cost_on_product_subtotal_status" value="on" <?php echo esc_attr( $cost_on_product_subtotal_status ); ?>>
										<div class="slider round"></div>
									</label>
									<p class="switch_in_pricing_rules_description">
										<?php echo esc_html( AFRSM_PERTICULAR_FEE_AMOUNT_NOTICE ); ?>
									</p>
								</div>
							</div>
							<div class="advance_rule_condition_match_type">
								<p class="switch_in_pricing_rules_description_left">
									<?php esc_html_e( 'below', 'woo-hide-shipping-methods' ); ?>
								</p>
								<select name="cost_rule_match[cost_on_product_subtotal_rule_match]" id="cost_on_product_subtotal_rule_match" class="arcmt_select">
									<option value="any" <?php selected( $cost_on_product_subtotal_rule_match, 'any' ); ?>><?php esc_html_e( 'Any One', 'woo-hide-shipping-methods' ); ?></option>
									<option value="all" <?php selected( $cost_on_product_subtotal_rule_match, 'all' ); ?>><?php esc_html_e( 'All', 'woo-hide-shipping-methods' ); ?></option>
								</select>
								<p class="switch_in_pricing_rules_description">
									<?php esc_html_e( 'rule match', 'woo-hide-shipping-methods' ); ?>
								</p>
							</div>
						</div>
						<table id="tbl_ap_product_subtotal_method" class="tbl_product_subtotal table-outer tap-cas form-table advance-shipping-method-table">
							<tbody>
							<tr class="heading">
								<th class="titledesc th_product_subtotal_fees_conditions_condition" scope="row"><?php esc_html_e( 'Product Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'Product Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
								</th>
								<th class="titledesc th_product_subtotal_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Min Subtotal ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e(
											'You can set a minimum total cart subtotal per row before the fee amount is
                                                  applied.',
											'advanced-flat-rate-shipping-for-woocommerce'
										);
										?>
									</p>
								</th>
								<th class="titledesc th_product_subtotal_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e(
											'You can set a maximum total cart subtotal per row before the fee amount is
                                                  applied.',
											'advanced-flat-rate-shipping-for-woocommerce'
										);
										?>
										<br/>
										<?php esc_html_e( 'Leave empty then will set with maximum 999999999999999999999999999', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_product_subtotal_fees_conditions_condition" scope="row" colspan="2"><?php esc_html_e( 'Fee Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e( 'A fixed amount ( e.g. 5 / -5 ) percentage ( e.g. 5% / -5% ) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' );
										?>
									</p>
								</th>
							</tr>
							<?php
							$filled_product_subtotal = array();
							if ( ! empty( $sm_metabox_ap_product_subtotal ) && is_array( $sm_metabox_ap_product_subtotal ) ) :
								foreach ( $sm_metabox_ap_product_subtotal as $apcat_arr ) :
									if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
										if (
											( '' !== $apcat_arr['ap_fees_product_subtotal'] && '' !== $apcat_arr['ap_fees_ap_price_product_subtotal'] ) &&
											( '' !== $apcat_arr['ap_fees_ap_product_subtotal_min_subtotal'] || '' !== $apcat_arr['ap_fees_ap_product_subtotal_max_subtotal'] )
										) {
											$filled_product_subtotal[] = $apcat_arr;
										}
									}
								endforeach;
							endif;
							if ( isset( $filled_product_subtotal ) && ! empty( $filled_product_subtotal ) ) {
								$cnt_product_subtotal = 2;
								foreach ( $filled_product_subtotal as $key => $productfees ) {
									$fees_ap_fees_product_subtotal            = isset( $productfees['ap_fees_product_subtotal'] ) ? $productfees['ap_fees_product_subtotal'] : '';
									$ap_fees_ap_product_subtotal_min_subtotal = isset( $productfees['ap_fees_ap_product_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_product_subtotal_min_subtotal'] : '';
									$ap_fees_ap_product_subtotal_max_subtotal = isset( $productfees['ap_fees_ap_product_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_product_subtotal_max_subtotal'] : '';
									$ap_fees_ap_price_product_subtotal        = isset( $productfees['ap_fees_ap_price_product_subtotal'] ) ? $productfees['ap_fees_ap_price_product_subtotal'] : '';
									?>
									<tr id="ap_product_subtotal_row_<?php echo esc_attr( $cnt_product_subtotal ); ?>" valign="top" class="ap_product_subtotal_row_tr">
										<td class="titledesc" scope="row">
											<select rel-id="<?php echo esc_attr( $cnt_product_subtotal ); ?>" id="ap_product_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_product_subtotal ); ?>" name="fees[ap_product_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_product_subtotal ); ?>][]" class="ap_product product_fees_conditions_values multiselect2 afrsm_select" multiple="multiple">
												<?php
												echo wp_kses( $afrsm_admin_object->afrsfwa_get_product_options( $cnt_product_subtotal, $fees_ap_fees_product_subtotal ), $afrsm_object::afrsmw_allowed_html_tags() );
												?>
											</select>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_product_subtotal ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_product_subtotal_min_subtotal][]" class="text-class price-class" id="ap_fees_ap_product_subtotal_min_subtotal[]" placeholder="<?php esc_html_e( 'Min Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" step="0.01" value="<?php echo esc_attr( $ap_fees_ap_product_subtotal_min_subtotal ); ?>" min="0.0">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_product_subtotal ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_product_subtotal_max_subtotal][]" class="text-class price-class" id="ap_fees_ap_product_subtotal_max_subtotal[]" placeholder="<?php esc_html_e( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" step="0.01" value="<?php echo esc_attr( $ap_fees_ap_product_subtotal_max_subtotal ); ?>" min="0.0">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_product_subtotal ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_price_product_subtotal][]" class="text-class number-field" id="ap_fees_ap_price_product_subtotal[]" placeholder="<?php esc_html_e( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_price_product_subtotal ); ?>">
											<?php
											$first_char = substr( $ap_fees_ap_price_product_subtotal, 0, 1 );
											if ( '-' === $first_char ) {
												$html = sprintf(
													'<p><b style="color: red;">%s</b>%s',
													esc_html__(
														'Note: ',
														'advanced-flat-rate-shipping-for-woocommerce'
													),
													esc_html__(
														'If entered fee amount is less than cart subtotal it will reflect with minus sign ( EX: $ -10.00 ) OR If entered fee amount is more than cart subtotal then the total amount shown as zero ( EX: Total: 0 ): ',
														'advanced-flat-rate-shipping-for-woocommerce'
													)
												);
												echo wp_kses_post( $html );
											}
											?>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_product_subtotal ); ?> condition-value">
											<a id="ap-product-subtotal-delete-field" rel-id="<?php echo esc_attr( $cnt_product_subtotal ); ?>" title="Delete" class="delete-row" href="javascript:;">
												<i class="fa fa-trash"></i>
											</a>
										</td>
									</tr>
									<?php
									$cnt_product_subtotal ++;
								}
								?>
								<?php
							} else {
								$cnt_product_subtotal = 1;
							}
							?>
							</tbody>
						</table>
						<input type="hidden" name="total_row_product_subtotal" id="total_row_product_subtotal" value="<?php echo esc_attr( $cnt_product_subtotal ); ?>">
					</div>
				</div>
				<div class="ap_product_weight_container advance_pricing_rule_box tab-content" id="tab-3" data-title="<?php esc_html_e( 'Cost on Product Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
					<div class="tap-class">
						<div class="predefined_elements">
							<div id="all_product_weight_list"></div>
						</div>
						<div class="sub-title">
							<div class="title-tip">
								<h2><?php esc_html_e( 'Cost on Product Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
							</div>
							<div class="tap">
								<a id="ap-product-weight-add-field" class="button button-primary button-large" href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
								<div class="switch_status_div">
									<label class="switch switch_in_pricing_rules">
										<input type="checkbox" name="cost_on_product_weight_status" value="on" <?php echo esc_attr( $cost_on_product_weight_status ); ?>>
										<div class="slider round"></div>
									</label>
								</div>
								<p class="switch_in_pricing_rules_description">
									<?php echo esc_html( AFRSM_PERTICULAR_FEE_AMOUNT_NOTICE ); ?>
								</p>
							</div>
							<div class="advance_rule_condition_match_type">
								<p class="switch_in_pricing_rules_description_left">
									<?php esc_html_e( 'below', 'woo-hide-shipping-methods' ); ?>
								</p>
								<select name="cost_rule_match[cost_on_product_weight_rule_match]" id="cost_on_product_weight_rule_match" class="arcmt_select">
									<option value="any" <?php selected( $cost_on_product_weight_rule_match, 'any' ); ?>><?php esc_html_e( 'Any One', 'woo-hide-shipping-methods' ); ?></option>
									<option value="all" <?php selected( $cost_on_product_weight_rule_match, 'all' ); ?>><?php esc_html_e( 'All', 'woo-hide-shipping-methods' ); ?></option>
								</select>
								<p class="switch_in_pricing_rules_description">
									<?php esc_html_e( 'rule match', 'woo-hide-shipping-methods' ); ?>
								</p>
							</div>
						</div>
						<table id="tbl_ap_product_weight_method" class="tbl_product_weight_fee table-outer tap-cas form-table advance-shipping-method-table">
							<tbody>
							<tr class="heading">
								<th class="titledesc th_product_weight_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'Select a product to apply the fee amount to when the min/max weight match.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_product_weight_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Min Weight *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a minimum product weight per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_product_weight_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Max Weight ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a maximum product weight per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
										<br/><?php esc_html_e( 'Leave empty then will set with maximum 999999999999999999999999999', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_product_weight_fees_conditions_condition" scope="row" colspan="2"><?php esc_html_e( 'Fee amount *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e( 'A fixed amount ( e.g. 5 / -5 ) percentage ( e.g. 5% / -5% ) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' );
										?>
									</p>
								</th>
							</tr>
							<?php
							$product_weight_filled_arr = array();
							if ( ! empty( $sm_metabox_ap_product_weight ) && is_array( $sm_metabox_ap_product_weight ) ) :
								foreach ( $sm_metabox_ap_product_weight as $app_arr ) :
									if ( ! empty( $app_arr ) || '' !== $app_arr ) {
										if ( ( '' !== $app_arr['ap_fees_product_weight'] && '' !== $app_arr['ap_fees_ap_price_product_weight'] ) && ( '' !== $app_arr['ap_fees_ap_product_weight_min_qty'] || '' !== $app_arr['ap_fees_ap_product_weight_max_qty'] ) ) {
											$product_weight_filled_arr[] = $app_arr;
										}
									}
								endforeach;
							endif;
							if ( isset( $product_weight_filled_arr ) && ! empty( $product_weight_filled_arr ) ) {
								$cnt_product_weight = 2;
								foreach ( $product_weight_filled_arr as $key => $product_weight_fees ) {
									$fees_ap_fees_product_weight     = isset( $product_weight_fees['ap_fees_product_weight'] ) ? $product_weight_fees['ap_fees_product_weight'] : '';
									$ap_fees_product_weight_min_qty  = isset( $product_weight_fees['ap_fees_ap_product_weight_min_qty'] ) ? $product_weight_fees['ap_fees_ap_product_weight_min_qty'] : '';
									$ap_fees_product_weight_max_qty  = isset( $product_weight_fees['ap_fees_ap_product_weight_max_qty'] ) ? $product_weight_fees['ap_fees_ap_product_weight_max_qty'] : '';
									$ap_fees_ap_price_product_weight = isset( $product_weight_fees['ap_fees_ap_price_product_weight'] ) ? $product_weight_fees['ap_fees_ap_price_product_weight'] : '';
									?>
									<tr id="ap_product_weight_row_<?php echo esc_attr( $cnt_product_weight ); ?>" valign="top" class="ap_product_weight_row_tr">
										<td class="titledesc" scope="row">
											<select rel-id="<?php echo esc_attr( $cnt_product_weight ); ?>" id="ap_product_weight_fees_conditions_condition_<?php echo esc_attr( $cnt_product_weight ); ?>" name="fees[ap_product_weight_fees_conditions_condition][<?php echo esc_attr( $cnt_product_weight ); ?>][]" id="ap_product_weight_fees_conditions_condition" class="ap_product_weight product_fees_conditions_values multiselect2 afrsm_select" multiple="multiple">
												<?php
												echo wp_kses( $afrsm_admin_object->afrsfwa_get_product_options( $cnt_product_weight, $fees_ap_fees_product_weight ), $afrsm_object::afrsmw_allowed_html_tags() );
												?>
											</select>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_product_weight ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_product_weight_min_weight][]" class="text-class weight-class" id="ap_fees_ap_product_weight_min_weight[]" placeholder="<?php esc_html_e( 'Min weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_product_weight_min_qty ); ?>">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_product_weight ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_product_weight_max_weight][]" class="text-class weight-class" id="ap_fees_ap_product_weight_max_weight[]" placeholder="<?php esc_html_e( 'Max weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_product_weight_max_qty ); ?>">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_product_weight ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_price_product_weight][]" class="text-class number-field" id="ap_fees_ap_price_product_weight[]" placeholder="<?php esc_html_e( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_price_product_weight ); ?>">
											<?php
											$first_char = substr( $ap_fees_ap_price_product_weight, 0, 1 );
											if ( '-' === $first_char ) {
												$html = sprintf(
													'<p><b style="color: red;">%s</b>%s',
													esc_html__(
														'Note: ',
														'advanced-flat-rate-shipping-for-woocommerce'
													),
													esc_html__(
														'If entered fee amount is less than cart subtotal it will reflect with minus sign ( EX: $ -10.00 ) OR If entered fee amount is more than cart subtotal then the total amount shown as zero ( EX: Total: 0 ): ',
														'advanced-flat-rate-shipping-for-woocommerce'
													)
												);
												echo wp_kses_post( $html );
											}
											?>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_product_weight ); ?> condition-value">
											<a id="ap-product-weight-delete-field" rel-id="<?php echo esc_attr( $cnt_product_weight ); ?>" title="Delete" class="delete-row" href="javascript:;">
												<i class="fa fa-trash"></i>
											</a>
										</td>
									</tr>
									<?php
									$cnt_product_weight ++;
								}
								?>
								<?php
							} else {
								$cnt_product_weight = 1;
							}
							?>
							</tbody>
						</table>
						<input type="hidden" name="total_row_product_weight" id="total_row_product_weight" value="<?php echo esc_attr( $cnt_product_weight ); ?>">
					</div>
				</div>
				<div class="ap_category_container advance_pricing_rule_box tab-content" id="tab-4" data-title="<?php esc_html_e( 'Cost on Category', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
					<div class="tap-class">
						<div class="predefined_elements">
							<div id="all_category_list">
								<?php
								echo wp_kses( $afrsm_admin_object->afrsfwa_get_category_options( '', $json = true ), $afrsm_object::afrsmw_allowed_html_tags() );
								?>
							</div>
						</div>
						<div class="sub-title">
							<div class="title-tip">
								<h2><?php esc_html_e( 'Cost on Category', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
							</div>
							<div class="tap">
								<a id="ap-category-add-field" class="button button-primary button-large" href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
								<div class="switch_status_div">
									<label class="switch switch_in_pricing_rules">
										<input type="checkbox" name="cost_on_category_status" value="on" <?php echo esc_attr( $cost_on_category_status ); ?>>
										<div class="slider round"></div>
									</label>
								</div>
								<p class="switch_in_pricing_rules_description">
									<?php echo esc_html( AFRSM_PERTICULAR_FEE_AMOUNT_NOTICE ); ?>
								</p>
							</div>
							<div class="advance_rule_condition_match_type">
								<p class="switch_in_pricing_rules_description_left">
									<?php esc_html_e( 'below', 'woo-hide-shipping-methods' ); ?>
								</p>
								<select name="cost_rule_match[cost_on_category_rule_match]" id="cost_on_category_rule_match" class="arcmt_select">
									<option value="any" <?php selected( $cost_on_category_rule_match, 'any' ); ?>><?php esc_html_e( 'Any One', 'woo-hide-shipping-methods' ); ?></option>
									<option value="all" <?php selected( $cost_on_category_rule_match, 'all' ); ?>><?php esc_html_e( 'All', 'woo-hide-shipping-methods' ); ?></option>
								</select>
								<p class="switch_in_pricing_rules_description">
									<?php esc_html_e( 'rule match', 'woo-hide-shipping-methods' ); ?>
								</p>
							</div>
						</div>
						<table id="tbl_ap_category_method" class="tbl_category_fee table-outer tap-cas form-table advance-shipping-method-table">
							<tbody>
							<tr class="heading">
								<th class="titledesc th_category_fees_conditions_condition" scope="row"><?php esc_html_e( 'Category', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'Select a category to apply the fee amount to when the min/max quantity match.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_category_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Min Quantity *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a minimum category quantity per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_category_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Max Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a maximum category quantity per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
										<br/><?php esc_html_e( 'Leave empty then will set with maximum 999999999999999999999999999', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_category_fees_conditions_condition" scope="row" colspan="2"><?php esc_html_e( 'Fee amount *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e( 'A fixed amount ( e.g. 5 / -5 ) percentage ( e.g. 5% / -5% ) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' );
										?>
									</p>
								</th>
							</tr>
							<?php
							$filled_arr = array();
							if ( ! empty( $sm_metabox_ap_category ) && is_array( $sm_metabox_ap_category ) ) :
								foreach ( $sm_metabox_ap_category as $apcat_arr ) :
									if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
										if (
											( '' !== $apcat_arr['ap_fees_categories'] && '' !== $apcat_arr['ap_fees_ap_price_category'] ) &&
											( '' !== $apcat_arr['ap_fees_ap_cat_min_qty'] || '' !== $apcat_arr['ap_fees_ap_cat_max_qty'] )
										) {
											$filled_arr[] = $apcat_arr;
										}
									}
								endforeach;
							endif;
							if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
								$cnt_category = 2;
								foreach ( $filled_arr as $key => $productfees ) {
									$fees_ap_fees_categories   = isset( $productfees['ap_fees_categories'] ) ? $productfees['ap_fees_categories'] : '';
									$ap_fees_ap_cat_min_qty    = isset( $productfees['ap_fees_ap_cat_min_qty'] ) ? $productfees['ap_fees_ap_cat_min_qty'] : '';
									$ap_fees_ap_cat_max_qty    = isset( $productfees['ap_fees_ap_cat_max_qty'] ) ? $productfees['ap_fees_ap_cat_max_qty'] : '';
									$ap_fees_ap_price_category = isset( $productfees['ap_fees_ap_price_category'] ) ? $productfees['ap_fees_ap_price_category'] : '';
									?>
									<tr id="ap_category_row_<?php echo esc_attr( $cnt_category ); ?>" valign="top" class="ap_category_row_tr">
										<td class="titledesc" scope="row">
											<select rel-id="<?php echo esc_attr( $cnt_category ); ?>" id="ap_category_fees_conditions_condition_<?php echo esc_attr( $cnt_category ); ?>" name="fees[ap_category_fees_conditions_condition][<?php echo esc_attr( $cnt_category ); ?>][]" id="ap_category_fees_conditions_condition" class="ap_category product_fees_conditions_values multiselect2 afrsm_select" multiple="multiple">
												<?php
												echo wp_kses( $afrsm_admin_object->afrsfwa_get_category_options( $fees_ap_fees_categories, $json = false ), $afrsm_object::afrsmw_allowed_html_tags() );
												?>
											</select>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_category ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_cat_min_qty][]" class="text-class qty-class" id="ap_fees_ap_cat_min_qty[]" placeholder="<?php esc_html_e( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_cat_min_qty ); ?>" min="1">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_category ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_cat_max_qty][]" class="text-class qty-class" id="ap_fees_ap_cat_max_qty[]" placeholder="<?php esc_html_e( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_cat_max_qty ); ?>" min="1">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_category ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_price_category][]" class="text-class number-field" id="ap_fees_ap_price_category[]" placeholder="<?php esc_html_e( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_price_category ); ?>">
											<?php
											$first_char = substr( $ap_fees_ap_price_category, 0, 1 );
											if ( '-' === $first_char ) {
												$html = sprintf(
													'<p><b style="color: red;">%s</b>%s',
													esc_html__(
														'Note: ',
														'advanced-flat-rate-shipping-for-woocommerce'
													),
													esc_html__(
														'If entered fee amount is less than cart subtotal it will reflect with minus sign ( EX: $ -10.00 ) OR If entered fee amount is more than cart subtotal then the total amount shown as zero ( EX: Total: 0 ): ',
														'advanced-flat-rate-shipping-for-woocommerce'
													)
												);
												echo wp_kses_post( $html );
											}
											?>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_category ); ?> condition-value">
											<a id="ap-category-delete-field" rel-id="<?php echo esc_attr( $cnt_category ); ?>" title="Delete" class="delete-row" href="javascript:;">
												<i class="fa fa-trash"></i>
											</a>
										</td>
									</tr>
									<?php
									$cnt_category ++;
								}
								?>
								<?php
							} else {
								$cnt_category = 1;
							}
							?>
							</tbody>
						</table>
						<input type="hidden" name="total_row_category" id="total_row_category" value="<?php echo esc_attr( $cnt_category ); ?>">
					</div>
				</div>
				<div class="ap_category_subtotal_container advance_pricing_rule_box tab-content" id="tab-5" data-title="<?php esc_html_e( 'Cost on Category Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
					<div class="tap-class">
						<div class="predefined_elements">
							<div id="all_cart_subtotal">
								<option value="category_subtotal"><?php esc_html_e( 'Category Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
							</div>
						</div>
						<div class="sub-title">
							<div class="title-tip">
								<h2><?php esc_html_e( 'Cost on Category Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
							</div>
							<div class="tap">
								<a id="ap-category-subtotal-add-field" class="button button-primary button-large" href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
								<div class="switch_status_div">
									<label class="switch switch_in_pricing_rules">
										<input type="checkbox" name="cost_on_category_subtotal_status" value="on" <?php echo esc_attr( $cost_on_category_subtotal_status ); ?>>
										<div class="slider round"></div>
									</label>
									<p class="switch_in_pricing_rules_description">
										<?php echo esc_html( AFRSM_PERTICULAR_FEE_AMOUNT_NOTICE ); ?>
									</p>
								</div>
							</div>
							<div class="advance_rule_condition_match_type">
								<p class="switch_in_pricing_rules_description_left">
									<?php esc_html_e( 'below', 'woo-hide-shipping-methods' ); ?>
								</p>
								<select name="cost_rule_match[cost_on_category_subtotal_rule_match]" id="cost_on_category_subtotal_rule_match" class="arcmt_select">
									<option value="any" <?php selected( $cost_on_category_subtotal_rule_match, 'any' ); ?>><?php esc_html_e( 'Any One', 'woo-hide-shipping-methods' ); ?></option>
									<option value="all" <?php selected( $cost_on_category_subtotal_rule_match, 'all' ); ?>><?php esc_html_e( 'All', 'woo-hide-shipping-methods' ); ?></option>
								</select>
								<p class="switch_in_pricing_rules_description">
									<?php esc_html_e( 'rule match', 'woo-hide-shipping-methods' ); ?>
								</p>
							</div>
						</div>
						<table id="tbl_ap_category_subtotal_method" class="tbl_category_subtotal table-outer tap-cas form-table advance-shipping-method-table">
							<tbody>
							<tr class="heading">
								<th class="titledesc th_category_subtotal_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Category Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'Category Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_category_subtotal_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Min Subtotal ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e(
											'You can set a minimum total cart subtotal per row before the fee amount is
                                                  applied.',
											'advanced-flat-rate-shipping-for-woocommerce'
										);
										?>
									</p>
								</th>
								<th class="titledesc th_category_subtotal_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e(
											'You can set a maximum total cart subtotal per row before the fee amount is
                                                  applied.',
											'advanced-flat-rate-shipping-for-woocommerce'
										);
										?>
										<br/>
										<?php esc_html_e( 'Leave empty then will set with maximum 999999999999999999999999999', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_category_subtotal_fees_conditions_condition" scope="row" colspan="2">
									<?php esc_html_e( 'Fee Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e( 'A fixed amount ( e.g. 5 / -5 ) percentage ( e.g. 5% / -5% ) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' );
										?>
								</th>
							</tr>
							<?php
							$filled_category_subtotal = array();
							if ( ! empty( $sm_metabox_ap_category_subtotal ) && is_array( $sm_metabox_ap_category_subtotal ) ) :
								foreach ( $sm_metabox_ap_category_subtotal as $apcat_arr ) :
									if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
										if (
											( '' !== $apcat_arr['ap_fees_category_subtotal'] && '' !== $apcat_arr['ap_fees_ap_price_category_subtotal'] ) &&
											( '' !== $apcat_arr['ap_fees_ap_category_subtotal_min_subtotal'] || '' !== $apcat_arr['ap_fees_ap_category_subtotal_max_subtotal'] )
										) {
											$filled_category_subtotal[] = $apcat_arr;
										}
									}
								endforeach;
							endif;
							if ( isset( $filled_category_subtotal ) && ! empty( $filled_category_subtotal ) ) {
								$cnt_category_subtotal = 2;
								foreach ( $filled_category_subtotal as $key => $productfees ) {
									$fees_ap_fees_category_subtotal            = isset( $productfees['ap_fees_category_subtotal'] ) ? $productfees['ap_fees_category_subtotal'] : '';
									$ap_fees_ap_category_subtotal_min_subtotal = isset( $productfees['ap_fees_ap_category_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_category_subtotal_min_subtotal'] : '';
									$ap_fees_ap_category_subtotal_max_subtotal = isset( $productfees['ap_fees_ap_category_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_category_subtotal_max_subtotal'] : '';
									$ap_fees_ap_price_category_subtotal        = isset( $productfees['ap_fees_ap_price_category_subtotal'] ) ? $productfees['ap_fees_ap_price_category_subtotal'] : '';
									?>
									<tr id="ap_category_subtotal_row_<?php echo esc_attr( $cnt_category_subtotal ); ?>" valign="top" class="ap_category_subtotal_row_tr">
										<td class="titledesc" scope="row">
											<select rel-id="<?php echo esc_attr( $cnt_category_subtotal ); ?>" id="ap_category_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_category_subtotal ); ?>" name="fees[ap_category_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_category_subtotal ); ?>][]" id="ap_category_subtotal_fees_conditions_condition" class="ap_category_subtotal product_fees_conditions_values multiselect2 afrsm_select" multiple="multiple">
												<?php
												echo wp_kses( $afrsm_admin_object->afrsfwa_get_category_options( $fees_ap_fees_category_subtotal, $json = false ), $afrsm_object::afrsmw_allowed_html_tags() );
												?>
											</select>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_category_subtotal ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_category_subtotal_min_subtotal][]" class="text-class price-class" id="ap_fees_ap_category_subtotal_min_subtotal[]" placeholder="<?php esc_html_e( 'Min Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" step="0.01" value="<?php echo esc_attr( $ap_fees_ap_category_subtotal_min_subtotal ); ?>" min="0.0">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_category_subtotal ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_category_subtotal_max_subtotal][]" class="text-class price-class" id="ap_fees_ap_category_subtotal_max_subtotal[]" placeholder="<?php esc_html_e( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" step="0.01" value="<?php echo esc_attr( $ap_fees_ap_category_subtotal_max_subtotal ); ?>" min="0.0">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_category_subtotal ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_price_category_subtotal][]" class="text-class number-field" id="ap_fees_ap_price_category_subtotal[]" placeholder="<?php esc_html_e( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_price_category_subtotal ); ?>">
											<?php
											$first_char = substr( $ap_fees_ap_price_category_subtotal, 0, 1 );
											if ( '-' === $first_char ) {
												$html = sprintf(
													'<p><b style="color: red;">%s</b>%s',
													esc_html__(
														'Note: ',
														'advanced-flat-rate-shipping-for-woocommerce'
													),
													esc_html__(
														'If entered fee amount is less than cart subtotal it will reflect with minus sign ( EX: $ -10.00 ) OR If entered fee amount is more than cart subtotal then the total amount shown as zero ( EX: Total: 0 ): ',
														'advanced-flat-rate-shipping-for-woocommerce'
													)
												);
												echo wp_kses_post( $html );
											}
											?>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_category_subtotal ); ?> condition-value">
											<a id="ap-category-subtotal-delete-field" rel-id="<?php echo esc_attr( $cnt_category_subtotal ); ?>" title="Delete" class="delete-row" href="javascript:;">
												<i class="fa fa-trash"></i>
											</a>
										</td>
									</tr>
									<?php
									$cnt_category_subtotal ++;
								}
								?>
								<?php
							} else {
								$cnt_category_subtotal = 1;
							}
							?>
							</tbody>
						</table>
						<input type="hidden" name="total_row_category_subtotal" id="total_row_category_subtotal" value="<?php echo esc_attr( $cnt_category_subtotal ); ?>">
					</div>
				</div>
				<div class="ap_category_weight_container advance_pricing_rule_box tab-content" id="tab-6" data-title="<?php esc_html_e( 'Cost on Category Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
					<div class="tap-class">
						<div class="predefined_elements">
							<div id="all_category_weight_list">
								<option value=""><?php esc_html_e( 'Select Category', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
								<?php
								echo wp_kses( $afrsm_admin_object->afrsfwa_get_category_options( '', $json = true ), $afrsm_object::afrsmw_allowed_html_tags() );
								?>
							</div>
						</div>
						<div class="sub-title">
							<div class="title-tip">
								<h2><?php esc_html_e( 'Cost on Category Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
							</div>
							<div class="tap">
								<a id="ap-category-weight-add-field" class="button button-primary button-large" href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
								<div class="switch_status_div">
									<label class="switch switch_in_pricing_rules">
										<input type="checkbox" name="cost_on_category_weight_status" value="on" <?php echo esc_attr( $cost_on_category_weight_status ); ?>>
										<div class="slider round"></div>
									</label>
								</div>
								<p class="switch_in_pricing_rules_description">
									<?php echo esc_html( AFRSM_PERTICULAR_FEE_AMOUNT_NOTICE ); ?>
								</p>
							</div>
							<div class="advance_rule_condition_match_type">
								<p class="switch_in_pricing_rules_description_left">
									<?php esc_html_e( 'below', 'woo-hide-shipping-methods' ); ?>
								</p>
								<select name="cost_rule_match[cost_on_category_weight_rule_match]" id="cost_on_category_weight_rule_match" class="arcmt_select">
									<option value="any" <?php selected( $cost_on_category_weight_rule_match, 'any' ); ?>><?php esc_html_e( 'Any One', 'woo-hide-shipping-methods' ); ?></option>
									<option value="all" <?php selected( $cost_on_category_weight_rule_match, 'all' ); ?>><?php esc_html_e( 'All', 'woo-hide-shipping-methods' ); ?></option>
								</select>
								<p class="switch_in_pricing_rules_description">
									<?php esc_html_e( 'rule match', 'woo-hide-shipping-methods' ); ?>
								</p>
							</div>
						</div>
						<table id="tbl_ap_category_weight_method" class="tbl_category_weight_fee table-outer tap-cas form-table advance-shipping-method-table">
							<tbody>
							<tr class="heading">
								<th class="titledesc th_category_weight_fees_conditions_condition" scope="row"><?php esc_html_e( 'Category', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'Select a category to apply the fee amount to when the min/max weight match.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_category_weight_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Min Weight *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a minimum category weight per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_category_weight_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Max Weight ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a maximum category weight per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
										<br/><?php esc_html_e( 'Leave empty then will set with maximum 999999999999999999999999999', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_category_weight_fees_conditions_condition" scope="row" colspan="2"><?php esc_html_e( 'Fee amount *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e( 'A fixed amount ( e.g. 5 / -5 ) percentage ( e.g. 5% / -5% ) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' );
										?>
									</p>
								</th>
							</tr>
							<?php
							$filled_arr_cat_weight = array();
							if ( ! empty( $sm_metabox_ap_category_weight ) && is_array( $sm_metabox_ap_category_weight ) ) :
								foreach ( $sm_metabox_ap_category_weight as $apcat_arr ) :
									if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
										if (
											( '' !== $apcat_arr['ap_fees_categories_weight'] && '' !== $apcat_arr['ap_fees_categories_weight'] ) &&
											( '' !== $apcat_arr['ap_fees_ap_category_weight_min_qty'] || '' !== $apcat_arr['ap_fees_ap_category_weight_max_qty'] )
										) {
											$filled_arr_cat_weight[] = $apcat_arr;
										}
									}
								endforeach;
							endif;
							if ( isset( $filled_arr_cat_weight ) && ! empty( $filled_arr_cat_weight ) ) {
								$cnt_category_weight = 2;
								foreach ( $filled_arr_cat_weight as $key => $productfees ) {
									$fees_ap_fees_categories_weight     = isset( $productfees['ap_fees_categories_weight'] ) ? $productfees['ap_fees_categories_weight'] : '';
									$ap_fees_ap_category_weight_min_qty = isset( $productfees['ap_fees_ap_category_weight_min_qty'] ) ? $productfees['ap_fees_ap_category_weight_min_qty'] : '';
									$ap_fees_ap_category_weight_max_qty = isset( $productfees['ap_fees_ap_category_weight_max_qty'] ) ? $productfees['ap_fees_ap_category_weight_max_qty'] : '';
									$ap_fees_ap_price_category_weight   = isset( $productfees['ap_fees_ap_price_category_weight'] ) ? $productfees['ap_fees_ap_price_category_weight'] : '';
									?>
									<tr id="ap_category_weight_row_<?php echo esc_attr( $cnt_category_weight ); ?>" valign="top" class="ap_category_weight_row_tr">
										<td class="titledesc" scope="row">
											<select rel-id="<?php echo esc_attr( $cnt_category_weight ); ?>" id="ap_category_weight_fees_conditions_condition_<?php echo esc_attr( $cnt_category_weight ); ?>" name="fees[ap_category_weight_fees_conditions_condition][<?php echo esc_attr( $cnt_category_weight ); ?>][]" class="ap_category_weight product_fees_conditions_values multiselect2 afrsm_select" multiple="multiple">
												<?php
												echo wp_kses( $afrsm_admin_object->afrsfwa_get_category_options( $fees_ap_fees_categories_weight, $json = false ), $afrsm_object::afrsmw_allowed_html_tags() );
												?>
											</select>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_category_weight ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_category_weight_min_weight][]" class="text-class weight-class" id="ap_fees_ap_category_weight_min_weight[]" placeholder="<?php esc_html_e( 'Min weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_category_weight_min_qty ); ?>">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_category_weight ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_category_weight_max_weight][]" class="text-class weight-class" id="ap_fees_ap_category_weight_max_weight[]" placeholder="<?php esc_html_e( 'Max weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_category_weight_max_qty ); ?>">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_category_weight ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_price_category_weight][]" class="text-class number-field" id="ap_fees_ap_price_category_weight[]" placeholder="<?php esc_html_e( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_price_category_weight ); ?>">
											<?php
											$first_char = substr( $ap_fees_ap_price_category_weight, 0, 1 );
											if ( '-' === $first_char ) {
												$html = sprintf(
													'<p><b style="color: red;">%s</b>%s',
													esc_html__(
														'Note: ',
														'advanced-flat-rate-shipping-for-woocommerce'
													),
													esc_html__(
														'If entered fee amount is less than cart subtotal it will reflect with minus sign ( EX: $ -10.00 ) OR If entered fee amount is more than cart subtotal then the total amount shown as zero ( EX: Total: 0 ): ',
														'advanced-flat-rate-shipping-for-woocommerce'
													)
												);
												echo wp_kses_post( $html );
											}
											?>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_category_weight ); ?> condition-value">
											<a id="ap-category-weight-delete-field" rel-id="<?php echo esc_attr( $cnt_category_weight ); ?>" title="Delete" class="delete-row" href="javascript:;">
												<i class="fa fa-trash"></i>
											</a>
										</td>
									</tr>
									<?php
									$cnt_category_weight ++;
								}
								?>
								<?php
							} else {
								$cnt_category_weight = 1;
							}
							?>
							</tbody>
						</table>
						<input type="hidden" name="total_row_category_weight" id="total_row_category_weight" value="<?php echo esc_attr( $cnt_category_weight ); ?>">
					</div>
				</div>
				<div class="ap_total_cart_container advance_pricing_rule_box tab-content" id="tab-7" data-title="<?php esc_html_e( 'Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
					<div class="tap-class">
						<div class="predefined_elements">
							<div id="all_cart_qty">
								<option value="total_cart_qty"><?php esc_html_e( 'Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
							</div>
						</div>
						<div class="sub-title">
							<div class="title-tip">
								<h2><?php esc_html_e( 'Cost on Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
							</div>
							<div class="tap">
								<a id="ap-total-cart-qty-add-field" class="button button-primary button-large" href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
								<div class="switch_status_div">
									<label class="switch switch_in_pricing_rules">
										<input type="checkbox" name="cost_on_total_cart_qty_status" value="on" <?php echo esc_attr( $cost_on_total_cart_qty_status ); ?>>
										<div class="slider round"></div>
									</label>
								</div>
								<p class="switch_in_pricing_rules_description">
									<?php echo esc_html( AFRSM_PERTICULAR_FEE_AMOUNT_NOTICE ); ?>
								</p>
							</div>
							<div class="advance_rule_condition_match_type">
								<p class="switch_in_pricing_rules_description_left">
									<?php esc_html_e( 'below', 'woo-hide-shipping-methods' ); ?>
								</p>
								<select name="cost_rule_match[cost_on_total_cart_qty_rule_match]" id="cost_on_total_cart_qty_rule_match" class="arcmt_select">
									<option value="any" <?php selected( $cost_on_total_cart_qty_rule_match, 'any' ); ?>><?php esc_html_e( 'Any One', 'woo-hide-shipping-methods' ); ?></option>
									<option value="all" <?php selected( $cost_on_total_cart_qty_rule_match, 'all' ); ?>><?php esc_html_e( 'All', 'woo-hide-shipping-methods' ); ?></option>
								</select>
								<p class="switch_in_pricing_rules_description">
									<?php esc_html_e( 'rule match', 'woo-hide-shipping-methods' ); ?>
								</p>
							</div>
						</div>
						<table id="tbl_ap_total_cart_qty_method" class="tbl_total_cart_qty table-outer tap-cas form-table advance-shipping-method-table">
							<tbody>
							<tr class="heading">
								<th class="titledesc th_total_cart_qty_fees_conditions_condition" scope="row"><?php esc_html_e( 'Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
								</th>
								<th class="titledesc th_total_cart_qty_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Min Quantity * ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a minimum total cart quantity per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p></th>
								<th class="titledesc th_total_cart_qty_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Max Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a maximum total cart quantity per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
										<br/>
										<?php esc_html_e( 'Leave empty then will set with maximum 999999999999999999999999999', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_total_cart_qty_fees_conditions_condition" scope="row" colspan="2"><?php esc_html_e( 'Fee amount *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e( 'A fixed amount ( e.g. 5 / -5 ) percentage ( e.g. 5% / -5% ) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' );
										?>
									</p>
								</th>
							</tr>
							<?php
							$filled_total_cart_qty = array();
							if ( ! empty( $sm_metabox_ap_total_cart_qty ) && is_array( $sm_metabox_ap_total_cart_qty ) ) :
								foreach ( $sm_metabox_ap_total_cart_qty as $apcat_arr ) :
									if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
										if (
											( '' !== $apcat_arr['ap_fees_total_cart_qty'] && '' !== $apcat_arr['ap_fees_ap_price_total_cart_qty'] ) &&
											( '' !== $apcat_arr['ap_fees_ap_total_cart_qty_min_qty'] || '' !== $apcat_arr['ap_fees_ap_total_cart_qty_max_qty'] )
										) {
											$filled_total_cart_qty[] = $apcat_arr;
										}
									}
								endforeach;
							endif;
							if ( isset( $filled_total_cart_qty ) && ! empty( $filled_total_cart_qty ) ) {
								$cnt_total_cart_qty = 2;
								foreach ( $filled_total_cart_qty as $key => $productfees ) {
									$fees_ap_fees_total_cart_qty       = isset( $productfees['ap_fees_total_cart_qty'] ) ? $productfees['ap_fees_total_cart_qty'] : '';
									$ap_fees_ap_total_cart_qty_min_qty = isset( $productfees['ap_fees_ap_total_cart_qty_min_qty'] ) ? $productfees['ap_fees_ap_total_cart_qty_min_qty'] : '';
									$ap_fees_ap_total_cart_qty_max_qty = isset( $productfees['ap_fees_ap_total_cart_qty_max_qty'] ) ? $productfees['ap_fees_ap_total_cart_qty_max_qty'] : '';
									$ap_fees_ap_price_total_cart_qty   = isset( $productfees['ap_fees_ap_price_total_cart_qty'] ) ? $productfees['ap_fees_ap_price_total_cart_qty'] : '';
									?>
									<tr id="ap_total_cart_qty_row_<?php echo esc_attr( $cnt_total_cart_qty ); ?>" valign="top" class="ap_total_cart_qty_row_tr">
										<td class="titledesc" scope="row">
											<label><?php echo esc_html_e( 'Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
											<input type="hidden" name="fees[ap_total_cart_qty_fees_conditions_condition][<?php echo esc_attr( $cnt_total_cart_qty ); ?>][]" id="ap_total_cart_qty_fees_conditions_condition_<?php echo esc_attr( $cnt_total_cart_qty ); ?>">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_total_cart_qty ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_total_cart_qty_min_qty][]" class="text-class qty-class" id="ap_fees_ap_total_cart_qty_min_qty[]" placeholder="<?php esc_html_e( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_total_cart_qty_min_qty ); ?>" min="1">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_total_cart_qty ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_total_cart_qty_max_qty][]" class="text-class qty-class" id="ap_fees_ap_total_cart_qty_max_qty[]" placeholder="<?php esc_html_e( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_total_cart_qty_max_qty ); ?>" min="1">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_total_cart_qty ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_price_total_cart_qty][]" class="text-class number-field" id="ap_fees_ap_price_total_cart_qty[]" placeholder="<?php esc_html_e( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_price_total_cart_qty ); ?>">
											<?php
											$first_char = substr( $ap_fees_ap_price_total_cart_qty, 0, 1 );
											if ( '-' === $first_char ) {
												$html = sprintf(
													'<p><b style="color: red;">%s</b>%s',
													esc_html__(
														'Note: ',
														'advanced-flat-rate-shipping-for-woocommerce'
													),
													esc_html__(
														'If entered fee amount is less than cart subtotal it will reflect with minus sign ( EX: $ -10.00 ) OR If entered fee amount is more than cart subtotal then the total amount shown as zero ( EX: Total: 0 ): ',
														'advanced-flat-rate-shipping-for-woocommerce'
													)
												);
												echo wp_kses_post( $html );
											}
											?>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_total_cart_qty ); ?> condition-value">
											<a id="ap-total-cart-qty-delete-field" rel-id="<?php echo esc_attr( $cnt_total_cart_qty ); ?>" title="Delete" class="delete-row" href="javascript:;">
												<i class="fa fa-trash"></i>
											</a>
										</td>
									</tr>
									<?php
									$cnt_total_cart_qty ++;
								}
								?>
								<?php
							} else {
								$cnt_total_cart_qty = 1;
							}
							?>
							</tbody>
						</table>
						<input type="hidden" name="total_row_total_cart_qty" id="total_row_total_cart_qty" value="<?php echo esc_attr( $cnt_total_cart_qty ); ?>">
					</div>
				</div>
				<div class="ap_total_cart_weight_container advance_pricing_rule_box tab-content" id="tab-8" data-title="<?php esc_html_e( 'Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
					<div class="tap-class">
						<div class="predefined_elements">
							<div id="all_cart_weight">
								<option value="total_cart_weight"><?php esc_html_e( 'Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
							</div>
						</div>
						<div class="sub-title">
							<div class="title-tip">
								<h2><?php esc_html_e( 'Cost on Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
							</div>
							<div class="tap">
								<a id="ap-total-cart-weight-add-field" class="button button-primary button-large" href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
								<div class="switch_status_div">
									<label class="switch switch_in_pricing_rules">
										<input type="checkbox" name="cost_on_total_cart_weight_status" value="on" <?php echo esc_attr( $cost_on_total_cart_weight_status ); ?>>
										<div class="slider round"></div>
									</label>
								</div>
								<p class="switch_in_pricing_rules_description">
									<?php echo esc_html( AFRSM_PERTICULAR_FEE_AMOUNT_NOTICE ); ?>
								</p>
							</div>
							<div class="advance_rule_condition_match_type">
								<p class="switch_in_pricing_rules_description_left">
									<?php esc_html_e( 'below', 'woo-hide-shipping-methods' ); ?>
								</p>
								<select name="cost_rule_match[cost_on_total_cart_weight_rule_match]" id="cost_on_total_cart_weight_rule_match" class="arcmt_select">
									<option value="any" <?php selected( $cost_on_total_cart_weight_rule_match, 'any' ); ?>><?php esc_html_e( 'Any One', 'woo-hide-shipping-methods' ); ?></option>
									<option value="all" <?php selected( $cost_on_total_cart_weight_rule_match, 'all' ); ?>><?php esc_html_e( 'All', 'woo-hide-shipping-methods' ); ?></option>
								</select>
								<p class="switch_in_pricing_rules_description">
									<?php esc_html_e( 'rule match', 'woo-hide-shipping-methods' ); ?>
								</p>
							</div>
						</div>
						<table id="tbl_ap_total_cart_weight_method" class="tbl_total_cart_weight table-outer tap-cas form-table advance-shipping-method-table">
							<tbody>
							<tr class="heading">
								<th class="titledesc th_total_cart_weight_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_total_cart_weight_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Min Weight *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a minimum total cart weight per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_total_cart_weight_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Max Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a maximum total cart weight per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
										<br/><?php esc_html_e( 'Leave empty then will set with maximum 999999999999999999999999999', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_total_cart_weight_fees_conditions_condition" scope="row" colspan="2"><?php esc_html_e( 'Fee amount *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e( 'A fixed amount ( e.g. 5 / -5 ) percentage ( e.g. 5% / -5% ) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' );
										?>
									</p>
								</th>
							</tr>
							<?php
							$filled_total_cart_weight = array();
							if ( ! empty( $sm_metabox_ap_total_cart_weight ) && is_array( $sm_metabox_ap_total_cart_weight ) ) :
								foreach ( $sm_metabox_ap_total_cart_weight as $apcat_arr ) :
									if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
										if (
											( '' !== $apcat_arr['ap_fees_total_cart_weight'] && '' !== $apcat_arr['ap_fees_ap_price_total_cart_weight'] ) &&
											( '' !== $apcat_arr['ap_fees_ap_total_cart_weight_min_weight'] || '' !== $apcat_arr['ap_fees_ap_total_cart_weight_max_weight'] )
										) {
											$filled_total_cart_weight[] = $apcat_arr;
										}
									}
								endforeach;
							endif;
							if ( isset( $filled_total_cart_weight ) && ! empty( $filled_total_cart_weight ) ) {
								$cnt_total_cart_weight = 2;
								foreach ( $filled_total_cart_weight as $key => $productfees ) {
									$fees_ap_fees_total_cart_weight          = isset( $productfees['ap_fees_total_cart_weight'] ) ? $productfees['ap_fees_total_cart_weight'] : '';
									$ap_fees_ap_total_cart_weight_min_weight = isset( $productfees['ap_fees_ap_total_cart_weight_min_weight'] ) ? $productfees['ap_fees_ap_total_cart_weight_min_weight'] : '';
									$ap_fees_ap_total_cart_weight_max_weight = isset( $productfees['ap_fees_ap_total_cart_weight_max_weight'] ) ? $productfees['ap_fees_ap_total_cart_weight_max_weight'] : '';
									$ap_fees_ap_price_total_cart_weight      = isset( $productfees['ap_fees_ap_price_total_cart_weight'] ) ? $productfees['ap_fees_ap_price_total_cart_weight'] : '';
									?>
									<tr id="ap_total_cart_weight_row_<?php echo esc_attr( $cnt_total_cart_weight ); ?>" valign="top" class="ap_total_cart_weight_row_tr">
										<td class="titledesc" scope="row">
											<label><?php echo esc_html_e( 'Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
											<input type="hidden" name="fees[ap_total_cart_weight_fees_conditions_condition][<?php echo esc_attr( $cnt_total_cart_weight ); ?>][]" id="ap_total_cart_weight_fees_conditions_condition_<?php echo esc_attr( $cnt_total_cart_weight ); ?>">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_total_cart_weight ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_total_cart_weight_min_weight][]" class="text-class weight-class" id="ap_fees_ap_total_cart_weight_min_weight[]" placeholder="<?php esc_html_e( 'Min weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_total_cart_weight_min_weight ); ?>">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_total_cart_weight ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_total_cart_weight_max_weight][]" class="text-class weight-class" id="ap_fees_ap_total_cart_weight_max_weight[]" placeholder="<?php esc_html_e( 'Max weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_total_cart_weight_max_weight ); ?>">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_total_cart_weight ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_price_total_cart_weight][]" class="text-class number-field" id="ap_fees_ap_price_total_cart_weight[]" placeholder="<?php esc_html_e( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_price_total_cart_weight ); ?>">
											<?php
											$first_char = substr( $ap_fees_ap_price_total_cart_weight, 0, 1 );
											if ( '-' === $first_char ) {
												$html = sprintf(
													'<p><b style="color: red;">%s</b>%s',
													esc_html__(
														'Note: ',
														'advanced-flat-rate-shipping-for-woocommerce'
													),
													esc_html__(
														'If entered fee amount is less than cart subtotal it will reflect with minus sign ( EX: $ -10.00 ) OR If entered fee amount is more than cart subtotal then the total amount shown as zero ( EX: Total: 0 ): ',
														'advanced-flat-rate-shipping-for-woocommerce'
													)
												);
												echo wp_kses_post( $html );
											}
											?>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_total_cart_weight ); ?> condition-value">
											<a id="ap-total-cart-weight-delete-field" rel-id="<?php echo esc_attr( $cnt_total_cart_weight ); ?>" title="Delete" class="delete-row" href="javascript:;">
												<i class="fa fa-trash"></i>
											</a>
										</td>
									</tr>
									<?php
									$cnt_total_cart_weight ++;
								}
								?>
								<?php
							} else {
								$cnt_total_cart_weight = 1;
							}
							?>
							</tbody>
						</table>
						<input type="hidden" name="total_row_total_cart_weight" id="total_row_total_cart_weight" value="<?php echo esc_attr( $cnt_total_cart_weight ); ?>">
					</div>
				</div>
				<div class="ap_total_cart_subtotal_container advance_pricing_rule_box tab-content" id="tab-9" data-title="<?php esc_html_e( 'Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
					<div class="tap-class">
						<div class="predefined_elements">
							<div id="all_cart_subtotal">
								<option value="total_cart_subtotal"><?php esc_html_e( 'Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
							</div>
						</div>
						<div class="sub-title">
							<div class="title-tip">
								<h2><?php esc_html_e( 'Cost on Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
							</div>
							<div class="tap">
								<a id="ap-total-cart-subtotal-add-field" class="button button-primary button-large" href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
								<div class="switch_status_div">
									<label class="switch switch_in_pricing_rules">
										<input type="checkbox" name="cost_on_total_cart_subtotal_status" value="on" <?php echo esc_attr( $cost_on_total_cart_subtotal_status ); ?>>
										<div class="slider round"></div>
									</label>
								</div>
								<p class="switch_in_pricing_rules_description">
									<?php echo esc_html( AFRSM_PERTICULAR_FEE_AMOUNT_NOTICE ); ?>
								</p>
							</div>
							<div class="advance_rule_condition_match_type">
								<p class="switch_in_pricing_rules_description_left">
									<?php esc_html_e( 'below', 'woo-hide-shipping-methods' ); ?>
								</p>
								<select name="cost_rule_match[cost_on_total_cart_subtotal_rule_match]" id="cost_on_total_cart_subtotal_rule_match" class="arcmt_select">
									<option value="any" <?php selected( $cost_on_total_cart_subtotal_rule_match, 'any' ); ?>><?php esc_html_e( 'Any One', 'woo-hide-shipping-methods' ); ?></option>
									<option value="all" <?php selected( $cost_on_total_cart_subtotal_rule_match, 'all' ); ?>><?php esc_html_e( 'All', 'woo-hide-shipping-methods' ); ?></option>
								</select>
								<p class="switch_in_pricing_rules_description">
									<?php esc_html_e( 'rule match', 'woo-hide-shipping-methods' ); ?>
								</p>
							</div>
						</div>
						<table id="tbl_ap_total_cart_subtotal_method" class="tbl_total_cart_subtotal table-outer tap-cas form-table advance-shipping-method-table">
							<tbody>
							<tr class="heading">
								<th class="titledesc th_total_cart_subtotal_fees_conditions_condition" scope="row"><?php esc_html_e( 'Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
								</th>
								<th class="titledesc th_total_cart_subtotal_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Min Subtotal *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e(
											'You can set a minimum total cart subtotal per row before the fee amount is
                                             applied.',
											'advanced-flat-rate-shipping-for-woocommerce'
										);
										?>
									</p>
								</th>
								<th class="titledesc th_total_cart_subtotal_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e(
											'You can set a maximum total cart subtotal per row before the fee amount is
                                             applied.',
											'advanced-flat-rate-shipping-for-woocommerce'
										);
										?>
										<br/>
										<?php esc_html_e( 'Leave empty then will set with maximum 999999999999999999999999999', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_total_cart_subtotal_fees_conditions_condition" scope="row" colspan="2"><?php esc_html_e( 'Fee Amount *', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e( 'A fixed amount ( e.g. 5 / -5 ) percentage ( e.g. 5% / -5% ) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' );
										?>
								</th>
							</tr>
							<?php
							$filled_total_cart_subtotal = array();
							if ( ! empty( $sm_metabox_ap_total_cart_subtotal ) && is_array( $sm_metabox_ap_total_cart_subtotal ) ) :
								foreach ( $sm_metabox_ap_total_cart_subtotal as $apcat_arr ) :
									if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
										if (
											( '' !== $apcat_arr['ap_fees_total_cart_subtotal'] && '' !== $apcat_arr['ap_fees_ap_price_total_cart_subtotal'] ) &&
											( '' !== $apcat_arr['ap_fees_ap_total_cart_subtotal_min_subtotal'] || '' !== $apcat_arr['ap_fees_ap_total_cart_subtotal_max_subtotal'] )
										) {
											$filled_total_cart_subtotal[] = $apcat_arr;
										}
									}
								endforeach;
							endif;
							if ( isset( $filled_total_cart_subtotal ) && ! empty( $filled_total_cart_subtotal ) ) {
								$cnt_total_cart_subtotal = 2;
								foreach ( $filled_total_cart_subtotal as $key => $productfees ) {
									$fees_ap_fees_total_cart_subtotal            = isset( $productfees['ap_fees_total_cart_subtotal'] ) ? $productfees['ap_fees_total_cart_subtotal'] : '';
									$ap_fees_ap_total_cart_subtotal_min_subtotal = isset( $productfees['ap_fees_ap_total_cart_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_total_cart_subtotal_min_subtotal'] : '';
									$ap_fees_ap_total_cart_subtotal_max_subtotal = isset( $productfees['ap_fees_ap_total_cart_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_total_cart_subtotal_max_subtotal'] : '';
									$ap_fees_ap_price_total_cart_subtotal        = isset( $productfees['ap_fees_ap_price_total_cart_subtotal'] ) ? $productfees['ap_fees_ap_price_total_cart_subtotal'] : '';
									?>
									<tr id="ap_total_cart_subtotal_row_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>" valign="top" class="ap_total_cart_subtotal_row_tr">
										<td class="titledesc" scope="row">
											<label><?php echo esc_html_e( 'Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
											<input type="hidden" name="fees[ap_total_cart_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>][]" id="ap_total_cart_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_total_cart_subtotal_min_subtotal][]" class="text-class price-class" id="ap_fees_ap_total_cart_subtotal_min_subtotal[]" placeholder="<?php esc_html_e( 'Min Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" step="0.01" value="<?php echo esc_attr( $ap_fees_ap_total_cart_subtotal_min_subtotal ); ?>" min="0.0">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_total_cart_subtotal_max_subtotal][]" class="text-class price-class" id="ap_fees_ap_total_cart_subtotal_max_subtotal[]" placeholder="<?php esc_html_e( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" step="0.01" value="<?php echo esc_attr( $ap_fees_ap_total_cart_subtotal_max_subtotal ); ?>" min="0.0">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_price_total_cart_subtotal][]" class="text-class number-field" id="ap_fees_ap_price_total_cart_subtotal[]" placeholder="<?php esc_html_e( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_price_total_cart_subtotal ); ?>">
											<?php
											$first_char = substr( $ap_fees_ap_price_total_cart_subtotal, 0, 1 );
											if ( '-' === $first_char ) {
												$html = sprintf(
													'<p><b style="color: red;">%s</b>%s',
													esc_html__(
														'Note: ',
														'advanced-flat-rate-shipping-for-woocommerce'
													),
													esc_html__(
														'If entered fee amount is less than cart subtotal it will reflect with minus sign ( EX: $ -10.00 ) OR If entered fee amount is more than cart subtotal then the total amount shown as zero ( EX: Total: 0 ): ',
														'advanced-flat-rate-shipping-for-woocommerce'
													)
												);
												echo wp_kses_post( $html );
											}
											?>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?> condition-value">
											<a id="ap-total-cart-subtotal-delete-field" rel-id="<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>" title="Delete" class="delete-row" href="javascript:;">
												<i class="fa fa-trash"></i>
											</a>
										</td>
									</tr>
									<?php
									$cnt_total_cart_subtotal ++;
								}
								?>
								<?php
							} else {
								$cnt_total_cart_subtotal = 1;
							}
							?>
							</tbody>
						</table>
						<input type="hidden" name="total_row_total_cart_subtotal" id="total_row_total_cart_subtotal" value="<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>">
					</div>
				</div>
				<div class="ap_shipping_class_subtotal_container advance_pricing_rule_box tab-content" id="tab-10" data-title="<?php esc_html_e( 'Cost on Shipping Class Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
					<div class="tap-class">
						<div class="predefined_elements">
							<div id="all_shipping_class_list">
								<?php
								echo wp_kses( $afrsm_admin_object->afrsfwa_get_class_options__premium_only( '', $json = true ), Advanced_Flat_Rate_Shipping_For_WooCommerce::afrsmw_allowed_html_tags() );
								?>
							</div>
						</div>
						<div class="sub-title">
							<div class="title-tip">
								<h2><?php esc_html_e( 'Cost on Shipping Class Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
							</div>
							<div class="tap">
								<a id="ap-shipping-class-subtotal-add-field" class="button button-primary button-large" href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
								<div class="switch_status_div">
									<label class="switch switch_in_pricing_rules">
										<input type="checkbox" name="cost_on_shipping_class_subtotal_status" value="on" <?php echo esc_attr( $cost_on_shipping_class_subtotal_status ); ?>>
										<div class="slider round"></div>
									</label>
									<p class="switch_in_pricing_rules_description">
										<?php echo esc_html( AFRSM_PERTICULAR_FEE_AMOUNT_NOTICE ); ?>
									</p>
								</div>
							</div>
							<div class="advance_rule_condition_match_type">
								<p class="switch_in_pricing_rules_description_left">
									<?php esc_html_e( 'below', 'woo-hide-shipping-methods' ); ?>
								</p>
								<select name="cost_rule_match[cost_on_shipping_class_subtotal_rule_match]" id="cost_on_shipping_class_subtotal_rule_match" class="arcmt_select">
									<option value="any" <?php selected( $cost_on_shipping_class_subtotal_rule_match, 'any' ); ?>><?php esc_html_e( 'Any One', 'woo-hide-shipping-methods' ); ?></option>
									<option value="all" <?php selected( $cost_on_shipping_class_subtotal_rule_match, 'all' ); ?>><?php esc_html_e( 'All', 'woo-hide-shipping-methods' ); ?></option>
								</select>
								<p class="switch_in_pricing_rules_description">
									<?php esc_html_e( 'rule match', 'woo-hide-shipping-methods' ); ?>
								</p>
							</div>
						</div>
						<table id="tbl_ap_shipping_class_subtotal_method" class="tbl_shipping_class_subtotal_fee table-outer tap-cas form-table advance-shipping-method-table">
							<tbody>
							<tr class="heading">
								<th class="titledesc th_shipping_class_subtotal_fees_conditions_condition" scope="row"><?php esc_html_e( 'Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'Select a category to apply the fee amount to when the min/max quantity match.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_shipping_class_subtotal_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Min Subtotal ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a minimum category quantity per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_shipping_class_subtotal_fees_conditions_condition" scope="row">
									<?php esc_html_e( 'Max Subtotal ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php esc_html_e( 'You can set a maximum category quantity per row before the fee amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
										<br/><?php esc_html_e( 'Leave empty then will set with maximum 999999999999999999999999999', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</th>
								<th class="titledesc th_shipping_class_subtotal_fees_conditions_condition" scope="row" colspan="2">
									<?php esc_html_e( 'Fee amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									<span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
									<p class="description" style="display:none;">
										<?php
										esc_html_e( 'A fixed amount ( e.g. 5 / -5 ) percentage ( e.g. 5% / -5% ) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' );
										?>
									</p>
								</th>
							</tr>
							<?php
							$filled_arr = array();
							if ( ! empty( $sm_metabox_ap_shipping_class_subtotal ) && is_array( $sm_metabox_ap_shipping_class_subtotal ) ) :
								foreach ( $sm_metabox_ap_shipping_class_subtotal as $apscs_arr ) :
									if ( ! empty( $apscs_arr ) || '' !== $apscs_arr ) {
										if (
											( '' !== $apscs_arr['ap_fees_shipping_class_subtotals']
											&& '' !== $apscs_arr['ap_fees_ap_price_shipping_class_subtotal'] )
											&&
											( '' !== $apscs_arr['ap_fees_ap_shipping_class_subtotal_min_subtotal']
											|| '' !== $apscs_arr['ap_fees_ap_shipping_class_subtotal_max_subtotal']
											)
										) {
											$filled_arr[] = $apscs_arr;
										}
									}
								endforeach;
							endif;
							if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
								$cnt_shipping_class_subtotal = 2;
								foreach ( $filled_arr as $key => $productfees ) {
									$fees_ap_fees_shipping_class_subtotals           = isset( $productfees['ap_fees_shipping_class_subtotals'] ) ? $productfees['ap_fees_shipping_class_subtotals'] : '';
									$ap_fees_ap_shipping_class_subtotal_min_subtotal = isset( $productfees['ap_fees_ap_shipping_class_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_shipping_class_subtotal_min_subtotal'] : '';
									$ap_fees_ap_shipping_class_subtotal_max_subtotal = isset( $productfees['ap_fees_ap_shipping_class_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_shipping_class_subtotal_max_subtotal'] : '';
									$ap_fees_ap_price_shipping_class_subtotal        = isset( $productfees['ap_fees_ap_price_shipping_class_subtotal'] ) ? $productfees['ap_fees_ap_price_shipping_class_subtotal'] : '';
									?>
									<tr id="ap_shipping_class_subtotal_row_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>" valign="top" class="ap_shipping_class_subtotal_row_tr">
										<td class="titledesc" scope="row">
											<select rel-id="<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>" id="ap_shipping_class_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>" name="fees[ap_shipping_class_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>][]" class="ap_shipping_class_subtotal product_fees_conditions_values multiselect2 afrsm_select" multiple="multiple">
												<?php
												echo wp_kses( $afrsm_admin_object->afrsfwa_get_class_options__premium_only( $fees_ap_fees_shipping_class_subtotals, $json = false ), $afrsm_object::afrsmw_allowed_html_tags() );
												?>
											</select>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_shipping_class_subtotal_min_subtotal][]" class="text-class qty-class" id="ap_fees_ap_shipping_class_subtotal_min_subtotal[]" placeholder="<?php esc_html_e( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_shipping_class_subtotal_min_subtotal ); ?>" min="1">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?> condition-value">
											<input type="number" name="fees[ap_fees_ap_shipping_class_subtotal_max_subtotal][]" class="text-class qty-class" id="ap_fees_ap_shipping_class_subtotal_max_subtotal[]" placeholder="<?php esc_html_e( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_shipping_class_subtotal_max_subtotal ); ?>" min="1">
										</td>
										<td class="column_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?> condition-value">
											<input type="text" name="fees[ap_fees_ap_price_shipping_class_subtotal][]" class="text-class number-field" id="ap_fees_ap_price_shipping_class_subtotal[]" placeholder="<?php esc_html_e( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $ap_fees_ap_price_shipping_class_subtotal ); ?>">
											<?php
											$first_char = substr( $ap_fees_ap_price_shipping_class_subtotal, 0, 1 );
											if ( '-' === $first_char ) {
												$html = sprintf(
													'<p><b style="color: red;">%s</b>%s',
													esc_html__(
														'Note: ',
														'advanced-flat-rate-shipping-for-woocommerce'
													),
													esc_html__(
														'If entered fee amount is less than cart subtotal it will reflect with minus sign ( EX: $ -10.00 ) OR If entered fee amount is more than cart subtotal then the total amount shown as zero ( EX: Total: 0 ): ',
														'advanced-flat-rate-shipping-for-woocommerce'
													)
												);
												echo wp_kses_post( $html );
											}
											?>
										</td>
										<td class="column_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?> condition-value">
											<a id="ap-shipping-class-subtotal-delete-field" rel-id="<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>" title="Delete" class="delete-row" href="javascript:;">
												<i class="fa fa-trash"></i>
											</a>
										</td>
									</tr>
									<?php
									$cnt_shipping_class_subtotal ++;
								}
								?>
								<?php
							} else {
								$cnt_shipping_class_subtotal = 1;
							}
							?>
							</tbody>
						</table>
						<input type="hidden" name="total_row_shipping_class_subtotal" id="total_row_shipping_class_subtotal" value="<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<p class="submit">
		<input type="submit" class="button button-primary" name="afrsm_save" value="<?php esc_html_e( 'Save Changes', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
	</p>
<?php
wp_nonce_field( 'woocommerce_save_method', 'woocommerce_save_method_nonce' );
