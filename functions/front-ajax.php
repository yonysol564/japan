<?php
add_action( 'wp_enqueue_scripts', 'add_frontend_ajax' );
function add_frontend_ajax() {
    wp_enqueue_script( 'ajax_custom_script', THEME . '/js/ajax.js', array('jquery') );
    wp_localize_script( 'ajax_custom_script', 'ajaxurl', admin_url( 'admin-ajax.php' ));
}

class Japan_wc_handler{
    function __construct(){
        /** Add products to cart **/
        add_action( 'wp_ajax_add_product_to_cart', array($this,'add_product_to_cart') );
        add_action( 'wp_ajax_nopriv_add_product_to_cart', array($this,'add_product_to_cart'));

        /** Refresh mini cart **/
        add_filter( 'wp_ajax_nopriv_refresh_update_mini_cart', array($this,'refresh_update_mini_cart') );
        add_filter( 'wp_ajax_refresh_update_mini_cart', array($this,'refresh_update_mini_cart') );

        add_action( 'wp_ajax_get_products_by_term', array($this,'get_products_by_term') );
        add_action( 'wp_ajax_nopriv_get_products_by_term', array($this,'get_products_by_term') );

        /** Update order payments method **/
        add_action( 'wp_ajax_update_order_payment_method', array($this,'update_order_payment_method') );
        add_action( 'wp_ajax_nopriv_update_order_payment_method', array($this,'update_order_payment_method') );

        /** Remove product from mini cart **/
        add_filter( 'wp_ajax_nopriv_remove_product_from_mini_cart', array($this,'remove_product_from_mini_cart') );
        add_filter( 'wp_ajax_remove_product_from_mini_cart', array($this,'remove_product_from_mini_cart') );

        //Empty Cart
        add_filter( 'wp_ajax_nopriv_mode_empty_mini_cart', array($this,'mode_empty_mini_cart') );
        add_filter( 'wp_ajax_mode_empty_mini_cart', array($this,'mode_empty_mini_cart') );

        // Get single product [popup]
        add_action( 'wp_ajax_get_single_product', array($this,'get_single_product') );
        add_action( 'wp_ajax_nopriv_get_single_product', array($this,'get_single_product') );

        // Update mini cart quantity
        add_action( 'wp_ajax_update_mini_cart_quantity', array($this,'update_mini_cart_quantity') );
        add_action( 'wp_ajax_nopriv_update_mini_cart_quantity', array($this,'update_mini_cart_quantity') );

        // Get cart item total for mobile button
        add_action( 'wp_ajax_get_cart_item_total_mobile', array($this,'get_cart_item_total_mobile') );
        add_action( 'wp_ajax_nopriv_get_cart_item_total_mobile', array($this,'get_cart_item_total_mobile') );

        add_filter("woocommerce_get_item_data",array($this,"get_item_data"),10,2);
        add_filter("woocommerce_get_cart_item_from_session",array($this,"addons_get_cart_item_from_session"),10,1);

        add_filter("woocommerce_add_cart_item",array($this,"woocommerce_add_cart_item_addons"),10,1);
        add_filter("woocommerce_add_cart_item_data",array($this,"woocommerce_add_cart_item_data"),10,1);

        add_action("woocommerce_after_order_notes",array($this,"checkout_page_total_price"));
        add_action( 'woocommerce_add_order_item_meta', array( $this, 'order_item_meta' ), 10, 2 );

    }

    public function checkout_page_total_price($checkout){
        global $woocommerce;
        $bid             = get_selected_branch_id();
        $otype           = get_otype();
        $selected_region = get_selected_region();

        $total_price = $woocommerce->cart->total;
        if( $otype && $otype=='shipping' ) {
            $total_price = woocommerce_cart_subtotal_with_branch_shipping($woocommerce->cart->cart_contents_total);
        }

        ?>
        <?php if( $total_price ) : ?>
            <div class="order_page_total_price_wrapper">
                <div class="row expanded">
                    <div class="medium-6 columns">
                        <div class="total_price_label">
                            סה"כ לתשלום
                        </div>
                    </div>
                    <div class="medium-6 columns">
                        <div class="total_price_value">
                            <?php echo $total_price; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php
    }

