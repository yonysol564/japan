<?php
    $order_popup_title     = get_field('order_popup_title','options');
    $shipping_button_title = get_field('shipping_button_title','options');
    $pickup_button_title   = get_field('pickup_button_title','options');
    $online_order_page     = get_field('online_order_page','options');
    $minimum_order_label   = get_field('minimum_order_label','options');
    $branches              = get_japan_branches();
?>

<div id="branches_popup" class="popup_layer mfp-hide">
    <div class="popup_layer_inner">

        <h3><?php echo $order_popup_title; ?> <span class="popup ajax_loader" style="opacity: 0;"></span></h3>

        <form name="online_order_page" id="online_order_page" action="<?php echo $online_order_page;?>">

            <?php if($branches): ?>
                <select class="branches_select" name="bid">
                    <option value="">בחר סניף</option>
                    <?php foreach( $branches as $snif_id=>$snif_title ): ?>
                        <option value="<?php echo $snif_id;?>"><?php echo $snif_title; ?></option>
                    <?php endforeach; ?>
                </select>

                <select class="regions_select" name="region" disabled>
                    <option value="">בחר איזור חלוקה</option>
                </select>

                <?php if($minimum_order_label): ?>
                    <div class="minimum_order_label">
                        <?php echo $minimum_order_label; ?> <span class="minimum_order_value">(בחר איזור חלוקה)</span>
                    </div>
                <?php endif; ?>

                <div class="actions">
                    <input type="hidden" id="otype" name="otype" value="shipping" />
                    <a href="#" data-otype="shipping" class="shipping_button red_button not_active">
                        <?php echo $shipping_button_title; ?>
                    </a>
                    <a href="#" data-otype="pickup" class="pickup_button red_button not_active">
                        <?php echo $pickup_button_title; ?>
                    </a>
                </div>
            <?php endif; ?>

        </form>
    </div>
</div>
