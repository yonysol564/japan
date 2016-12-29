/**********************************
***         DOM                 ***
**********************************/
jQuery(document).ready(function(){

    //Get total items in cart for mobile
    get_cart_item_total_mobile();

    //Edit product from cart
    edit_product_from_cart();

    calculate_cart_items_price();

    jQuery("body").on("click",".mini_quantity_down", function() {
        var this_element = jQuery(this);
        var current_value = jQuery(this).parents(".quantity_mini_cart_item").find(".quantity input").val();
        if(current_value != 1) {
            jQuery(this).parents(".quantity_mini_cart_item").find(".quantity input").val(parseInt(current_value)-1);
            update_mini_cart_item_quantity(this_element);
            get_cart_item_total_mobile();
        }
    });
    jQuery("body").on("click",".mini_quantity_up", function() {
        var this_element = jQuery(this);
        var current_value = jQuery(this).parents(".quantity_mini_cart_item").find(".quantity input").val();
        jQuery(this).parents(".quantity_mini_cart_item").find(".quantity input").val(parseInt(current_value)+1);
        update_mini_cart_item_quantity(this_element);
        get_cart_item_total_mobile();
    });

    //add event handler to remove from minicart button
    jQuery(".woo_cart").on("click",".ajax_mini_cart_items a.remove",function(e){
        e.preventDefault();

        var _this = jQuery(this);
        var cartItemKey = _this.data("cart_item_key");

        remove_product_from_mini_cart(cartItemKey);
    });
    jQuery("body").on("click",".sp_large_button a",function(event){
        add_product_to_cart_hook(event,this);
    });
    //Update Order payment method
    jQuery(".step_2 .payment_method a").click(function(e){
        var method = jQuery(this).data("method");
        var oid = jQuery(this).data("oid");
        if(method){
            jQuery.ajax({
                type     : "post",
                dataType : "json",
                url      : ajaxurl,
                data     : {action: "update_order_payment_method", method : method, oid:oid},
                success: function(response) {

                }
            });
        }
    });

    //Empty mini cart
    empty_mini_cart();

    //Update mini cart quantity
    jQuery(".quantity_mini_cart_item .quantity input").change( function(){
        var this_element      = jQuery(this);
        update_mini_cart_item_quantity(this_element);
    });


    //Click on term & get all term products
    jQuery(".ajax-term-link").click(function(event){
        event.preventDefault();
        var this_element = jQuery(this);
        var term_id      = this_element.attr("href");
        var branch_id    = jQuery("input[name=branch_id]").val();

        jQuery(".ajax-term-link").removeClass("active");
        this_element.addClass("active");
        this_element.find(".ajax_loader").css('opacity',1);

        jQuery.ajax({
            type     : "post",
            dataType : "json",
            url      : ajaxurl,
            data     : {
                action: "get_products_by_term",
                term_id : term_id,
                branch_id : branch_id
            },
            success: function(response) {
                jQuery(".product_category_head_title").html(response.category_title);
                jQuery(".product_category_head_description").html(response.category_description);
                jQuery(".ajax_woo_products_list").html(response.products_list);
                jQuery(".ajax-term-link[href="+term_id+"] .ajax_loader").css("opacity",0);
                open_single_product_popup();
            }
        });
    });

    //Empty Cart After Step 4 Completed
    jQuery("#step4-order").click(function(e){
        e.preventDefault();
        var redirect_url = jQuery(this).attr("href");
        jQuery.ajax({
            type       : "post",
            dataType   : "json",
            url        : ajaxurl,
            data       : {action: "mode_empty_mini_cart"},
            success: function(response) {
                if(response.status == 'ok') {
                    window.location.assign(redirect_url);
                }
            }
        });
    });

    //Create new Order from Order step 1
    jQuery("#step1-order").click(function(e){
        e.preventDefault();

        if( !jQuery(this).hasClass("not_active") ){

            var user_phone_number = jQuery("#billing_phone").val();
            errors = false;
            if(!user_phone_number || user_phone_number.length > 10 ) {
                swal("יש להזין מספר טלפון תקין");
                errors = true;
                jQuery("input#billing_phone").addClass("not_valid_input");
                return false;
            }

            jQuery("input.validate_me").each(function(){
                if( jQuery(this).val() === '' || !jQuery(this).val() ) {
                    jQuery(this).addClass("not_valid_input");
                    errors = true;
                }
            });

            if( jQuery('#payment_method_cod').is(':checked') && !errors ) {
                //Send SMS
                jQuery.ajax({
                    type     : "post",
                    dataType : "json",
                    url      : ajaxurl,
                    data     : {
                        action : "send_sms",
                        user_phone_number : user_phone_number
                    },
                    success: function(response) {
                        if( response && response.status=='ok' ) {
                            jQuery(".visible_form_personal_info").fadeOut(300, function(){
                                jQuery("#user_phone_activation").fadeIn(100);
                            });
                        }
                    }
                });
            }

            if( jQuery('#payment_method_yaadpay').is(':checked') ) {
                jQuery("#user_personal_info").find("form.woocommerce-checkout #place_order").click();
            }
        }

    });

    jQuery("body").on("click","#step2-phone-order", function(event){
        event.preventDefault();
        if( !jQuery(this).hasClass('disabled') ) {
            jQuery("#place_order").click();
        }
    });

    //Click on submit/validate user activation code
    jQuery(".submit_phone_code").click(function(event){
        event.preventDefault();
        var user_code = jQuery("#user_activation_code").val();
        var sms_code = Cookies.get('user_sms_code');
        if(user_code && user_code !=' '){
            jQuery.ajax({
                type     : "post",
                dataType : "json",
                url      : ajaxurl,
                data     : {
                    action    : "validate_sms_code",
                    user_code : user_code,
                    sms_code  : sms_code
                },
                success: function(response) {
                    if(response.status == 'ok') {
                        jQuery(".submit_phone_code").remove();
                        jQuery(".submit_phone_code_success").fadeIn(200);
                        jQuery("#step2-phone-order").removeClass("disabled");
                    } else {
                        jQuery(".error_validation").fadeIn();
                        jQuery("#user_activation_code").addClass("not_valid_input");
                    }
                }
            });
        } else {
            swal("משתמש יקר","הזן קוד אימות");
        }

    });

    jQuery(".go_back_to_hidden_form").click(function(event){
        event.preventDefault();
        jQuery("#user_phone_activation").fadeOut(300, function(){
            jQuery(".visible_form_personal_info").fadeIn(100);
        });
    });

    //Active first product category
    jQuery("a.ajax-term-link:first-child").addClass("active");
    jQuery(".woo_cats_list_inner").find("a.ajax-term-link.active").trigger("click");

});

