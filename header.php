<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" />
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/wickedpicker.min.css">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/glDatePicker.flatwhite.css">
		<script src="https://use.fontawesome.com/70c98e5b29.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/wickedpicker.min.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/glDatePicker.min.js"></script>
		<script>
			$( function() {
				$('.datepicker').datepicker({ dateFormat: 'dd/mm/yy' });
				var options = { now: "00:00",  };
				$('.timepicker').wickedpicker(options);
				
				$('#calendar').datepicker({
					beforeShowDay: function(date) {
						var result = [true, '', null];
						var matching = $.grep(events, function(event) {
							return event.Date.valueOf() === date.valueOf();
						});
						
						if (matching.length) {
							result = [true, 'highlight', null];
						}
						return result;
					},
					onSelect: function(dateText) {
						var date,
							selectedDate = new Date(dateText),
							i = 0,
							event = null;
						
						while (i < events.length && !event) {
							date = events[i].Date;

							if (selectedDate.valueOf() === date.valueOf()) {
								event = events[i];
							}
							i++;
						}
						if (event) {
							alert(event.Title);
						}
					},
					inline: true,
					firstDay: 1,
					showOtherMonths: true,
					dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S']
				});
			});
			$( document ).ready(function() {
				$('.col-accordion-wrapper .col-title').click(function() {
					$(this).siblings('.accordion-content').toggle();
				});
			});
		</script>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="wrapper" class="hfeed">
			<header id="header">
				<h1>Oot</h1>
			</header>
			<div id="container">