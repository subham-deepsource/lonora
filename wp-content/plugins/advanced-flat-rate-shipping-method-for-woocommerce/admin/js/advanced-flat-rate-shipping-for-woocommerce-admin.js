( function ( $ ) {
    'use strict';
    jQuery( ".multiselect2" ).select2();
    
    function allowSpeicalCharacter( str ) {
        return str.replace( '&#8211;', '–' ).replace( "&gt;", ">" ).replace( "&lt;", "<" ).replace( "&#197;", "Å" );
    }
    
    function productFilter() {
        jQuery( '.product_fees_conditions_values_product' ).each( function () {
            $( '.product_fees_conditions_values_product' ).select2( {
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function ( params ) {
                        return {
                            value: params.term,
                            action: 'afrsfwa_product_fees_conditions_values_product_ajax'
                        };
                    },
                    processResults: function ( data ) {
                        var options = [];
                        if ( data ) {
                            $.each( data, function ( index, text ) {
                                options.push( {id: text[0], text: allowSpeicalCharacter( text[1] )} );
                            } );
                            
                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            } );
        } );
    }
    
    function varproductFilter() {
        $( '.product_fees_conditions_values_var_product' ).each( function () {
            var select_name = $( this ).attr( 'id' );
            $( '.product_fees_conditions_values_var_product' ).select2( {
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function ( params ) {
                        return {
                            value: params.term,
                            action: 'afrsfwa_product_fees_conditions_varible_values_product_ajax__premium_only'
                        };
                    },
                    processResults: function ( data ) {
                        var options = [];
                        if ( data ) {
                            $.each( data, function ( index, text ) {
                                options.push( {id: text[0], text: allowSpeicalCharacter( text[1] )} );
                            } );
                            
                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            } );
        } );
    }
    
    
    function getProductListBasedOnThreeCharAfterUpdate() {
        $( '.pricing_rules .ap_product, ' +
           '.pricing_rules .ap_product_weight, ' +
           '.pricing_rules .ap_product_subtotal' ).each( function () {
            $( '.pricing_rules .ap_product, ' +
               '.pricing_rules .ap_product_weight, ' +
               '.pricing_rules .ap_product_subtotal' ).select2( {
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function ( params ) {
                        return {
                            value: params.term,
                            action: 'afrsfwa_simple_and_variation_product_list_ajax__premium_only'
                        };
                    },
                    processResults: function ( data ) {
                        var options = [];
                        if ( data ) {
                            $.each( data, function ( index, text ) {
                                options.push( {id: text[0], text: allowSpeicalCharacter( text[1] )} );
                            } );
                            
                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            } );
        } );
    }
    
    
    function setAllAttributes( element, attributes ) {
        Object.keys( attributes ).forEach( function ( key ) {
            element.setAttribute( key, attributes[key] );
            // use val
        } );
        return element;
    }
    
    function numberValidateForAdvanceRules() {
        $( '.number-field' ).keypress( function ( e ) {
            var regex = new RegExp( "^[0-9-%.]+$" );
            var str   = String.fromCharCode( ! e.charCode ? e.which : e.charCode );
            if ( regex.test( str ) ) {
                return true;
            }
            e.preventDefault();
            return false;
        } );
        $( '.qty-class' ).keypress( function ( e ) {
            var regex = new RegExp( "^[0-9]+$" );
            var str   = String.fromCharCode( ! e.charCode ? e.which : e.charCode );
            if ( regex.test( str ) ) {
                return true;
            }
            e.preventDefault();
            return false;
        } );
        $( '.weight-class, .price-class' ).keypress( function ( e ) {
            var regex = new RegExp( "^[0-9.]+$" );
            var str   = String.fromCharCode( ! e.charCode ? e.which : e.charCode );
            if ( regex.test( str ) ) {
                return true;
            }
            e.preventDefault();
            return false;
        } );
    }
    
    $( window ).load( function () {
        jQuery( ".multiselect2" ).select2();
        /*Start: Get last url parameters*/
        function getUrlVars() {
            var vars            = [], hash;
            var get_current_url = coditional_vars.current_url;
            var hashes          = get_current_url.slice( get_current_url.indexOf( '?' ) + 1 ).split( '&' );
            for ( var i = 0; i < hashes.length; i++ ) {
                hash = hashes[i].split( '=' );
                vars.push( hash[0] );
                vars[hash[0]] = hash[1];
            }
            return vars;
        }
        
        var url_parameters = getUrlVars();
        var current_action = url_parameters.action;
        var current_tab    = url_parameters.tab;
        
        function getCustomUrlVars( URL ) {
            var vars    = [], hash;
            var get_url = URL;
            var hashes  = get_url.slice( get_url.indexOf( '?' ) + 1 ).split( '&' );
            for ( var i = 0; i < hashes.length; i++ ) {
                hash = hashes[i].split( '=' );
                vars.push( hash[0] );
                vars[hash[0]] = hash[1];
            }
            return vars;
        }
        
        /*End: Get last url parameters*/
        /*
				 * Timepicker
				 * */
        var sm_time_from = $( '#sm_time_from' ).val();
        var sm_time_to   = $( '#sm_time_to' ).val();
        
        $( '#sm_time_from' ).timepicker( {
            timeFormat: 'h:mm p',
            interval: 60,
            minTime: '00:00AM',
            maxTime: '11:59PM',
            startTime: sm_time_from,
            dynamic: true,
            dropdown: true,
            scrollbar: true
        } );
        
        $( '#sm_time_to' ).timepicker( {
            timeFormat: 'h:mm p',
            interval: 60,
            minTime: '00:00AM',
            maxTime: '11:59PM',
            startTime: sm_time_to,
            dynamic: true,
            dropdown: true,
            scrollbar: true
        } );
        /**
         * Datepicker for start date
         */
        $( "#sm_start_date" ).datepicker( {
            dateFormat: 'dd-mm-yy',
            minDate: '0',
            onSelect: function ( selected ) {
                var dt = $( this ).datepicker( 'getDate' );
                dt.setDate( dt.getDate() + 1 );
                $( "#sm_end_date" ).datepicker( "option", "minDate", dt );
            }
        } );
        
        /**
         * Datepicker for end date
         */
        $( "#sm_end_date" ).datepicker( {
            dateFormat: 'dd-mm-yy',
            minDate: '0',
            onSelect: function ( selected ) {
                var dt = $( this ).datepicker( 'getDate' );
                dt.setDate( dt.getDate() - 1 );
                $( "#sm_start_date" ).datepicker( "option", "maxDate", dt );
            }
        } );
        
        /*
				 * Sortable
				 */
        if ( current_tab == "advance_shipping_method" ) {
            $( '.woocommerce_page_afrsm-start-page table.wp-list-table tbody' ).sortable( {
                start: function ( event, ui ) {
                },
                receive: function ( event, ui ) {
                },
                stop: function ( event, ui ) {
                    getSortingElement();
                }
            } );
        }
        
        function getSortingElement() {
            var get_sortable_post_id_arr = [];
            $( '.woocommerce_page_afrsm-start-page table.wp-list-table tbody tr' ).each( function () {
                var get_href             = $( this ).find( 'td:first strong a' ).attr( 'href' );
                var get_custom_var       = getCustomUrlVars( get_href );
                var get_sortable_post_id = get_custom_var.post;
                get_sortable_post_id_arr.push( get_sortable_post_id );
            } );
            saveAllIdOrderWise( get_sortable_post_id_arr );
        }
        
        /*Start code for save all method as per sequence in list*/
        function saveAllIdOrderWise( smOrderArray ) {
            $.ajax( {
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'afrsfwa_sm_sort_order',
                    'smOrderArray': smOrderArray
                }, beforeSend: function () {
                    var div = document.createElement( "div" );
                    div     = setAllAttributes( div, {
                        "class": "loader-overlay",
                    } );
                    
                    var img = document.createElement( "img" );
                    img     = setAllAttributes( img, {
                        "id": "before_ajax_id",
                        "src": coditional_vars.ajax_icon
                    } );
                    
                    div.appendChild( img );
                    jQuery( ".woocommerce_page_afrsm-start-page table #the-list" ).after( div );
                }, complete: function () {
                    jQuery( ".woocommerce_page_afrsm-start-page .loader-overlay" ).remove();
                }, success: function ( response ) {
                    if ( 'true' === jQuery.trim( response ) ) {
                    
                    }
                }
            } );
        }
        
        
        var ele = $( '#total_row' ).val();
        var count;
        if ( ele > 2 ) {
            count = ele;
        } else {
            count = 2;
        }
        $( 'body' ).on( 'click', '#fee-add-field', function () {
            var fee_add_field = $( '#tbl-shipping-method tbody' ).get( 0 );
            
            var tr = document.createElement( "tr" );
            tr     = setAllAttributes( tr, {"id": "row_" + count} );
            fee_add_field.appendChild( tr );
            
            // generate td of condition
            var td = document.createElement( "td" );
            td     = setAllAttributes( td, {} );
            tr.appendChild( td );
            var conditions = document.createElement( "select" );
            conditions     = setAllAttributes( conditions, {
                "rel-id": count,
                "id": "product_fees_conditions_condition_" + count,
                "name": "fees[product_fees_conditions_condition][]",
                "class": "product_fees_conditions_condition"
            } );
            conditions     = insertOptions( conditions, get_all_condition() );
            td.appendChild( conditions );
            // td ends
            
            // generate td for equal or no equal to
            td = document.createElement( "td" );
            td = setAllAttributes( td, {} );
            tr.appendChild( td );
            var conditions_is = document.createElement( "select" );
            conditions_is     = setAllAttributes( conditions_is, {
                "name": "fees[product_fees_conditions_is][]",
                "class": "product_fees_conditions_is product_fees_conditions_is_" + count
            } );
            conditions_is     = insertOptions( conditions_is, condition_types() );
            td.appendChild( conditions_is );
            // td ends
            
            // td for condition values
            td = document.createElement( "td" );
            td = setAllAttributes( td, {"id": "column_" + count} );
            tr.appendChild( td );
            condition_values( jQuery( '#product_fees_conditions_condition_' + count ) );
            
            var condition_key = document.createElement( "input" );
            condition_key     = setAllAttributes( condition_key, {
                "type": "hidden",
                "name": "condition_key[value_" + count + "][]",
                "value": "",
            } );
            td.appendChild( condition_key );
            var conditions_values_index = jQuery( ".product_fees_conditions_values_" + count ).get( 0 );
            jQuery( ".product_fees_conditions_values_" + count ).trigger( 'change' );
            jQuery( ".multiselect2" ).select2();
            // td ends
            
            // td for delete button
            td = document.createElement( "td" );
            tr.appendChild( td );
            var delete_button = document.createElement( "a" );
            delete_button     = setAllAttributes( delete_button, {
                "id": "fee-delete-field",
                "rel-id": count,
                "title": coditional_vars.delete,
                "class": "delete-row",
                "href": "javascript:;"
            } );
            var deleteicon    = document.createElement( 'i' );
            deleteicon        = setAllAttributes( deleteicon, {
                "class": "fa fa-trash"
            } );
            delete_button.appendChild( deleteicon );
            td.appendChild( delete_button );
            // td ends
            
            numberValidateForAdvanceRules();
            count++;
        } );
        
        $( 'body' ).on( 'change', '.product_fees_conditions_condition', function () {
            condition_values( this );
        } );
        
        /* description toggle */
        $( 'span.advanced_flat_rate_shipping_for_woocommerce_tab_description' ).click( function ( event ) {
            event.preventDefault();
            $( this ).next( 'p.description' ).toggle();
        } );
        
        
        /* Apply per quantity conditions end */
        /* Add AP Product functionality start */
        //get total count row from hidden field
        var row_product_ele = $( '#total_row_product' ).val();
        var count_product;
        if ( row_product_ele > 2 ) {
            count_product = row_product_ele;
        } else {
            count_product = 2;
        }
        
        //on click add rule create new method row
        $( 'body' ).on( 'click', '#ap-product-add-field', function () {
            //design new format of advanced pricing method row html
            createAdvancePricingRulesField( 'select', 'qty', 'product', count_product, 'prd', '' );
            getProductListBasedOnThreeCharAfterUpdate();
            numberValidateForAdvanceRules();
            is_percent_valid();//bind percent on blur event for checking the amount is proper format or not
            count_product++;
        } );
        /* Add AP Product functionality end here */
        
        /* Apply per product subtotal conditions end */
        /* Add AP product subtotal functionality start */
        //get total count row from hidden field
        var row_total_row_product_subtotal_ele = $( '#total_row_product_subtotal' ).val();
        var count_product_subtotal;
        if ( row_total_row_product_subtotal_ele > 2 ) {
            count_product_subtotal = row_product_ele;
        } else {
            count_product_subtotal = 2;
        }
        
        //on click add rule create new method row
        $( 'body' ).on( 'click', '#ap-product-subtotal-add-field', function () {
            //design new format of advanced pricing method row html
            createAdvancePricingRulesField( 'select', 'subtotal', 'product_subtotal', count_product_subtotal, 'product_subtotal', '' );
            getProductListBasedOnThreeCharAfterUpdate();
            numberValidateForAdvanceRules();
            is_percent_valid();//bind percent on blur event for checking the amount is proper format or not
            count_product_subtotal++;
        } );
        
        /* Add AP Category functionality start here*/
        //get total count row from hidden field
        var row_category_ele = $( '#total_row_category' ).val();
        var row_category_count;
        if ( row_category_ele > 2 ) {
            row_category_count = row_category_ele;
        } else {
            row_category_count = 2;
        }
        //on click add rule create new method row
        $( 'body' ).on( 'click', '#ap-category-add-field', function () {
            createAdvancePricingRulesField( 'select', 'qty', 'category', row_category_count, 'cat', 'category_list' );
            jQuery( ".ap_category" ).select2();
            numberValidateForAdvanceRules();
            // jQuery( '#ap_category_fees_conditions_condition_' + count ).trigger( "chosen:updated" );
            //rebide the new row with validation
            is_percent_valid();//bind percent on blur event for checking the amount is proper format or not
            row_category_count++;
        } );
        
        /* Add AP Category subtotal functionality start here*/
        //get total count row from hidden field
        var row_category_subtotal_ele = $( '#total_row_category_subtotal' ).val();
        var row_category_subtotal_count;
        if ( row_category_subtotal_ele > 2 ) {
            row_category_subtotal_count = row_category_subtotal_ele;
        } else {
            row_category_subtotal_count = 2;
        }
        //on click add rule create new method row
        $( 'body' ).on( 'click', '#ap-category-subtotal-add-field', function () {
            createAdvancePricingRulesField( 'select', 'subtotal', 'category_subtotal', row_category_subtotal_count, 'category_subtotal', 'category_list' );
            jQuery( ".ap_category_subtotal" ).select2();
            numberValidateForAdvanceRules();
            // jQuery( '#ap_category_fees_conditions_condition_' + count ).trigger( "chosen:updated" );
            //rebide the new row with validation
            is_percent_valid();//bind percent on blur event for checking the amount is proper format or not
            row_category_subtotal_count++;
        } );
        
        //get total count row from hidden field fro cart qty
        var total_cart_qty_ele = $( '#total_row_total_cart_qty' ).val();
        var total_cart_qty_count;
        if ( total_cart_qty_ele > 2 ) {
            total_cart_qty_count = total_cart_qty_ele;
        } else {
            total_cart_qty_count = 2;
        }
        //on click add rule create new method row for total cart
        $( 'body' ).on( 'click', '#ap-total-cart-qty-add-field', function () {
            createAdvancePricingRulesField( 'label', 'qty', 'total_cart_qty', total_cart_qty_count, 'total_cart_qty', '' );
            numberValidateForAdvanceRules();
            //rebide the new row with validation
            is_percent_valid();//bind percent on blur event for checking the amount is proper format or not
            total_cart_qty_count++;
        } );
        
        //get total count row from hidden field for Product Weight
        var count_product_weight_ele = $( '#total_row_product_weight' ).val();
        var count_product_weight;
        if ( count_product_weight_ele > 2 ) {
            count_product_weight = count_product_weight_ele;
        } else {
            count_product_weight = 2;
        }
        //on click add rule create new method row for Product Weight
        $( 'body' ).on( 'click', '#ap-product-weight-add-field', function () {
            createAdvancePricingRulesField( 'select', 'weight', 'product_weight', count_product_weight, 'product_weight', '' );
            getProductListBasedOnThreeCharAfterUpdate();
            numberValidateForAdvanceRules();
            is_percent_valid();//bind percent on blur event for checking the amount is proper format or not
            count_product_weight++;
        } );
        
        //get total count row from hidden field for Category Weight
        var category_weight_ele = $( '#total_row_category_weight' ).val();
        var category_weight_count;
        if ( category_weight_ele > 2 ) {
            category_weight_count = category_weight_ele;
        } else {
            category_weight_count = 2;
        }
        //on click add rule create new method row for Category Weight
        $( 'body' ).on( 'click', '#ap-category-weight-add-field', function () {
            createAdvancePricingRulesField( 'select', 'weight', 'category_weight', category_weight_count, 'category_weight', 'category_list' );
            jQuery( ".ap_category_weight" ).select2();
            numberValidateForAdvanceRules();
            // jQuery( '#ap_category_weight_fees_conditions_condition_' + count ).trigger( "chosen:updated" );
            //rebide the new row with validation
            is_percent_valid();//bind percent on blur event for checking the amount is proper format or not
            category_weight_count++;
        } );
        
        //get total count row from hidden field fro cart weight
        var total_cart_weight_ele = $( '#total_row_total_cart_weight' ).val();
        var total_cart_weight_count;
        if ( total_cart_weight_ele > 2 ) {
            total_cart_weight_count = total_cart_weight_ele;
        } else {
            total_cart_weight_count = 2;
        }
        //on click add rule create new method row for total cart weight
        $( 'body' ).on( 'click', '#ap-total-cart-weight-add-field', function () {
            createAdvancePricingRulesField( 'label', 'weight', 'total_cart_weight', total_cart_weight_count, 'total_cart_weight', '' );
            numberValidateForAdvanceRules();
            //rebide the new row with validation
            is_percent_valid();//bind percent on blur event for checking the amount is proper format or not
            total_cart_weight_count++;
        } );
        
        //get total count row from hidden field fro cart weight
        var total_cart_subtotal_ele = $( '#total_row_total_cart_subtotal' ).val();
        var total_cart_subtotal_count;
        if ( total_cart_subtotal_ele > 2 ) {
            total_cart_subtotal_count = total_cart_subtotal_ele;
        } else {
            total_cart_subtotal_count = 2;
        }
        //on click add rule create new method row for total cart weight
        $( 'body' ).on( 'click', '#ap-total-cart-subtotal-add-field', function () {
            createAdvancePricingRulesField( 'label', 'subtotal', 'total_cart_subtotal', total_cart_subtotal_count, 'total_cart_subtotal', '' );
            numberValidateForAdvanceRules();
            //rebide the new row with validation
            is_percent_valid();//bind percent on blur event for checking the amount is proper format or not
            total_cart_subtotal_count++;
        } );
        
        //get total count row from hidden field fro cart weight
        var shipping_class_subtotal_ele = $( '#total_row_shipping_class_subtotal' ).val();
        var shipping_class_subtotal_count;
        if ( shipping_class_subtotal_ele > 2 ) {
            shipping_class_subtotal_count = shipping_class_subtotal_ele;
        } else {
            shipping_class_subtotal_count = 2;
        }
        //on click add rule create new method row for total cart weight
        $( 'body' ).on( 'click', '#ap-shipping-class-subtotal-add-field', function () {
            createAdvancePricingRulesField( 'select', 'subtotal', 'shipping_class_subtotal', shipping_class_subtotal_count, 'shipping_class_subtotal', 'shipping_class_list' );
            jQuery( ".ap_shipping_class_subtotal" ).select2();
            numberValidateForAdvanceRules();
            //rebide the new row with validation
            is_percent_valid();//bind percent on blur event for checking the amount is proper format or not
            shipping_class_subtotal_count++;
        } );
        /* Add AP Category functionality end here */
        
        /* Defines AP Rules validate functions */
        function is_percent_valid() {
            //check amount only contains number or percentage
            $( '.percent_only' ).blur( function ( event ) {
                
                //regular expression for the valid amount enter like 20 or 20% or 50.0 or 50.55% etc.. is valid
                var is_valid_percent = /^[-]{0,1}((100 )|( \d{1,2}( \.\d{1,2} )? ) )[%]{0,1}$/;
                var percent_val      = $( this ).val();
                //check that entered amount for the advanced price is valid or not like 20 or 20% or 50.0 or 50.55% etc.. is valid
                if ( ! is_valid_percent.test( percent_val ) ) {
                    $( this ).val( '' );//if percent not in proper format than it will blank the textbox
                }
                //display note that if admin add - price than how message display in shipping method
                var first_char = percent_val.charAt( 0 );
                if ( first_char == '-' ) {
                    //remove old notice message if exist
                    $( this ).next().remove( 'p' );
                    $( this ).after( coditional_vars.warning_msg1 );
                } else {
                    //remove notice message if value is possitive
                    $( this ).next().remove( 'p' );
                }
            } );
        }
        
        /*End code for save all method as per sequence in list*/
        
        /* Apply per quantity conditions start */
        if ( $( "#fee_chk_qty_price" ).is( ':checked' ) ) {
            $( ".shipping-method-table .product_cost_right_div .applyperqty-boxtwo" ).show();
            $( ".shipping-method-table .product_cost_right_div .applyperqty-boxthree" ).show();
            $( "#extra_product_cost" ).prop( 'required', true );
        } else {
            $( ".shipping-method-table .product_cost_right_div .applyperqty-boxtwo" ).hide();
            $( ".shipping-method-table .product_cost_right_div .applyperqty-boxthree" ).hide();
            $( "#extra_product_cost" ).prop( 'required', false );
        }
        $( document ).on( 'change', '#fee_chk_qty_price', function () {
            if ( this.checked ) {
                $( ".shipping-method-table .product_cost_right_div .applyperqty-boxtwo" ).show();
                $( ".shipping-method-table .product_cost_right_div .applyperqty-boxthree" ).show();
                $( "#extra_product_cost" ).prop( 'required', true );
            } else {
                $( ".shipping-method-table .product_cost_right_div .applyperqty-boxtwo" ).hide();
                $( ".shipping-method-table .product_cost_right_div .applyperqty-boxthree" ).hide();
                $( "#extra_product_cost" ).prop( 'required', false );
            }
        } );
        
        $( 'ul.tabs li' ).click( function () {
            var tab_id = $( this ).attr( 'data-tab' );
            
            $( 'ul.tabs li' ).removeClass( 'current' );
            $( '.tab-content' ).removeClass( 'current' );
            
            $( this ).addClass( 'current' );
            $( "#" + tab_id ).addClass( 'current' );
        } );
        
        /*Start : Hide Show Shipping Display Mode*/
        $( '#display_mode' ).hide();
        var current_val = $( "#what_to_do_method" ).val();
        hideShowDisplayMode( current_val );
        
        /* What to do when multiple shipping methods are available based on change*/
        $( 'body' ).on( 'change', '#what_to_do_method', function () {
            var current_val = $( this ).val();
            hideShowDisplayMode( current_val );
        } );
        
        /* What to do when multiple shipping methods are available based on change*/
        function hideShowDisplayMode( current_val ) {
            if ( "allow_customer" === jQuery.trim( current_val ) ) {
                $( '#display_mode' ).show();
            } else {
                $( '#display_mode' ).hide();
            }
            if ( "force_all" === jQuery.trim( current_val ) ) {
                $( '#combine_default_shipping_with_forceall_td' ).show();
                $( '#forceall_text' ).show();
            } else {
                $( '#combine_default_shipping_with_forceall_td' ).hide();
                $( '#forceall_text' ).hide();
            }
        }
        
        function createAdvancePricingRulesField( field_type, qty_or_weight, field_title, field_count, field_title2, category_list_option ) {
            var label_text, min_input_placeholder, max_input_placeholder, inpt_class, inpt_type;
            if ( qty_or_weight == 'qty' ) {
                label_text = coditional_vars.cart_qty;
            } else if ( qty_or_weight == 'weight' ) {
                label_text = coditional_vars.cart_weight;
            } else if ( qty_or_weight == 'subtotal' ) {
                label_text = coditional_vars.cart_subtotal;
            }
            
            if ( qty_or_weight == 'qty' ) {
                min_input_placeholder = coditional_vars.min_quantity;
            } else if ( qty_or_weight == 'weight' ) {
                min_input_placeholder = coditional_vars.min_weight;
            } else if ( qty_or_weight == 'subtotal' ) {
                min_input_placeholder = coditional_vars.min_subtotal;
            }
            
            if ( qty_or_weight == 'qty' ) {
                max_input_placeholder = coditional_vars.max_quantity;
            } else if ( qty_or_weight == 'weight' ) {
                max_input_placeholder = coditional_vars.max_weight;
            } else if ( qty_or_weight == 'subtotal' ) {
                max_input_placeholder = coditional_vars.max_subtotal;
            }
            
            if ( qty_or_weight == 'qty' ) {
                inpt_class = 'qty-class';
                inpt_type  = 'number';
            } else if ( qty_or_weight == 'weight' ) {
                inpt_class = 'weight-class';
                inpt_type  = 'text';
            } else if ( qty_or_weight == 'subtotal' ) {
                inpt_class = 'price-class';
                inpt_type  = 'text';
            }
            var tr = document.createElement( "tr" );
            tr     = setAllAttributes( tr, {
                "class": "ap_" + field_title + "_row_tr",
                "id": "ap_" + field_title + "_row_" + field_count,
            } );
            
            var product_td = document.createElement( "td" );
            if ( field_type == "select" ) {
                var product_select = document.createElement( "select" );
                product_select     = setAllAttributes( product_select, {
                    "rel-id": field_count,
                    "id": "ap_" + field_title + "_fees_conditions_condition_" + field_count,
                    "name": "fees[ap_" + field_title + "_fees_conditions_condition][" + field_count + "][]",
                    "class": "afrsm_select ap_" + field_title + " product_fees_conditions_values multiselect2",
                    "multiple": "multiple",
                    "data-placeholder": coditional_vars.validation_length1
                } );
                
                product_td.appendChild( product_select );
                
                if ( category_list_option == 'category_list' ) {
                    var all_category_option = JSON.parse( $( '#all_category_list' ).html() );
                    for ( var i = 0; i < all_category_option.length; i++ ) {
                        var option          = document.createElement( "option" );
                        var category_option = all_category_option[i];
                        option.value        = category_option.attributes.value;
                        option.text         = allowSpeicalCharacter( category_option.name );
                        product_select.appendChild( option );
                    }
                }
                if ( category_list_option == 'shipping_class_list' ) {
                    var all_category_option = JSON.parse( $( '#all_shipping_class_list' ).html() );
                    for ( var i = 0; i < all_category_option.length; i++ ) {
                        var option          = document.createElement( "option" );
                        var category_option = all_category_option[i];
                        option.value        = category_option.attributes.value;
                        option.text         = allowSpeicalCharacter( category_option.name );
                        product_select.appendChild( option );
                    }
                }
            }
            if ( field_type == "label" ) {
                var product_label      = document.createElement( "label" );
                var product_label_text = document.createTextNode( label_text );
                product_label          = setAllAttributes( product_label, {
                    "for": label_text.toLowerCase(),
                } );
                product_label.appendChild( product_label_text );
                product_td.appendChild( product_label );
                
                var input_hidden = document.createElement( "input" );
                input_hidden     = setAllAttributes( input_hidden, {
                    "id": "ap_" + field_title + "_fees_conditions_condition_" + field_count,
                    "type": "hidden",
                    "name": "fees[ap_" + field_title + "_fees_conditions_condition][" + field_count + "][]",
                } );
                product_td.appendChild( input_hidden );
            }
            tr.appendChild( product_td );
            
            var min_qty_td    = document.createElement( "td" );
            min_qty_td        = setAllAttributes( min_qty_td, {
                "class": "column_" + field_count + " condition-value",
            } );
            var min_qty_input = document.createElement( "input" );
            if ( qty_or_weight == 'qty' ) {
                min_qty_input = setAllAttributes( min_qty_input, {
                    "type": inpt_type,
                    "id": "ap_fees_ap_" + field_title2 + "_min_" + qty_or_weight + "[]",
                    "name": "fees[ap_fees_ap_" + field_title2 + "_min_" + qty_or_weight + "][]",
                    "class": "text-class " + inpt_class,
                    "placeholder": min_input_placeholder,
                    "value": "",
                    "min": "1",
                    "required": "1",
                } );
            } else {
                min_qty_input = setAllAttributes( min_qty_input, {
                    "type": inpt_type,
                    "id": "ap_fees_ap_" + field_title2 + "_min_" + qty_or_weight + "[]",
                    "name": "fees[ap_fees_ap_" + field_title2 + "_min_" + qty_or_weight + "][]",
                    "class": "text-class " + inpt_class,
                    "placeholder": min_input_placeholder,
                    "value": "",
                    "required": "1",
                } );
            }
            
            min_qty_td.appendChild( min_qty_input );
            tr.appendChild( min_qty_td );
            
            var max_qty_td    = document.createElement( "td" );
            max_qty_td        = setAllAttributes( max_qty_td, {
                "class": "column_" + field_count + " condition-value",
            } );
            var max_qty_input = document.createElement( "input" );
            if ( qty_or_weight == 'qty' ) {
                max_qty_input = setAllAttributes( max_qty_input, {
                    "type": inpt_type,
                    "id": "ap_fees_ap_" + field_title2 + "_max_" + qty_or_weight + "[]",
                    "name": "fees[ap_fees_ap_" + field_title2 + "_max_" + qty_or_weight + "][]",
                    "class": "text-class " + inpt_class,
                    "placeholder": max_input_placeholder,
                    "value": "",
                    "min": "1",
                } );
            } else {
                max_qty_input = setAllAttributes( max_qty_input, {
                    "type": inpt_type,
                    "id": "ap_fees_ap_" + field_title2 + "_max_" + qty_or_weight + "[]",
                    "name": "fees[ap_fees_ap_" + field_title2 + "_max_" + qty_or_weight + "][]",
                    "class": "text-class " + inpt_class,
                    "placeholder": max_input_placeholder,
                    "value": "",
                } );
            }
            
            max_qty_td.appendChild( max_qty_input );
            tr.appendChild( max_qty_td );
            
            var price_td    = document.createElement( "td" );
            var price_input = document.createElement( "input" );
            price_input     = setAllAttributes( price_input, {
                "type": "text",
                "id": "ap_fees_ap_price_" + field_title + "[]",
                "name": "fees[ap_fees_ap_price_" + field_title + "][]",
                "class": "text-class number-field",
                "placeholder": coditional_vars.amount,
                "value": "",
                "required": "1",
            } );
            price_td.appendChild( price_input );
            tr.appendChild( price_td );
            
            var delete_td = document.createElement( "td" );
            var delete_a  = document.createElement( "a" );
            delete_a      = setAllAttributes( delete_a, {
                "id": "ap_" + field_title + "_delete_field",
                "rel-id": field_count,
                "title": coditional_vars.delete,
                "class": "delete-row",
                "href": "javascript:;"
            } );
            var delete_i  = document.createElement( "i" );
            delete_i      = setAllAttributes( delete_i, {
                "class": "fa fa-trash"
            } );
            delete_a.appendChild( delete_i );
            delete_td.appendChild( delete_a );
            
            
            tr.appendChild( delete_td );
            
            $( '#tbl_ap_' + field_title + '_method tbody tr' ).last().after( tr );
        }
        
        function insertOptions( parentElement, options ) {
            for ( var i = 0; i < options.length; i++ ) {
                if ( options[i].type == 'optgroup' ) {
                    var optgroup = document.createElement( "optgroup" );
                    optgroup     = setAllAttributes( optgroup, options[i].attributes );
                    for ( var j = 0; j < options[i].options.length; j++ ) {
                        var option         = document.createElement( "option" );
                        option             = setAllAttributes( option, options[i].options[j].attributes );
                        option.textContent = options[i].options[j].name;
                        optgroup.appendChild( option );
                    }
                    parentElement.appendChild( optgroup );
                } else {
                    var option         = document.createElement( "option" );
                    option             = setAllAttributes( option, options[i].attributes );
                    option.textContent = allowSpeicalCharacter( options[i].name );
                    parentElement.appendChild( option );
                }
                
            }
            return parentElement;
            
        }
        
        function allowSpeicalCharacter( str ) {
            return str.replace( '&#8211;', '–' ).replace( "&gt;", ">" ).replace( "&lt;", "<" ).replace( "&#197;", "Å" );
        }
        
        //remove tr on delete icon click
        $( 'body' ).on( 'click', '.delete-row', function () {
            $( this ).parent().parent().remove();
        } );
        
        $( ".chosen-select" ).select2();
        
        $( 'body' ).on( 'click', 'a.shipping-zone-delete', function () {
            var answer = confirm( $( this ).data( 'message' ) );
            if ( answer ) {
                return true;
            } else {
                return false;
            }
        } );
        
        $( '.zone_type_options .zone_type_selectbox' ).hide();
        
        $( '.zone_type_options input[type="radio"]' ).each( function () {
            if ( $( this ).is( ':checked' ) ) {
                $( this ).parent().parent().next().show();
                $( this ).parent().parent().parent().addClass( "active_zone" );
            } else {
                $( this ).parent().parent().next().hide();
                $( this ).parent().parent().parent().removeClass( "active_zone" );
            }
        } );
        
        $( 'body' ).on( 'change', '.zone_type_options input[name=zone_type]', function () {
            if ( $( this ).is( ':checked' ) ) {
                var value = $( this ).val();
                $( '.zone_type_options input[type="radio"]' ).each( function () {
                    var tmp_nm = $( this ).val();
                    $( this ).parent().parent().parent().removeClass( "active_zone" );
                    if ( tmp_nm !== value ) {
                        $( this ).parent().parent().next().hide();
                    } else {
                        $( this ).parent().parent().next().show();
                    }
                } );
                $( this ).parent().parent().parent().addClass( "active_zone" );
            }
        } );
        
        $( 'body' ).on( 'click', '.select_us_states', function () {
            $( this ).closest( 'div' ).find( 'option[value="US:AK"], option[value="US:AL"], option[value="US:AZ"], option[value="US:AR"], option[value="US:CA"], ' +
                                             'option[value="US:CO"], option[value="US:CT"], option[value="US:DE"], option[value="US:DC"], option[value="US:FL"], option[value="US:GA"], ' +
                                             'option[value="US:HI"], option[value="US:ID"], option[value="US:IL"], option[value="US:IN"], option[value="US:IA"], option[value="US:KS"], ' +
                                             'option[value="US:KY"], option[value="US:LA"], option[value="US:ME"], option[value="US:MD"], option[value="US:MA"], option[value="US:MI"], ' +
                                             'option[value="US:MN"], option[value="US:MS"], option[value="US:MO"], option[value="US:MT"], option[value="US:NE"], option[value="US:NV"], ' +
                                             'option[value="US:NH"], option[value="US:NJ"], option[value="US:NM"], option[value="US:NY"], option[value="US:NC"], option[value="US:ND"], ' +
                                             'option[value="US:OH"], option[value="US:OK"], option[value="US:OR"], option[value="US:PA"], option[value="US:RI"], option[value="US:SC"], ' +
                                             'option[value="US:SD"], option[value="US:TN"], option[value="US:TX"], option[value="US:UT"], option[value="US:VT"], option[value="US:VA"], ' +
                                             'option[value="US:WA"], option[value="US:WV"], option[value="US:WI"], option[value="US:WY"]' ).attr( "selected", "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        $( 'body' ).on( 'click', '.select_europe', function () {
            $( this ).closest( 'div' ).find( 'option[value="AL"], option[value="AD"], option[value="AM"], option[value="AT"], option[value="BY"], option[value="BE"], ' +
                                             'option[value="BA"], option[value="BG"], option[value="CH"], option[value="CY"], option[value="CZ"], option[value="DE"], option[value="DK"], ' +
                                             'option[value="EE"], option[value="ES"], option[value="FO"], option[value="FI"], option[value="FR"], option[value="GB"], option[value="GE"], ' +
                                             'option[value="GI"], option[value="GR"], option[value="HU"], option[value="HR"], option[value="IE"], option[value="IS"], option[value="IT"], ' +
                                             'option[value="LT"], option[value="LU"], option[value="LV"], option[value="MC"], option[value="MK"], option[value="MT"], option[value="NO"], ' +
                                             'option[value="NL"], option[value="PO"], option[value="PT"], option[value="RO"], option[value="RU"], option[value="SE"], option[value="SI"], ' +
                                             'option[value="SK"], option[value="SM"], option[value="TR"], option[value="UA"], option[value="VA"]' ).attr( "selected", "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        $( 'body' ).on( 'click', '.select_asia', function () {
            $( this ).closest( 'div' ).find( 'option[value="AE"], option[value="AF"], option[value="AM"], option[value="AZ"], option[value="BD"], option[value="BH"], ' +
                                             'option[value="BN"], option[value="BT"], option[value="CC"], option[value="CN"], option[value="CX"], option[value="CY"], option[value="GE"], ' +
                                             'option[value="HK"], option[value="ID"], option[value="IL"], option[value="IN"], option[value="IO"], option[value="IQ"], option[value="IR"], ' +
                                             'option[value="JO"], option[value="JP"], option[value="KG"], option[value="KH"], option[value="KP"], option[value="KR"], option[value="KW"], ' +
                                             'option[value="KZ"], option[value="LA"], option[value="LB"], option[value="LK"], option[value="MM"], option[value="MN"], option[value="MO"], ' +
                                             'option[value="MV"], option[value="MY"], option[value="NP"], option[value="OM"], option[value="PH"], option[value="PK"], option[value="PS"], ' +
                                             'option[value="QA"], option[value="SA"], option[value="SG"], option[value="SY"], option[value="TH"], option[value="TJ"], option[value="TL"], ' +
                                             'option[value="TM"], option[value="TW"], option[value="UZ"], option[value="VN"], option[value="YE"]' ).attr( "selected", "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        $( 'body' ).on( 'click', '.select_africa', function () {
            $( this ).closest( 'div' ).find( ' option[value="AO"], option[value="BF"], option[value="BI"], option[value="BJ"], option[value="BW"], option[value="CD"], ' +
                                             'option[value="CF"], option[value="CG"], option[value="CI"], option[value="CM"], option[value="CV"], option[value="DJ"], option[value="DZ"], ' +
                                             'option[value="EG"], option[value="EH"], option[value="ER"], option[value="ET"], option[value="GA"], option[value="GH"], option[value="GM"], ' +
                                             'option[value="GN"], option[value="GQ"], option[value="GW"], option[value="KE"], option[value="KM"], option[value="LR"], option[value="LS"], ' +
                                             'option[value="LY"], option[value="MA"], option[value="MG"], option[value="ML"], option[value="MR"], option[value="MU"], option[value="MW"], ' +
                                             'option[value="MZ"], option[value="NA"], option[value="NE"], option[value="NG"], option[value="RE"], option[value="RW"], option[value="SC"], ' +
                                             'option[value="SD"], option[value="SS"], option[value="SH"], option[value="SL"], option[value="SN"], option[value="SO"], option[value="ST"], ' +
                                             'option[value="SZ"], option[value="TD"], option[value="TG"], option[value="TN"], option[value="TZ"], option[value="UG"], option[value="YT"], ' +
                                             'option[value="ZA"], option[value="ZM"], option[value="ZW"]' )
              .attr( "selected", "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        $( 'body' ).on( 'click', '.select_antarctica', function () {
            $( this ).closest( 'div' ).find( 'option[value="AQ"], option[value="BV"], option[value="GS"], option[value="HM"], option[value="TF"]' ).attr( "selected", "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        $( 'body' ).on( 'click', '.select_northamerica', function () {
            $( this ).closest( 'div' ).find( 'option[value="AG"], option[value="AI"], option[value="AN"], option[value="AW"], option[value="BB"], option[value="BL"], ' +
                                             'option[value="BM"], option[value="BS"], option[value="BZ"], option[value="CA"], option[value="CR"], option[value="CU"], option[value="DM"], ' +
                                             'option[value="DO"], option[value="GD"], option[value="GL"], option[value="GP"], option[value="GT"], option[value="HN"], option[value="HT"], ' +
                                             'option[value="JM"], option[value="KN"], option[value="KY"], option[value="LC"], option[value="MF"], option[value="MQ"], option[value="MS"], ' +
                                             'option[value="MX"], option[value="NI"], option[value="PA"], option[value="PM"], option[value="PR"], option[value="SV"], option[value="TC"], ' +
                                             'option[value="TT"], option[value="US"], option[value="VC"], option[value="VG"], option[value="VI"]' ).attr( "selected", "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        $( 'body' ).on( 'click', '.select_oceania', function () {
            $( this ).closest( 'div' ).find( ' option[value="AS"], option[value="AU"], option[value="CK"], option[value="FJ"], option[value="FM"], option[value="GU"], ' +
                                             'option[value="KI"], option[value="MH"], option[value="MP"], option[value="NC"], option[value="NF"], option[value="NR"], option[value="NU"], ' +
                                             'option[value="NZ"], option[value="PF"], option[value="PG"], option[value="PN"], option[value="PW"], option[value="SB"], option[value="TK"], ' +
                                             'option[value="TO"], option[value="TV"], option[value="UM"], option[value="VU"], option[value="WF"], option[value="WS"]' ).attr( "selected", "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        $( 'body' ).on( 'click', '.select_southamerica', function () {
            $( this ).closest( 'div' ).find( ' option[value="AR"], option[value="BO"], option[value="BR"], option[value="CL"], option[value="CO"], option[value="EC"], ' +
                                             'option[value="FK"], option[value="GF"], option[value="GY"], option[value="PE"], option[value="PY"], option[value="SR"], option[value="UY"], ' +
                                             'option[value="VE"]' ).attr( "selected", "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        $( 'body' ).on( 'click', '.select_africa_states', function () {
            $( this ).closest( 'div' ).find( 'option[value="ZA:EC"], option[value="ZA:FS"], option[value="ZA:GP"], option[value="ZA:KZN"], option[value="ZA:LP"], ' +
                                             'option[value="ZA:MP"], option[value="ZA:NC"], option[value="ZA:NW"], option[value="ZA:WC"]' ).attr( "selected", "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        $( 'body' ).on( 'click', '.select_asia_states', function () {
            $( this ).closest( 'div' ).find( 'option[value="BD"],option[value="BD:BAG"], option[value="BD:BAN"], option[value="BD:BAR"], option[value="BD:BARI"], ' +
                                             'option[value="BD:BHO"], option[value="BD:BOG"], option[value="BD:BRA"], option[value="BD:CHA"], option[value="BD:CHI"], option[value="BD:CHU"], ' +
                                             'option[value="BD:COM"], option[value="BD:COX"], option[value="BD:DHA"], option[value="BD:DIN"], option[value="BD:FAR"], option[value="BD:FEN"], ' +
                                             'option[value="BD:GAI"], option[value="BD:GAZI"], option[value="BD:GOP"], option[value="BD:HAB"], option[value="BD:JAM"], option[value="BD:JES"], ' +
                                             'option[value="BD:JHA"], option[value="BD:JHE"], option[value="BD:JOY"], option[value="BD:KHA"], option[value="BD:KHU"], option[value="BD:KIS"], ' +
                                             'option[value="BD:KUR"], option[value="BD:KUS"], option[value="BD:LAK"], option[value="BD:LAL"], option[value="BD:MAD"], option[value="BD:MAG"], ' +
                                             'option[value="BD:MAN"], option[value="BD:MEH"], option[value="BD:MOU"], option[value="BD:MUN"], option[value="BD:MYM"], option[value="BD:NAO"], ' +
                                             'option[value="BD:NAR"], option[value="BD:NARG"], option[value="BD:NARD"], option[value="BD:NAT"], option[value="BD:NAW"], option[value="BD:NET"], ' +
                                             'option[value="BD:NIL"], option[value="BD:NOA"], option[value="BD:PAB"], option[value="BD:PAN"], option[value="BD:PAT"], option[value="BD:PIR"], ' +
                                             'option[value="BD:RAJB"], option[value="BD:RAJ"], option[value="BD:RAN"], option[value="BD:RANP"], option[value="BD:SAT"], option[value="BD:SHA"], ' +
                                             'option[value="BD:SHE"], option[value="BD:SIR"], option[value="BD:SUN"], option[value="BD:SYL"], option[value="BD:TAN"], option[value="BD:THA"],' +
                                             'option[value="CN:CN1"], option[value="CN:CN2"],option[value="CN"], option[value="CN:CN3"], option[value="CN:CN4"], option[value="CN:CN5"], ' +
                                             'option[value="CN:CN6"], option[value="CN:CN7"], option[value="CN:CN8"], option[value="CN:CN9"], option[value="CN:CN10"], option[value="CN:CN11"], ' +
                                             'option[value="CN:CN12"], option[value="CN:CN13"], option[value="CN:CN14"], option[value="CN:CN15"], option[value="CN:CN16"], option[value="CN:CN17"], ' +
                                             'option[value="CN:CN18"], option[value="CN:CN19"], option[value="CN:CN20"], option[value="CN:CN21"], option[value="CN:CN22"], option[value="CN:CN23"], ' +
                                             'option[value="CN:CN24"], option[value="CN:CN25"], option[value="CN:CN26"], option[value="CN:CN27"], option[value="CN:CN28"], option[value="CN:CN29"], ' +
                                             'option[value="CN:CN30"], option[value="CN:CN31"], option[value="CN:CN32"],option[value="HK:HONG KONG"], option[value="HK:KOWLOON"], ' +
                                             'option[value="HK:NEW TERRITORIES"], option[value="HK:KOWLOON"], option[value="HK:NEW TERRITORIES"],option[value="HK"], option[value="ID"], ' +
                                             'option[value="ID:AC"], option[value="ID:SU"], option[value="ID:SB"], option[value="ID:RI"], option[value="ID:KR"], option[value="ID:JA"], ' +
                                             'option[value="ID:SS"], option[value="ID:BB"], option[value="ID:BE"], option[value="ID:LA"], option[value="ID:JK"], option[value="ID:JB"], ' +
                                             'option[value="ID:BT"], option[value="ID:JT"], option[value="ID:JI"], option[value="ID:YO"], option[value="ID:BA"], option[value="ID:NB"], ' +
                                             'option[value="ID:NT"], option[value="ID:KB"], option[value="ID:KT"], option[value="ID:KI"], option[value="ID:KS"], option[value="ID:KU"], ' +
                                             'option[value="ID:SA"], option[value="ID:ST"], option[value="ID:SG"], option[value="ID:SR"], option[value="ID:SN"], option[value="ID:GO"], ' +
                                             'option[value="ID:MA"], option[value="ID:MU"], option[value="ID:PA"], option[value="ID:PB"],option[value="IN"], option[value="IN:AP"], ' +
                                             'option[value="IN:AR"], option[value="IN:AS"], option[value="IN:BR"], option[value="IN:CT"], option[value="IN:GA"], option[value="IN:GJ"], ' +
                                             'option[value="IN:HR"], option[value="IN:HP"], option[value="IN:JK"], option[value="IN:JH"], option[value="IN:KA"], option[value="IN:KL"], ' +
                                             'option[value="IN:MP"], option[value="IN:MH"], option[value="IN:MN"], option[value="IN:ML"], option[value="IN:MZ"], option[value="IN:NL"], ' +
                                             'option[value="IN:OR"], option[value="IN:PB"], option[value="IN:RJ"], option[value="IN:SK"], option[value="IN:TN"], option[value="IN:TS"], ' +
                                             'option[value="IN:TR"], option[value="IN:UK"], option[value="IN:UP"], option[value="IN:WB"], option[value="IN:AN"], option[value="IN:CH"], ' +
                                             'option[value="IN:DN"], option[value="IN:DD"], option[value="IN:DL"], option[value="IN:LD"], option[value="IN:PY"],option[value="IR"], ' +
                                             'option[value="IR:KHZ"], option[value="IR:THR"], option[value="IR:ILM"], option[value="IR:BHR"], option[value="IR:ADL"], option[value="IR:ESF"], ' +
                                             'option[value="IR:YZD"], option[value="IR:KRH"], option[value="IR:KRN"], option[value="IR:HDN"], option[value="IR:GZN"], option[value="IR:ZJN"], ' +
                                             'option[value="IR:LRS"], option[value="IR:ABZ"], option[value="IR:EAZ"], option[value="IR:WAZ"], option[value="IR:CHB"], option[value="IR:SKH"], ' +
                                             'option[value="IR:RKH"], option[value="IR:NKH"], option[value="IR:SMN"], option[value="IR:FRS"], option[value="IR:QHM"], option[value="IR:KRD"], ' +
                                             'option[value="IR:KBD"], option[value="IR:GLS"], option[value="IR:GIL"], option[value="IR:MZN"], option[value="IR:MKZ"], option[value="IR:HRZ"], ' +
                                             'option[value="IR:SBN"],option[value="JP"], option[value="JP:JP01"], option[value="JP:JP02"], option[value="JP:JP03"], option[value="JP:JP04"], ' +
                                             'option[value="JP:JP05"], option[value="JP:JP06"], option[value="JP:JP07"], option[value="JP:JP08"], option[value="JP:JP09"], option[value="JP:JP10"], ' +
                                             'option[value="JP:JP11"], option[value="JP:JP12"], option[value="JP:JP13"], option[value="JP:JP14"], option[value="JP:JP15"], option[value="JP:JP16"], ' +
                                             'option[value="JP:JP17"], option[value="JP:JP18"], option[value="JP:JP19"], option[value="JP:JP20"], option[value="JP:JP21"], option[value="JP:JP22"], ' +
                                             'option[value="JP:JP23"], option[value="JP:JP24"], option[value="JP:JP25"], option[value="JP:JP26"], option[value="JP:JP27"], option[value="JP:JP28"], ' +
                                             'option[value="JP:JP29"], option[value="JP:JP30"], option[value="JP:JP31"], option[value="JP:JP32"], option[value="JP:JP33"], option[value="JP:JP34"], ' +
                                             'option[value="JP:JP35"], option[value="JP:JP36"], option[value="JP:JP37"], option[value="JP:JP38"], option[value="JP:JP39"], option[value="JP:JP40"], ' +
                                             'option[value="JP:JP41"], option[value="JP:JP42"], option[value="JP:JP43"], option[value="JP:JP44"], option[value="JP:JP45"], option[value="JP:JP46"], ' +
                                             'option[value="JP:JP47"],option[value="MY"], option[value="MY:JHR"], option[value="MY:KDH"], option[value="MY:KTN"], option[value="MY:LBN"], ' +
                                             'option[value="MY:MLK"], option[value="MY:NSN"], option[value="MY:PHG"], option[value="MY:PNG"], option[value="MY:PRK"], option[value="MY:PLS"], ' +
                                             'option[value="MY:SBH"], option[value="MY:SWK"], option[value="MY:SGR"], option[value="MY:TRG"], option[value="MY:PJY"], option[value="MY:KUL"],' +
                                             'option[value="NP"], option[value="NP:BAG"], option[value="NP:BHE"], option[value="NP:DHA"], option[value="NP:GAN"], option[value="NP:JAN"], ' +
                                             'option[value="NP:KAR"], option[value="NP:KOS"], option[value="NP:LUM"], option[value="NP:MAH"], option[value="NP:MEC"], option[value="NP:NAR"], ' +
                                             'option[value="NP:RAP"], option[value="NP:SAG"], option[value="NP:SET"],option[value="PH"], option[value="PH:ABR"], option[value="PH:AGN"], ' +
                                             'option[value="PH:AGS"], option[value="PH:AKL"], option[value="PH:ALB"], option[value="PH:ANT"], option[value="PH:APA"], option[value="PH:AUR"], ' +
                                             'option[value="PH:BAS"], option[value="PH:BAN"], option[value="PH:BTN"], option[value="PH:BTG"], option[value="PH:BEN"], option[value="PH:BIL"], ' +
                                             'option[value="PH:BOH"], option[value="PH:BUK"], option[value="PH:BUL"], option[value="PH:CAG"], option[value="PH:CAN"], option[value="PH:CAS"], ' +
                                             'option[value="PH:CAM"], option[value="PH:CAP"], option[value="PH:CAT"], option[value="PH:CAV"], option[value="PH:CEB"], option[value="PH:COM"], ' +
                                             'option[value="PH:NCO"], option[value="PH:DAV"], option[value="PH:DAS"], option[value="PH:DAC"], option[value="PH:DAO"], option[value="PH:DIN"],' +
                                             'option[value="PH:EAS"], option[value="PH:GUI"], option[value="PH:IFU"], option[value="PH:ILN"], option[value="PH:ILS"], option[value="PH:ILI"], ' +
                                             'option[value="PH:ISA"], option[value="PH:KAL"], option[value="PH:LUN"], option[value="PH:LAG"], option[value="PH:LAN"], option[value="PH:LAS"], ' +
                                             'option[value="PH:LEY"], option[value="PH:MAG"], option[value="PH:MAD"], option[value="PH:MAS"], option[value="PH:MSC"], option[value="PH:MSR"], ' +
                                             'option[value="PH:MOU"], option[value="PH:NEC"], option[value="PH:NER"], option[value="PH:NSA"], option[value="PH:NUE"], option[value="PH:NUV"], ' +
                                             'option[value="PH:MDC"], option[value="PH:MDR"], option[value="PH:PLW"], option[value="PH:PAM"], option[value="PH:PAN"], option[value="PH:QUE"], ' +
                                             'option[value="PH:QUI"], option[value="PH:RIZ"], option[value="PH:ROM"], option[value="PH:WSA"], option[value="PH:SAR"], option[value="PH:SIQ"], ' +
                                             'option[value="PH:SOR"], option[value="PH:SCO"], option[value="PH:SLE"], option[value="PH:SUK"], option[value="PH:SLU"], option[value="PH:SUN"], ' +
                                             'option[value="PH:SUR"], option[value="PH:TAR"], option[value="PH:TAW"], option[value="PH:ZMB"], option[value="PH:ZAN"], option[value="PH:ZAS"], ' +
                                             'option[value="PH:ZSI"], option[value="PH:00"],option[value="TH"], option[value="TH:TH-37"], option[value="TH:TH-15"], option[value="TH:TH-14"], ' +
                                             'option[value="TH:TH-10"], option[value="TH:TH-38"], option[value="TH:TH-31"], option[value="TH:TH-24"], option[value="TH:TH-18"], ' +
                                             'option[value="TH:TH-36"], option[value="TH:TH-22"], option[value="TH:TH-50"], option[value="TH:TH-57"], option[value="TH:TH-20"], ' +
                                             'option[value="TH:TH-86"], option[value="TH:TH-46"], option[value="TH:TH-62"], option[value="TH:TH-71"], option[value="TH:TH-40"],' +
                                             ' option[value="TH:TH-81"], option[value="TH:TH-52"], option[value="TH:TH-51"], option[value="TH:TH-42"], option[value="TH:TH-16"], ' +
                                             'option[value="TH:TH-58"], option[value="TH:TH-44"], option[value="TH:TH-49"], option[value="TH:TH-26"], option[value="TH:TH-73"], ' +
                                             'option[value="TH:TH-48"], option[value="TH:TH-30"], option[value="TH:TH-60"], option[value="TH:TH-80"], option[value="TH:TH-55"], ' +
                                             'option[value="TH:TH-96"], option[value="TH:TH-39"], option[value="TH:TH-43"], option[value="TH:TH-12"], option[value="TH:TH-13"], ' +
                                             'option[value="TH:TH-94"], option[value="TH:TH-82"], option[value="TH:TH-93"], option[value="TH:TH-56"], option[value="TH:TH-67"], ' +
                                             'option[value="TH:TH-76"], option[value="TH:TH-66"], option[value="TH:TH-65"], option[value="TH:TH-54"], option[value="TH:TH-83"], ' +
                                             'option[value="TH:TH-25"], option[value="TH:TH-77"], option[value="TH:TH-85"], option[value="TH:TH-70"], option[value="TH:TH-21"], ' +
                                             'option[value="TH:TH-45"], option[value="TH:TH-27"], option[value="TH:TH-47"], option[value="TH:TH-11"], option[value="TH:TH-74"], ' +
                                             'option[value="TH:TH-75"], option[value="TH:TH-19"], option[value="TH:TH-91"], option[value="TH:TH-17"], option[value="TH:TH-33"], ' +
                                             'option[value="TH:TH-90"], option[value="TH:TH-64"], option[value="TH:TH-72"], option[value="TH:TH-84"], option[value="TH:TH-32"], ' +
                                             'option[value="TH:TH-63"], option[value="TH:TH-92"], option[value="TH:TH-23"], option[value="TH:TH-34"], option[value="TH:TH-41"], ' +
                                             'option[value="TH:TH-61"], option[value="TH:TH-53"], option[value="TH:TH-95"], option[value="TH:TH-35"]' ).attr( "selected", "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        $( 'body' ).on( 'click', '.select_oceania_states', function () {
            $( this ).closest( 'div' ).find( 'option[value="AU"], option[value="AU:ACT"], option[value="AU:NSW"], option[value="AU:NT"], option[value="AU:QLD"], ' +
                                             'option[value="AU:SA"], option[value="AU:TAS"], option[value="AU:VIC"], option[value="AU:WA"],option[value="NZ"], option[value="NZ:NL"], ' +
                                             'option[value="NZ:AK"], option[value="NZ:WA"], option[value="NZ:BP"], option[value="NZ:TK"], option[value="NZ:GI"], option[value="NZ:HB"], ' +
                                             'option[value="NZ:MW"], option[value="NZ:WE"], option[value="NZ:NS"], option[value="NZ:MB"], option[value="NZ:TM"], option[value="NZ:WC"], ' +
                                             'option[value="NZ:CT"], option[value="NZ:OT"], option[value="NZ:SL"]' ).attr( "selected", "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        $( 'body' ).on( 'click', '.select_none', function () {
            $( this ).closest( 'div' ).find( 'select option' ).removeAttr( "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        $( 'body' ).on( 'click', '.select_all', function () {
            $( this ).closest( 'div' ).find( 'select option' ).attr( "selected", "selected" );
            $( this ).closest( 'div' ).find( 'select' ).trigger( 'change' );
            return false;
        } );
        
        /**
         * Select availability
         */
        $( 'select.availability' ).change( function () {
            if ( $( this ).val() === 'all' ) {
                $( this ).closest( 'tr' ).next( 'tr' ).hide();
                $( this ).closest( 'tr' ).next( 'tr' ).next( 'tr' ).hide();
                $( this ).closest( 'tr' ).next( 'tr' ).next( 'tr' ).next( 'tr' ).hide();
                $( this ).closest( 'tr' ).next( 'tr' ).next( 'tr' ).next( 'tr' ).next( 'tr' ).hide();
            } else if ( $( this ).val() === 'specific' ) {
                $( this ).closest( 'tr' ).next( 'tr' ).show();
                $( this ).closest( 'tr' ).next( 'tr' ).next( 'tr' ).hide();
                $( this ).closest( 'tr' ).next( 'tr' ).next( 'tr' ).next( 'tr' ).hide();
                $( this ).closest( 'tr' ).next( 'tr' ).next( 'tr' ).next( 'tr' ).next( 'tr' ).hide();
            } else if ( $( this ).val() === 'Countrybase' ) {
                $( this ).closest( 'tr' ).next( 'tr' ).hide();
                $( this ).closest( 'tr' ).next( 'tr' ).next( 'tr' ).show();
                $( this ).closest( 'tr' ).next( 'tr' ).next( 'tr' ).next( 'tr' ).show();
                $( this ).closest( 'tr' ).next( 'tr' ).next( 'tr' ).next( 'tr' ).next( 'tr' ).show();
            } else {
                $( this ).closest( 'tr' ).next( 'tr' ).hide();
                $( this ).closest( 'tr' ).next( 'tr' ).next( 'tr' ).hide();
                $( this ).closest( 'tr' ).next( 'tr' ).next( 'tr' ).next( 'tr' ).hide();
                $( this ).closest( 'tr' ).next( 'tr' ).next( 'tr' ).next( 'tr' ).next( 'tr' ).hide();
            }
        } ).change();
        /* Shipping Zone Section */
        
        
        $( '#extra_product_cost, .price-field' ).keypress( function ( e ) {
            var regex = new RegExp( "^[0-9.]+$" );
            var str   = String.fromCharCode( ! e.charCode ? e.which : e.charCode );
            if ( regex.test( str ) ) {
                return true;
            }
            e.preventDefault();
            return false;
        } );
        
        
        /*Extra Validation*/
        numberValidateForAdvanceRules();
        
        /*how to apply start */
        howToApply( 'load' );
        
        $( 'body' ).on( 'change', '#how_to_apply', function () {
            howToApply( 'onchange' );
        } );
        
        function howToApply( action ) {
            var current_val         = $( '#how_to_apply' ).val();
            var price_cartqty_based = $( '#price_cartqty_based' ).val();
            
            if ( current_val == 'apply_per_qty' ) {
                if ( $( '#apply_per_qty_div' ).length ) {
                    $( "#apply_per_qty_div" ).show();
                    
                    $( "#apply_per_qty_div #extra_product_cost" ).prop( 'required', true );
                    
                    if ( action == 'onchange' ) {
                        $( 'html,body' ).animate( {
                            scrollTop: $( "#apply_per_qty_div" ).offset().top
                        }, '5000' );
                    }
                }
                
                if ( $( '#apm_wrap' ).length ) {
                    $( "#apm_wrap" ).hide();
                }
                
                if ( jQuery( ".adv-pricing-rules .advance-shipping-method-table" ).is( ":hidden" ) ) {
                    jQuery( '.adv-pricing-rules .advance-shipping-method-table tr td input' ).each( function () {
                        $( this ).removeAttr( 'required' );
                    } );
                }
                
            } else if ( current_val == 'advance_shipping_rules' ) {
                $( "#apply_per_qty_div" ).hide();
                
                if ( $( '#apm_wrap' ).length ) {
                    $( "#apm_wrap" ).show();
                    
                    if ( action == 'onchange' ) {
                        $( 'html,body' ).animate( {
                            scrollTop: $( "#apm_wrap" ).offset().top
                        }, '5000' );
                    }
                }
                
                if ( $( '#apply_per_qty_div' ).length ) {
                    $( "#apply_per_qty_div" ).hide();
                    $( "#apply_per_qty_div #extra_product_cost" ).prop( 'required', false );
                }
            } else {
                if ( $( '#apply_per_qty_div' ).length ) {
                    $( "#apply_per_qty_div" ).hide();
                    $( "#apply_per_qty_div #extra_product_cost" ).prop( 'required', false );
                }
                
                if ( $( '#apm_wrap' ).length ) {
                    $( "#apm_wrap" ).hide();
                }
                
                if ( jQuery( ".adv-pricing-rules .advance-shipping-method-table" ).is( ":hidden" ) ) {
                    jQuery( '.adv-pricing-rules .advance-shipping-method-table tr td input' ).each( function () {
                        $( this ).removeAttr( 'required' );
                    } );
                }
            }
            
            
        }
        
        var how_to_apply = $( '#how_to_apply' ).val();
        addProductFieldAutomatically( how_to_apply, 'load' );
        //addProductFieldAutomatically( 'load' );
        $( 'body' ).on( 'change', '#price_cartqty_based', function () {
            var how_to_apply = $( '#how_to_apply' ).val();
            addProductFieldAutomatically( how_to_apply, 'onchange' );
        } );
        
        function addProductFieldAutomatically( how_to_apply, action ) {
            if (how_to_apply == 'apply_per_qty') {
                var price_cartqty_based = $( '#price_cartqty_based' ).val();
                if ( price_cartqty_based == 'qty_product_based' ) {
                    var return_falg;
                    var already_added_tr_length = $( '#tbl-shipping-method tr' ).length;
                    if ( already_added_tr_length > 0 ) {
                        var check_val = [];
                        $( '#tbl-shipping-method .product_fees_conditions_condition' ).each( function () {
                            check_val.push( $( this ).val() );
                        } );
                        if ( check_val != "" ) {
                            if ( jQuery.inArray( 'product', check_val ) != '-1' ||
                                 jQuery.inArray( 'variableproduct', check_val ) != '-1' ||
                                 jQuery.inArray( 'category', check_val ) != '-1' ||
                                 jQuery.inArray( 'tag', check_val ) != '-1' ||
                                 jQuery.inArray( 'sku', check_val ) != '-1' ) {
                                return_falg = 0;
                            } else {
                                return_falg = 1;
                            }
                        }
                        
                    } else {
                        if ( current_action == 'add' ) {
                            $( '#tbl-shipping-method tr:last .product_fees_conditions_condition' ).val( 'product' );
                            var selcted_drp_val = $( "#tbl-shipping-method tr:last .product_fees_conditions_condition" ).val();
                            condition_values( "#tbl-shipping-method tr:last .product_fees_conditions_condition" );
                        }
                    }
                    
                    if ( return_falg == 1 ) {
                        $( "#fee-add-field" ).trigger( "click" );
                        $( '#tbl-shipping-method tr:last .product_fees_conditions_condition' ).val( 'product' );
                        var selcted_drp_val = $( "#tbl-shipping-method tr:last .product_fees_conditions_condition" ).val();
                        condition_values( "#tbl-shipping-method tr:last .product_fees_conditions_condition" );
                    }
                }
            }
        }
        
        
        /*how to apply end */
        
        function get_all_condition() {
            return [
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.location_specific},
                    "options": [
                        {"name": coditional_vars.country, "attributes": {"value": "country"}},
                        {"name": coditional_vars.state, "attributes": {"value": "state"}},
                        {"name": coditional_vars.postcode, "attributes": {"value": "postcode"}},
                        {"name": coditional_vars.zone, "attributes": {"value": "zone"}},
                    ]
                },
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.product_specific},
                    "options": [
                        {"name": coditional_vars.cart_contains_product, "attributes": {"value": "product"}},
                        {
                            "name": coditional_vars.cart_contains_variable_product,
                            "attributes": {"value": "variableproduct"}
                        },
                        {"name": coditional_vars.cart_contains_category_product, "attributes": {"value": "category"}},
                        {"name": coditional_vars.cart_contains_tag_product, "attributes": {"value": "tag"}},
                        {"name": coditional_vars.cart_contains_sku_product, "attributes": {"value": "sku"}},
                        {"name": coditional_vars.cart_contains_product_qty, "attributes": {"value": "product_qty"}},
                    ]
                },
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.attribute_specific},
                    "options": JSON.parse( coditional_vars.attribute_list )
                },
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.user_specific},
                    "options": [
                        {"name": coditional_vars.user, "attributes": {"value": "user"}},
                        {"name": coditional_vars.user_role, "attributes": {"value": "user_role"}}
                    ]
                },
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.cart_specific},
                    "options": [
                        {"name": coditional_vars.cart_subtotal_before_discount, "attributes": {"value": "cart_total"}},
                        {
                            "name": coditional_vars.cart_subtotal_after_discount,
                            "attributes": {"value": "cart_totalafter"}
                        },
                        {"name": coditional_vars.quantity, "attributes": {"value": "quantity"}},
                        {"name": coditional_vars.weight, "attributes": {"value": "weight"}},
                        {"name": coditional_vars.coupon, "attributes": {"value": "coupon"}},
                        {"name": coditional_vars.shipping_class, "attributes": {"value": "shipping_class"}}
                    ]
                },
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.checkout_specific},
                    "options": [
                        {"name": coditional_vars.payment_method, "attributes": {"value": "payment_method"}},
                    ]
                },
            ];
        }
        
        function condition_values( element ) {
            var condition = $( element ).val();
            var count     = $( element ).attr( 'rel-id' );
            var column    = jQuery( '#column_' + count ).get( 0 );
            jQuery( column ).empty();
            var loader = document.createElement( 'img' );
            loader     = setAllAttributes( loader, {'src': coditional_vars.plugin_url + 'images/ajax-loader.gif'} );
            column.appendChild( loader );
            
            $.ajax( {
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'afrsfwa_product_fees_conditions_values_ajax',
                    'condition': condition,
                    'count': count
                },
                contentType: "application/json",
                success: function ( response ) {
                    var condition_values;
                    jQuery( '.product_fees_conditions_is_' + count ).empty();
                    var column       = jQuery( '#column_' + count ).get( 0 );
                    var condition_is = jQuery( '.product_fees_conditions_is_' + count ).get( 0 );
                    if ( condition == 'cart_total'
                         || condition == 'quantity'
                         || condition == 'product_qty'
                         || condition == 'cart_totalafter'
                         || condition == 'weight'
                    
                    ) {
                        condition_is = insertOptions( condition_is, condition_types( true ) );
                    } else {
                        condition_is = insertOptions( condition_is, condition_types( false ) );
                    }
                    jQuery( '.product_fees_conditions_is_' + count ).trigger( "chosen:updated" );
                    jQuery( column ).empty();
                    
                    var condition_values_id = '';
                    var extra_class         = '';
                    if ( condition == 'product' ) {
                        condition_values_id = 'product-filter-' + count;
                        extra_class         = 'product_fees_conditions_values_product';
                    }
                    
                    if ( condition == 'variableproduct' ) {
                        condition_values_id = 'var-product-filter-' + count;
                        extra_class         = 'product_fees_conditions_values_var_product';
                    }
                    
                    
                    if ( isJson( response ) ) {
                        condition_values = document.createElement( "select" );
                        condition_values = setAllAttributes( condition_values, {
                            "name": "fees[product_fees_conditions_values][value_" + count + "][]",
                            "class": "afrsm_select product_fees_conditions_values product_fees_conditions_values_" + count + " multiselect2 " + extra_class,
                            "multiple": "multiple",
                            "id": condition_values_id
                        } );
                        column.appendChild( condition_values );
                        var data         = JSON.parse( response );
                        condition_values = insertOptions( condition_values, data );
                    } else {
                        var input_extra_class;
                        if ( condition == 'quantity' ) {
                            input_extra_class = ' qty-class'
                        }
                        if ( condition == 'weight' ) {
                            input_extra_class = ' weight-class'
                        }
                        if ( condition == 'cart_total' || condition == 'cart_totalafter' ) {
                            input_extra_class = ' price-class'
                        }
                        
                        condition_values = document.createElement( jQuery.trim( response ) );
                        condition_values = setAllAttributes( condition_values, {
                            "name": "fees[product_fees_conditions_values][value_" + count + "]",
                            "class": "product_fees_conditions_values" + input_extra_class,
                            "type": "text",
                            
                        } );
                        column.appendChild( condition_values );
                    }
                    column         = $( '#column_' + count ).get( 0 );
                    var input_node = document.createElement( 'input' );
                    input_node     = setAllAttributes( input_node, {
                        'type': 'hidden',
                        'name': 'condition_key[value_' + count + '][]',
                        'value': ''
                    } );
                    column.appendChild( input_node );
                    
                    
                    if ( condition == 'product'
                         || condition == 'category'
                         || condition == 'weight'
                         || condition == 'cart_totalafter'
                         || condition == 'payment_method'
                    ) {
                        var p_node      = document.createElement( 'p' );
                        var b_node      = document.createElement( 'b' );
                        b_node          = setAllAttributes( b_node, {
                            'style': 'color: #dc3232;',
                        } );
                        var b_text_node = document.createTextNode( coditional_vars.note );
                        b_node.appendChild( b_text_node );
                        
                        var text_node;
                        if ( condition == 'product' ) {
                            var text_node = document.createTextNode( coditional_vars.cart_contains_product_msg );
                        }
                        if ( condition == 'category' ) {
                            var text_node = document.createTextNode( coditional_vars.cart_contains_category_msg );
                        }
                        
                        if ( condition == 'weight' ) {
                            var text_node = document.createTextNode( coditional_vars.weight_msg );
                        }
                        if ( condition == 'cart_totalafter' ) {
                            var text_node = document.createTextNode( coditional_vars.cart_subtotal_after_discount_msg );
                        }
                        if ( condition == 'payment_method' ) {
                            var text_node = document.createTextNode( coditional_vars.payment_method_msg );
                        }
                        var a_node = document.createElement( 'a' );
                        if ( condition == 'payment_method' ) {
                            a_node = setAllAttributes( a_node, {
                                'href': coditional_vars.list_page_url,
                                'target': '_blank'
                            } );
                        } else {
                            a_node = setAllAttributes( a_node, {
                                'href': coditional_vars.doc_url,
                                'target': '_blank'
                            } );
                        }
                        var a_text_node = document.createTextNode( coditional_vars.click_here );
                        a_node.appendChild( a_text_node );
                        p_node.appendChild( b_node );
                        p_node.appendChild( text_node );
                        p_node.appendChild( a_node );
                        
                        column.appendChild( p_node );
                        
                    }
                    
                    
                    jQuery( ".multiselect2" ).select2();
                    productFilter();
                    
                    
                    varproductFilter();
                    getProductListBasedOnThreeCharAfterUpdate();
                    
                    numberValidateForAdvanceRules();
                }
            } );
        }
        
        function condition_types( text = false ) {
            if ( text == true ) {
                return [
                    {"name": coditional_vars.equal_to, "attributes": {"value": "is_equal_to"}},
                    {"name": coditional_vars.less_or_equal_to, "attributes": {"value": "less_equal_to"}},
                    {"name": coditional_vars.less_than, "attributes": {"value": "less_then"}},
                    {"name": coditional_vars.greater_or_equal_to, "attributes": {"value": "greater_equal_to"}},
                    {"name": coditional_vars.greater_than, "attributes": {"value": "greater_then"}},
                    {"name": coditional_vars.not_equal_to, "attributes": {"value": "not_in"}},
                ];
            } else {
                return [
                    {"name": coditional_vars.equal_to, "attributes": {"value": "is_equal_to"}},
                    {"name": coditional_vars.not_equal_to, "attributes": {"value": "not_in"}},
                ];
                
            }
            
        }
        
        function isJson( str ) {
            try {
                JSON.parse( str );
            } catch ( err ) {
                return false;
            }
            return true;
        }
        
        $( '.shipping-method-class input[name="afrsm_save"]' ).on( 'click', function ( e ) {
            validation( e );
        } );
        
        function validation( e ) {
            var validation_color_code         = '#dc3232';
            var default_color_code            = '#0085BA';
            var fees_pricing_rules_validation = true;
            var current_val                   = $( '#how_to_apply' ).val();
            
            if ( current_val == 'advance_shipping_rules' ) {
                if ( $( '.pricing_rules:visible' ).length != 0 ) {
                    //set flag default to n
                    var submit_prd_form_flag = true;
                    var submit_prd_flag      = false;
                    
                    var submit_prd_subtotal_form_flag = true;
                    var submit_prd_subtotal_flag      = false;
                    
                    var submit_cat_form_flag = true;
                    var submit_cat_flag      = false;
                    
                    var submit_cat_subtotal_form_flag = true;
                    var submit_cat_subtotal_flag      = false;
                    
                    var submit_total_cart_qty_form_flag = true;
                    var submit_total_cart_qty_flag      = false;
                    
                    var submit_product_weight_form_flag = true;
                    var submit_product_weight_flag      = false;
                    
                    var submit_category_weight_form_flag = true;
                    var submit_category_weight_flag      = false;
                    
                    var submit_total_cart_weight_form_flag = true;
                    var submit_total_cart_weight_flag      = false;
                    
                    var submit_total_cart_subtotal_form_flag = true;
                    var submit_total_cart_subtotal_flag      = false;
                    
                    var submit_shipping_class_subtotal_form_flag = true;
                    var submit_shipping_class_subtotal_flag      = false;
                    
                    var prd_val_arr                     = [];
                    var prd_subtotal_val_arr            = [];
                    var cat_val_arr                     = [];
                    var cat_subtotal_val_arr            = [];
                    var total_cart_qty_val_arr          = [];
                    var product_weight_val_arr          = [];
                    var category_weight_val_arr         = [];
                    var total_cart_weight_val_arr       = [];
                    var total_cart_subtotal_val_arr     = [];
                    var shipping_class_subtotal_val_arr = [];
                    
                    var no_one_product_row_flag;
                    var no_one_product_subtotal_row_flag;
                    var no_one_category_row_flag;
                    var no_one_category_subtotal_row_flag;
                    var no_one_total_cart_qty_row_flag;
                    var no_one_product_weight_row_flag;
                    var no_one_category_weight_row_flag;
                    var no_one_total_cart_weight_row_flag;
                    var no_one_total_cart_subtotal_row_flag;
                    var no_one_shipping_class_subtotal_row_flag;
                    
                    //Start loop each row of AP Product rules
                    no_one_product_row_flag                 = $( "#tbl_ap_product_method tr.ap_product_row_tr" ).length;
                    no_one_product_subtotal_row_flag        = $( "#tbl_ap_product_subtotal_method tr.ap_product_subtotal_row_tr" ).length;
                    no_one_category_row_flag                = $( "#tbl_ap_category_method tr.ap_category_row_tr" ).length;
                    no_one_category_subtotal_row_flag       = $( "#tbl_ap_category_subtotal_method tr.ap_category_subtotal_row_tr" ).length;
                    no_one_total_cart_qty_row_flag          = $( "#tbl_ap_total_cart_qty_method tr.ap_total_cart_qty_row_tr" ).length;
                    no_one_product_weight_row_flag          = $( "#tbl_ap_product_weight_method tr.ap_product_weight_row_tr" ).length;
                    no_one_category_weight_row_flag         = $( "#tbl_ap_category_weight_method tr.ap_category_weight_row_tr" ).length;
                    no_one_total_cart_weight_row_flag       = $( "#tbl_ap_total_cart_weight_method tr.ap_total_cart_weight_row_tr" ).length;
                    no_one_total_cart_subtotal_row_flag     = $( "#tbl_ap_total_cart_subtotal_method tr.ap_total_cart_subtotal_row_tr" ).length;
                    no_one_shipping_class_subtotal_row_flag = $( "#tbl_ap_shipping_class_subtotal_method tr.ap_shipping_class_subtotal_row_tr" ).length;
                    
                    var count_total_tr = no_one_product_row_flag +
                                         no_one_product_subtotal_row_flag +
                                         no_one_category_row_flag +
                                         no_one_category_subtotal_row_flag +
                                         no_one_total_cart_qty_row_flag +
                                         no_one_product_weight_row_flag +
                                         no_one_category_weight_row_flag +
                                         no_one_total_cart_weight_row_flag +
                                         no_one_total_cart_subtotal_row_flag +
                                         no_one_shipping_class_subtotal_row_flag;
                    
                    if ( $( "#tbl_ap_product_method tr.ap_product_row_tr" ).length ) {
                        $( '#tbl_ap_product_method tr.ap_product_row_tr' ).each( function ( index, item ) {
                            //initialize variables
                            var min_qty          = '',
                              max_qty      = '';
                            var product_id_count = '';
                            var product_price    = 0;
                            var tr_id            = jQuery( this ).attr( 'id' );
                            var tr_int_id        = tr_id.substr( tr_id.lastIndexOf( "_" ) + 1 );
                            var max_qty_flag     = true;
                            
                            //check product empty or not
                            if ( jQuery( this ).find( '[name="fees[ap_product_fees_conditions_condition][' + tr_int_id + '][]"]' ).length ) {
                                product_id_count = jQuery( this ).find( '[name="fees[ap_product_fees_conditions_condition][' + tr_int_id + '][]"]' ).find( 'option:selected' ).length;
                                if ( product_id_count == 0 ) {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '' );
                                }
                            }
                            //check product price empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_price_product][]"]' ).length ) {
                                product_price = $( this ).find( '[name="fees[ap_fees_ap_price_product][]"]' ).val();
                                if ( product_price == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_product][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_product][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if min quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_prd_min_qty][]"]' ).length ) {
                                min_qty = $( this ).find( '[name="fees[ap_fees_ap_prd_min_qty][]"]' ).val();
                                if ( min_qty == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_prd_min_qty][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_prd_min_qty][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if max quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_prd_max_qty][]"]' ).length ) {
                                max_qty = $( this ).find( '[name="fees[ap_fees_ap_prd_max_qty][]"]' ).val();
                                if ( max_qty != '' && min_qty != '' ) {
                                    max_qty = parseInt( max_qty );
                                    if ( min_qty > max_qty ) {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_prd_max_qty][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                        max_qty_flag = false;
                                    } else {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_prd_max_qty][]"]' ) ).css( 'border', '' );
                                    }
                                }
                            }
                            
                            if ( product_id_count == 0 && min_qty == '' && product_price == '' ) {
                                submit_prd_flag = false;
                            } else if ( product_id_count == 0 ) {
                                submit_prd_flag = false;
                            } else if ( min_qty == '' ) {
                                submit_prd_flag = false;
                            } else if ( max_qty_flag == false ) {
                                submit_prd_flag = false;
                                displayMsg( 'message_prd_qty', coditional_vars.min_max_qty_error );
                            } else if ( product_price == "" ) {
                                submit_prd_flag = false;
                            } else {
                                submit_prd_flag = true;
                            }
                            
                            prd_val_arr[tr_int_id] = submit_prd_flag;
                            
                        } );
                        
                        if ( prd_val_arr != "" ) {
                            var current_tab_id = jQuery( $( '#tbl_ap_product_method tr.ap_product_row_tr' ).parent().parent().parent().parent() ).attr( 'id' );
                            if ( jQuery.inArray( false, prd_val_arr ) !== -1 ) {
                                submit_prd_form_flag = false;
                                changeColorValidation( current_tab_id, false, validation_color_code );
                            } else {
                                submit_prd_form_flag = true;
                                changeColorValidation( current_tab_id, true, default_color_code );
                            }
                        }
                    }
                    
                    if ( $( "#tbl_ap_product_subtotal_method tr.ap_product_subtotal_row_tr" ).length ) {
                        $( '#tbl_ap_product_subtotal_method tr.ap_product_subtotal_row_tr' ).each( function ( index, item ) {
                            //initialize variables
                            var min_qty          = '',
                              max_qty      = '';
                            var product_id_count = '';
                            var product_price    = 0;
                            var tr_id            = jQuery( this ).attr( 'id' );
                            var tr_int_id        = tr_id.substr( tr_id.lastIndexOf( "_" ) + 1 );
                            var max_qty_flag     = true;
                            
                            //check product empty or not
                            if ( jQuery( this ).find( '[name="fees[ap_product_subtotal_fees_conditions_condition][' + tr_int_id + '][]"]' ).length ) {
                                product_id_count = jQuery( this ).find( '[name="fees[ap_product_subtotal_fees_conditions_condition][' + tr_int_id + '][]"]' ).find( 'option:selected' ).length;
                                if ( product_id_count == 0 ) {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '' );
                                }
                            }
                            //check product price empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_price_product_subtotal][]"]' ).length ) {
                                product_price = $( this ).find( '[name="fees[ap_fees_ap_price_product_subtotal][]"]' ).val();
                                if ( product_price == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_product_subtotal][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_product_subtotal][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if min quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_product_subtotal_min_subtotal][]"]' ).length ) {
                                min_qty = $( this ).find( '[name="fees[ap_fees_ap_product_subtotal_min_subtotal][]"]' ).val();
                                if ( min_qty == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_product_subtotal_min_subtotal][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_product_subtotal_min_subtotal][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if max quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_product_subtotal_max_subtotal][]"]' ).length ) {
                                max_qty = $( this ).find( '[name="fees[ap_fees_ap_product_subtotal_max_subtotal][]"]' ).val();
                                if ( max_qty != '' && min_qty != '' ) {
                                    max_qty = parseInt( max_qty );
                                    if ( min_qty > max_qty ) {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_product_subtotal_max_subtotal][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                        max_qty_flag = false;
                                    } else {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_product_subtotal_max_subtotal][]"]' ) ).css( 'border', '' );
                                    }
                                }
                            }
                            
                            if ( product_id_count == 0 && min_qty == '' && product_price == '' ) {
                                submit_prd_subtotal_flag = false;
                            } else if ( product_id_count == 0 ) {
                                submit_prd_subtotal_flag = false;
                            } else if ( min_qty == '' ) {
                                submit_prd_subtotal_flag = false;
                            } else if ( max_qty_flag == false ) {
                                submit_prd_subtotal_flag = false;
                                displayMsg( 'message_prd_subtotal', coditional_vars.min_max_subtotal_error );
                            } else if ( product_price == "" ) {
                                submit_prd_subtotal_flag = false;
                            } else {
                                submit_prd_subtotal_flag = true;
                            }
                            
                            prd_subtotal_val_arr[tr_int_id] = submit_prd_subtotal_flag;
                            
                        } );
                        
                        if ( prd_subtotal_val_arr != "" ) {
                            var current_tab_id = jQuery( $( '#tbl_ap_product_subtotal_method tr.ap_product_subtotal_row_tr' ).parent().parent().parent().parent() ).attr( 'id' );
                            if ( jQuery.inArray( false, prd_subtotal_val_arr ) !== -1 ) {
                                submit_prd_subtotal_form_flag = false;
                                changeColorValidation( current_tab_id, false, validation_color_code );
                            } else {
                                submit_prd_subtotal_form_flag = true;
                                changeColorValidation( current_tab_id, true, default_color_code );
                            }
                        }
                    }
                    //End loop each row of AP Product rules
                    
                    //Start loop each row of AP Category rules
                    if ( $( "#tbl_ap_category_method tr.ap_category_row_tr" ).length ) {
                        $( '#tbl_ap_category_method tr.ap_category_row_tr' ).each( function ( index, item ) {
                            //initialize variables
                            var category_id_count = '';
                            var cat_product_price = '';
                            var min_qty           = '',
                              max_qty       = '';
                            var cat_tr_id         = jQuery( this ).attr( 'id' );
                            var cat_tr_int_id     = cat_tr_id.substr( cat_tr_id.lastIndexOf( "_" ) + 1 );
                            var max_qty_flag      = true;
                            
                            //check product empty or not
                            if ( $( this ).find( '[name="fees[ap_category_fees_conditions_condition][' + cat_tr_int_id + '][]"]' ).length ) {
                                category_id_count = jQuery( this ).find( '[name="fees[ap_category_fees_conditions_condition][' + cat_tr_int_id + '][]"]' ).find( 'option:selected' ).length;
                                if ( category_id_count == 0 ) {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '' );
                                }
                            }
                            //check product price empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_price_category][]"]' ).length ) {
                                cat_product_price = $( this ).find( '[name="fees[ap_fees_ap_price_category][]"]' ).val();
                                if ( cat_product_price == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_category][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_category][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if min quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_cat_min_qty][]"]' ).length ) {
                                min_qty = $( this ).find( '[name="fees[ap_fees_ap_cat_min_qty][]"]' ).val();
                                if ( min_qty == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_cat_min_qty][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_cat_min_qty][]"]' ) ).css( 'border', '' );
                                }
                            }
                            
                            //check if max quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_cat_max_qty][]"]' ).length ) {
                                max_qty = $( this ).find( '[name="fees[ap_fees_ap_cat_max_qty][]"]' ).val();
                                if ( max_qty != '' && min_qty != '' ) {
                                    max_qty = parseInt( max_qty );
                                    if ( min_qty > max_qty ) {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_cat_max_qty][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                        max_qty_flag = false;
                                    } else {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_cat_max_qty][]"]' ) ).css( 'border', '' );
                                    }
                                }
                            }
                            
                            if ( category_id_count == 0 && min_qty == '' && cat_product_price == '' ) {
                                submit_cat_flag = false;
                            } else if ( category_id_count == 0 ) {
                                submit_cat_flag = false;
                            } else if ( min_qty == '' ) {
                                submit_cat_flag = false;
                            } else if ( max_qty_flag == false ) {
                                submit_cat_flag = false;
                                displayMsg( 'message_cat_qty', coditional_vars.min_max_qty_error );
                            } else if ( cat_product_price == '' ) {
                                submit_cat_flag = false;
                            } else {
                                submit_cat_flag = true;
                            }
                            
                            cat_val_arr[cat_tr_int_id] = submit_cat_flag;
                            
                        } );
                        
                        if ( cat_val_arr != "" ) {
                            var current_tab_id = jQuery( $( '#tbl_ap_category_method tr.ap_category_row_tr' ).parent().parent().parent().parent() ).attr( 'id' );
                            if ( jQuery.inArray( false, cat_val_arr ) !== -1 ) {
                                submit_cat_form_flag = false;
                                changeColorValidation( current_tab_id, false, validation_color_code );
                            } else {
                                submit_cat_form_flag = true;
                                changeColorValidation( current_tab_id, true, default_color_code );
                            }
                        }
                    }
                    
                    if ( $( "#tbl_ap_category_subtotal_method tr.ap_category_subtotal_row_tr" ).length ) {
                        $( '#tbl_ap_category_subtotal_method tr.ap_category_subtotal_row_tr' ).each( function ( index, item ) {
                            //initialize variables
                            var category_id_count = '';
                            var cat_product_price = '';
                            var min_qty           = '',
                              max_qty       = '';
                            var cat_tr_id         = jQuery( this ).attr( 'id' );
                            var cat_tr_int_id     = cat_tr_id.substr( cat_tr_id.lastIndexOf( "_" ) + 1 );
                            var max_qty_flag      = true;
                            
                            //check product empty or not
                            if ( $( this ).find( '[name="fees[ap_category_subtotal_fees_conditions_condition][' + cat_tr_int_id + '][]"]' ).length ) {
                                category_id_count = jQuery( this ).find( '[name="fees[ap_category_subtotal_fees_conditions_condition][' + cat_tr_int_id + '][]"]' ).find( 'option:selected' ).length;
                                if ( category_id_count == 0 ) {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '' );
                                }
                            }
                            //check product price empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_price_category_subtotal][]"]' ).length ) {
                                cat_product_price = $( this ).find( '[name="fees[ap_fees_ap_price_category_subtotal][]"]' ).val();
                                if ( cat_product_price == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_category_subtotal][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_category_subtotal][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if min quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_category_subtotal_min_subtotal][]"]' ).length ) {
                                min_qty = $( this ).find( '[name="fees[ap_fees_ap_category_subtotal_min_subtotal][]"]' ).val();
                                if ( min_qty == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_category_subtotal_min_subtotal][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_category_subtotal_min_subtotal][]"]' ) ).css( 'border', '' );
                                }
                            }
                            
                            //check if max quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_category_subtotal_max_subtotal][]"]' ).length ) {
                                max_qty = $( this ).find( '[name="fees[ap_fees_ap_category_subtotal_max_subtotal][]"]' ).val();
                                if ( max_qty != '' && min_qty != '' ) {
                                    max_qty = parseInt( max_qty );
                                    if ( min_qty > max_qty ) {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_category_subtotal_max_subtotal][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                        max_qty_flag = false;
                                    } else {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_category_subtotal_max_subtotal][]"]' ) ).css( 'border', '' );
                                    }
                                }
                            }
                            
                            if ( category_id_count == 0 && min_qty == '' && cat_product_price == '' ) {
                                submit_cat_subtotal_flag = false;
                            } else if ( category_id_count == 0 ) {
                                submit_cat_subtotal_flag = false;
                            } else if ( min_qty == '' ) {
                                submit_cat_subtotal_flag = false;
                            } else if ( max_qty_flag == false ) {
                                submit_cat_subtotal_flag = false;
                                displayMsg( 'message_cat_qty', coditional_vars.min_max_subtotal_error );
                            } else if ( cat_product_price == '' ) {
                                submit_cat_subtotal_flag = false;
                            } else {
                                submit_cat_subtotal_flag = true;
                            }
                            
                            cat_subtotal_val_arr[cat_tr_int_id] = submit_cat_subtotal_flag;
                            
                        } );
                        
                        if ( cat_subtotal_val_arr != "" ) {
                            var current_tab_id = jQuery( $( '#tbl_ap_category_subtotal_method tr.ap_category_subtotal_row_tr' ).parent().parent().parent().parent() ).attr( 'id' );
                            if ( jQuery.inArray( false, cat_subtotal_val_arr ) !== -1 ) {
                                submit_cat_subtotal_form_flag = false;
                                changeColorValidation( current_tab_id, false, validation_color_code );
                            } else {
                                submit_cat_subtotal_form_flag = true;
                                changeColorValidation( current_tab_id, true, default_color_code );
                            }
                        }
                    }
                    //End loop each row of AP Product rules
                    
                    //Start loop each row of AP Total Cart QTY rules
                    if ( $( "#tbl_ap_total_cart_qty_method tr.ap_total_cart_qty_row_tr" ).length ) {
                        $( '#tbl_ap_total_cart_qty_method tr.ap_total_cart_qty_row_tr' ).each( function ( index, item ) {
                            //initialize variables
                            var total_cart_qty_product_price = '';
                            var min_qty                      = '',
                              max_qty                  = '';
                            var total_cart_qty_tr_id         = jQuery( this ).attr( 'id' );
                            var total_cart_qty_tr_int_id     = total_cart_qty_tr_id.substr( total_cart_qty_tr_id.lastIndexOf( "_" ) + 1 );
                            var max_qty_flag                 = true;
                            
                            //check product empty or not
                            //check product price empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_price_total_cart_qty][]"]' ).length ) {
                                total_cart_qty_product_price = $( this ).find( '[name="fees[ap_fees_ap_price_total_cart_qty][]"]' ).val();
                                if ( total_cart_qty_product_price == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_total_cart_qty][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_total_cart_qty][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if min quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_total_cart_qty_min_qty][]"]' ).length ) {
                                min_qty = $( this ).find( '[name="fees[ap_fees_ap_total_cart_qty_min_qty][]"]' ).val();
                                if ( min_qty == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_total_cart_qty_min_qty][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_total_cart_qty_min_qty][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if max quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_total_cart_qty_max_qty][]"]' ).length ) {
                                max_qty = $( this ).find( '[name="fees[ap_fees_ap_total_cart_qty_max_qty][]"]' ).val();
                                if ( max_qty != '' && min_qty != '' ) {
                                    max_qty = parseInt( max_qty );
                                    if ( min_qty > max_qty ) {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_total_cart_qty_max_qty][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                        max_qty_flag = false;
                                    } else {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_total_cart_qty_max_qty][]"]' ) ).css( 'border', '' );
                                    }
                                }
                            }
                            
                            //check if both min and max quantity empty than error focus and set prevent submit flag
                            
                            if ( min_qty == '' && total_cart_qty_product_price == '' ) {
                                submit_total_cart_qty_flag = false;
                            } else if ( max_qty_flag == false ) {
                                submit_total_cart_qty_flag = false;
                                displayMsg( 'message_cart_qty', coditional_vars.min_max_qty_error );
                            } else if ( total_cart_qty_product_price == '' ) {
                                submit_total_cart_qty_flag = false;
                            } else {
                                submit_total_cart_qty_flag = true;
                            }
                            total_cart_qty_val_arr[total_cart_qty_tr_int_id] = submit_total_cart_qty_flag;
                        } );
                        
                        if ( total_cart_qty_val_arr != "" ) {
                            var current_tab_id = jQuery( $( '#tbl_ap_total_cart_qty_method tr.ap_total_cart_qty_row_tr' ).parent().parent().parent().parent() ).attr( 'id' );
                            if ( jQuery.inArray( false, total_cart_qty_val_arr ) !== -1 ) {
                                submit_total_cart_qty_form_flag = false;
                                changeColorValidation( current_tab_id, false, validation_color_code );
                            } else {
                                submit_total_cart_qty_form_flag = true;
                                changeColorValidation( current_tab_id, true, default_color_code );
                            }
                        }
                    }
                    //End loop each row of AP Total Cart QTY rules
                    
                    //Start loop each row of AP Product Weight rules
                    if ( $( "#tbl_ap_product_weight_method tr.ap_product_weight_row_tr" ).length ) {
                        $( '#tbl_ap_product_weight_method tr.ap_product_weight_row_tr' ).each( function ( index, item ) {
                            //initialize variables
                            var product_weight_id            = '';
                            var product_weight_product_price = '';
                            var min_weight                   = '',
                              max_weight;
                            var product_weight_tr_id         = jQuery( this ).attr( 'id' );
                            var product_weight_tr_int_id     = product_weight_tr_id.substr( product_weight_tr_id.lastIndexOf( "_" ) + 1 );
                            var max_weight_flag              = true;
                            
                            //check product empty or not
                            if ( $( this ).find( '[name="fees[ap_product_weight_fees_conditions_condition][' + product_weight_tr_int_id + '][]"]' ).length ) {
                                product_weight_id = jQuery( this ).find( '[name="fees[ap_product_weight_fees_conditions_condition][' + product_weight_tr_int_id + '][]"]' ).find( 'option:selected' ).length;
                                if ( product_weight_id == 0 ) {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '1px solid #dc3232' );
                                } else {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '' );
                                }
                            }
                            
                            //check product price empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_price_product_weight][]"]' ).length ) {
                                product_weight_product_price = $( this ).find( '[name="fees[ap_fees_ap_price_product_weight][]"]' ).val();
                                if ( product_weight_product_price == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_product_weight][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_product_weight][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if min quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_product_weight_min_weight][]"]' ).length ) {
                                min_weight = $( this ).find( '[name="fees[ap_fees_ap_product_weight_min_weight][]"]' ).val();
                                if ( min_weight == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_product_weight_min_weight][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_product_weight_min_weight][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if max quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_product_weight_max_weight][]"]' ).length ) {
                                max_weight = $( this ).find( '[name="fees[ap_fees_ap_product_weight_max_weight][]"]' ).val();
                                if ( max_weight != '' && min_weight != '' ) {
                                    max_weight = parseFloat( max_weight );
                                    if ( min_weight > max_weight ) {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_product_weight_max_weight][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                        max_weight_flag = false;
                                    } else {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_product_weight_max_weight][]"]' ) ).css( 'border', '' );
                                    }
                                }
                            }
                            
                            if ( product_weight_id == 0 && min_weight == '' && product_weight_product_price == '' ) {
                                submit_product_weight_flag = false;
                            } else if ( product_weight_id == 0 ) {
                                submit_product_weight_flag = false;
                            } else if ( min_weight == '' ) {
                                submit_product_weight_flag = false;
                            } else if ( max_weight_flag == false ) {
                                submit_product_weight_flag = false;
                                displayMsg( 'message_prd_weight', coditional_vars.min_max_weight_error );
                            } else if ( product_weight_product_price == '' ) {
                                submit_product_weight_flag = false;
                            } else {
                                submit_product_weight_flag = true;
                            }
                            
                            product_weight_val_arr[product_weight_tr_int_id] = submit_product_weight_flag;
                        } );
                        
                        if ( product_weight_val_arr != "" ) {
                            var current_tab_id = jQuery( $( '#tbl_ap_product_weight_method tr.ap_product_weight_row_tr' ).parent().parent().parent().parent() ).attr( 'id' );
                            if ( jQuery.inArray( false, product_weight_val_arr ) !== -1 ) {
                                submit_product_weight_form_flag = false;
                                changeColorValidation( current_tab_id, false, validation_color_code );
                            } else {
                                submit_product_weight_form_flag = true;
                                changeColorValidation( current_tab_id, true, default_color_code );
                            }
                        }
                    }
                    //End loop each row of AP Product Weight rules
                    
                    //Start loop each row of AP Category Weight rules
                    if ( $( "#tbl_ap_category_weight_method tr.ap_category_weight_row_tr" ).length ) {
                        $( '#tbl_ap_category_weight_method tr.ap_category_weight_row_tr' ).each( function ( index, item ) {
                            //initialize variables
                            var category_weight_id            = '';
                            var category_weight_product_price = '';
                            var min_weight                    = '',
                              max_weight;
                            var category_weight_tr_id         = jQuery( this ).attr( 'id' );
                            var category_weight_tr_int_id     = category_weight_tr_id.substr( category_weight_tr_id.lastIndexOf( "_" ) + 1 );
                            var max_weight_flag               = true;
                            
                            //check product empty or not
                            if ( $( this ).find( '[name="fees[ap_category_weight_fees_conditions_condition][' + category_weight_tr_int_id + '][]"]' ).length ) {
                                category_weight_id = jQuery( this ).find( '[name="fees[ap_category_weight_fees_conditions_condition][' + category_weight_tr_int_id + '][]"]' ).find( 'option:selected' ).length;
                                if ( category_weight_id == 0 ) {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '1px solid #dc3232' );
                                } else {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '' );
                                }
                            }
                            
                            //check product price empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_price_category_weight][]"]' ).length ) {
                                category_weight_product_price = $( this ).find( '[name="fees[ap_fees_ap_price_category_weight][]"]' ).val();
                                if ( category_weight_product_price == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_category_weight][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_category_weight][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if min quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_category_weight_min_weight][]"]' ).length ) {
                                min_weight = $( this ).find( '[name="fees[ap_fees_ap_category_weight_min_weight][]"]' ).val();
                                if ( min_weight == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_category_weight_min_weight][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_category_weight_min_weight][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if max quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_category_weight_max_weight][]"]' ).length ) {
                                max_weight = $( this ).find( '[name="fees[ap_fees_ap_category_weight_max_weight][]"]' ).val();
                                if ( max_weight != '' && min_weight != '' ) {
                                    max_weight = parseFloat( max_weight );
                                    if ( min_weight > max_weight ) {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_category_weight_max_weight][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                        max_weight_flag = false;
                                    } else {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_category_weight_max_weight][]"]' ) ).css( 'border', '' );
                                    }
                                }
                            }
                            
                            if ( category_weight_id == 0 && min_weight == '' && category_weight_product_price == '' ) {
                                submit_category_weight_flag = false;
                            } else if ( category_weight_id == 0 ) {
                                submit_category_weight_flag = false;
                            } else if ( min_weight == '' ) {
                                submit_category_weight_flag = false;
                            } else if ( max_weight_flag == false ) {
                                submit_category_weight_flag = false;
                                displayMsg( 'message_prd_weight', coditional_vars.min_max_weight_error );
                            } else if ( category_weight_product_price == '' ) {
                                submit_category_weight_flag = false;
                            } else {
                                submit_category_weight_flag = true;
                            }
                            category_weight_val_arr[category_weight_tr_int_id] = submit_category_weight_flag;
                        } );
                        
                        if ( category_weight_val_arr != "" ) {
                            var current_tab_id = jQuery( $( '#tbl_ap_category_weight_method tr.ap_category_weight_row_tr' ).parent().parent().parent().parent() ).attr( 'id' );
                            if ( jQuery.inArray( false, category_weight_val_arr ) !== -1 ) {
                                submit_category_weight_form_flag = false;
                                changeColorValidation( current_tab_id, false, validation_color_code );
                            } else {
                                submit_category_weight_form_flag = true;
                                changeColorValidation( current_tab_id, true, default_color_code );
                            }
                        }
                    }
                    //End loop each row of AP Category Weight rules
                    
                    //Start loop each row of AP Total Cart Weight rules
                    if ( $( "#tbl_ap_total_cart_weight_method tr.ap_total_cart_weight_row_tr" ).length ) {
                        $( '#tbl_ap_total_cart_weight_method tr.ap_total_cart_weight_row_tr' ).each( function ( index, item ) {
                            //initialize variables
                            var total_cart_weight_product_price = '';
                            var min_weight                      = '',
                              max_weight                  = '';
                            var total_cart_weight_tr_id         = jQuery( this ).attr( 'id' );
                            var total_cart_weight_tr_int_id     = total_cart_weight_tr_id.substr( total_cart_weight_tr_id.lastIndexOf( "_" ) + 1 );
                            var max_weight_flag                 = true;
                            
                            //check product empty or not
                            //check product price empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_price_total_cart_weight][]"]' ).length ) {
                                total_cart_weight_product_price = $( this ).find( '[name="fees[ap_fees_ap_price_total_cart_weight][]"]' ).val();
                                if ( total_cart_weight_product_price == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_total_cart_weight][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_total_cart_weight][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if min quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_total_cart_weight_min_weight][]"]' ).length ) {
                                min_weight = $( this ).find( '[name="fees[ap_fees_ap_total_cart_weight_min_weight][]"]' ).val();
                                if ( min_weight == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_total_cart_weight_min_weight][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    min_weight = parseFloat( min_weight );
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_total_cart_weight_min_weight][]"]' ) ).css( 'border', '' );
                                }
                            }
                            
                            //check if max quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_total_cart_weight_max_weight][]"]' ).length ) {
                                max_weight = $( this ).find( '[name="fees[ap_fees_ap_total_cart_weight_max_weight][]"]' ).val();
                                if ( max_weight != '' && min_weight != '' ) {
                                    max_weight = parseFloat( max_weight );
                                    if ( min_weight > max_weight ) {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_total_cart_weight_max_weight][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                        max_weight_flag = false;
                                    } else {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_total_cart_weight_max_weight][]"]' ) ).css( 'border', '' );
                                    }
                                }
                            }
                            
                            //check if both min and max quantity empty than error focus and set prevent submit flag
                            if ( min_weight == '' && total_cart_weight_product_price == '' ) {
                                submit_total_cart_weight_flag = false;
                            } else if ( max_weight_flag == false ) {
                                submit_total_cart_weight_flag = false;
                                displayMsg( 'message_cart_weight', coditional_vars.min_max_weight_error );
                            } else if ( total_cart_weight_product_price == '' ) {
                                submit_total_cart_weight_flag = false;
                            } else {
                                submit_total_cart_weight_flag = true;
                            }
                            
                            total_cart_weight_val_arr[total_cart_weight_tr_int_id] = submit_total_cart_weight_flag;
                        } );
                        
                        if ( total_cart_weight_val_arr != "" ) {
                            var current_tab_id = jQuery( $( '#tbl_ap_total_cart_weight_method tr.ap_total_cart_weight_row_tr' ).parent().parent().parent().parent() ).attr( 'id' );
                            if ( jQuery.inArray( false, total_cart_weight_val_arr ) !== -1 ) {
                                submit_total_cart_weight_form_flag = false;
                                changeColorValidation( current_tab_id, false, validation_color_code );
                            } else {
                                submit_total_cart_weight_form_flag = true;
                                changeColorValidation( current_tab_id, true, default_color_code );
                            }
                        }
                    }
                    //End loop each row of AP Total Cart Weight rules
                    
                    //Start loop each row of AP Total Subcart rules
                    if ( $( "#tbl_ap_total_cart_subtotal_method tr.ap_total_cart_subtotal_row_tr" ).length ) {
                        $( '#tbl_ap_total_cart_subtotal_method tr.ap_total_cart_subtotal_row_tr' ).each( function ( index, item ) {
                            //initialize variables
                            var total_cart_subtotal_product_price  = '';
                            var min_subtotal                       = '',
                              max_subtotal                   = '';
                            var total_cart_subtotal_tr_id          = jQuery( this ).attr( 'id' );
                            var total_cart_subtotal_tr_int_id      = total_cart_subtotal_tr_id.substr( total_cart_subtotal_tr_id.lastIndexOf( "_" ) + 1 );
                            var current_total_cart_subtotal_tab_id = jQuery( $( this ).parent().parent().parent().parent() ).attr( 'id' );
                            var max_subtotal_flag                  = true;
                            
                            //check product empty or not
                            //check product price empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_price_total_cart_subtotal][]"]' ).length ) {
                                total_cart_subtotal_product_price = $( this ).find( '[name="fees[ap_fees_ap_price_total_cart_subtotal][]"]' ).val();
                                if ( total_cart_subtotal_product_price == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_total_cart_subtotal][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_total_cart_subtotal][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if min quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_total_cart_subtotal_min_subtotal][]"]' ).length ) {
                                min_subtotal = $( this ).find( '[name="fees[ap_fees_ap_total_cart_subtotal_min_subtotal][]"]' ).val();
                                if ( min_subtotal == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_total_cart_subtotal_min_subtotal][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    min_subtotal = parseFloat( min_subtotal );
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_total_cart_subtotal_min_subtotal][]"]' ) ).css( 'border', '' );
                                }
                            }
                            
                            //check if max quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_total_cart_subtotal_max_subtotal][]"]' ).length ) {
                                max_subtotal = $( this ).find( '[name="fees[ap_fees_ap_total_cart_subtotal_max_subtotal][]"]' ).val();
                                if ( max_subtotal != '' && max_subtotal != '' ) {
                                    max_subtotal = parseFloat( max_subtotal );
                                    if ( min_subtotal > max_subtotal ) {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_total_cart_subtotal_max_subtotal][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                        max_subtotal_flag = false;
                                    } else {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_total_cart_subtotal_max_subtotal][]"]' ) ).css( 'border', '' );
                                    }
                                }
                            }
                            
                            if ( min_subtotal == '' && total_cart_subtotal_product_price == '' ) {
                                submit_total_cart_subtotal_flag = false;
                            } else if ( max_subtotal_flag == false ) {
                                submit_total_cart_subtotal_flag = false;
                                displayMsg( 'message_cart_weight', coditional_vars.min_max_subtotal_error );
                            } else if ( total_cart_subtotal_product_price == '' ) {
                                submit_total_cart_subtotal_flag = false;
                            } else {
                                submit_total_cart_subtotal_flag = true;
                            }
                            total_cart_subtotal_val_arr[total_cart_subtotal_tr_int_id] = submit_total_cart_subtotal_flag;
                        } );
                        
                        if ( total_cart_subtotal_val_arr != "" ) {
                            var current_tab_id = jQuery( $( '#tbl_ap_total_cart_subtotal_method tr.ap_total_cart_subtotal_row_tr' ).parent().parent().parent().parent() ).attr( 'id' );
                            if ( jQuery.inArray( false, total_cart_subtotal_val_arr ) !== -1 ) {
                                submit_total_cart_subtotal_form_flag = false;
                                changeColorValidation( current_tab_id, false, validation_color_code );
                            } else {
                                submit_total_cart_subtotal_form_flag = true;
                                changeColorValidation( current_tab_id, true, default_color_code );
                            }
                        }
                    }
                    //End loop each row of AP Total Subcart rules
                    
                    //Start loop each row of AP Category Weight rules
                    if ( $( "#tbl_ap_shipping_class_subtotal_method tr.ap_shipping_class_subtotal_row_tr" ).length ) {
                        $( '#tbl_ap_shipping_class_subtotal_method tr.ap_shipping_class_subtotal_row_tr' ).each( function ( index, item ) {
                            //initialize variables
                            var shipping_class_subtotal_id        = '';
                            var shipping_class_subtotal           = '';
                            var min_subtotal                      = '',
                              max_subtotal;
                            var shipping_class_subtotal_tr_id     = jQuery( this ).attr( 'id' );
                            var shipping_class_subtotal_tr_int_id = shipping_class_subtotal_tr_id.substr( shipping_class_subtotal_tr_id.lastIndexOf( "_" ) + 1 );
                            var max_subtotal_flag                 = true;
                            
                            //check product empty or not
                            if ( $( this ).find( '[name="fees[ap_shipping_class_subtotal_fees_conditions_condition][' + shipping_class_subtotal_tr_int_id + '][]"]' ).length ) {
                                shipping_class_subtotal_id = jQuery( this ).find( '[name="fees[ap_shipping_class_subtotal_fees_conditions_condition][' + shipping_class_subtotal_tr_int_id + '][]"]' ).find( 'option:selected' ).length;
                                if ( shipping_class_subtotal_id == 0 ) {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '1px solid #dc3232' );
                                } else {
                                    jQuery( $( this ).find( '.select2-container .selection .select2-selection' ) ).css( 'border', '' );
                                }
                            }
                            
                            //check product price empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_price_shipping_class_subtotal][]"]' ).length ) {
                                shipping_class_subtotal = $( this ).find( '[name="fees[ap_fees_ap_price_shipping_class_subtotal][]"]' ).val();
                                if ( shipping_class_subtotal == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_shipping_class_subtotal][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_price_shipping_class_subtotal][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if min quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_shipping_class_subtotal_min_subtotal][]"]' ).length ) {
                                min_subtotal = $( this ).find( '[name="fees[ap_fees_ap_shipping_class_subtotal_min_subtotal][]"]' ).val();
                                if ( min_subtotal == "" ) {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_shipping_class_subtotal_min_subtotal][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                } else {
                                    jQuery( $( this ).find( '[name="fees[ap_fees_ap_shipping_class_subtotal_min_subtotal][]"]' ) ).css( 'border', '' );
                                }
                            }
                            //check if max quantity empty or not
                            if ( $( this ).find( '[name="fees[ap_fees_ap_shipping_class_subtotal_max_subtotal][]"]' ).length ) {
                                max_subtotal = $( this ).find( '[name="fees[ap_fees_ap_shipping_class_subtotal_max_subtotal][]"]' ).val();
                                if ( max_subtotal != '' && min_subtotal != '' ) {
                                    max_subtotal = parseFloat( max_subtotal );
                                    if ( min_subtotal > max_subtotal ) {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_shipping_class_subtotal_max_subtotal][]"]' ) ).css( 'border', '1px solid ' + validation_color_code );
                                        max_subtotal_flag = false;
                                    } else {
                                        jQuery( $( this ).find( '[name="fees[ap_fees_ap_shipping_class_subtotal_max_subtotal][]"]' ) ).css( 'border', '' );
                                    }
                                }
                            }
                            
                            if ( shipping_class_subtotal_id == 0 && min_subtotal == '' && shipping_class_subtotal == '' ) {
                                submit_shipping_class_subtotal_flag = false;
                            } else if ( shipping_class_subtotal_id == 0 ) {
                                submit_shipping_class_subtotal_flag = false;
                            } else if ( min_subtotal == '' ) {
                                submit_shipping_class_subtotal_flag = false;
                            } else if ( max_subtotal_flag == false ) {
                                submit_shipping_class_subtotal_flag = false;
                                displayMsg( 'message_prd_weight', coditional_vars.min_max_subtotal_error );
                            } else if ( shipping_class_subtotal == '' ) {
                                submit_shipping_class_subtotal_flag = false;
                            } else {
                                submit_shipping_class_subtotal_flag = true;
                            }
                            shipping_class_subtotal_val_arr[shipping_class_subtotal_tr_int_id] = submit_shipping_class_subtotal_flag;
                        } );
                        
                        if ( shipping_class_subtotal_val_arr != "" ) {
                            var current_tab_id = jQuery( $( '#tbl_ap_shipping_class_subtotal_method tr.ap_shipping_class_subtotal_row_tr' ).parent().parent().parent().parent() ).attr( 'id' );
                            if ( jQuery.inArray( false, shipping_class_subtotal_val_arr ) !== -1 ) {
                                submit_shipping_class_subtotal_form_flag = false;
                                changeColorValidation( current_tab_id, false, validation_color_code );
                            } else {
                                submit_shipping_class_subtotal_form_flag = true;
                                changeColorValidation( current_tab_id, true, default_color_code );
                            }
                        }
                    }
                    //End loop each row of AP Category Weight rules
                    
                    //if error in validation than prevent form submit.
                    if ( submit_prd_form_flag == false ||
                         submit_prd_subtotal_form_flag == false ||
                         submit_cat_form_flag == false ||
                         submit_cat_subtotal_form_flag == false ||
                         submit_total_cart_qty_form_flag == false ||
                         submit_product_weight_form_flag == false ||
                         submit_category_weight_form_flag == false ||
                         submit_total_cart_weight_form_flag == false ||
                         submit_total_cart_subtotal_form_flag == false ||
                         submit_shipping_class_subtotal_form_flag == false ) {//if validate error found
                        fees_pricing_rules_validation = false;
                    } else {
                        if ( count_total_tr > 0 ) {
                            fees_pricing_rules_validation = true;
                        } else {
                            var div         = document.createElement( 'div' );
                            div             = setAllAttributes( div, {
                                "class": "warning_msg",
                                "id": "warning_msg_1"
                            } );
                            div.textContent = coditional_vars.warning_msg2;
                            $( div ).insertBefore( ".afrsm-section-left .afrsm-main-table" );
                            if ( $( '#warning_msg_1' ).length ) {
                                $( "html, body" ).animate( {scrollTop: 0}, "slow" );
                                setTimeout( function () {
                                    $( '#warning_msg_1' ).remove();
                                }, 7000 );
                            }
                            fees_pricing_rules_validation = false;
                        }
                    }
                }
            }
            
            if ( fees_pricing_rules_validation == false ) {
                if ( $( '#message_validate' ).length <= 0 ) {
                    var msg_div = document.createElement( 'div' );
                    msg_div     = setAllAttributes( msg_div, {
                        "class": "notice notice-error inline",
                        "id": "message_validate"
                    } );
                    var msg_p   = document.createElement( 'p' );
                    
                    var msg_strong         = document.createElement( 'strong' );
                    msg_strong.textContent = coditional_vars.warning_msg2;
                    msg_p.appendChild( msg_strong );
                    msg_div.appendChild( msg_p );
                    $( msg_div ).insertBefore( ".main-shipping-conf" );
                }
                e.preventDefault();
                return false;
            } else {
                if ( jQuery( ".adv-pricing-rules .advance-shipping-method-table" ).is( ":hidden" ) ) {
                    jQuery( '.adv-pricing-rules .advance-shipping-method-table tr td input' ).each( function () {
                        $( this ).removeAttr( 'required' );
                    } );
                }
                return true;
            }
        }
        
        
        function changeColorValidation( current_tab, required, validation_color_code ) {
            if ( required == false ) {
                jQuery( ".pricing_rules_tab ul li[data-tab=" + current_tab + "]" ).css( 'border-top-color', validation_color_code );
                jQuery( ".pricing_rules_tab ul li[data-tab=" + current_tab + "]" ).css( 'box-shadow', 'inset 0 3px 0 ' + validation_color_code );
            } else {
                jQuery( ".pricing_rules_tab ul li[data-tab=" + current_tab + "]" ).css( 'border-top-color', '' );
                jQuery( ".pricing_rules_tab ul li[data-tab=" + current_tab + "]" ).css( 'box-shadow', '' );
            }
        }
        
        function displayMsg( msg_id, msg_content ) {
            if ( $( '#' + msg_id ).length <= 0 ) {
                var msg_div = document.createElement( 'div' );
                msg_div     = setAllAttributes( msg_div, {
                    "class": "notice notice-error inline",
                    "id": msg_id
                } );
                var msg_p   = document.createElement( 'p' );
                
                var msg_strong         = document.createElement( 'strong' );
                msg_strong.textContent = msg_content;
                msg_p.appendChild( msg_strong );
                msg_div.appendChild( msg_p );
                $( msg_div ).insertBefore( ".main-shipping-conf" );
                
                $( "html, body" ).animate( {scrollTop: 0}, "slow" );
                setTimeout( function () {
                    $( '#' + msg_id ).remove();
                }, 7000 );
            }
        }
    } );
    jQuery( window ).on( 'load', function () {
        jQuery( ".multiselect2" ).select2();
        
        function allowSpeicalCharacter( str ) {
            return str.replace( '&#8211;', '–' ).replace( "&gt;", ">" ).replace( "&lt;", "<" ).replace( "&#197;", "Å" );
        }
        
        jQuery( '.product_fees_conditions_values_product' ).each( function () {
            jQuery( '.product_fees_conditions_values_product' ).select2();
            jQuery( '.product_fees_conditions_values_product' ).select2( {
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function ( params ) {
                        return {
                            value: params.term,
                            action: 'afrsfwa_product_fees_conditions_values_product_ajax'
                        };
                    },
                    processResults: function ( data ) {
                        var options = [];
                        if ( data ) {
                            jQuery.each( data, function ( index, text ) {
                                options.push( {id: text[0], text: allowSpeicalCharacter( text[1] )} );
                            } );
                            
                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            } );
        } );
        
        jQuery( '.product_fees_conditions_values_var_product' ).each( function () {
            jQuery( '.product_fees_conditions_values_var_product' ).select2();
            
            jQuery( '.product_fees_conditions_values_var_product' ).select2( {
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function ( params ) {
                        return {
                            value: params.term,
                            action: 'afrsfwa_product_fees_conditions_varible_values_product_ajax__premium_only'
                        };
                    },
                    processResults: function ( data ) {
                        var options = [];
                        if ( data ) {
                            jQuery.each( data, function ( index, text ) {
                                options.push( {id: text[0], text: allowSpeicalCharacter( text[1] )} );
                            } );
                            
                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            } );
        } );
        jQuery( '.pricing_rules .ap_product, ' +
                '.pricing_rules .ap_product_weight, ' +
                '.pricing_rules .ap_product_subtotal' ).each( function () {
            jQuery( '.pricing_rules .ap_product, ' +
                    '.pricing_rules .ap_product_weight, ' +
                    '.pricing_rules .ap_product_subtotal' ).select2( {
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function ( params ) {
                        return {
                            value: params.term,
                            action: 'afrsfwa_simple_and_variation_product_list_ajax__premium_only'
                        };
                    },
                    processResults: function ( data ) {
                        var options = [];
                        if ( data ) {
                            jQuery.each( data, function ( index, text ) {
                                options.push( {id: text[0], text: allowSpeicalCharacter( text[1] )} );
                            } );
                            
                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            } );
        } );
        
    } );
} )( jQuery );

jQuery( document ).ready( function () {
    if ( jQuery( window ).width() <= 980 ) {
        jQuery( '.adv-pricing-rules .pricing_rules .tab-content' ).click( function () {
            var acc_id = jQuery( this ).attr( 'id' );
            jQuery( ".adv-pricing-rules .pricing_rules .tab-content" ).removeClass( 'current' );
            jQuery( "#" + acc_id ).addClass( 'current' );
        } );
    }
} );

jQuery( window ).resize( function () {
    if ( jQuery( window ).width() <= 980 ) {
        jQuery( '.adv-pricing-rules .pricing_rules .tab-content' ).click( function () {
            var acc_id = jQuery( this ).attr( 'id' );
            jQuery( ".adv-pricing-rules .pricing_rules .tab-content" ).removeClass( 'current' );
            jQuery( "#" + acc_id ).addClass( 'current' );
        } );
    }
} );
