<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
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
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
$add_to_cart_button_text = get_field('add_to_cart_button_text','option') ? get_field('add_to_cart_button_text','option') : 'הוסף להזמנה >';
if ( ! $product->is_purchasable() ) {
	return;
}
$region = get_selected_region();

?>

<?php
	// Availability
	$availability      = $product->get_availability();
	$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

	echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
?>

<?php if ( $product->is_in_stock() ) : ?>

	<?php get_template_part("inc/single-product","addons"); ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" method="post" enctype='multipart/form-data'>
	 	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	 	<?php
	 		if ( ! $product->is_sold_individually() ) {
	 			woocommerce_quantity_input( array(
	 				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
	 				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
	 				'input_value' => ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 )
	 			) );
	 		}
	 	?>

	 	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

		<?php /*
	 	<button type="submit" class="add_to_cart_button button alt" data-product_id="<?php echo $product->id; ?>">
			<?php echo $add_to_cart_button_text; ?>
		</button>
		*/ ?>

		<?php /*
		<button type="submit" data-quantity="1" data-product_id="<?php echo $product->id; ?>"class="button alt add_to_cart_button product_type_simple">
			<?php echo $product->single_add_to_cart_text(); ?>
		</button>
		*/ ?>

		<div class="sp_notes">
			<textarea name="single_product_notes" class="single_product_notes" placeholder="הערות" data-productid="<?php echo $product->id; ?>"></textarea>
		</div>

		<div class="sp_large_button">
			<a href="<?php echo $product->id; ?>" class="add_to_cart_ajax"
				data-addons="0"
				data-pid="<?php echo $product->id; ?>"
				data-productnotes=""
			>
				<?php echo $add_to_cart_button_text; ?>
			</a>

		</div>


		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
