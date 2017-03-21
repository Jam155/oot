$(document).ready(function() {

	$( function() {
		$('#dialog').dialog({
			modal: true,
			resizable: false
		});
	});
	
	function drawCalendar() {
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
	}
	drawCalendar();

	$('.venue-description-editor-wrapper').hide();
	
	function startDatePicker(pickerClass) {
		var pickerClass = pickerClass;
		var dateToday = new Date(); 
		$('.' + pickerClass).datepicker({ dateFormat: "dd/mm/yy", minDate: dateToday });
	}
	
	function startTimePicker(pickerClass, now) {
		var pickerClass = pickerClass;
		$('.' + pickerClass).wickedpicker({ now: now, title: "Select a Time", });
	}
	
	$('.edit').click(function() {
		var input, origField, origFieldNum;
		var editType = $(this).attr('data-edit-type');
		switch(editType) {
			case 'url':
				origField = $(this).siblings('.text-info').attr('data-orig-url');
				input = $('<input id="url-field" type="text" value="' + origField + '"/>');
				var textElem = $(this).siblings('.text-info');
				$(this).siblings('.text-info').text('').append(input);
				input.select();
				
				input.on('blur', function() {
					var fieldText = $('#url-field').val();
					var trimField = fieldText.replace(/((^\w+:|^)\/\/)?(www\.)?/, '');
					if(trimField == '') {
						trimField = origField.replace(/((^\w+:|^)\/\/)?(www\.)?/, '');
					}
					$('#url-field').parent().text(trimField);
					textElem.attr("data-orig-url", fieldText);
					$('#url-field').remove();
				});
				break;
			case 'text':
				origField = $(this).siblings('.text-info').text();
				input = $('<input id="attribute" type="text" value="' + origField + '" />');
				$(this).siblings('.text-info').text('').append(input);
				input.select();
				break;
			case 'tel':
				origField = $(this).siblings('.text-info').text();
				input = $('<input id="attribute" type="tel" value="' + origField + '" />');
				$(this).siblings('.text-info').text('').append(input);
				input.select();
				break;
			case 'number':
				origField = $(this).siblings('.text-info').text();
				input = $('<input id="attribute" type="number" min="0" />')
				$(this).siblings('.text-info').text('').append(input);
				input.select();
				
				input.on('blur', function() {
				var amount = $('#attribute').val();
				amount = amount.toString();
					if( amount == '' || amount < 1 || amount.indexOf("E") > 0 || amount.indexOf("e") > 0) {
						if( $(this).closest('.offer-item').parent('.col-content').length > 0 ) {
							$('#attribute').parent().html(origField);
						} else {
							$('#attribute').parent().html('Enter redeem amount');
						}
					} else {
						if(amount.indexOf(".") > 0) {
							amount = amount.slice(0, (amount.indexOf(".")));
						};						
						$('#attribute').parent().html(amount);
					}
					$('#currency-field').remove();
				});
				break;
			case 'currency':
				origField = $(this).siblings('.text-info').text();
				origFieldNum = origField.replace(/[^\d\.]/g, '');
				input = $('<input id="currency-field" type="number" min="0" />')
				$(this).siblings('.text-info').text('').append(input);
				input.val(origFieldNum)
				input.select();
				
				input.on('blur', function() {
				var amount = $('#currency-field').val();
					if(amount == '' || amount < 0.00) {
						if( $(this).closest('.event-item').parent('.col-content').length > 0 ) {
							$('#currency-field').parent().html(origField);
						} else {
							$('#currency-field').parent().html('Enter ticket price');
						}
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
				var randID = Date.now();
				var input = $('<input name="acf_fields[date]" id="' + fieldMod + '-submission-date' + randID + '" class="datepicker">');
				thisFieldWrapper.find('.text-info').text('').append(input);
				startDatePicker('datepicker');
				input.select();
				
				$(document).click(function(e) {
					if ( !$(e.target).is(thisFieldWrapper.children('.fa')) && !$(e.target).parents('.ui-datepicker').length > 0 && !$(e.target).is('.datepicker') && !$(e.target).is('.ui-corner-all') && !$(e.target).is('.ui-icon') ) {
						if( $('#' + fieldMod + '-submission-date').text() == '' ) {
							if(thisFieldWrapper.closest('.' + fieldMod + '-item').parent('.col-content').length > 0 ) {
								$('#' + fieldMod + '-submission-date' + randID ).closest('.text-info').text(text);
								$('#' + fieldMod + '-submission-date' + randID ).remove();
							} else {
								$('#' + fieldMod + '-submission-date' + randID ).closest('.text-info').text('Select ' + fieldMod + ' date');
								$('#' + fieldMod + '-submission-date' + randID ).remove();
							}
						}
					} else {
						$('.ui-datepicker-calendar td').click( function() {
							text = $('#' + fieldMod + '-submission-date' + randID ).val();
							$('#' + fieldMod + '-submission-date' + randID ).closest('.text-info').text(text);
							$('#' + fieldMod + '-submission-date' + randID ).remove();
						});
					}
				});
				break;
			case 'time':
				var startTime = $(this).siblings('.text-info').find('.starttime').text();
				var endTime = $(this).siblings('.text-info').find('.endtime').text();
				console.log(startTime);
				console.log(endTime);
				var thisFieldWrapper = $(this).closest('.control-label');
				var fieldMod;
				var randID = Date.now();
				if( thisFieldWrapper.parents('div').hasClass('offer-details') ) {
					fieldMod = 'offer';
				} else if( thisFieldWrapper.parents('div').hasClass('event-details')) {
					fieldMod = 'event';
				}
				var input = $('<input name="acf_fields[start_time]" id="' + fieldMod + '-submission-start' + randID + '" class="timepicker"><input name="acf_fields[end_time]" id="' + fieldMod + '-submission-end' + randID + '" class="timepicker">')
				$(this).siblings('.text-info').text('').append(input);
				startTimePicker('timepicker', '00:00');
				input.select();
				
				$('.timepicker').focus(function() {
					$('.wickedpicker').css({'display': 'none'});
				});
				
				$(document).click(function(e) {
					if ( !$(e.target).is(thisFieldWrapper.children('.fa')) && !$(e.target).parents('.wickedpicker').length > 0 && !$(e.target).is('.timepicker') ) {
						if( $('#' + fieldMod + '-submission-start' + randID).val() == $('#' + fieldMod + '-submission-end' + randID).val() ) {
							if( thisFieldWrapper.closest('.' + fieldMod + '-item').parent('.col-content').length > 0 ) {
								text = '<span class="starttime">' + startTime + '</span> - <span class="endtime">' + endTime + '</span>';
								$('#' + fieldMod + '-submission-start' + randID).closest('.text-info').html(text);
							} else {
								console.log('time new');
								$('#' + fieldMod + '-submission-start' + randID).closest('.text-info').text('Select ' + fieldMod + ' time');
							}
						} else {
							text = '<span class="starttime">' + $('#' + fieldMod + '-submission-start' + randID).val() + '</span> - <span class="endtime">' + $('#' + fieldMod + '-submission-end' + randID).val() + '</span>';
							$('#' + fieldMod + '-submission-start' + randID).closest('.text-info').html(text);
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
					$(this).replaceWith( '<input name="acf_fields[start_time]" id="venu-open" class="timepickerstart">' );
					startTimePicker('timepickerstart', moment($(this).text(), ["h:mm A"]).format("HH:mm") );
				});
				$('.left .opening-hours li span.closetime').each(function() {
					$(this).replaceWith( '<input name="acf_fields[end_time]" id="venu-close" class="timepickerend">' );
					startTimePicker('timepickerend', moment($(this).text(), ["h:mm A"]).format("HH:mm") );
				});
				$('.left .opening-hours .list-hours').each(function() {
					$(this).children('.fa-plus-circle').show();
					if( $(this).children('li').length > 1 ) {
						$(this).children('.fa-minus-circle').show();
					}
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
		input.on('blur', function() {
			var text = $('#attribute').val();
			if(text == '') {
				text = origField;
			}
			$('#attribute').parent().text(text);
			$('#attribute').remove();
		});
	});

	$('.remove').click(function() {
		var elem = $(this);
		var path = '';
		if(elem.attr('data-offer-id') ) {
			path = 'offer';	
		} else if( elem.attr('data-event-id') ) {
			path = 'event';	
		}
	
		function deleteOffer(elem) {
			var useElem = elem;
			var post_id;
			if( !!useElem.attr('data-offer-id') ) {
				post_id = useElem.attr('data-offer-id');
				path = 'offer';
				console.log(path);
			} else if( !!useElem.attr('data-event-id') ) {
				post_id = useElem.attr('data-event-id');
				path = 'event';
				console.log(path);
			}
			
			console.log(path);
			
			useElem.closest('.'+ path + '-item').remove();
			$.ajax({
				method: "DELETE",
				url: POST_SUBMITTER.root + 'wp/v2/' + path + '/' + post_id,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', POST_SUBMITTER.nonce );
				},
				success: function() {
					$("#datepicker").datepicker("refresh");
				}
			});
		}
		
		$('#dialog').dialog({
			title: 'Delete?',
			buttons: {
				'Delete offer': function() {
					$( this ).dialog( "close" );
					deleteOffer(elem);
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
		$('#dialog').dialog('open');
		$('#dialog').find('ul').empty();
		$('.ui-dialog').find('.ui-dialog-titlebar').addClass( path + 'BG' );
		$('#dialog').find('p').text('You are about to delete an offer.');		
	});
	
	function ConvertTimeformat(format, str) {
		var str = str.replace(/\s:\s/,':');
		var time = str;
		var hours = Number(time.match(/^(\d+)/)[1]);
		var minutes = Number(time.match(/:(\d+)/)[1]);
		var AMPM = time.match(/\s(.*)$/)[1];
		if (AMPM == "PM" && hours < 12) hours = hours + 12;
		if (AMPM == "AM" && hours == 12) hours = hours - 12;
		var sHours = hours.toString();
		var sMinutes = minutes.toString();
		if (hours < 10) sHours = "0" + sHours;
		if (minutes < 10) sMinutes = "0" + sMinutes;
		return sHours + ':' + sMinutes + ':00'
	}
	
	$('.new-offer-save').on('click', function() {
		var newOffer = $(this).closest('.offer-item');
		var offer_title = newOffer.find('label[for="offer-title"]').text();
		var offer_thumbnail = newOffer.find('img').attr('src');
		var offer_date = newOffer.find('label[for="offer-date"]').text();
		var offer_starttime = newOffer.find('label[for="offer-time"] .starttime').text();
		var offer_endtime = newOffer.find('label[for="offer-time"] .endtime').text();
		var offer_quantity = newOffer.find('label[for="offer-quantity"]').text();
		var offer_description = newOffer.find('.offer-description .text-info').text();
		var featured_media = newOffer.find('.left img').attr('data-image-id');
		
		if(offer_title != 'Offer Title' && offer_date != 'Select offer date' && offer_starttime != null && offer_endtime != null && offer_quantity != 'Enter redeem amount' && offer_description != 'Full Description + T&C' && featured_media ) {				
			var cleanDate = offer_date.replace(/\s+/g, '');
			cleanDate = cleanDate.split('/');
			cleanDate = cleanDate[2] + '-' + cleanDate[1] + '-' + cleanDate[0];

			var start = ConvertTimeformat("24", offer_starttime);
			var end = ConvertTimeformat("24", offer_endtime);
			
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
			$('.current-offers .col-content').append( post_template( data ) );
			
			var offerData = {
				status: 'publish',
				featured_media: featured_media,
				title: offer_title,
				content: offer_description,
				acf_fields: {
					date: cleanDate,
					start_time: start,
					end_time: end,
					maximum_redeemable: offer_quantity,
					venue: '57'
				}
			};
			
			$.ajax({
				method: "POST",
				url: POST_SUBMITTER.root + 'wp/v2/offer/',
				data: offerData,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', POST_SUBMITTER.nonce );
				}
			});
		} else {
			var errors = [];
			if( offer_title && offer_title.indexOf('Offer Title') > -1) { errors.push('Title') }
			if( offer_date && offer_date.indexOf('Select offer date') > -1) { errors.push('Date') }
			if( !offer_starttime && !offer_endtime) { errors.push('Start & End Time') }
			if( offer_quantity && offer_quantity.indexOf('Enter redeem amount') > -1) { errors.push('Quantity Redeemable') }
			if( offer_description && offer_description.indexOf('Full Description + T&C') > -1) { errors.push('An Offer Description') }
			if( !featured_media ) { errors.push('An Image') }
			
			$('#dialog').dialog({
				title: 'Error',
				buttons: {
					Ok: function() {
						$( this ).dialog( "close" );
					}
				}
			});
			$('.new-offer-error').dialog('open');
			$('#dialog').find('p').text('Unable to save offer. The following information is still missing:');
			$('#dialog').find('ul').empty();
			$('.ui-dialog').find('.ui-dialog-titlebar').addClass('offerBG');
			$.each(errors, function( index, value ) {
				$('#dialog ul').append('<li>' + value + '</li>');
			});
		}
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
		var featured_media = newEvent.find('.left img').attr('data-image-id');
				
		if(event_title != 'Event Title' && event_date != 'Select event date' && event_starttime != null && event_endtime != null && event_quantity != 'Enter redeem amount' && event_description != 'Full Description + T&C' && featured_media ) {				
			var cleanDate = event_date.replace(/\s+/g, '');
			cleanDate = cleanDate.split('/');
			cleanDate = cleanDate[2] + '-' + cleanDate[1] + '-' + cleanDate[0];

			var start = ConvertTimeformat("24", event_starttime);
			var end = ConvertTimeformat("24", event_endtime);
			
			var data = {
				event_title: event_title,
				event_thumbnail: event_thumbnail,
				event_date: event_date,
				event_starttime: event_starttime,
				event_endtime: event_endtime,
				event_quantity: event_quantity,
				event_description: event_description
			}
			
			newEvent.find('label[for="event-title"] .text-info').text('Event Title');
			newEvent.find('label[for="event-date"] .text-info').text('Select event date');
			newEvent.find('label[for="event-time"] .text-info').text('Select event time');
			newEvent.find('label[for="event-quantity"] .text-info').text('Enter redeem amount');
			newEvent.find('.event-description .text-info').text('Full Description + T&C');
			
			newEvent.closest('.accordion-content').toggle();
			
			var post_template = wp.template( 'oot-event' );
			$('.current-events .col-content').append( post_template( data ) );
			
			var eventData = {
				status: 'publish',
				featured_media: featured_media,
				title: event_title,
				content: event_description,
				acf_fields: {
					date: cleanDate,
					start_time: start,
					end_time: end,
					maximum_redeemable: event_quantity,
					venue: '57'
				}
			};
			
			$.ajax({
				method: "POST",
				url: POST_SUBMITTER.root + 'wp/v2/event/',
				data: eventData,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', POST_SUBMITTER.nonce );
				}
			});
		} else {
			var errors = [];
			if( event_title && event_title.indexOf('Event Title') > -1) { errors.push('Title') }
			if( event_date && event_date.indexOf('Select event date') > -1) { errors.push('Date') }
			if( !event_starttime && !event_endtime) { errors.push('Start & End Time') }
			if( event_quantity && event_quantity.indexOf('Enter redeem amount') > -1) { errors.push('Quantity Redeemable') }
			if( event_description && event_description.indexOf('Full Description + T&C') > -1) { errors.push('An Event Description') }
			if( !featured_media ) { errors.push('An Image') }
			
			$('#dialog').dialog({
				title: 'Error',
				buttons: {
					Ok: function() {
						$( this ).dialog( "close" );
					}
				}
			});
			$('.new-event-error').dialog('open');
			$('#dialog').find('p').text('Unable to save event. The following information is still missing:');
			$('.ui-dialog').find('.ui-dialog-titlebar').addClass('eventBG');
			$('#dialog').find('ul').empty();
			$.each(errors, function( index, value ) {
				$('#dialog ul').append('<li>' + value + '</li>');
			});
		}	




		
	});
	
	$('.current-offers').on('click', '.offer-item .editoffer', function() {
		$(this).hide();
		var thisOffer = $(this).closest('.offer-item');
		thisOffer.find('.offer-details .edit').show();
		thisOffer.find('label[for="offer-title"] .edit').show();
		if( thisOffer.find('.offer-description').hasClass('visible') ) {
			thisOffer.find('.offer-description').removeClass('visible');
		} else {
			thisOffer.find('.offer-description').removeClass('visible');
			thisOffer.find('.offer-description').addClass('visible');
		}
	});
	
	// UPDATE Existing offer Post
	$('.current-offers').on('click', '.offer-item .offer-description .save', function() {
		var thisOffer = $(this).closest('.offer-item');
		var newOffer = $(this).closest('.offer-item');
		var offerID = $(this).closest('.offer-item').attr('data-offer-id');
		var offer_title = thisOffer.find('label[for="offer-title"]').text();
		var offer_thumbnail = thisOffer.find('img').attr('src');
		var offer_date = thisOffer.find('label[for="offer-date"]').text();
		var offer_starttime = thisOffer.find('label[for="offer-time"] .starttime').text();
		var offer_endtime = thisOffer.find('label[for="offer-time"] .endtime').text();
		var offer_quantity = thisOffer.find('label[for="offer-quantity"]').text();
		var offer_description = thisOffer.find('.offer-description .text-info').text();
		var featured_media = thisOffer.find('.left img').attr('data-image-id');
		
		if(offer_title != 'Offer Title' && offer_date != 'Select offer date' && offer_starttime != null && offer_endtime != null && offer_quantity != 'Enter redeem amount' && offer_description != 'Full Description + T&C' && featured_media ) {				
			var cleanDate = offer_date.replace(/\s+/g, '');
			cleanDate = cleanDate.split('/');
			cleanDate = cleanDate[2] + '-' + cleanDate[1] + '-' + cleanDate[0];

			var start = ConvertTimeformat("24", offer_starttime);
			var end = ConvertTimeformat("24", offer_endtime);
			
			var data = {
				offer_title: offer_title,
				offer_thumbnail: offer_thumbnail,
				offer_date: offer_date,
				offer_starttime: offer_starttime,
				offer_endtime: offer_endtime,
				offer_quantity: offer_quantity,
				offer_description: offer_description
			}
			
			thisOffer.find('label[for="offer-title"] .text-info').text('Offer Title');
			thisOffer.find('label[for="offer-date"] .text-info').text('Select offer date');
			thisOffer.find('label[for="offer-time"] .text-info').text('Select offer time');
			thisOffer.find('label[for="offer-quantity"] .text-info').text('Enter redeem amount');
			thisOffer.find('.offer-description .text-info').text('Full Description + T&C');
			
			thisOffer.closest('.accordion-content').toggle();
			
			var post_template = wp.template( 'oot-offer' );
			$('.current-offers .col-content').append( post_template( data ) );
			
			var offerData = {
				status: 'publish',
				featured_media: featured_media,
				title: offer_title,
				content: offer_description,
				acf_fields: {
					date: cleanDate,
					start_time: start,
					end_time: end,
					maximum_redeemable: offer_quantity,
					venue: '57'
				}
			};
			
			$.ajax({
				method: "PUT",
				url: POST_SUBMITTER.root + 'wp/v2/offer/',
				data: offerData,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', POST_SUBMITTER.nonce );
				}
			});
		} else {
			var errors = [];
			if( offer_title && offer_title.indexOf('Offer Title') > -1) { errors.push('Title') }
			if( offer_date && offer_date.indexOf('Select offer date') > -1) { errors.push('Date') }
			if( !offer_starttime && !offer_endtime) { errors.push('Start & End Time') }
			if( offer_quantity && offer_quantity.indexOf('Enter redeem amount') > -1) { errors.push('Quantity Redeemable') }
			if( offer_description && offer_description.indexOf('Full Description + T&C') > -1) { errors.push('An Offer Description') }
			if( !featured_media ) { errors.push('An Image') }
			
			$('#dialog').dialog({
				title: 'Error',
				buttons: {
					Ok: function() {
						$( this ).dialog( "close" );
					}
				}
			});
			$('.new-offer-error').dialog('open');
			$('#dialog').find('p').text('Unable to save offer. The following information is still missing:');
			$('#dialog').find('ul').empty();
			$.each(errors, function( index, value ) {
				$('#dialog ul').append('<li>' + value + '</li>');
			});
			$('.new-offer-error').find('p ul').append('Errors lol?');
		}		
		
		thisOffer.find('.editoffer').show();
		thisOffer.find('.offer-details .edit').hide();
		$('.current-offers .col-content label[for="offer-title"] .edit').hide();
		if( thisOffer.find('.offer-description').hasClass('visible') ) {
			thisOffer.find('.offer-description').removeClass('visible');
		} else {
			thisOffer.find('.offer-description').removeClass('visible');
			thisOffer.find('.offer-description').addClass('visible');
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
		$('<li><input name="acf_fields[start_time]" id="venu-open" class="timepickerstart"> - <input name="acf_fields[start_time]" id="venu-close" class="timepickerend"></li>').insertAfter($(this).parent().children('li').last());
		startTimePicker('timepickerstart', '00:00' );
		startTimePicker('timepickerend', '00:00' );
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
		$(this).hide();
		var thisEvent = $(this).closest('.event-item');
		thisEvent.find('.event-details .edit').show();
		thisEvent.find('label[for="event-title"] .edit').show();
		if( thisEvent.find('.event-description').hasClass('visible') ) {
			thisEvent.find('.event-description').removeClass('visible');
		} else {
			thisEvent.find('.event-description').removeClass('visible');
			thisEvent.find('.event-description').addClass('visible');
		}
	});
	
	$('.upcoming-events').on('click', '.event-item .event-description .save', function() {
		var thisEvent = $(this).closest('.event-item');
		thisEvent.find('.editevent').show();
		thisEvent.find('.event-details .edit').hide();
		thisEvent.find('label[for="event-title"] .edit').hide();
		if( thisEvent.find('.event-description').hasClass('visible') ) {
			thisEvent.find('.event-description').removeClass('visible');
		} else {
			thisEvent.find('.event-description').removeClass('visible');
			thisEvent.find('.event-description').addClass('visible');
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