//Update mini cart quantity
function update_mini_cart_item_quantity(this_element){
    var cart_item_key      = this_element.parents('.quantity_mini_cart_item').find('.mini_quantity_down').data('cartitemkey');
    var cart_item_quantity = this_element.parents('.quantity_mini_cart_item').find('input').val();
    show_mini_cart_loader();
    jQuery.ajax({
        type     : "post",
        dataType : "json",
        url      : ajaxurl,
        data     : { action: "update_mini_cart_quantity", cart_item_key : cart_item_key, cart_item_quantity : cart_item_quantity },
        success: function(response) {
            if(response.status == 'ok') {
                refresh_mini_cart();
                hide_mini_cart_loader();
            }
        }
    });
}

/****************************************************************************
            Single Product Popup [START]
*****************************************************************************/

//Open Magnific popup Single Product
function openPopup(el,product_id) {
    //Clear popup content before showing new product
    jQuery("#product_popup .popup_layer_inner .ajax_popup_content").html('');

    jQuery.magnificPopup.open({
        items: { src: '#product_popup' },
        type: 'inline',
        fixedContentPos : false,
        callbacks: {
            beforeOpen: function() {
                jQuery.ajax({
                    type       : "post",
                    dataType   : "json",
                    url        : ajaxurl,
                    data       : {action: "get_single_product", product_id : product_id},
                    success: function(response) {
                        jQuery("#product_popup .popup_layer_inner .ajax_popup_content").html(response.html);
                    }
                });
            },
        }
    });
}
//Open single product popup
function open_single_product_popup() {
    jQuery(".product_item_add_to_cart a").click(function(event){
        event.preventDefault();
        var product_id = jQuery(this).attr("href");
        if( jQuery(this).hasClass('has_addons') ) {
            openPopup(".product_item_add_to_cart a", product_id );
        } else {
            //add to cart with no popup open
            show_mini_cart_loader();
            add_product_to_cart(product_id,1,addons = '',product_notes = '');
        }
    });
}
//Edit product in cart
function edit_product_from_cart(){

    jQuery("body").on("click",".edit_cart_item", function(event){
        event.preventDefault();
        element = jQuery(this);
        var product_id    = element.data("productid");
        var cartitemkey   = element.data("cartitemkey");
        var addons        = element.data("addons");
        var productnotes = element.data("productnotes");
        editPopup("",product_id,cartitemkey,addons,productnotes);
    });

}

