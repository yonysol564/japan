<?php
    /* Template Name: Order Step #1 */
    checkout_access();
    get_header();

    $otype  = isset($_GET['otype']) ? sanitize_text_field($_GET['otype'])   : '';
    $bid    = isset($_GET['bid']) ? sanitize_text_field($_GET['bid'])       : '';
    $region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';

    $order1_page_title           = get_field('order1_page_title');
    $order1_form_title           = get_field('order1_form_title');
    $order1_fullname_placeholder = get_field('order1_fullname_placeholder');
    $order1_phone_placeholder    = get_field('order1_user_phone_placeholder');
    $order1_email_placeholder    = get_field('order1_user_email_placeholder');
    $order1_newsletters_label    = get_field('order1_newsletters_label');
    $order1_about_shipping       = get_field('order1_about_shipping');
    $order1_address_placeholder  = get_field('order1_address_placeholder');
    $order1_number_placeholder   = get_field('order1_user_number_placeholder');
    $order1_user_app_number      = get_field('order1_user_app_number');
    $order1_message_placeholder  = get_field('order1_order_messaeg');

    $order_step_2_link = add_query_arg(array('otype'=>$otype, 'bid'=>$bid,'region'=>$region), get_field('order_step_2','option') );
?>

    <main class="main_container space_holder">

        <div class="row page_header_title">
            <div class="large-12 columns">
                <h1 class="page_title"><span></span> <?php echo $order1_page_title; ?></h1>
            </div>
        </div>

        <section class="order_step step_1">

            <div class="order_forms">
                <form id="user_personal_info" name="user_personal_info">
                    <div class="row">
                        <div class="large-6 columns right_form">
                            <div class="right_form_wrapper">
                                <div class="top_form_section">
                                    <?php if($order1_form_title): ?>
                                        <h3><?php echo $order1_form_title; ?></h3>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <input class="required" required name="user_full_name" id="user_full_name" type="text" placeholder="<?php echo $order1_fullname_placeholder; ?>" />
                                </div>
                                <div>
                                    <input class="required" required name="user_phone_number" id="user_phone_number" type="tel" placeholder="<?php echo $order1_phone_placeholder; ?>" />
                                </div>
                                <div>
                                    <input class="required" required name="user_email" id="user_email" type="email" placeholder="<?php echo $order1_email_placeholder; ?>" />
                                </div>
                                <div>
                                    <input name="user_newsletters" id="user_newsletters" type="checkbox" />
                                    <label for="user_newsletters"><?php echo $order1_newsletters_label; ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="large-6 columns left_form">
                            <div class="left_form_wrapper">
                                <div class="top_form_section">
                                    <div class="shipping_methods_wrapper">
                                        <a href="#" class="shipping_button <?php if($otype && $otype=='shipping'):?>active<?php endif; ?>">משלוח</a>
                                        <a href="#" class="pickup_button <?php if($otype && $otype=='pickup'):?>active<?php endif; ?>">איסוף עצמי</a>
                                    </div>
                                    <div class="about_shipping"><strong>דמי משלוח:</strong> <?php echo $order1_about_shipping; ?></div>
                                </div>
                                <div>
                                    <input required name="user_address" id="user_address" type="text" placeholder="<?php echo $order1_address_placeholder; ?>" class="required" />
                                </div>
                                <div class="half">
                                    <input required name="user_home_number" id="user_home_number" type="number" placeholder="<?php echo $order1_number_placeholder; ?>" class="required"  />
                                </div>
                                <div class="half last-child">
                                    <input required name="user_app_number" id="user_app_number" type="number" placeholder="<?php echo $order1_user_app_number; ?>" class="required"  />
                                </div>
                                <div>
                                    <textarea id="order1_message" name="order1_message" placeholder="<?php echo $order1_message_placeholder; ?>"></textarea>
                                </div>

                                <?php if( WC()->cart->cart_contents_total ): ?>
                                    <div class="order1_minicart_total">
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
                                <a id="step1-order" href="<?php echo $order_step_2_link; ?>" data-city="<?php echo $region; ?>" class="next_order_step_button">המשך ></a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </section>

    </main>

<?php get_footer(); ?>
