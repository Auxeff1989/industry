<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&display=swap" rel="stylesheet">
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<?php //get_template_part( 'template-parts/header/header', 'mobile' ); ?>
	<header id="masthead" class="site-header" role="banner">
		<?php get_template_part( 'template-parts/header/header', 'menu' ); ?>
	</header><!-- #masthead -->

	<div class="site-content-contain">
		<div id="content" class="site-content">