<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
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
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li <?php wc_product_cat_class( '', $category ); ?>>
			
		<div class="progression-studios-woocommerce-index-container  <?php echo esc_attr( get_theme_mod('progression_studios_blog_transition', 'progression-studios-blog-image-scale') ); ?>">
		
	
			<div class="progression-studios-feaured-shop progression-studios-feaured-image">
				<?php do_action( 'woocommerce_before_subcategory', $category ); ?>
				<?php do_action( 'woocommerce_before_subcategory_title', $category ); ?>
				<?php  do_action( 'woocommerce_after_subcategory', $category ); ?>
			</div>
	
			<div class="progression-studios-shop-text-container">
				<?php do_action( 'woocommerce_before_subcategory', $category ); ?><?php do_action( 'woocommerce_shop_loop_subcategory_title', $category ); ?>	<?php  do_action( 'woocommerce_after_subcategory', $category ); ?>
				<?php do_action( 'woocommerce_after_subcategory_title', $category ); ?>
				<div class="clearfix-pro"></div>
			</div>
		
		
		</div><!-- close .progression-studios-woocommerce-index-container -->
	
</li>
