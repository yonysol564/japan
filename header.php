<?php
	global $header_left_menu,$order_button_title;
	$site_logo           = get_field('site_logo','options');
	$order_button_title  = get_field('order_button_title','options');
	$header_left_menu	 = get_field('header_left_menu_repeater','options');
	$mobile_phone_number = get_field('mobile_phone_number','option');
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
	<link href="//www.google-analytics.com" rel="dns-prefetch" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<script>
      // Picture element HTML5 shiv
      document.createElement( "picture" );
	  var themeUrl = "<?php echo THEME; ?>";
    </script>
    <script src="<?php echo THEME; ?>/js/picturefill.min.js" async></script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div class="wrapper">

<?php get_template_part("inc/mobile","menu"); ?>

<header class="header clear" role="banner">

	<div class="header_top">
		<div class="header_top_inner">
			<div class="row">
				<div class="large-12 columns">
					<div class="main_header_menu">
						<?php header_menu(); ?>
					</div>
				</div>
			</div>
			<div class="mobile_header">
				<a href="#" class="mobile_nav"></a>
				<?php if($mobile_phone_number): ?>
					<a href="tel:<?php echo $mobile_phone_number; ?>" class="mobile_phone"></a>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="header_bottom">
		<div class="header_buttom_inner">
			<div class="row">
				<div class="large-12 columns">

					<div class="logo_wrapper">
						<a href="<?php echo home_url(); ?>">
							<img src="<?php echo $site_logo['url']; ?>" alt="<?php bloginfo('name'); ?>" class="site_logo" />
						</a>
					</div>

					<?php get_template_part("inc/desktop","header-bottom");?>
					<?php get_template_part("inc/mobile","header-bottom");?>

				</div>
			</div>
		</div>
	</div>

</header>
