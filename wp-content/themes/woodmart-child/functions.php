<?php
/**
 * Enqueue script and styles for child theme
 */
function woodmart_child_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'woodmart-style' ), woodmart_get_theme_info( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 1000 );

/*Modifica del titolo della pagina principale di Woocommerce*/
add_filter( 'woocommerce_page_title', 'woo_shop_page_title');
function woo_shop_page_title( $page_title ) {
if( 'Shop' == $page_title) {
return "<h1 style='font-size: 21px;'> CONSEGNA IN MENO DI 48H – DISPONIBILE RESO E PAGAMENTO ALLA CONSEGNA</H1>
<H2 style='font-size: 28px;'>SPEDIZIONI GRATUITE SOPRA I 29,90 EURO DI SPESA</h2> ";
}
}

add_action( 'woocommerce_checkout_process', 'wpspecial_imposta_ordine_minimo_importo' );
add_action( 'woocommerce_before_cart' , 'wpspecial_imposta_ordine_minimo_importo' );
add_action( 'woocommerce_before_checkout_form', 'wpspecial_imposta_ordine_minimo_importo' );
function wpspecial_imposta_ordine_minimo_importo() {
    // Specifica l'importo minimo per l'acquisto
    $minimo_acquisto = 25;

    if ( WC()->cart->subtotal < $minimo_acquisto ) {

        if( is_cart() ) {

            wc_print_notice(
                sprintf( 'Devi effettuare un acquisto minimo di %s per completare un ordine, attualmente il tuo ordine è di %s. Se arrivi a 29,90 euro di spesa hai anche la spedizione gratuita.' ,
                    wc_price( $minimo_acquisto ),
                    wc_price( WC()->cart->subtotal )
                ), 'error'
            );

        } else {

            wc_add_notice(
                sprintf( 'Devi effettuare un acquisto minimo di %s per completare un ordine, attualmente il tuo ordine è di %s. Se arrivi a 29,90 euro di spesa hai anche la spedizione gratuita.' ,
                    wc_price( $minimo_acquisto ),
                    wc_price( WC()->cart->subtotal )
                ), 'error'
            );

        }
    }
}


?>
