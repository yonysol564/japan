<?php
    /* Template Name: Order Step #2 - Phone */
    //checkout_access();
    get_header();

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

    <main class="main_container space_holder">

        <div class="row page_header_title">
            <div class="large-12 columns">
                <h1 class="page_title"><span></span> <?php echo $order2_phone_page_title; ?></h1>
            </div>
        </div>

        <section class="order_step step_2">

            <div class="order_forms">
                <?php get_template_part("inc/go","back"); ?>
                <form id="user_payments_phone" name="user_payments_phone">
                    <div class="row">
                        <div class="large-8 columns right_form">
                            <div class="right_form_wrapper">
                                <div class="top_form_section">
                                    <?php if($order2_phone_form_title): ?>
                                        <h3><?php echo $order2_phone_form_title; ?></h3>
                                    <?php endif; ?>
                                </div>
                                <div class="phone_payment_form">
                                    <a href="#" class="send_test_sms">Send test SMS</a>
                                    <div class="inputs_wrapper clear">
                                        <label>
                                            <?php echo $order2_phone_input_desc; ?>
                                            <input class="required" required name="visa_full_name" id="visa_full_name" type="text" placeholder="<?php echo $order2_phone_code_placeholder; ?>" />
                                        </label>
                                        <input type="submit" class="submit_phone_code" value="שלח" />
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
                            <div class="large-8 columns">
                                <?php get_template_part("inc/order","steps"); ?>
                            </div>
                            <div class="large-4 columns">
                                <a id="step2-order-phone" href="<?php echo $order_step_3_link; ?>" data-city="<?php echo $region; ?>" class="next_order_step_button">המשך ></a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </section>

    </main>

<?php get_footer(); ?>
