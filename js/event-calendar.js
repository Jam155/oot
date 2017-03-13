
$(document).ready(function() {
	var events = [];

	$.getJSON(site_url + '/wp-json/wp/v2/event', function(data) {
	    $.each(data, function(key, element) {
	    	element.date = element.date.slice(0,4) + '-' + element.date.slice(4,6) + '-' + element.date.slice(6,8);
			events.push({
				Title: he.decode(element.name),
				Date: new Date(element.date),
				Repeatable: element.repeatable
			});
	    });
	
		var today = new Date();
		var todayMonth = today.getMonth();
		var todayYear = today.getYear();
		var weekTimestamp = 	7 * 24 * 60 * 60 * 1000;
		var fortnightTimestamp = 14 * 24 * 60 * 60 * 1000;
		var margin = 1 * 60 * 60 * 1000;

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

	$('.venue-description-editor-wrapper').hide();
	
	$('.editdate').click(function() {

	});
	
	$('.edittime').click(function() {
		var fieldStart, fieldEnd, origText;
		if($(this).parents('label').parents('div').hasClass('offer-details')) {
			fieldStart = 'offer-submission-start';
			fieldEnd = 'offer-submission-end';
			origText = 'Select offer time';
		}
		if($(this).parents('label').parents('div').hasClass('event-details')) {
			fieldStart = 'event-submission-start';
			fieldEnd = 'event-submission-end';
			origText = 'Select event time';
		}
		var input = $('<input name="acf_fields[start_time]" id="' + fieldStart + '" class="timepicker"><input name="acf_fields[end_time]" id="' + fieldEnd + '" class="timepicker"><script>var options = { now: "00:00", title: "Select a Time", }; $(".timepicker").wickedpicker(options);</script>')
		$(this).siblings('.text-info').text('').append(input);
		
		$(document).click(function(e) {
			if ( !$(e.target).hasClass('fa') && !$(e.target).parents('.wickedpicker').length > 0 && !$(e.target).is('#event-submission-start') && !$(e.target).is('#event-submission-end') && !$(e.target).is('#offer-submission-start') && !$(e.target).is('#offer-submission-end') ) {
				
				if( $('#' + fieldStart).val() == '12 : 00 AM' && $('#' + fieldEnd).val() == '12 : 00 AM' || $('#' + fieldStart).val() == $('#' + fieldEnd).val() ) {
					$('#' + fieldStart).closest('.text-info').text(origText);
				} else {
					var text = $('#' + fieldStart).val() + ' - ' + $('#' + fieldEnd).val();
					$('#' + fieldStart).closest('.text-info').text(text);
				}

				$('#' + fieldStart).remove();
				$('#' + fieldEnd).remove();
			}
		});
	});
	
	$('#offer-submission-start, #offer-submission-end, #event-submission-start, #event-submission-end').focus(function() {
		$('.wickedpicker').css({'display': 'none'});
	});
	
	$('.edit').click(function() {
		var input, origField;
		var editType = $(this).attr('data-edit-type');
		switch(editType) {
			case 'text':
			case 'url':
			case 'tel':
				console.log('text');
				origField = $(this).siblings('.text-info').text();
				input = $('<input id="attribute" type="text" value="' + origField + '" />');
				$(this).siblings('.text-info').text('').append(input);
				break;
			case 'number':
				console.log('number');
				origField = $(this).siblings('.text-info').text();
				input = $('<input id="attribute" type="number" />')
				$(this).siblings('.text-info').text('').append(input);
				input.select();
				break;
			case 'date':
				console.log('date');
				var text = $(this).siblings('.text-info').text();
				var $this = $(this).siblings('.text-info');
				console.log($this);
				
				var input = $('<input name="acf_fields[date]" id="offer-submission-date" class="datepicker"><script>$(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });</script>')
				$this.text('').append(input);
				input.select();
				
				$(document).click(function(e) {
					if ( !$(e.target).hasClass('fa') && !$(e.target).parents('.ui-datepicker').length > 0 ) {
						$this.text(text);
						$this.children('input').remove();
					}
				});
				
				$('.ui-datepicker-calendar td').click(function() {
					var text = $('#offer-submission-date').val();
					$('#offer-submission-date').parent().text(text);
					$('#offer-submission-date').remove();
				});
				break;
			case 'time':
				console.log('time');
				break;
			case 'textarea':
				input = $('<textarea class="new-offer-desc-textarea" id="attribute" />')
				origField = $(this).siblings('.text-info-wrapper').children('.text-info').text();
				$(this).siblings('.text-info-wrapper').children('.text-info').text('').append(input);
				input.text(origField);
				input.select();
				break;
			case 'hours':
				$('.savehours').show();	
				$('.left .opening-hours li span.opentime').each(function() {
					$(this).replaceWith( '<input name="acf_fields[start_time]" id="venu-open" class="timepickerstart"><script>var options = { now: "' + moment($(this).text(), ["h:mm A"]).format("HH:mm") + '", title: "Select a Time", }; $(".timepickerstart").wickedpicker(options);</script>' );
				});
				$('.left .opening-hours .list-hours').each(function() {
					$(this).children('.fa-plus-circle').show();
					if( $(this).children('li').length > 1 ) {
						$(this).children('.fa-minus-circle').show();
					}
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
				break;
			case 'expand':
				break;
			default:
				break;
		}
		input.select();
		input.on('blur', function() {
			var text = $('#attribute').val();
			if(text == '') {
				text = origField;
			}
			$('#attribute').parent().text(text);
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
	
	$('.left .opening-hours .list-hours').on('click', '.fa-minus-circle', function() {
		$(this).parent().children('li').last().remove();
		if( $(this).parent().children('li').length == 1 ) {
			$(this).hide();
		}
		
		$('.timepickerstart, .timepickerend').focus(function() {
			$('.wickedpicker').css({'display': 'none'});
		});
	});
	
	$('.left .opening-hours .list-hours').on('click', '.fa-plus-circle', function() {
		$('<li><input name="acf_fields[start_time]" id="venu-open" class="timepickerstart"><script>var options = { now: "00:00", title: "Select a Time", }; $(".timepickerstart").wickedpicker(options);</script> - <input name="acf_fields[start_time]" id="venu-close" class="timepickerend"><script>var options = { now: "00:00", title: "Select a Time", }; $(".timepickerend").wickedpicker(options);</script></li>').insertAfter($(this).parent().children('li').last());
		if( $(this).parent().children('li').length > 1 ) {
			$(this).siblings('.fa-minus-circle').show();
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
		$('.left .opening-hours .list-hours .fa-minus-circle').hide();
		$('.left .opening-hours .list-hours .fa-plus-circle').hide();
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
	
	$('.edit-offertextarea').click(function() {
		var input = $('<textarea class="new-offer-desc-textarea" id="attribute" />')
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
		
		var post_id = $(this).attr('data-post-id');
		var featured_media = '';
		var venue_image = $(".venue-img img").attr('src');
		var name = $("label[for='venue-title'] .text-info").text();
		var address_1 = $("label[for='venue-address-1'] .text-info").text();
		var address_2 = $("label[for='venue-address-2'] .text-info").text();
		var post_code = $("label[for='venue-post-code'] .text-info").text();
		var city = $("label[for='venue-city'] .text-info").text();
		var phone = $("label[for='venue-phone'] .text-info").text();
		var website = $("label[for='venue-website'] .text-info").text();
		var twitter = $("label[for='venue-twitter'] .text-info").text();
		var facebook = $("label[for='venue-facebook'] .text-info").text();
		var description = $(".venue-details .text-info-wrapper .text-info").text();
		var term_id = $(".cat-select select").val();
		var catname = $(".cat-select select").find(":selected").text();
		var openinghours = getOpeningHours();
		var tags = getTags();
		
		if(venue_image.indexOf("150x150") > -1) {
		   //console.log( "image hasn't changed" );
		} else {
			var featured_media = $(".venue-img img").attr('data-image-id');
		}
		
		function getTags() {
			var tagsArray = [];
			$( ".venue-active-tags .venue-tag" ).each(function() {
				tagsArray.push( $(this).text() );
			});
			return tagsArray;
		}
		
		function makeSlug(str) {
			var makeSlug = '';
			var trimmed = $.trim(str);
			$makeSlug = trimmed.replace(/[^a-z0-9-]/gi, '-').
			replace(/-+/g, '-').
			replace(/^-|-$/g, '');
			return $makeSlug.toLowerCase();
		}
		var slug = makeSlug(name);
		
		function getOpeningHours() {
			var weekDays = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
			var dayObject = [];
			var times = [];
			var i = 0;
			
			$('.left .opening-hours tr').each(function(index) {
					$(this).children('td').children('ul').children('li').each(function() {
						var open_t = $(this).children('span.opentime').text();
						var close_t = $(this).children('span.closetime').text();
						times.push({ open: open_t, close: close_t });
					});
					dayObject[weekDays[index]] = times;
					times = [];
					i++;
			});
			return dayObject;
		}
		
		var finalVenuData = {
			post_id: post_id,
			name: name,
			description: description,
			categories: {
				term_id: term_id,
				name: catname,
				slug: makeSlug(catname)
			},
			tags: tags,
			times: openinghours,
			address: {
				address_line_1: address_1,
				address_line_2: address_2,
				post_code: post_code,
				city: city	
			},
			contact: {
				phone: phone,
				website: website,
				twitter: twitter,
				facebook: facebook
			}
		};
		
		console.log(finalVenuData);

		var valid = 'true';
		
		if(valid) {
			/*
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
			
			*/
			
		}
	});
	
});