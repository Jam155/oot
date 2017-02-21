<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" />
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/wickedpicker.min.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/wickedpicker.min.js"></script>
		<script>
			$( function() {
				$( '.datepicker' ).datepicker({ dateFormat: 'dd/mm/yy' });
				var options = { now: "00:00",  };
				$( '.timepicker' ).wickedpicker(options);
			} );
		</script>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<input type="text" id="example" />
		<div id="wrapper" class="hfeed">
			<header id="header" role="banner">
				<section id="branding">
					<div id="site-title">
						<?php if ( is_front_page() || is_home() || is_front_page() && is_home() ) { echo '<h1>'; } ?><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_html( get_bloginfo( 'name' ) ); ?>" rel="home"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a><?php if ( is_front_page() || is_home() || is_front_page() && is_home() ) { echo '</h1>'; } ?>
					</div>
				</section>
				<nav id="menu" role="navigation">
					<?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
				</nav>
			</header>
			<div id="container">