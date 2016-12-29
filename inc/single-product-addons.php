<?php
    $post_id               = $post->ID;
    $single_product_addons = get_field('single_product_addons',$post_id);
    if($single_product_addons):
?>
<style media="screen">
    .product_popup_pre_title {display: block;}
</style>
    <div id="single_product_addons">
        <div class="product_addons_wrapper">
            <?php
            $addon_counter = 0;
            foreach($single_product_addons as $product_addon):
                $data_max = $data_min = '';
                if( $max = $product_addon['addon_maximum'] ) {
                    $data_max = 'data-max="'.$max.'"';
                }
                if( $min = $product_addon['addon_minimum'] ) {
                    $data_min = 'data-min="'.$min.'"';
                }
                $addon_counter++;
            ?>
                <div class="product_addon_section clear addon_section_<?php echo $addon_counter; ?>" <?php echo $data_max; ?> <?php echo $data_min; ?>>

                    <?php if($product_addon['addon_title']): ?>
                        <h3><?php echo $product_addon['addon_title']; ?></h3>
                    <?php endif; ?>

                    <?php if($product_addon['addon_options']): ?>
                        <div class="product_addon_options_section clear">
                            <?php
                                $addon_options_counter = 0;
                                foreach( $product_addon['addon_options'] as $addon_option ):
                                    $addon_options_counter++;
                                    $chkbox_id = $addon_options_counter."_".$addon_counter;
                            ?>
                                <?php if( $addon_option['addon_item_title'] ) : ?>
                                    <div class="product_addon_option addon_option_<?php echo $addon_options_counter;?>"
                                        data-optionname="<?php echo $product_addon['addon_title']; ?>">

                                        <?php /*
                                        <label>
                                            <input type="checkbox" name="addon_price[<?php echo isset($product_addon['addon_title']) ? $product_addon['addon_title'] : ""; ?>]"
                                            value="<?php echo isset($addon_option['addon_item_title']) ? $addon_option['addon_item_title'] : ""; ?>" class="addon_checkbox_element">
                                            <?php echo $addon_option['addon_item_title']; ?>
                                            <?php if( $addon_option['addon_item_price'] ) : ?>
                                                <?php echo get_woocommerce_currency_symbol(); ?><?php echo $addon_option['addon_item_price']; ?>
                                            <?php endif; ?>
                                        </label>
                                        */ ?>

                                        <div class="squaredFour">
                                            <input id="addon_<?php echo $chkbox_id;?>" type="checkbox" name="addon_price[<?php echo isset($product_addon['addon_title']) ? $product_addon['addon_title'] : ""; ?>]"
                                            value="<?php echo isset($addon_option['addon_item_title']) ? $addon_option['addon_item_title'] : ""; ?>" class="addon_checkbox_element">
                                            <label for="addon_<?php echo $chkbox_id;?>"></label>
                                            <?php echo $addon_option['addon_item_title']; ?>
                                            <?php if( $addon_option['addon_item_price'] ) : ?>
                                                <?php echo get_woocommerce_currency_symbol(); ?><?php echo $addon_option['addon_item_price']; ?>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
