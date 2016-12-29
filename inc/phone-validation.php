<?php
    global $woocommerce;

    $otype  = isset($_GET['otype']) ? sanitize_text_field($_GET['otype'])   : '';
    $bid    = isset($_GET['bid']) ? sanitize_text_field($_GET['bid'])       : '';
    $region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
    $oid    = isset($_GET['oid']) ? sanitize_text_field($_GET['oid'])       : '';

    $order2_phone_page_title       = get_field('order2_phone_page_title');
    $order2_phone_form_title       = get_field('order2_phone_form_title');
    $order2_phone_input_desc       = get_field('order2_phone_input_description');
    $order2_phone_code_placeholder = get_field('order2_phone_code_placeholder');
    $resend_activation_code_label  = get_field('resend_activation_code_label');

    $order_step_3_link = add_query_arg(array('otype'=>$otype, 'bid'=>$bid, 'region'=>$region, 'oid' => $oid), get_field('order_step_3','option') );
?>

<div id="user_phone_activation">
    <form id="user_payments_phone" name="user_payments_phone">
        <a href="#" class="go_back_to_hidden_form">חזור</a>
        <div class="row">
            <div class="large-8 columns right_form">
                <div class="right_form_wrapper">
                    <div class="top_form_section">
                        <?php if($order2_phone_form_title): ?>
                            <h3><?php echo $order2_phone_form_title; ?></h3>
                        <?php endif; ?>
                    </div>
                    <div class="phone_payment_form">
                        <div class="inputs_wrapper clear">
                            <label>
                                <?php echo $order2_phone_input_desc; ?>
                                <input class="required" required name="user_activation_code" id="user_activation_code" type="text" placeholder="<?php echo $order2_phone_code_placeholder; ?>" />
                                <span class="error_validation">קוד אימות אינו תקין. אנא נסה שנית.</span>
                            </label>
                            <input type="submit" class="submit_phone_code" value="אמת קוד" />
                            <div class="submit_phone_code_success">מכשיר אומת בהצלחה!</div>
                        </div>
                        <div class="resend_activation_code_wrapper clear">
                            <a href="#" class="resend_activation_code"><?php echo $resend_activation_code_label; ?></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="phone_step_cart_total">
            <div class="phone_total_label">סה"כ לתשלום</div>
            <div class="phone_total_value"><?php echo $woocommerce->cart->get_cart_total(); ?></div>
        </div>

        <div class="steps_section">
            <div class="row">
                <div class="large-8 columns order_steps_column_wrapper">
                    <?php $order_titles = get_order_titles(); ?>
                    <?php if($order_titles): $current_step = 2; ?>
                        <div class="order_titles_wrapper clear">
                            <?php
                                $counter=0;
                                foreach($order_titles as $title):
                                    $counter++;
                                    $class="";
                                    if($counter==$current_step){
                                        $class="current_step";
                                    }
                            ?>
                                <div class="step_circle <?php echo $class; ?>">
                                    <div class="circle_title"><?php echo $title; ?></div>
                                    <div class="circle_element">
                                        <div class="circle_element_inner"><?php echo $counter; ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="large-4 columns order_steps_button_column_wrapper">
                    <a id="step2-phone-order" href="<?php echo $order_step_3_link; ?>" data-city="<?php echo $region; ?>" class="next_order_step_button disabled">המשך ></a>
                </div>
            </div>
        </div>

    </form>
</div>