//EDIT Magnific popup Single Product
function editPopup(el,product_id,cartitemkey,addons,productnotes) {

    jQuery("#product_popup .popup_layer_inner .ajax_popup_content").html('');

    if( product_id ) {
        jQuery.magnificPopup.open({
            items: { src: '#product_popup' },
            type: 'inline',
            callbacks: {
                beforeOpen: function() {
                    jQuery.ajax({
                        type       : "post",
                        dataType   : "json",
                        url        : ajaxurl,
                        data       : {
                            action     : "get_single_product",
                            product_id : product_id,
                            addons     : addons,
                            productnotes : productnotes
                        },
                        success: function(response) {
                            jQuery("#product_popup .popup_layer_inner .ajax_popup_content").html(response.html);
                            addToCartButton = jQuery("#product_popup").find(".sp_large_button a");
                            addToCartButton.html("לשמור את השינויים >");
                            addToCartButton.attr("data-cart_item_key",cartitemkey).addClass("toUpdate");
                            if(productnotes){
                                jQuery("#product_popup .popup_layer_inner .ajax_popup_content textarea.single_product_notes").val(productnotes);
                            }

                            jQuery.each(addons.addons, function(main_index,main_value){

                                if(main_value){
                                    all_checked_addons = [];
                                    jQuery.each(main_value, function(index, value){
                                        var checked_addon = value[0];
                                        if(checked_addon){
                                            var target_element = jQuery(".product_addon_option[data-optionname='"+main_index+"']").find("input[value='"+checked_addon+"']");
                                            target_element.prop("checked",true);
                                            target_element.parents(".product_addon_option").find(".squaredFour input").addClass("checked");
                                        }
                                    });
                                }
                            });
                        }
                    });
                },
            }
        });
    }

}
/****************************************************************************
            Single Product Popup [END]
*****************************************************************************/

//Add product to cart
function add_product_to_cart_hook(event,_this) {

        event.preventDefault();
        var this_element  = jQuery(_this);
        var product_id    = jQuery(_this).attr("href");
        var addons        = jQuery(_this).parents(".entry-summary").find(".product_addon_section input").serializeArray();
        var this_addons   = jQuery(_this).parents("#product_popup").find(".product_addon_section");

        var cart_item_key = jQuery(_this).data("cart_item_key");
        var product_notes = jQuery(_this).parents("form").find(".single_product_notes").val();

        var process_add_to_cart = true;

        this_addons.each(function(){
            var this_addon = jQuery(this);
            var min_limit  = this_addon.data("min");
            var cnt        = this_addon.find("input.addon_checkbox_element:checked").length;

            if ( cnt < min_limit ) {
                jQuery(_this).attr("disabled", "disabled");
                alert_minimum_addons(min_limit);
                process_add_to_cart = false;
                return false;
            }
        });
        if(process_add_to_cart){
            show_popup_loader();
            //add product
            if( !this_element.hasClass("toUpdate") ) {
                add_product_to_cart(product_id,1,addons,product_notes);
                get_cart_item_total_mobile();
            //remove current product from cart and add the new one
            } else {
                remove_product_from_mini_cart(cart_item_key);
                setTimeout(function(){
                    add_product_to_cart(product_id,1,addons,product_notes);
                    get_cart_item_total_mobile();
                }, 500);
            }
        }
}
//Add product to cart func
function add_product_to_cart(product_id,quantity,addons,product_notes){
    if(typeof product_notes == "undefined"){
        product_notes = "";
    }
    if(typeof quantity == "undefined"){
        quantity = 1;
    }
    if(typeof addons == "undefined"){
        addons = "";
    }

    jQuery.ajax({
        type       : "post",
        dataType   : "json",
        url        : ajaxurl,
        data       : {
            action          : "add_product_to_cart",
            product_id      : product_id,
            addons          : addons,
            product_notes : product_notes
        },
        success: function(response) {
            refresh_mini_cart();
            hide_popup_loader();
            get_cart_item_total_mobile();
            //close single product popup
            jQuery.magnificPopup.close();

            setTimeout(function(){
                var next_step_link = Cookies.get('next_step_link');
                if(next_step_link && typeof next_step_link !='undefined' ) {
                    jQuery(".ajax_mini_cart_items .buttons a.large_checkout_button").attr("href", next_step_link);
                }

            }, 500);
        }
    });
}
//Calculate cart items price
function calculate_cart_items_price(){

    jQuery.ajax({
        type       : "post",
        dataType   : "json",
        url        : ajaxurl,
        data       : {
            action     : "get_cart_items_price_by_branch",
        },
        success: function(response) {
            jQuery(".items_total_minicart").html(response);
        }
    });

}
//Remove product from mini cart
function remove_product_from_mini_cart(cart_item_key) {
    show_mini_cart_loader();

    jQuery.ajax({
        type       : "post",
        dataType   : "json",
        url        : ajaxurl,
        data       : {action: "remove_product_from_mini_cart", cart_item_key : cart_item_key},
        success: function(response) {
            if(response.status == 'ok') {
                hide_mini_cart_loader();
                refresh_mini_cart();
                get_cart_item_total_mobile();
            } else {
                alert("error");
            }
        }
    });
}

