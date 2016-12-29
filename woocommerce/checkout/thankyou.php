<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */
global $woocommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p class="woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

		<p class="woocommerce-thankyou-order-failed-actions">
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

		<style media="screen">
			.choose_payment_status,
			.steps_section {
				display: none;
			}
			#finish_order .steps_section {
				display: block;
			}
			section.order_step.step_1 {
			    clear: both;
			    margin-top: 12rem;
			}
		</style>

		<?php
	    $bid    = isset($_COOKIE['branch_id']) ? sanitize_text_field($_COOKIE['branch_id'])       : '';
	    $oid    = $order->id;
		$order_step_4_page_id = get_field('order_step_4','option');
	    $order4_page_title    = get_field('order4_page_title',$order_step_4_page_id);
	    $order4_form_title    = get_field('order4_form_title',$order_step_4_page_id);
	    $thanks_image_right   = get_field('thanks_image_right',$order_step_4_page_id);
	    $thanks_image_left    = get_field('thanks_image_left',$order_step_4_page_id);
		$woocommerce->cart->empty_cart();

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

	    $order4_message = get_field('order4_message',$order_step_4_page_id);
	    $order4_message = str_replace("%order_number%", $order_id, $order4_message);
	    if( isset($branch_phone_number) && !empty($branch_phone_number) ){
	        $order4_message = str_replace("%branch_phone_number%", $branch_phone_number, $order4_message);
	    } else {
			$order4_message = str_replace("%branch_phone_number%", "", $order4_message);
		}
		?>

		<main class="main_container space_holder">

			<section class="order_step step_4">

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
									<?php
										$order_titles = get_order_titles();
										if($order_titles):
									?>
									    <div class="order_titles_wrapper clear">
									        <?php
									            $counter=0;
									            foreach($order_titles as $title):
									                $counter++;
													$current_step = 3;
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
								<div class="large-4 columns">
									<a id="step4-order" href="<?php echo $order_step_5_link; ?>" class="next_order_step_button">סיום ></a>
								</div>
							</div>
						</div>

					</form>
				</div>

			</section>

		</main>

		<?php /*

		<p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>

		<ul class="woocommerce-thankyou-order-details order_details">
			<li class="order">
				<?php _e( 'Order Number:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<?php _e( 'Total:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<?php _e( 'Payment Method:', 'woocommerce' ); ?>
				<strong><?php echo $order->payment_method_title; ?></strong>
			</li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>

		*/ ?>

	<?php endif; ?>

	<?php //do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php //do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php endif; ?>