    public function get_cart_item_total_mobile(){
        global $woocommerce;
        $results = array();
        $items_counter =  WC()->cart->get_cart_contents_count();
        if($items_counter > 0) {
            $results['html'] = $items_counter;
        } else {
            $results['html'] = 0;
        }
        echo json_encode($results);
        die();
    }

    public function order_item_meta( $item_id, $values ) {
        if ( ! empty( $values['addons'] ) ) {

            foreach ( $values['addons'] as $addon_type=>$addons ) {
                foreach($addons as $addon){
                    $name = $addon[0];
                    $price = $addon[1];

                    woocommerce_add_order_item_meta( $item_id, $name, $price );
                }
            }
        }
        if ( ! empty( $values['product_notes'] ) ) {
            woocommerce_add_order_item_meta( $item_id, __("Product notes"), $values['product_notes'] );
        }
	}
    public function get_item_data( $other_data, $cart_item ) {
        if ( ! empty( $cart_item['addons'] ) ) {

            foreach ( $cart_item['addons'] as $addon_type=>$addons ) {
                foreach($addons as $addon){
                    $name = $addon[0];
                    $price = $addon[1];

                    $other_data[] = array(
                        'name'    => $name,
                        'value'   => $price,
                        'display' => ""
                    );
                }
            }
        }

        if( $cart_item['product_notes']){
            $other_data[] = array(
                'name'    => __("Remarks"),
                'value'   => $cart_item['product_notes'],
                'display' => ""
            );
        }
                return $other_data;
    }
    public function addons_get_cart_item_from_session( $cart_item ) {
		// Adjust price if addons are set
		if ( ! empty( $cart_item['addons'] ) && apply_filters( 'woocommerce_product_addons_adjust_price', true, $cart_item ) ) {

			$extra_cost = 0;

			$extra_cost = $cart_item['total_addons_price'];

			$cart_item['data']->adjust_price( $extra_cost );
		}

		return $cart_item;
	}

    public function update_order_payment_method(){
        $order_id       = isset($_POST['oid']) ? sanitize_text_field($_POST['oid']): '';
        $payment_method = isset($_POST['method']) ? sanitize_text_field($_POST['method']) : '';
        if($order_id && $payment_method){
            update_post_meta( $order_id, 'Payment Method', $payment_method );
        }
        die();
    }
    public function get_products_by_term() {
        global $product,$post;
        $term_id = isset($_POST['term_id']) ? sanitize_text_field($_POST['term_id']) : '';
        $branch_id = isset($_COOKIE["branch_id"]) ? sanitize_text_field($_COOKIE["branch_id"]) : '';
        if( !empty($term_id) ) {

            $results = array();

            $product_category = get_term_by("id",$term_id,'product_cat');

            $results['category_title']       = $product_category->name;
            $results['category_description'] = $product_category->description ? $product_category->description : 'בחרו את המנות שברצונכם להזמין';

            $cat_args = array(
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'terms'    => array($term_id),
                        'field'    => 'term_id'
                    )
                ),
                'meta_query' => array(
                    array(
                        'key'   => '_stock_status',
                        'value' => 'instock'
                    )
                )
            );
            $cat = new WP_Query($cat_args);

            if( $cat->have_posts() ) {
                ob_start();
                    while($cat->have_posts()): $cat->the_post();
                        $product = get_product($post->ID);
                        $branch_stock_status = get_post_meta($branch_id,"_stock_status_{$post->ID}",true);

                        if(!$branch_stock_status || $branch_stock_status == "instock"){
                            get_template_part("inc/ajax","products-loop");
                        }
                    endwhile; wp_reset_query();
                $results['products_list'] = ob_get_clean();
            } else {
                $results['products_list'] = '<h4 class="no_products_ajax">אין מוצרים</h4>';
            }

