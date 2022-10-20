<?php
/**
 * Booster for WooCommerce - Settings Meta Box - Frequently Bought Together
 *
 * @version 4.5.2
 * @since   4.5.2
 * @author  Pluggabl LLC.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$product_id       = get_the_ID();
$products         = wcj_get_products( array(),'publish',256,true,true );
unset( $products[ $product_id  ] );
$options = array(
	array(
		'title'    => __( 'Enable', 'woocommerce-jetpack' ),
		'name'     => 'wcj_product_fbt_enabled',
		'default'  => "no",
		'type'     => 'select',
		'options'  => array(
			'no'  => __( 'No', 'woocommerce-jetpack' ),
			'yes' => __( 'Yes', 'woocommerce-jetpack' ),
		),
	),
	array(
		'title'    => __( 'Frequently Bought Together Products', 'woocommerce-jetpack' ),
		'name'     => 'wcj_product_fbt_products_ids',
		'default'  => '',
		'type'     => 'select',
		'options'  => $products,
		'multiple' => true,
		'css'      => 'width:100%',
		'class'    => 'chosen_select',
	),
);
return $options;