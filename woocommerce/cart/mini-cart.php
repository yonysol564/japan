<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}

	$bid    = get_selected_branch_id();
	$otype  = get_otype();
	$region = get_selected_region();

	$shipping_regions         = get_field('shipping_regions', $bid);
	$minicart_shipping_notes  = get_field('minicart_shipping_notes','options');
	$minicart_shipping_cities = get_field('minicart_shippimg_cities','options');
	$order_step1_url          = get_field('order_step_1','option');

	$order_step1_url = add_query_arg(array("bid"=>$bid, "otype"=>$otype, "region"=>$region),$order_step1_url);
	$subtotal = WC()->cart->get_cart_subtotal();
	$subtotal_clean = (int)str_replace("&#8362;","",strip_tags($subtotal));
	$minimum_order_price = get_minimum_order_by_region(true);
?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>

<ul class="cart_list product_list_widget" >

	<?php if ( ! WC()->cart->is_empty() ) : ?>

		<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
					$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

					$product_notes = isset($cart_item['product_notes']) ? sanitize_text_field($cart_item['product_notes']): '';

					?>
					<li class="<?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?> clear" data-productid="<?php echo $product_id; ?>">
						<?php
						echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
							'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">&times;</a>',
							esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
							__( 'הסר פריט', 'woocommerce' ),
							esc_attr( $product_id ),
							esc_attr( $_product->get_sku() ),
							$cart_item_key
						), $cart_item_key );
						?>
						<?php if ( ! $_product->is_visible() ) : ?>
							<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . $product_name . '&nbsp;'; ?>
						<?php else : ?>
							<div class="mini_cart_link">
								<div class="mini_cart_item_thumbnail"><?php echo $thumbnail; ?></div>
								<div class="mini_cart_item_data">
									<div class="mini_cart_item_title"><?php echo $product_name; ?></div>
									<div class="mini_cart_item_price"><?php echo $product_price; ?></div>
									<?php cart_item_addons($cart_item); ?>
									<div class="mini_cart_item_controls">
										<div class="edit_mini_cart_item">
											<a href="#" class="grey_button edit_cart_item"
											data-productid="<?php echo $product_id; ?>"
											data-cartitemkey="<?php echo $cart_item_key; ?>"
											data-addons='<?php echo esc_attr(json_encode($cart_item)); ?>'
											data-productnotes="<?php echo $product_notes; ?>"
											>ערוך</a>
										</div>
										<div class="quantity_mini_cart_item">
											<span class="mini_quantity_down" data-cartitemkey="<?php echo $cart_item_key; ?>">-</span>
											<?php
												if ( $_product->is_sold_individually() ) {
													$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
												} else {
													$product_quantity = woocommerce_quantity_input( array(
														'input_name'  => "cart[{$cart_item_key}][qty]",
														'input_value' => $cart_item['quantity'],
														'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
														'min_value'   => '1'
													), $_product, false );
												}

												echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
											?>
											<span class="mini_quantity_up" data-cartitemkey="<?php echo $cart_item_key; ?>">+</span>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<?php echo WC()->cart->get_item_data( $cart_item ); ?>

						<?php //echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
					</li>
					<?php
				}
			}
		?>

	<?php else : ?>

		<li class="empty"><?php _e( 'אין מוצרים בעגלה.', 'woocommerce' ); ?></li>

	<?php endif; ?>

</ul><!-- end product list -->

<div class="mini_cart_left_column_wrapper">
	<?php get_template_part("inc/minicart","fields"); ?>

	<?php if ( ! WC()->cart->is_empty() ) : ?>

		<div class="total">
			<?php _e( 'סה"כ לתשלום', 'woocommerce' ); ?>:
			<span class="total_span"><?php echo $subtotal; ?></span>
		</div>

		<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

		<?php /*
		<div class="shipping_notes">
			<ul class="no-bullet">
				<?php if($minicart_shipping_notes): ?>
					<li><strong>דמי משלוח:</strong> <?php echo $minicart_shipping_notes; ?></li>
				<?php endif; ?>

				<?php if( $bid && $shipping_regions ) : ?>
					<li>
						<strong>ערי משלוח:</strong>
						<?php
							$list = array();
			                foreach($shipping_regions as $data){
								$list[] = $data['region'];
							}
							if($list){
								echo implode(", ",$list);
							}
						?>
					</li>
				<?php endif; ?>
			</ul>
		</div>
		*/ ?>
		
		<div class="empty_mini_cart">
			<a href="#">איפוס הזמנה</a>
		</div>

		<div class="buttons">
			<?php if($subtotal_clean < $minimum_order_price):?>
				<div class="minimum_remark">
					<a href="#" class="button checkout wc-forward large_checkout_button not_active">מינימום הזמנה: <?php echo get_woocommerce_currency_symbol();?><?php echo $minimum_order_price;?></a>
				</div>
			<?php else:?>
				<a href="<?php echo $order_step1_url; ?>" class="button checkout wc-forward large_checkout_button">
					<?php _e( 'המשך לתשלום >', 'woocommerce' ); ?>
				</a>
			<?php endif;?>
		</div>

	<?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
