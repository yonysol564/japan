<?php $cart_title = get_field('cart_title','options'); ?>

<div class="woo_cart">
    <div class="woo_cart_inner">
        <div class="cart_title">
            <span class="mini_cart_title"><?php echo $cart_title; ?></span>
            <span class="cart_icon"></span>
            <span class="ajax_loader mini_cart_loader"></span>
            <a class="close_mobile_cart" href="#">
                <img src="<?php echo THEME; ?>/images/close_mobile_cart_icon.png" alt="סגור" />
            </a>
        </div>

        <div class="ajax_mini_cart_items">
            <?php get_template_part("woocommerce/cart/mini","cart"); ?>
        </div>

    </div>
</div>
