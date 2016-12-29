<?php

function handle_store_cookies(){
    global $woocommerce;

    $region    = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
    $branch_id = isset($_GET['bid']) ? sanitize_text_field($_GET['bid'])   : '';
    $otype     = isset($_GET['otype']) ? sanitize_text_field($_GET['otype'])   : '';
    if($branch_id){
        if(isset($_COOKIE["branch_id"]) && $_COOKIE["branch_id"] != $branch_id)
            $woocommerce->cart->empty_cart();

        setcookie("branch_id",$branch_id,0,"/");

    }
    if($region){
        setcookie("region",$region,0,"/");
    }
    if($otype){
        setcookie("otype",$otype,0,"/");
    }
}

add_action("init","handle_store_cookies");

function get_selected_branch_id(){
    $branch_id = isset($_GET['bid']) ? sanitize_text_field($_GET['bid']) : '';

    if(!$branch_id && isset($_COOKIE["branch_id"]) && $_COOKIE["branch_id"]){
        $branch_id = $_COOKIE["branch_id"];
    }

    return $branch_id;
}
function get_selected_region(){
    $region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';

    if(!$region && isset($_COOKIE["region"]) && $_COOKIE["region"]){
        $region = $_COOKIE["region"];
    }

    return $region;
}
function get_otype(){
    $otype = isset($_GET['otype']) ? sanitize_text_field($_GET['otype']) : '';

    if(!$otype && isset($_COOKIE["otype"]) && $_COOKIE["otype"]){
        $otype = $_COOKIE["otype"];
    }

    return $otype;
}
remove_action('woocommerce_single_product_summary','woocommerce_template_single_rating',10,0);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_price',10,0);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_meta',40,0);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_sharing',50,0);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5,0);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt',20,0);


remove_action('woocommerce_after_single_product_summary','woocommerce_output_product_data_tabs',10,0);
remove_action('woocommerce_single_product_summary','woocommerce_upsell_display',15,0);
remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products',20,0);

remove_action('woocommerce_before_main_content','woocommerce_output_content_wrapper',10,0);
remove_action('woocommerce_before_main_content','woocommerce_breadcrumb',20,0);

remove_action('woocommerce_after_main_content','woocommerce_output_content_wrapper_end',10,0);

remove_action('woocommerce_before_single_product_summary','woocommerce_show_product_sale_flash',10,0);

add_filter("woocommerce_get_price","woocommerce_get_price_by_branch",10,2);
add_filter("yaddPay_terminal_number","specific_branch_terminal_number",1,1);

add_action("woocommerce_payment_complete","handle_cash_payment_verification");

function handle_cash_payment_verification(){
    $thanks_page_id = get_field('order_step_4','option');
    wp_redirect( get_permalink($thanks_page_id) );
    exit();
}
function specific_branch_terminal_number($terminal_number){
    $branch_id = isset($_COOKIE["branch_id"]) ? $_COOKIE["branch_id"] : 0;
    $terminal_number = get_field("branch_code",$branch_id)? get_field("branch_code",$branch_id) : $terminal_number;

    return $terminal_number;
}
function woocommerce_get_price_by_branch($price,$product){
    $branch_price = 0;
    if(isset($_COOKIE["branch_id"]) && $branch_id = $_COOKIE["branch_id"]){
        $branch_price = get_post_meta($branch_id,"_regular_price_{$product->id}",true);
    }

    return $branch_price ? $branch_price : $price;
}

//Add region price to cart total
add_action( 'wp_ajax_add_product_to_cart', 'woocommerce_cart_subtotal_with_branch_shipping' );
add_action( 'wp_ajax_nopriv_add_product_to_cart', 'woocommerce_cart_subtotal_with_branch_shipping');

add_filter( 'woocommerce_order_amount_total', 'add_shipping_cost_to_total',10,2);
add_filter('woocommerce_cart_subtotal','woocommerce_cart_subtotal_with_branch_shipping');

function woocommerce_cart_subtotal_with_branch_shipping( $cart_subtotal, $compound = false){
    $shipping_cost = get_chosen_region_shipping_cost();

    $cart_subtotal = wc_price( WC()->cart->cart_contents_total + $shipping_cost + WC()->cart->get_taxes_total( false, false ) );

    return $cart_subtotal;
}
function get_chosen_region_shipping_cost(){
    $region_price = 0;
    if((isset($_COOKIE["branch_id"]) && $branch_id = $_COOKIE["branch_id"]) && (isset($_COOKIE["region"]) && $selected_region = $_COOKIE["region"])){
        $shipping_regions = get_field('shipping_regions',$branch_id);
        foreach( $shipping_regions as $region){
            $region_name = $region['region'];
            if( $region_name == $selected_region) {
                $region_price = $region['shipping_tax'];
                break;
            }
        }
    }
    return $region_price;
}
function add_shipping_cost_to_total($total,$order){
    $shipping_cost = get_chosen_region_shipping_cost();

    $total = $total + $shipping_cost;

    return $total;
}
/**
 * Add the field to the checkout
 */
