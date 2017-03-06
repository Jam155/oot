
$(document).ready(function() {
	var events = [];
	//var len = 0;

	$.getJSON(site_url + '/wp-json/wp/v2/event', function(data) {

	    //console.log(data);
		//len = Object.keys(data).length;
	    $.each(data, function(key, element) {
	    		//console.log(element.date);
	    		element.date = element.date.slice(0,4) + '-' + element.date.slice(4,6) + '-' + element.date.slice(6,8);
	    		//console.log(element.date);
			events.push({
				Title: he.decode(element.name),
				Date: new Date(element.date),
				Repeatable: element.repeatable
			});
	    });
	
		//console.log(events);
		var today = new Date();
		var todayMonth = today.getMonth();
		var todayYear = today.getYear();
		var weekTimestamp = 	7 * 24 * 60 * 60 * 1000;
		var fortnightTimestamp = 14 * 24 * 60 * 60 * 1000;
		var margin = 1 * 60 * 60 * 1000;

		//console.log(weekTimestamp);
		//console.log(fortnightTimestamp);

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
	
	$('.editdate').click(function() {
		var text = $(this).siblings('.text-info').text();
		var input = $('<input name="acf_fields[date]" id="offer-submission-date" class="datepicker"><script>$(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });</script>')
		$(this).siblings('.text-info').text('').append(input);
		input.select();
		
		$(document).click(function(e) {
			if ( !$(e.target).hasClass('fa') && !$(e.target).parents('.ui-datepicker').length > 0 ) {
				var text = $('#offer-submission-date').val();
				$('#offer-submission-date').parent().text('Select offer date');
				$('#offer-submission-date').remove();
			}
		});

		$('.ui-datepicker-calendar td').click(function() {
			var text = $('#offer-submission-date').val();
			$('#offer-submission-date').parent().text(text);
			$('#offer-submission-date').remove();
		});

	});
	
	$('.edittime').click(function() {
		var input = $('<input name="acf_fields[start_time]" id="offer-submission-start" class="timepicker"><input name="acf_fields[end_time]" id="offer-submission-end" class="timepicker"><script>var options = { now: "00:00", title: "Select a Time", }; $(".timepicker").wickedpicker(options);</script>')
		$(this).siblings('.text-info').text('').append(input);
		//var timepickers = $('.timepicker').wickedpicker();
		$(document).click(function(e) {
			if ( !$(e.target).hasClass('fa') && !$(e.target).parents('.wickedpicker').length > 0 && !$(e.target).is('#offer-submission-start') && !$(e.target).is('#offer-submission-end') ) {
				$(e.target).closest('.text-info').text('Select offer time');
				if( $('#offer-submission-start').val() == '12 : 00 AM' && $('#offer-submission-end').val() == '12 : 00 AM' || $('#offer-submission-start').val() == $('#offer-submission-end').val() ) {
					$('#offer-submission-start').closest('.text-info').text('Select offer time');
					console.log('fields same');
				} else {
					var text = $('#offer-submission-start').val() + ' - ' + $('#offer-submission-end').val();
					$('#offer-submission-start').closest('.text-info').text(text);
					console.log('fields not same');
				}
				$('#offer-submission-start').remove();
				$('#offer-submission-end').remove();
			}
		});

		$('#offer-submission-start, #offer-submission-end').focus(function() {
			$('.wickedpicker').css({'display': 'none'});
		});

	});
	
	
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
	
	$('.editquantity').click(function() {
		var text = $(this).siblings('.text-info').text();
		var input = $('<input id="attribute" type="number" />')
		$(this).siblings('.text-info').text('').append(input);
		input.select();
	
		input.blur(function() {
			var text = $('#attribute').val();
			if(text != '') {
				$('#attribute').parent().text(text);
			} else {
				$('#attribute').parent().text('Enter redeem amount');
			}
			$('#attribute').remove();
		});
	});
	
	$('.offer-item').on('click', '.editoffer', function() {
		if( $(this).parent().parent().siblings('.offer-description').hasClass('visible') ) {
			$('.offer-description').removeClass('visible');
		} else {
			$('.offer-description').removeClass('visible');
			$(this).parent().parent().siblings('.offer-description').addClass('visible');
		}
	});
	
	$('.venue-detail-columns').on('click', '.edithours', function() {
		
		$('.savehours').show();
				
		$('.left .opening-hours li span.opentime').each(function() {
			$(this).replaceWith( '<input name="acf_fields[start_time]" id="venu-open" class="timepickerstart"><script>var options = { now: "' + moment($(this).text(), ["h:mm A"]).format("HH:mm") + '", title: "Select a Time", }; $(".timepickerstart").wickedpicker(options);</script>' );
		});
		
		$('.left .opening-hours li span.closetime').each(function() {
			$(this).replaceWith( '<input name="acf_fields[start_time]" id="venu-close" class="timepickerend"><script>var options = { now: "' + moment($(this).text(), ["h:mm A"]).format("HH:mm") + '", title: "Select a Time", }; $(".timepickerend").wickedpicker(options);</script>' );
		});
		
		if( $(this).parent().parent().parent().siblings('.edit-hours-content').hasClass('visible') ) {
			$('.edit-hours-content').removeClass('visible');
		} else {
			$('.edit-hours-content').removeClass('visible');
			$(this).parent().parent().parent().siblings('.edit-hours-content').addClass('visible');
		}
		
		$('.timepickerstart, .timepickerend').focus(function() {
			$('.wickedpicker').css({'display': 'none'});
		});
		
	});
	
	$('.venue-detail-columns').on('click', '.savehours', function() {
				
		$('.left .opening-hours li input.timepickerstart').each(function() {
			$(this).replaceWith( '<span class="opentime">' + moment($(this).val(), ["h : mm A"]).format("h:mm A") + '</span>' );
		});
		
		$('.left .opening-hours li input.timepickerend').each(function() {
			$(this).replaceWith( '<span class="closetime">' +  moment($(this).val(), ["h : mm A"]).format("h:mm A") + '</span>' );
		});
		
		if( $(this).parent().parent().parent().siblings('.edit-hours-content').hasClass('visible') ) {
			$('.edit-hours-content').removeClass('visible');
		} else {
			$('.edit-hours-content').removeClass('visible');
			$(this).parent().parent().parent().siblings('.edit-hours-content').addClass('visible');
		}
		
		$('.savehours').hide();
		
	});
	
	$('.event-item').on('click', '.editevent', function() {
		if( $(this).parent().parent().siblings('.event-description').hasClass('visible') ) {
			$('.event-description').removeClass('visible');
		} else {
			$('.event-description').removeClass('visible');
			$(this).parent().parent().siblings('.event-description').addClass('visible');
		}
	});
	
	$('.venue-active-tags').on('click', '.del', function() {
		$(this).parent().remove();
	});
	
	$('.tag-result').on('click', '.result-tag', function() {
		$('.tag-search-field').val("");
		$('.venue-active-tags').append( '<span class="venue-tag" data-tag="' + $(this).text() + '">' + $(this).text() + '<i class="fa fa-close del" aria-hidden="true"></i></span>' );
		$(this).remove();
	});
	
	$('.tag-result').on('click', '.no-match-tag', function() {
		$('.venue-active-tags').append( '<span class="venue-tag" data-tag="' + $('.tag-search-field').val() + '">' + $('.tag-search-field').val() + '<i class="fa fa-close del" aria-hidden="true"></i></span>' );
		$('.tag-search-field').val("");
		$(this).remove();
	});

	$('.edit-textarea').click(function() {
		var input = $('<textarea cass="new-offer-desc-textarea" id="attribute" />')
		var origText = $(this).siblings('.text-info-wrapper').children('.text-info').text();
		var text = $(this).siblings('.text-info-wrapper').children('.text-info').text();
		$(this).siblings('.text-info-wrapper').children('.text-info').text('').append(input);
		input.text(text);
		input.select();

		input.blur(function() {
			var text = $(this).val();
			if(text != '') {
				$(this).parent().text(text);
			} else {
				$(this).parent().text(origText);
			}
			$(this).remove();
		});
	});
	
	$('.edit-offertextarea').click(function() {
		var input = $('<textarea cass="new-offer-desc-textarea" id="attribute" />')
		var text = $(this).siblings('.text-info-wrapper').children('.text-info').text();
		$(this).siblings('.text-info-wrapper').children('.text-info').text('').append(input);
		input.text(text);
		input.select();
	
		input.blur(function() {
			var text = $(this).val();
			if(text != '') {
				$(this).parent().text(text);
			} else {
				$(this).parent().text('Full Description + T&C');
			}
			$(this).remove();
		});
	});
	
	// VENU SUBMIT
	$('.save-venue-btn').click(function() {
		console.log('Saving...');
		
		var post_id = '57';
		var name = $("label[for='venue-title'] .text-info").text();
		var address_line_1 = $("label[for='venue-address-1'] .text-info").text();
		var address_line_2 = '';
		var post_code = $("label[for='venue-post-code'] .text-info").text();
		var city = $("label[for='venue-city'] .text-info").text();
		var phone = $("label[for='venue-phone'] .text-info").text();
		var website = $("label[for='venue-website'] .text-info").text();
		var twitter = $("label[for='venue-twitter'] .text-info").text();
		var facebook = $("label[for='venue-facebook'] .text-info").text();
		var description = $(".venue-details .text-info-wrapper .text-info").text();
		var term_id = $(".cat-select select").val();
		var catname = $(".cat-select select").find(":selected").text();
		console.log( website );
		
		function makeSlug(str) {
			var makeSlug = '';
			var trimmed = $.trim(str);
			$makeSlug = trimmed.replace(/[^a-z0-9-]/gi, '-').
			replace(/-+/g, '-').
			replace(/^-|-$/g, '');
			return $makeSlug.toLowerCase();
		}
		var slug = makeSlug(name);
		
		var finalVenuData = {
			title: name,
			content: description,
			acf_fields: {
				phone: phone,
				website: website
			}
			/*
			categories: {
				term_id: term_id,
				name: catname,
				slug: slug
			},
            address: {
				address_line_1: address_line_1,
				address_line_2: address_line_2,
				post_code: post_code,
				city: city
			},
			contact: {
				phone: phone,
				website: website,
				twitter: twitter,
				facebook: facebook
			}
			*/
		};

		var valid = 'true';
		
		if(valid) {
			$.ajax({
				method: "PUT",
				url: POST_SUBMITTER.root + 'wp/v2/venue/' + post_id,
				data: finalVenuData,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', POST_SUBMITTER.nonce );
				},
				success : function( response ) {
					console.log( response );
					alert( POST_SUBMITTER.success );
				},
				fail : function( response ) {
					console.log( response );
					alert( POST_SUBMITTER.failure );
				}
			});
			
			$( ".venue-active-tags .venue-tag" ).each(function() {
				console.log( $(this).text() );
			});
		}
	});
	
});