            echo json_encode($results);

        }

        die();

    }
    public function handle_addons($addons){
        if(!$addons) return;

        $addon_array = array();
        $total_addons_cost = 0;
        foreach($addons as $addon){
            $addon_type = str_replace(array("addon_price[","]"),"",$addon["name"]);
            $addon_price = $this->get_addon_price($addon_type,$addon["value"]);
            $addon_array[$addon_type][] = array(
                $addon["value"],
                $addon_price
            );
            $total_addons_cost+=$addon_price;
        }

        return array("addons"=>$addon_array,"total_price"=>$total_addons_cost);
    }
    public function get_addon_price($addon_type,$addon_name){
        $product_addons = get_field("single_product_addons",$this->product_id);

        foreach($product_addons as $product_addon){
            if($product_addon["addon_title"] == $addon_type){
                foreach($product_addon['addon_options'] as $addon_option){
                    if($addon_option["addon_item_title"] == $addon_name){
                        return $addon_option["addon_item_price"];
                    }
                }
            }
        }
    }
    public function add_product_to_cart() {
        global $woocommerce;

        $result           = array();
        $this->product_id = isset($_POST['product_id']) ? sanitize_text_field($_POST['product_id']) : '';
        $addons           = isset($_POST['addons']) ? filter_var_array( $_POST['addons'], FILTER_SANITIZE_STRING) : '';
        $product_notes  = isset($_POST['product_notes']) ? sanitize_text_field( $_POST['product_notes']) : '';

        $this->addons = $this->handle_addons($addons);
        $this->product_notes = $product_notes;

        if( $this->product_id ) {

            $cart_item_key = $woocommerce->cart->add_to_cart($this->product_id);

            if($cart_item_key){
                $result['message'] = "ok";
            } else {
                $result['message'] = "error";
            }

            echo json_encode($result);
        }

        die();
    }
    public function woocommerce_add_cart_item_addons( $cart_item ){
        global $woocommerce;

        if(isset($cart_item["total_addons_price"])){
            $cart_item["data"]->price = $cart_item["data"]->price + $cart_item["total_addons_price"];
        }

        return $cart_item;
    }
    public function woocommerce_add_cart_item_data($cart_item_data){

        $cart_item_data["addons"]             = $this->addons["addons"];
        $cart_item_data["total_addons_price"] = $this->addons["total_price"];
        $cart_item_data["product_notes"]    = $this->product_notes;

        return $cart_item_data;
    }

    public function refresh_update_mini_cart() {
        echo wc_get_template( 'cart/mini-cart.php' );
        die();
    }
    public function remove_product_from_mini_cart() {
        $results = array();

        $cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';

        if( $cart_item_key ) {
            $cart = WC()->cart->get_cart();

            if(isset($cart[$cart_item_key]) && $cart[$cart_item_key]){
                WC()->cart->remove_cart_item($cart_item_key);
            }
            $results['status']       = 'ok';
        }else{
            $results['status']       = 'error';
        }

        $results['new_subtotal'] = $this->recalculate_minicart_subtotal();
        echo json_encode($results);

        die();
    }

    //Recalculate Subtotals
    public function recalculate_minicart_subtotal() {
        return WC()->cart->get_cart_subtotal();
    }

    public function mode_empty_mini_cart() {

        global $woocommerce;

        $results = array();
        $woocommerce->cart->empty_cart();

        $results['status']       = 'ok';
        $results['new_subtotal'] = $this->recalculate_minicart_subtotal();

        echo json_encode($results);

        die();
    }

    public function get_single_product() {

        $product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : '';
        if(!empty($product_id)) {
            ob_start();
            $pargs = array(
                'post_type'     => 'product',
                'posts_per_page'=> 1,
                'post__in'      => array($product_id)
            );
            $pro = new WP_Query($pargs);
            $results = array();
    ?>

            <?php do_action( 'woocommerce_before_main_content' ); ?>
                <?php while ( $pro->have_posts() ) : $pro->the_post(); ?>
                    <?php wc_get_template_part( 'content', 'single-product' ); ?>
                <?php endwhile; ?>
            <?php do_action( 'woocommerce_after_main_content' ); ?>

    <?php
            $results['html'] = ob_get_clean();
            echo json_encode($results);
        }
        die();
    }
    function update_mini_cart_quantity() {

        global $woocommerce;
        $cart_item_key      = isset($_POST['cart_item_key']) ? sanitize_text_field( $_POST['cart_item_key'] )           : '';
        $cart_item_quantity = isset($_POST['cart_item_quantity']) ? sanitize_text_field( $_POST['cart_item_quantity'] ) : '';

        if( $cart_item_key && $cart_item_quantity ) {
            $results = array();
            $woocommerce->cart->set_quantity($cart_item_key, $cart_item_quantity);
            $results['status'] = 'ok';
            $results['new_subtotal'] = $this->recalculate_minicart_subtotal();
            echo json_encode($results);
        }

        die();
    }
}

