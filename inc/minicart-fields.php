<?php
    $shipping_on            = (isset($_GET['otype']) && $_GET['otype'] == 'pickup') ? sanitize_text_field( $_GET['otype'] ) : '';
    $minicart_shipping_time = get_field('minicart_shipping_time','options');
    $cart_subtotal          = WC()->cart->is_empty() ? 0 : WC()->cart->get_cart_subtotal();
?>

<div class="mini_cart_fields_wrapper">

    <div class="mini_cart_notes">
    	<textarea name="order_notes" id="order_notes" placeholder="הערות"></textarea>
    </div>

    <div class="mini_cart_pickup">

        <div class="squaredFour">
            <input type="checkbox" value="None" id="pickup" name="pickup" <?php if($shipping_on == 'pickup'): ?>checked<?php endif; ?> />
            <label for="pickup"></label>
            איסוף מהמסעדה
        </div>

        <?php /*
    	<label>
    		<input type="checkbox" name="pickup" id="pickup" <?php if($shipping_on == 'pickup'): ?>checked<?php endif; ?> />
    		איסוף מהמסעדה
    	</label>
        */ ?>
    </div>

    <div class="minicart_shipping_time">

        <?php if(!$shipping_on): ?>
    	<div>
            <strong>זמן משלוח:</strong> <?php echo $minicart_shipping_time; ?>
        </div>
        <?php endif; ?>

        <div>
            <strong>מחיר לפריטים:</strong>
            <span class="items_total_minicart"></span>
        </div>
        <?php /*
        <div>
            <strong>סך לתשלום:</strong>
            <span class="payments_total_minicart"><?php echo isset($cart_subtotal) ? $cart_subtotal :''; ?></span>
        </div>
        */?>
        <?php
            if($shipping_on != 'pickup'):
                $shipping_cost = get_chosen_region_shipping_cost();
                if($shipping_cost): ?>
                    <div class="mini_field_shipping_tax">
                        <strong>דמי משלוח:</strong>
                        <span class="minicart_shipping_coast"><?php echo get_woocommerce_currency_symbol(); ?><?php echo $shipping_cost; ?></span>
                    </div>
                <?php endif; ?>
        <?php endif; ?>
    </div>

</div>
