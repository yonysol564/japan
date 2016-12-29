<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
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
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
?>

<div class="row product_data_row">
	<div class="sp_description">
		<div class="sp_title"><?php woocommerce_template_single_title(); ?></div>
		<div class="sp_content"><?php woocommerce_template_single_excerpt(); ?></div>
	</div>
	<div class="sp_thumbnail">
	<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail('large_product_thumbnail');
		}
	?>
	</div>
</div>
