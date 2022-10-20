<?php
/**
 * Booster for WooCommerce - Settings - One page Checkout
 *
 * @version 5.4.2
 * @author  Pluggabl LLC.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$settings = array(
	array(
		'title'    => __( 'Options', 'woocommerce-jetpack' ),
		'type'     => 'title',
		'id'       => 'wcj_opc_options',
	),
	array(
		'title'    => __( 'Products', 'woocommerce-jetpack' ),
		'type'     => 'multiselect',
		'id'       => 'wcj_opc_global_ids',
		'default'  => '',
		'class'    => 'chosen_select',
		'options'  => wcj_get_products(),
		'desc'     => __( "If you did not set product_ids on the shortcode [wcj_one_page_checkout], then the selected products will be used.", "woocommerce-jetpack" ),
	),
	array(
		'type'     => 'sectionend',
		'id'       => 'wcj_opc_global_ids',
	),
);


return $settings;