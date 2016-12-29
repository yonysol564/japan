<?php
    /* Template Name: Checkout Page */
    global $otype,$order1_about_shipping;
    //checkout_access();
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

    $choose_payment_title = get_field('choose_payment_title','option');

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

                <div class="visible_form_personal_info">
                    <?php while( have_posts() ): the_post(); ?>
                    <div id="user_personal_info">
                        <div class="checkout_wrapper">
                            <?php the_content(); ?>
                        </div>

                        <?php if($choose_payment_title): ?>
                            <div class="choose_payment_title clear">
                                <?php echo $choose_payment_title; ?>
                            </div>
                        <?php endif; ?>

                        <div class="choose_payment_status clear">
                            <a href="#" data-target="payment_method_yaadpay" class="payment_method_yaadpay">אשראי</a>
                            <a href="#" data-target="payment_method_cod" class="payment_method_cod">מזומן</a>
                        </div>
                    </div>
                    <?php endwhile; wp_reset_query(); ?>



                    <div class="steps_section">
                        <div class="row">
                            <div class="large-8 columns order_steps_column_wrapper">
                                <?php get_template_part("inc/order","steps"); ?>
                            </div>
                            <div class="large-4 columns order_steps_button_column_wrapper">
                                <a id="step1-order" href="#" data-city="<?php echo $region; ?>" class="next_order_step_button not_active">המשך ></a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php get_template_part("inc/phone","validation"); ?>

            </div>

        </section>

    </main>

<?php get_footer(); ?>
