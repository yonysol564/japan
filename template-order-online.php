<?php
    /* Template Name: Online Order */
    checkout_access();

    $branch_id = isset( $_GET['bid'] ) ? sanitize_text_field( $_GET['bid'] ) : '';
    if( $branch_id ) {
        //echo $branch_id;
    }

    $bid         = isset($_GET['bid']) ? sanitize_text_field($_GET['bid'])       : '';
	$otype       = isset($_GET['otype']) ? sanitize_text_field($_GET['otype'])   : '';
	$region      = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
    $order_step1 = get_field('order_step_1','option');
	if( $bid && $otype && $region && $order_step1) {
		$order_step1_link = add_query_arg(array("bid"=>$bid, "otype"=>$otype, "region"=>$region),$order_step1);
	}
    $product_categories = get_terms('product_cat', array('hide_empty' =>false));

    get_header();
?>

<main class="main_container space_holder">
    <input type="hidden" name="branch_id" value="<?php echo $bid;?>" />
    <div class="row page_header_title">
        <div class="large-12 columns">
            <h1 class="page_title"><span></span> <?php echo get_the_title(); ?></h1>
        </div>
    </div>

    <div class="row large-uncollapse medium-collapse small-collapse" id="main_ajax_row">
        <div class="medium-3 columns category_list_column">
            <div class="woo_cats_list">
                <div class="woo_cats_list_inner" data-bid="<?php echo $bid ? $bid : ''; ?>">
                    <?php foreach( $product_categories as $cat ) : ?>
                        <a href="<?php echo $cat->term_id; ?>" class="ajax-term-link">
                            <?php echo $cat->name; ?> <span class="ajax_loader"></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="medium-5 columns products_list_column">

            <div class="woo_product_category_head">
                <div class="ajax_woo_product_category_head">
                    <div class="product_category_head_title">ראשונות</div>
                    <div class="product_category_head_description">בחרו את המנות שברצונכם להזמין</div>
                </div>
            </div>

            <div class="woo_products_list">
                <div class="ajax_woo_products_list" data-link="<?php echo $order_step1_link; ?>"></div>
            </div>
        </div>
        <div class="medium-4 columns mini_cart_column">
            <div class="mobile_cart_button">
                <a href="#" class="mobile_cart_trigger">
                    <span class="in_cart_item_counter"><span></span></span>
                    <img src="<?php echo THEME; ?>/images/mobile_cart_button.png" alt="עגלת קניות" />
                </a>
            </div>
            <?php get_template_part("inc/woo","mini-cart"); ?>
        </div>
    </div>
</main>

<div id="product_popup" class="popup_layer mfp-hide">
    <div class="popup_layer_inner clear">
        <div class="popup ajax_loader"></div>
        <div class="ajax_popup_content"></div>
    </div>
</div>

<?php get_footer(); ?>
