<?php
    global $product;
    $single_product_addons = get_field('single_product_addons',$post->ID);
    $addons_class = 'no_addons';
    if($single_product_addons){
        $addons_class = 'has_addons';
    }
    $price = $product->get_price_html();
?>

<article class="ajax-product-item clear" id="post-<?php echo $post->ID; ?>">

    <div class="product_item_thumb">
        <?php the_post_thumbnail('small_product_thumbnail'); ?>
    </div>
    <div class="product_item_description">
        <div class="pi_title"><?php the_title(); ?></div>
        <div class="pi_description">
            <?php echo wp_trim_words( get_the_content(), $num_words = 20, $more = null ); ?>
        </div>
    </div>
    <div class="product_item_add_to_cart trigger_product_popup">
        <a href="<?php echo $post->ID; ?>" class="<?php echo $addons_class; ?>">
            <span class="span_plus">+</span>
            <span class="span_add">הוסף</span>
            <span class="span_price"><?php echo $price; ?></span>
        </a>
    </div>

    <input type="hidden" name="product_id" value="<?php echo $post->ID; ?>">
    <input type="hidden" name="add-to-cart" value="<?php echo $post->ID; ?>">

</article>
