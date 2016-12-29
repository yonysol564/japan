<?php
    /* Template Name: Order Step #3 */
    //checkout_access();
    get_header();

    $otype  = isset($_GET['otype']) ? sanitize_text_field($_GET['otype'])   : '';
    $bid    = isset($_GET['bid']) ? sanitize_text_field($_GET['bid'])       : '';
    $region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
    $oid    = isset($_GET['oid']) ? sanitize_text_field($_GET['oid'])       : '';

    $order3_page_title = get_field('order3_page_title');
    $order3_form_title = get_field('order3_form_title');
    $order3_notes      = get_field('order3_notes');

    $order_step_4_link = add_query_arg(array('otype'=>$otype, 'bid'=>$bid,'region'=>$region,'oid'=>$oid), get_field('order_step_4','option') );
?>

    <main class="main_container space_holder">

        <div class="row page_header_title">
            <div class="large-12 columns">
                <h1 class="page_title"><span></span> <?php echo $order3_page_title; ?></h1>
            </div>
        </div>

        <section class="order_step step_1">

            <div class="order_forms">
                <?php get_template_part("inc/go","back"); ?>
                <form id="products_in_cart" name="products_in_cart">
                    <div class="row">
                        <div class="large-6 columns right_form">
                            <div class="right_form_wrapper">
                                <div class="top_form_section">
                                    <?php if($order3_form_title): ?>
                                        <h3><?php echo $order3_form_title; ?></h3>
                                    <?php endif; ?>
                                </div>

                                <?php if($oid): ?>
                                    <div class="user_order_info">
                                        <?php
                                            $order = new WC_Order($oid);
                                            $order_meta = get_post_custom($oid);
                                        ?>
                                        <?php if( $order_meta['_billing_first_name'][0] ) : ?>
                                            <p><strong><?php echo $order_meta['_billing_first_name'][0]; ?></strong></p>
                                        <?php endif; ?>
                                        <?php if( $order_meta['_billing_address_1'][0] ) : ?>
                                            <p><?php echo $order_meta['_billing_address_1'][0]; ?></p>
                                        <?php endif; ?>
                                        <?php if( $order_meta['_billing_city'][0] ) : ?>
                                            <p><?php echo $order_meta['_billing_city'][0]; ?></p>
                                        <?php endif; ?>
                                        <?php if( $otype && $otype=='shipping' ) : ?>
                                            <p>משלוח</p>
                                        <?php else: ?>
                                            <p>איסוף עצמי</p>
                                        <?php endif; ?>
                                        <p>שולם באשראי</p>
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <textarea name="order_notes" id="order_notes" placeholder="<?php echo $order3_notes; ?>"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="large-6 columns left_form">
                            <div class="left_form_wrapper">
                                <?php if ( ! WC()->cart->is_empty() ) : ?>
                                    <div class="product_in_cart_wrapper">
                                        <?php
                                            $products_in = array();
                                            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                                                $products_in[] = $cart_item['product_id'];
                                            }
                                            $pargs = array(
                                                'post_type' => 'product',
                                                'post__in'  => $products_in
                                            );
                                            $incart = new WP_Query($pargs);
                                            while( $incart->have_posts() ) : $incart->the_post();
                                                $price = get_post_meta( get_the_ID(), '_regular_price', true);
                                        ?>
                                            <article class="mini_product product_item_in_cart clear">
                                                <div class="incart_thumb"><?php the_post_thumbnail('incart_product_thumbnail'); ?></div>
                                                <div class="incart_title"><?php the_title(); ?></div>
                                                <div class="incart_price"><?php echo get_woocommerce_currency_symbol();?><?php echo $price; ?></div>
                                            </article>
                                        <?php endwhile; wp_reset_query(); ?>
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
                                <a id="step3-order" href="<?php echo $order_step_4_link; ?>" data-city="<?php echo $region; ?>" class="next_order_step_button">המשך ></a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </section>

    </main>

<?php get_footer(); ?>