$wc_japan = new Japan_wc_handler();



//Get shipping regions by branch
add_action( 'wp_ajax_get_regions_by_branch', 'get_regions_by_branch' );
add_action( 'wp_ajax_nopriv_get_regions_by_branch', 'get_regions_by_branch' );
function get_regions_by_branch(){

    $branch_id = isset($_POST['branch_id']) ? sanitize_text_field( $_POST['branch_id'] ) : '';
    $results   = array();

    if(!empty($branch_id)) {

        $shipping_regions = get_field('shipping_regions', $branch_id);

        ob_start();

            if( $shipping_regions ) { ?>
                <option value="">בחר איזור חלוקה</option>
                <?php foreach($shipping_regions as $data){ ?>
                    <option value="<?php echo $data['region']; ?>"><?php echo $data['region']; ?></option>
                <?php }
            }

        $results['html']  = ob_get_clean();
        $results['status'] = 'ok';
    } else {
        $results['html']  = '<option value ="">בחר איזור חלוקה</option>';
        $results['status'] = 'empty';
    }

    echo json_encode($results);

    die();

}

//Create new Order from step 1
add_action( 'wp_ajax_create_new_order_step1', 'create_new_order_step1' );
add_action( 'wp_ajax_nopriv_create_new_order_step1', 'create_new_order_step1' );
function create_new_order_step1(){

    global $woocommerce;

    $result = array();

    $order_array    = isset($_POST['newOrder']) ? $_POST['newOrder']                                  : '';
    $next_step_link = isset($_POST['next_step_link']) ? sanitize_text_field($_POST['next_step_link']) : '';
    $city           = isset($_POST['city']) ? sanitize_text_field($_POST['city'])                     : '';

    //print_r($order_array); die();

    $address = array(
        'first_name' => $order_array[0],
        'last_name'  => $order_array[0],
        'email'      => $order_array[2],
        'phone'      => $order_array[1],
        'address_1'  => $order_array[4]." ".$order_array[5]."/".$order_array[6],
        'city'       => $city ? $city : $order_array[4],
        'country'    => 'IL'
    );

    $order = wc_create_order();

    $cart_items = $woocommerce->cart->get_cart();
    if($cart_items) {
        foreach($cart_items as $item) {
            $product_in_cart = get_product($item['data']->id);
            $order->add_product( $product_in_cart, $item['quantity'] ); //(get_product with id and next is for quantity)
        }
    }
    $order->set_address( $address, 'billing' );
    $order->set_address( $address, 'shipping' );
    $order->calculate_totals();

    $result['message']  = 'ok';
    $result['order_id'] = $order->id;

    echo json_encode($result);

    die();
}

