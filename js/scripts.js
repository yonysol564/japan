/**************************
	DOM Ready
**************************/
jQuery(document).ready(function($) {

    jQuery("body").on("click",".mini_cart_pickup label", function(){
        if( jQuery(this).parents(".squaredFour").find("input").is(":checked") ) {
            //console.log("i am not checked");
            jQuery(".mini_field_shipping_tax").fadeIn();
        } else {
            //console.log("i am checked");
            jQuery(".mini_field_shipping_tax").fadeOut();
        }
    });

    //check shippimg mode
    var selectedShippingMethod = jQuery(".shipping_methods_wrapper a.active").data("shippingmethod");
    shipping_mode_change(selectedShippingMethod);

    var $body = jQuery("body"),
    $header_left_menu_link = jQuery(".header_left_section.desktop_div li a, .footer_socials ul li a"),
    $input_datepicker = jQuery(".input_datepicker"),
    $call_to_action = jQuery(".call_to_action"),
    $branches_select = jQuery(".branches_select"),
    $home_slider = jQuery(".homepage_slider_inner");

    $body.on("click","a.disabled, a.not_active",function(event){
        event.preventDefault();
    });

    //Desktop header menu icon hover
    $header_left_menu_link.mouseenter(function(event){
        var element = jQuery(this).find("img");
        var image_src = element.attr("src");
        var hover_src = element.data("hover");
        jQuery(element).attr("src",hover_src);
    });
    $header_left_menu_link.mouseleave(function(event){
        var element = jQuery(this).find("img");
        var image_src = element.attr("src");
        var unhover_src = element.data("unhover");
        jQuery(element).attr("src",unhover_src);
    });

    //Datepicker fields
    $input_datepicker.datepicker(jQuery.datepicker.regional.he);

    //Homepage click to call
    $call_to_action.click(function(event){
        var windowWidth = jQuery(window).width();
        if(windowWidth > 768){
            event.preventDefault();
        } else {
            var target_click = jQuery(this).find("a");
            href = target_click.attr("href");
            window.location.href = href;
        }
    });

    //Get regions by branch
    $branches_select.change(function(){
        show_popup_loader();
        var branch_id = jQuery(this).val();
        get_regions_by_branch(branch_id);
        enable_pickup_button();
    });
    $body.on("change",".regions_select", function(){
        if( jQuery(this).val() ){
            jQuery(".actions a.red_button").removeClass("not_active");
            get_minimum_order_by_region();
        } else {
            jQuery(".actions a.red_button").addClass("not_active");
            set_minimum_order_value();
            enable_pickup_button();
        }
    });

    $body.on("click",".shipping_methods_wrapper a", function(event){
        event.preventDefault();
        var method = jQuery(this).text();
        var methodType = jQuery(this).data("shippingmethod");

        jQuery(".shipping_methods_wrapper a").removeClass("active");
        jQuery(this).addClass("active");

        if(method && method != ' ') {
            jQuery("input#payment_method_checkout_field").val(method);
        }

        shipping_mode_change(methodType);
    });

    $body.on("keyup","#order_address,#order_number,#order_app", function(){
        var order_address = jQuery("#order_address").val();
        var order_number = jQuery("#order_number").val();
        var order_app = jQuery("#order_app").val();
        var region = Cookies.get("region");
        if(region){
            region = region.replace("+"," ");
        }
        var address_string = "רחוב:" + order_address+" "+order_number + " דירה:" + order_app + " " + region;
        jQuery("#billing_address_1").val(address_string);
    });

    //Trigger payment type on checkout page
    jQuery(".choose_payment_status a").click(function(event){
        event.preventDefault();
        var target = jQuery(this).data("target");

        jQuery(".choose_payment_status a").removeClass("active");
        jQuery(this).addClass("active");

        jQuery("ul.wc_payment_methods").find("label[for="+target+"]").click();
        jQuery("a#step1-order").removeClass("not_active");
    });
    //jQuery("a.payment_method_cod").css("font-weight","bold");

    //Open mobile cart
    $body.on("click","a.mobile_cart_trigger", function(event) {
        event.preventDefault();
        jQuery(this).fadeTo(250,0);
        jQuery(".woo_cart").slideDown();
    });
    // Close mobile cart
    $body.on("click","a.close_mobile_cart", function(event) {
        event.preventDefault();
        jQuery(".woo_cart").slideUp();
        jQuery(".mobile_cart_button a").fadeTo(250,1);
    });

    //Handle addons maximum
    $body.on("change","input.addon_checkbox_element", function(evt) {
        var this_element = jQuery(this).parents(".product_addon_section");
        var max_limit    = this_element.data("max");
        var cnt          = this_element.find("input.addon_checkbox_element:checked").length;
        if (cnt > max_limit) {
            jQuery(this).prop("checked", "");
            alert_maximum_addons(max_limit);
            //alert('אפשר לסמן עד ' + max_limit + ' תוספות');
        }
    });

    //Mobile menu trigger
    jQuery(".mobile_nav").click(function(e){
        e.preventDefault();
        jQuery(".mobile_menu_wrapper").toggleClass("active");
    });

    //Contact page google map
    if(jQuery("#contact_map").length) {
        init_google_map();
    }

    //Select payment method
    jQuery(".step_2 .payment_method a").click(function(e){
        jQuery(".step_2 .payment_method a").removeClass("selected");
        jQuery(this).addClass("selected");
    });
    jQuery(".step_2 .payment_method.--visa a").click();

    //Gallery template
	jQuery('.gallery').magnificPopup({
	    delegate: 'a',
	    type: 'image',
	    removalDelay: 100,
	    gallery:{
	      enabled  :true,
          tCounter : '',
	    },
	    callbacks: {
    	    beforeOpen: function() {
    	        this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
    	        this.st.mainClass = this.st.el.attr('data-effect');
    	    },
	    },
        image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			titleSrc: function(item) {
				return item.el.attr('title');
			}
		},
	    closeOnContentClick: true,
	    midClick: true
	});


    //Braches popup with dropdowns
    jQuery('.inline-popup-link').click(function(event){
        event.preventDefault();
        var this_element = jQuery(this);
        var bid = this_element.data("bid");

        jQuery.magnificPopup.open({
            items: {
                src: '#branches_popup'
            },
            type: 'inline',
            closeMarkup: '<button title="סגור" class="mfp-close"></button>',
            callbacks: {
                beforeOpen: function() {
                    if( bid && typeof bid !='undefined' ) {
                        get_regions_by_branch(bid);
                        jQuery(".branches_select option[value='"+bid+"']").prop('selected', true);
                    }
                },
                open: function() {
                    jQuery.magnificPopup.instance.close = function() {
                        jQuery(".branches_select option[value='']").prop('selected', true);
                        $branches_select.change();
                        jQuery.magnificPopup.proto.close.call(this);
                    };
                },
            }
        });
    });

	//Homepage slider
	$home_slider.slick({
		autoplay      : true,
		autoplaySpeed : 4000,
		arrows        : false,
		dots          : false,
		rtl           : true
	});

	//Update Branches popup actions links
    jQuery(".actions a.red_button").click(function(e){

        if(jQuery(this).hasClass("not_active")){
            e.preventDefault();
            return false;
        }
        jQuery("#otype").val( jQuery(this).data("otype") );
        jQuery("#online_order_page").submit();
    });

    //Go back
    jQuery(".go_back").click(function(e){
        e.preventDefault();
        window.history.back();
    });

	jQuery(document).foundation();

});

