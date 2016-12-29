<?php
    /* Template Name: Order Step #4 - Thank You Page */
    //checkout_access();
    get_header();

    $otype  = isset($_GET['otype']) ? sanitize_text_field($_GET['otype'])   : '';
    $bid    = get_selected_branch_id();
    $region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
    $oid    = isset($_GET['oid']) ? sanitize_text_field($_GET['oid'])       : '';

    $order4_page_title  = get_field('order4_page_title');
    $order4_form_title  = get_field('order4_form_title');
    $thanks_image_right = get_field('thanks_image_right');
    $thanks_image_left  = get_field('thanks_image_left');

    $order_step_5_link = home_url();

    if($oid){
        $order      = new WC_Order($oid);
        $order_meta = get_post_custom($oid);
        $user_name  = $order_meta['_billing_first_name'][0];
        $order_id   = $order->id;
    }
    if($bid){
        $branch_phone_number = get_field('branch_phone_number',$bid);
    }

    $order4_message = get_field('order4_message');
    if( isset($order_id) ){
        $order4_message = str_replace("%order_number%", $order_id, $order4_message);
    } else {
        $order4_message = str_replace("%order_number%", "", $order4_message);
    }

    if($branch_phone_number && isset($branch_phone_number)){
        $order4_message = str_replace("%branch_phone_number%", $branch_phone_number, $order4_message);
    } else {
        $order4_message = str_replace("%branch_phone_number%", "", $order4_message);
    }


?>

    <main class="main_container space_holder">

        <div class="row page_header_title">
            <div class="large-12 columns">
                <h1 class="page_title"><span></span> <?php echo $order4_page_title; ?></h1>
            </div>
        </div>

        <section class="order_step step_1">

            <div class="order_forms">

                <form id="finish_order" name="finish_order">
                    <div class="row large-collapse">

                        <div class="large-3 columns right_image">
                            <?php
                            if($thanks_image_right){
                                $right_src = $thanks_image_right['url'];
                                echo '<img src="'.$right_src.'" />';
                            }
                            ?>
                        </div>

                        <div class="large-6 columns thanks_wrapper">
                            <div class="right_form_wrapper">
                                <div class="top_form_section">
                                    <?php if($order4_form_title): ?>
                                        <h3><?php echo $order4_form_title; ?> <?php echo isset($user_name) ? $user_name : ''; ?></h3>
                                    <?php endif; ?>
                                </div>
                                <div class="order_review_message">
                                    <?php echo $order4_message; ?>
                                </div>
                            </div>
                        </div>

                        <div class="large-3 columns left_image">
                            <?php
                            if($thanks_image_left){
                                $left_src = $thanks_image_left['url'];
                                echo '<img src="'.$left_src.'" />';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="steps_section">
                        <div class="row">
                            <div class="large-8 columns">
                                <?php get_template_part("inc/order","steps"); ?>
                            </div>
                            <div class="large-4 columns">
                                <a id="step4-order" href="<?php echo $order_step_5_link; ?>" class="next_order_step_button">סיום ></a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </section>

    </main>

<?php get_footer(); ?>