function cart_item_addons($cart_item){
    if(!isset($cart_item["addons"])) return;

    echo '<div class="mini_cart_item_addons"><ul>';
    foreach($cart_item["addons"] as $addon_type=>$addon){
        foreach($addon as $addon_details){
            $price = '';
            if($addon_details[1]){
                $price   = ' - ' .wc_price($addon_details[1]);
            }
            $details = stripslashes($addon_details[0]);
            echo "<li>{$details}{$price}</li>";
        }
    }
    echo '</ul></div>';

}

add_filter("sms_to_phone_number","update_to_phone_number",1,1);
function update_to_phone_number($phone_number){
    if($_COOKIE["user_phone_number"]){
        $phone_number = $_COOKIE["user_phone_number"];
        return $phone_number;
    }
}

add_action( 'wp_ajax_send_sms', 'send_sms' );
add_action( 'wp_ajax_nopriv_send_sms', 'send_sms');
function send_sms(){

    global $sms;
    $results           = array();
    $user_phone_number = isset($_POST['user_phone_number']) ? sanitize_text_field($_POST['user_phone_number']) : '';
    $user_sms_code     = mt_rand(100000,999999);

    setcookie("user_sms_code", $user_sms_code,0,"/");
    setcookie("user_phone_number", $user_phone_number,0,"/");

    if( $user_phone_number && $user_sms_code ) {
        $sms->SetMsg($user_sms_code);
        $send = $sms->SendSMS($user_phone_number);
        $results['status'] = "ok";
    }

    echo json_encode($results);

    die();
}

add_action( 'wp_ajax_validate_sms_code', 'validate_sms_code' );
add_action( 'wp_ajax_nopriv_validate_sms_code', 'validate_sms_code');
function validate_sms_code(){

    global $sms;
    $results           = array();
    $user_code         = isset($_POST['user_code']) ? sanitize_text_field($_POST['user_code']) : '';
    $sms_code          = isset($_POST['sms_code']) ? sanitize_text_field($_POST['sms_code']) : '';

    $results['status'] = 'error';

    if( $user_code == $sms_code ) {

        $results['status'] = "ok";
    }

    echo json_encode($results);

    die();
}

add_action( 'wp_ajax_get_cart_items_price_by_branch', 'get_cart_items_price_by_branch' );
add_action( 'wp_ajax_nopriv_get_cart_items_price_by_branch', 'get_cart_items_price_by_branch');
function get_cart_items_price_by_branch(){

    if ( ! WC()->cart->is_empty() ) {

        $total_cart_items_price = array();

        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
            $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

                $japan = new Japan_wc_handler();
                $addons = $japan->addons_get_cart_item_from_session( $cart_item );
                $total_cart_items_price[] = $addons['line_subtotal'];
            }

        }

        $total_items_line = array_sum($total_cart_items_price);

        ob_start();
        ?>
            <span class="woocommerce-Price-amount amount">
                <?php echo $total_items_line; ?><span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
            </span>
        <?php
        $result = ob_get_clean();

        echo json_encode($result);
    }

    die();
}

add_action( 'wp_ajax_get_minimum_order_by_region', 'get_minimum_order_by_region' );
add_action( 'wp_ajax_nopriv_get_minimum_order_by_region', 'get_minimum_order_by_region');
function get_minimum_order_by_region($return = false){

    $branch_id   = get_selected_branch_id();
    $region_name = get_selected_region();
    $result      = 0;
    if( $branch_id && $region_name ) {
        $shipping_regions = get_field('shipping_regions',$branch_id);
        $minimum_order = array();
        foreach($shipping_regions as $region){
            if( $region['region'] == $region_name ) {
                $minimum_order[] = $region['minimum_order'];
                break;
            }
        }
        $result = $minimum_order[0];
    }

    if($return)
        return $result;

    echo json_encode($result);
    die();
}
