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
		var weekTimestamp = 7 * 24 * 60 * 60 * 1000;
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
	
	function startDatePicker(pickerClass) {
		var pickerClass = pickerClass;
		$('.' + pickerClass).datepicker({ dateFormat: "dd/mm/yy" });
	}
	
	$('.edit').click(function() {
		var input, origField;
		var editType = $(this).attr('data-edit-type');
		switch(editType) {
			case 'text':
			case 'url':
			case 'tel':
				origField = $(this).siblings('.text-info').text();
				input = $('<input id="attribute" type="text" value="' + origField + '" />');
				$(this).siblings('.text-info').text('').append(input);
				break;
			case 'number':
				origField = $(this).siblings('.text-info').text();
				input = $('<input id="attribute" type="number" min="0" />')
				$(this).siblings('.text-info').text('').append(input);
				input.select();
				
				input.on('blur', function() {
				var amount = $('#attribute').val();
				amount = amount.toString();
					if(amount == '' || amount < 1 || amount.indexOf("E") > 0 || amount.indexOf("e") > 0) {
						$('#attribute').parent().html('Enter redeem amount');
					} else {
						if(round.indexOf(".") > 0) {
							round = round.slice(0, (round.indexOf(".")));
						};						
						$('#attribute').parent().html(round);
					}
					$('#currency-field').remove();
				});
				break;
			case 'currency':
				input = $('<input id="currency-field" type="number" min="0" />')
				$(this).siblings('.text-info').text('').append(input);
				input.select();
				
				input.on('blur', function() {
				var amount = $('#currency-field').val();
					if(amount == '' || amount < 0.01) {
						$('#currency-field').parent().html('Enter ticket price');
					} else {
						var round = parseFloat(amount).toFixed(2);
						$('#currency-field').parent().html('&pound;' + round);
					}
					$('#currency-field').remove();
				});
				break;
			case 'date':
				var text = $(this).siblings('.text-info').text();
				var thisFieldWrapper = $(this).closest('.control-label');
				var fieldMod;
				if( thisFieldWrapper.parents('div').hasClass('offer-details') ) {
					fieldMod = 'offer';
				} else if( thisFieldWrapper.parents('div').hasClass('event-details')) {
					fieldMod = 'event';
				}				
				var input = $('<input name="acf_fields[date]" id="' + fieldMod +'-submission-date" class="datepicker">');
				thisFieldWrapper.children('.text-info').text('').append(input);
				startDatePicker('datepicker');
				input.select();
				
				$(document).click(function(e) {
					if ( !$(e.target).is(thisFieldWrapper.children('.fa')) && !$(e.target).parents('.ui-datepicker').length > 0 && !$(e.target).is('.datepicker') ) {
						if( $('#' + fieldMod + '-submission-date').val() == '' ) {
							$('#' + fieldMod + '-submission-date').closest('.text-info').text('Select ' + fieldMod + ' date');
						}
					}
				});
				
				$('.ui-datepicker-calendar td').click(function() {
					var text = $('#' + fieldMod + '-submission-date').val();
					$('#' + fieldMod + '-submission-date').parent().text(text);
					$('#' + fieldMod + '-submission-date').remove();
				});
				break;
			case 'time':
				var thisFieldWrapper = $(this).closest('.control-label');
				var fieldMod;
				if( thisFieldWrapper.parents('div').hasClass('offer-details') ) {
					fieldMod = 'offer';
				} else if( thisFieldWrapper.parents('div').hasClass('event-details')) {
					fieldMod = 'event';
				}
				var input = $('<input name="acf_fields[start_time]" id="' + fieldMod + '-submission-start" class="timepicker"><input name="acf_fields[end_time]" id="' + fieldMod + '-submission-end" class="timepicker"><script>var options = { now: "00:00", title: "Select a Time", }; $(".timepicker").wickedpicker(options);</script>')
				$(this).siblings('.text-info').text('').append(input);
				
				$('.timepicker').focus(function() {
					$('.wickedpicker').css({'display': 'none'});
				});
				
				$(document).click(function(e) {
					if ( !$(e.target).is(thisFieldWrapper.children('.fa')) && !$(e.target).parents('.wickedpicker').length > 0 && !$(e.target).is('.timepicker') ) {
						if( $('#' + fieldMod + '-submission-start').val() == '12 : 00 AM' && $('#' + fieldMod + '-submission-end').val() == '12 : 00 AM' || $('#' + fieldMod + '-submission-start').val() == $('#' + fieldMod + '-submission-end').val() ) {
							$('#' + fieldMod + '-submission-start').closest('.text-info').text('Select ' + fieldMod + ' time');
						} else {
							var text = '<span class="starttime">' + $('#' + fieldMod + '-submission-start').val() + '</span> - <span class="endtime">' + $('#' + fieldMod + '-submission-end').val() + '</span>';
							$('#' + fieldMod + '-submission-start').closest('.text-info').html(text);
						}
					}
				});
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
	
	$('.new-offer-save').on('click', function() {
		var newOffer = $(this).closest('.offer-item');
		var offer_title = newOffer.find('label[for="offer-title"]').text();
		var offer_thumbnail = newOffer.find('img').attr('src');
		var offer_date = newOffer.find('label[for="offer-date"]').text();
		var offer_starttime = newOffer.find('label[for="offer-time"] .starttime').text();
		var offer_endtime = newOffer.find('label[for="offer-time"] .endtime').text();
		var offer_quantity = newOffer.find('label[for="offer-quantity"]').text();
		var offer_description = newOffer.find('.offer-description .text-info').text();
		
		var data = {
			offer_title: offer_title,
			offer_thumbnail: offer_thumbnail,
			offer_date: offer_date,
			offer_starttime: offer_starttime,
			offer_endtime: offer_endtime,
			offer_quantity: offer_quantity,
			offer_description: offer_description
		}
		
		newOffer.find('label[for="offer-title"] .text-info').text('Offer Title');
		newOffer.find('label[for="offer-date"] .text-info').text('Select offer date');
		newOffer.find('label[for="offer-time"] .text-info').text('Select offer time');
		newOffer.find('label[for="offer-quantity"] .text-info').text('Enter redeem amount');
		newOffer.find('.offer-description .text-info').text('Full Description + T&C');
		
		newOffer.closest('.accordion-content').toggle();
		
		var post_template = wp.template( 'oot-offer' );
		$( '.current-offers .col-content' ).append( post_template( data ) );
	});
		
	$('.new-event-save').on('click', function() {
		var newEvent = $(this).closest('.event-item');
		var event_title = newEvent.find('label[for="event-title"]').text();
		var event_thumbnail = newEvent.find('img').attr('src');
		var event_date = newEvent.find('label[for="event-date"]').text();
		var event_starttime = newEvent.find('label[for="event-time"] .starttime').text();
		var event_endtime = newEvent.find('label[for="event-time"] .endtime').text();
		var event_quantity = newEvent.find('label[for="event-quantity"]').text();
		var event_description = newEvent.find('.event-description .text-info').text();
		var event_repeat = newEvent.find('.event-description input[type="radio"]:checked').attr('data-value');
		var event_random_id = Date.now();
		
		var data = {
			event_title: event_title,
			event_thumbnail: event_thumbnail,
			event_date: event_date,
			event_starttime: event_starttime,
			event_endtime: event_endtime,
			event_quantity: event_quantity,
			event_description: event_description,
			event_random_id: event_random_id
		}
		
		newEvent.find('label[for="event-title"] .text-info').text('Event Title');
		newEvent.find('label[for="event-date"] .text-info').text('Select event date');
		newEvent.find('label[for="event-time"] .text-info').text('Select event time');
		newEvent.find('label[for="event-quantity"] .text-info').text('Enter ticket price');
		newEvent.find('.event-description .text-info').text('Full Description + T&C');
		
		newEvent.closest('.accordion-content').toggle();
		
		var post_template = wp.template( 'oot-event' );
		$( '.upcoming-events .col-content' ).append( post_template( data ) );
		$( '#event-' + event_random_id ).find('#' + event_random_id + event_repeat).prop('checked', true);
	});
	
	$('.current-offers').on('click', '.offer-item .editoffer', function() {
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
	
	$('.upcoming-events').on('click', '.event-item .editevent', function() {
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