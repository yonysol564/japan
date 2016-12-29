<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author     WooThemes
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}
	$product_id_number = get_field('product_id_number',$post->ID);
?>
<div class="product_popup_pre_title">בחר תוספות למנה</div>
<h1 itemprop="name" class="product_title entry-title">
	<?php if($product_id_number): ?><span class="product_id_number"><?php echo $product_id_number; ?>.</span><?php endif; ?>
	<?php echo get_the_title(); ?>
</h1>
