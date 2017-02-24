
$(document).ready(function() {
	console.log("Stuff");
	var events = [];
	//var len = 0;

	$.getJSON(site_url + '/wp-json/wp/v2/event', function(data) {

	    console.log(data);
		//len = Object.keys(data).length;
	    $.each(data, function(key, element) {
	    		console.log(element.date);
	    		element.date = element.date.slice(0,4) + '-' + element.date.slice(4,6) + '-' + element.date.slice(6,8);
	    		console.log(element.date);
			events.push({
				Title: he.decode(element.name),
				Date: new Date(element.date),
				Repeatable: element.repeatable
			});
	    });
	
		console.log(events);
		var today = new Date();
		var todayMonth = today.getMonth();
		var todayYear = today.getYear();
		var weekTimestamp = 	7 * 24 * 60 * 60 * 1000;
		var fortnightTimestamp = 14 * 24 * 60 * 60 * 1000;
		var margin = 1 * 60 * 60 * 1000;

		console.log(weekTimestamp);
		console.log(fortnightTimestamp);

		$('#calendar').datepicker({
			onChangeMonthYear: function(year, month, inst) {
				todayMonth = month-1;
				todayYear = year;
			},
			beforeShowDay: function(date) {
				var result = [true, '', null];
				var matching = $.grep(events, function(event) {

					var result = false;
					var eventDate = new Date(event.Date);
					var eventTimestamp = eventDate.getTime();
					var curTimestamp = date.getTime();
					var diffTime = curTimestamp - eventTimestamp;

					if (diffTime == 0) {

						result = true;

					}

					if (curTimestamp > eventTimestamp) {

						var daysSince = Math.ceil(diffTime / (24 * 60 * 60 * 1000));	

						if (event.Repeatable == 'Weekly' && (daysSince % 7) == 0) {

							result = true;

						} else if (event.Repeatable == 'Fortnightly' && (daysSince % 14) == 0) {
							
							result = true;

						} else if (event.Repeatable == 'Monthly') {

							eventDate.setMonth(date.getMonth());

							if (eventDate.getTime() == date.getTime()) {

								result = true;

							}

						}

					}

					return result;

				
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

	/*
	var events = [
		{ Title: "Event 1", Date: new Date("02/02/2017") }, 
		{ Title: "Event 2", Date: new Date("02/20/2017") }, 
		{ Title: "Event 3", Date: new Date("02/16/2017") }
	];
	*/

	$('.venue-description-editor-wrapper').hide();

	$('.edit').click(function() {
		var text = $(this).siblings('.text-info').text();
		var input = $('<input id="attribute" type="text" value="' + text + '" />')
		$(this).siblings('.text-info').text('').append(input);
		input.select();
	
		input.blur(function() {
			var text = $('#attribute').val();
			$('#attribute').parent().text(text);
			$('#attribute').remove();
		});
	});

	$('.edit-textarea').click(function() {
		var text = $('.venue-description .text-info-wrapper').hide();
		$('.venue-description-editor-wrapper').show();

		$(document).mouseup(function (e) {
			if($('.venue-description-editor-wrapper').is(':visible')) {		
				var container = $('.venue-description-editor-wrapper');
				if (!container.is(e.target) && container.has(e.target).length === 0)
				{
					container.hide();
					console.log( tinyMCE.get('venuedescriptioneditor').getContent() );
					$('.venue-description .text-info-wrapper').html( tinyMCE.get('venuedescriptioneditor').getContent() );
					$('.venue-description .text-info-wrapper').toggle();
				}
			}
		});
	});
});
