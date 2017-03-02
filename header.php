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
		<script src="<?php echo get_template_directory_uri(); ?>/js/he.js"></script>
		
		<script>
		
			
			function getActiveTags() {
				var activeTags = new Array();
				$('.venue-active-tags').children('.venue-tag').each(function () {
					activeTags.push($(this).attr('data-tag'));
				});
				return activeTags;
			};
			
			$( function() {
				$('.datepicker').datepicker({ dateFormat: 'dd/mm/yy' });
				var options = {
					now: "00:00",
					title: "Select a Time",
				};
				$('.timepicker').wickedpicker(options);
			});
			$( document ).ready(function() {
				$('.col-accordion-wrapper .col-title').click(function() {
					$(this).siblings('.accordion-content').toggle();
				});
			});
						
			$(document).ready(function(){
				$('.tag-search-field').on("keyup input", function(){
					var inputVal = $(this).val();
					var resultDropdown = $(this).siblings(".tag-result");
					var currentPostTags = getActiveTags();
					if(inputVal.length){
						$.get("<?php echo get_template_directory_uri(); ?>/ajax-search-tag.php", {term: inputVal, post_tags: currentPostTags }).done(function(data){
							resultDropdown.html(data);
						});
					} else{
						resultDropdown.empty();
					}
				});
				$(document).on("click", ".tag-result p", function(){
					$(this).parents(".search-box").find('input[type="text"]').val($(this).text());
					$(this).parent(".tag-result").empty();
				});
			});
		</script>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="wrapper" class="hfeed">
		
		    <div class="search-box">