function alert_maximum_addons(max_limit){
    swal({
        title    : "משתמש יקר!",
        text     : "אפשר לסמן עד "+max_limit+" תוספות",
        imageUrl : themeUrl+"/images/japan_small_logo.png"
    });
}
function alert_minimum_addons(min_limit){
    swal({
        title    : "משתמש יקר!",
        text     : "יש לסמן מינימום "+min_limit+" תוספות",
        imageUrl : themeUrl+"/images/japan_small_logo.png"
    });
}
//Refresh mini cart
function refresh_mini_cart(){
    var data = {
      'action': 'refresh_update_mini_cart'
    };
    jQuery.post( woocommerce_params.ajax_url, data, function(response) {
        jQuery('.ajax_mini_cart_items').html(response);
        hide_mini_cart_loader();
        empty_mini_cart();
        calculate_cart_items_price();
      }
    );
}
//Show minicart loader
function show_mini_cart_loader(){
    jQuery(".cart_title .ajax_loader").css('opacity',1);
}

//Hide minicart loader
function hide_mini_cart_loader(){
    jQuery(".cart_title .ajax_loader").css('opacity',0);
}

//Show popup loader
function show_popup_loader(){
    jQuery(".popup.ajax_loader").css('opacity',1);
}

//Hide popup loader
function hide_popup_loader(){
    jQuery(".popup.ajax_loader").css('opacity',0);
}


//Empty mini cart
function empty_mini_cart() {

    jQuery(".empty_mini_cart a").click(function(event){
        event.preventDefault();
        show_mini_cart_loader();
        jQuery.ajax({
            type       : "post",
            dataType   : "json",
            url        : ajaxurl,
            data       : {action: "mode_empty_mini_cart"},
            success: function(response) {
                if(response.status == 'ok') {
                    jQuery(".ajax_mini_cart_items ul.cart_list.product_list_widget").html("אין מוצרים בעגלה");

                    get_cart_item_total_mobile();
                    refresh_mini_cart();
                    hide_mini_cart_loader();
                    //calculate_cart_items_price();
                }
            }
        });
    });

}

//Get cart items total for mobile button
function get_cart_item_total_mobile(){

    jQuery.ajax({
        type       : "post",
        dataType   : "json",
        url        : ajaxurl,
        data       : { action: "get_cart_item_total_mobile" },
        success: function(response) {
            if( response.html ) {
                if(response.html > 0){
                    jQuery("span.in_cart_item_counter").html("<span>"+response.html+"</span>");
                    jQuery("span.in_cart_item_counter span").css('opacity',1);
                }
            }
        }
    });

}