add_action( 'woocommerce_after_order_notes', 'payment_method_checkout_field' );
function payment_method_checkout_field( $checkout ) {

    echo '<div id="payment_method_checkout_field"><h2>' . __('Payment Method') . '</h2>';

    woocommerce_form_field( 'payment_method_checkout_field', array(
        'type'          => 'text',
        'class'         => array('payment_method_checkout_field form-row-wide'),
        'label'         => __('Payment Method'),
        'placeholder'   => __('Payment Method'),
        ),
        $checkout->get_value( 'payment_method_checkout_field' )
    );

    echo '</div>';

}
/** Update the order meta with field value */
add_action( 'woocommerce_checkout_update_order_meta', 'japan_custom_checkout_field_update_order_meta' );
function japan_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['payment_method_checkout_field'] ) ) {
        update_post_meta( $order_id, 'Payment Method', sanitize_text_field( $_POST['payment_method_checkout_field'] ) );
    }
}
/** Display field value on the order edit page */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );
function my_custom_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Payment Method').':</strong> ' . get_post_meta( $order->id, 'Payment Method', true ) . '</p>';
}


// Hook in checkout files
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {

    //remove fields
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_postcode']);
    //unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_city']);

    unset($fields['shipping']);


    $fields['billing']['billing_first_name']['placeholder'] = 'שם פרטי';
    $fields['billing']['billing_first_name']['label'] = 'שם פרטי';
    $fields['billing']['billing_first_name']['required'] = true;
    $fields['billing']['billing_first_name']['input_class'] = array('validate_me');

    $fields['billing']['billing_last_name']['placeholder'] = 'שם משפחה';
    $fields['billing']['billing_last_name']['label'] = 'שם משפחה';
    $fields['billing']['billing_last_name']['required'] = true;
    $fields['billing']['billing_last_name']['input_class'] = array('validate_me');

    $fields['billing']['billing_email']['placeholder'] = 'אימייל';
    $fields['billing']['billing_email']['label'] = 'אימייל';
    $fields['billing']['billing_email']['required'] = true;
    $fields['billing']['billing_email']['input_class'] = array('validate_me');

    $fields['billing']['billing_phone']['placeholder'] = 'טלפון';
    $fields['billing']['billing_phone']['label'] = 'טלפון';
    $fields['billing']['billing_phone']['required'] = true;
    $fields['billing']['billing_phone']['input_class'] = array('validate_me');
    $fields['billing']['billing_phone']['custom_attributes'] = array( "maxlength" => "10","minlength" => "9" );

    //adding custom fields
    $fields['shipping']['order_address'] = array(
       'label'       => __('כתובת', 'woocommerce'),
       'placeholder' => _x('כתובת', 'placeholder', 'woocommerce'),
       'required'    => true,
       'input_class' => array('validate_me'),
       'class'       => array('form-row-wide'),
       'clear'       => true
    );
    $fields['shipping']['order_number'] = array(
       'label'       => __('מספר', 'woocommerce'),
       'placeholder' => _x('מספר', 'placeholder', 'woocommerce'),
       'required'    => true,
       'class'       => array('form-row-wide'),
       'input_class' => array('validate_me'),
       'clear'       => false,
       'type'        => 'number'
    );
    $fields['shipping']['order_app'] = array(
       'label'       => __('דירה', 'woocommerce'),
       'placeholder' => _x('דירה', 'placeholder', 'woocommerce'),
       'required'    => true,
       'input_class' => array('validate_me'),
       'class'       => array('form-row-wide'),
       'clear'       => false,
       'type'        => 'number'
    );

    //order_comments
    $fields['order']['order_comments']['placeholder'] = 'הערות';

    return $fields;
}

/**
 * Display field value on the order edit page
 */

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'japan_custom_checkout_field_display_admin_order_meta', 10, 1 );

function japan_custom_checkout_field_display_admin_order_meta($order){

    echo '<p><strong>'.__('Order Address').':</strong> ' . get_post_meta( $order->id, '_shipping_order_address', true ) . '</p>';
    echo '<p><strong>'.__('Order Number').':</strong> ' . get_post_meta( $order->id, '_shipping_order_number', true ) . '</p>';
    echo '<p><strong>'.__('Order App Number').':</strong> ' . get_post_meta( $order->id, '_shipping_order_app', true ) . '</p>';
}


function redirect_to_step2() {

}
