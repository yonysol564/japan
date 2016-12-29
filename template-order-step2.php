<?php
    /* Template Name: Order Step #2 */
    checkout_access();
    get_header();

    $otype  = isset($_GET['otype']) ? sanitize_text_field($_GET['otype'])   : '';
    $bid    = isset($_GET['bid']) ? sanitize_text_field($_GET['bid'])       : '';
    $region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
    $oid    = isset($_GET['oid']) ? sanitize_text_field($_GET['oid'])       : '';

    $order2_page_title         = get_field('order2_page_title');
    $order2_form_title         = get_field('order2_form_title');
    $order2_visa_full_name     = get_field('order2_visa_full_name');
    $order2_visa_number        = get_field('order2_visa_number');
    $order2_visa_secure_number = get_field('order2_visa_secure_number');

    $order_step_3_link = add_query_arg(array('otype'=>$otype, 'bid'=>$bid, 'region'=>$region, 'oid' => $oid), get_field('order_step_3','option') );
?>

    <main class="main_container space_holder">

        <div class="row page_header_title">
            <div class="large-12 columns">
                <h1 class="page_title"><span></span> <?php echo $order2_page_title; ?></h1>
            </div>
        </div>

        <section class="order_step step_2">

            <div class="order_forms">
                <?php get_template_part("inc/go","back"); ?>
                <form id="user_payments" name="user_payments">
                    <div class="row">
                        <div class="large-6 columns right_form">
                            <div class="right_form_wrapper">
                                <div class="top_form_section">
                                    <?php if($order2_form_title): ?>
                                        <h3><?php echo $order2_form_title; ?></h3>
                                    <?php endif; ?>
                                    <div class="payment_method --visa">
                                        <a href="#" data-method="visa" data-oid="<?php echo $oid; ?>">אשראי</a>
                                    </div>
                                </div>
                                <div class="payments_visa_form_wrapper">
                                    <div>
                                        <input class="required" required name="visa_full_name" id="visa_full_name" type="text" placeholder="<?php echo $order2_visa_full_name; ?>" />
                                    </div>
                                    <div>
                                        <input class="required" required name="visa_card_number" id="visa_card_number" type="number" placeholder="<?php echo $order2_visa_number; ?>" />
                                    </div>
                                    <div>
                                        <input class="required" required name="visa_secure_number" id="visa_secure_number" type="number" placeholder="<?php echo $order2_visa_secure_number; ?>" min="3" max="3" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="large-6 columns left_form">
                            <div class="left_form_wrapper">
                                <div class="top_form_section">
                                    <div class="payment_method --cash">
                                        <a href="#" data-method="cash" data-oid="<?php echo $oid; ?>">מזומן</a>
                                    </div>
                                </div>

                                <?php if( WC()->cart->cart_contents_total ): ?>
                                    <div class="order1_minicart_total align_bottom">
                                        <div class="order_total_label">סה"כ לתשלום</div>
                                        <div class="order_total_value">
                                            <?php echo get_woocommerce_currency_symbol(); ?><?php echo WC()->cart->cart_contents_total; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="steps_section">
                        <div class="row">
                            <div class="large-8 columns">
                                <?php get_template_part("inc/order","steps"); ?>
                            </div>
                            <div class="large-4 columns">
                                <a id="step2-order" href="<?php echo $order_step_3_link; ?>" data-city="<?php echo $region; ?>" class="next_order_step_button">המשך ></a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </section>

    </main>

<?php get_footer(); ?>