/**************************
	Functions
**************************/
function shipping_mode_change(methodType){
    if(methodType == "noshipping"){
        jQuery("#order_address").val("איסוף עצמי");
        jQuery("#order_address_field,#order_app_field,#order_number_field").slideUp();
    }else{
        jQuery("#order_address").val("");
        jQuery("#order_address_field,#order_app_field,#order_number_field").slideDown();
    }
}
function init_google_map(){
    if(locations && typeof locations !='undefined'){
        map = new google.maps.Map(document.getElementById('contact_map'), {
          zoom: 12,
          center: new google.maps.LatLng(locations[1],locations[2])
        });
    }
}

function get_regions_by_branch(bid){
    jQuery.ajax({
        type     : "post",
        dataType : "json",
        url      : ajaxurl,
        data     : {action: "get_regions_by_branch", branch_id : bid},
        success: function(response) {
            if( response.status === 'ok' ){
                jQuery(".regions_select").html(response.html);
                jQuery(".regions_select").prop('disabled', false);
                jQuery(".regions_select").removeAttr("disabled");
                hide_popup_loader();
            } else {
                jQuery(".regions_select").html(response.html);
                jQuery(".regions_select").attr("disabled", "disabled");
                jQuery(".actions a.red_button").addClass("not_active");
                hide_popup_loader();
            }
        }
    });
}

function get_minimum_order_by_region(){

    var branch_id = jQuery(".branches_select option:selected").val();
    var region_name = jQuery(".regions_select").val();

    jQuery.ajax({
        type     : "post",
        dataType : "json",
        url      : ajaxurl,
        data     : {
            action      : "get_minimum_order_by_region",
            branch_id   : branch_id,
            region_name : region_name
        },
        success: function(response) {
            jQuery(".minimum_order_value").html('&#8362;'+response);
        }
    });
}

function enable_pickup_button(){
    jQuery("a.pickup_button").removeClass("not_active");
}
function disable_pickup_button(){
    jQuery("a.pickup_button").addClass("not_active");
}
function set_minimum_order_value(){
    jQuery(".minimum_order_value").html('(בחר איזור חלוקה)');
}
