jQuery(document).ready(function() {

    jQuery('#example').DataTable({
        "iDisplayLength": 25
    });

    jQuery("body").on("change","input.product_price", function(){
        var branch_id = jQuery("#post_ID").val();
        metabox_update_product_price( jQuery(this), jQuery(this).val(), jQuery(this).data("id"), branch_id);
    });
    jQuery("body").on("change","input.product_not_in", function(){
        var status = 1;
        var branch_id = jQuery("#post_ID").val();
        if( jQuery(this).is(":checked") ) {
            status = 0;
        }
        metabox_update_product_status( jQuery(this), jQuery(this).data("id"), status ,branch_id );
    });

} );


function metabox_update_product_price(element,product_price,product_id , branch_id){
    if( product_id && product_price ) {
        jQuery.ajax({
            type     : "post",
            dataType : "json",
            url      : ajaxurl,
            data     : {
                action: "update_product_price_metabox",
                product_price : product_price,
                product_id : product_id,
                branch_id : branch_id
            },
            success: function(response) {
                if(response){
                    element.effect( "highlight", {color:"#00c387"}, 2000 );
                }
            }
        });
    }

}

function metabox_update_product_status(element,product_id,status ,branch_id){
    if( product_id ) {
        jQuery.ajax({
            type     : "post",
            dataType : "json",
            url      : ajaxurl,
            data     : {
                action: "update_product_stock_status_metabox",
                product_id : product_id ,
                status : status,
                branch_id : branch_id
            },
            success: function(response) {
                if(response && response.status=='ok'){
                    element.effect( "highlight", {color:"#00c387"}, 2000 );
                    element.parents("td").find("span.stock_status_label").html(response.stock);
                    element.parents("td").find("span.stock_status_label").removeClass("instock outofstock").addClass(response.stock);
                }
            }
        });
    }

}
