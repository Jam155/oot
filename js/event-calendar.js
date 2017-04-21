$(document).ready(function() {

	var venueID = $('.venue-details').attr('data-venue-id');		
	function drawCalendar(venueID, calendarID) {
		$('#' + calendarID ).monthly({
			weekStart: 'Mon',
			mode: 'event',
			stylePast: true,
			jsonUrl: site_url + '/wp-json/wp/v2/venue/' + venueID + '/event',
			//jsonUrl: site_url + '/wp-content/themes/oot/events.json',
			dataType: 'json',
			monthNameFormat: 'long',
			dayNames: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
		});
	}
	drawCalendar(venueID, 'monthly-calendar');
	
	$( function() {
		$('#dialog').dialog({
			modal: true,
			resizable: false
		});
	});

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
	
	$(document).click(function(e) {
		if( $(e.target).is('.monthly-event-list') || $('.monthly-event-list').has(e.target).length > 0 || $(e.target).is('.day-with-event') ) {
			
		} else {
			$(".monthly-cal").remove();
			$(".monthly-event-list").css("transform", "scale(0)");
			setTimeout(function() {
				$(".monthly-event-list").hide();
			}, 250);
			//console.log(e.target + ' NOT .monthly-event-list OR child of .monthly-event-list');
		}
	});
	
	$('.edit').click(function() {
		var input, origField, origFieldNum, placeholder;
		var editType = $(this).attr('data-edit-type');
		switch(editType) {
			case 'url':
				origField = $(this).siblings('.text-info').attr('data-orig-url');
				input = $('<input id="url-field" type="text" value="' + origField + '" placeholder="Website" />');
				var textElem = $(this).siblings('.text-info');
				$(this).siblings('.text-info').text('').append(input);
				input.select();
				
				input.on('blur', function() {
					var fieldText = $('#url-field').val();
					var trimField = fieldText.replace(/((^\w+:|^)\/\/)?(www\.)?/, '');
					if(trimField == '') {
						trimField = origField.replace(/((^\w+:|^)\/\/)?(www\.)?/, '');
					}
					$(this).parent().text(trimField);
					textElem.attr("data-orig-url", fieldText);
					$(this).remove();
				});
				break;
			case 'twitter':
				origField = $(this).siblings('.text-info').attr('data-orig-twitter');
				input = $('<input id="url-field" type="text" value="' + origField + '" placeholder="Twitter" />');
				var textElem = $(this).siblings('.text-info');
				$(this).siblings('.text-info').text('').append(input);
				input.select();
				
				input.on('blur', function() {
					var fieldText = $('#url-field').val();
					var trimField = fieldText.replace(/((^\w+:|^)\/\/)?(www\.)?(twitter\.com\/)?/, '');
					if(trimField == '') {
						trimField = origField.replace(/((^\w+:|^)\/\/)?(www\.)?(twitter\.com\/)?/, '');
					}
					$(this).parent().text(trimField);
					textElem.attr("data-orig-twitter", fieldText);
					$(this).remove();
				});
				break;
			case 'facebook':
				origField = $(this).siblings('.text-info').attr('data-orig-facebook');
				input = $('<input id="url-field" type="text" value="' + origField + '" placeholder="Facebook" />');
				var textElem = $(this).siblings('.text-info');
				$(this).siblings('.text-info').text('').append(input);
				input.select();
				
				input.on('blur', function() {
					var fieldText = $('#url-field').val();
					var trimField = fieldText.replace(/((^\w+:|^)\/\/)?(www\.)?(facebook\.com\/)?/, '');
					if(trimField == '') {
						trimField = origField.replace(/((^\w+:|^)\/\/)?(www\.)?(facebook\.com\/)?/, '');
					}
					$('#url-field').parent().text(trimField);
					textElem.attr("data-facebook-url", fieldText);
					$('#url-field').remove();
				});
				break;
			case 'text':
				placeholder = $(this).parent().attr('for');
				placeholder = placeholder.replace('venue-','');
				placeholder = placeholder.replace('-',' ');
				placeholder = placeholder.toLowerCase().replace(/^[\u00C0-\u1FFF\u2C00-\uD7FF\w]|\s[\u00C0-\u1FFF\u2C00-\uD7FF\w]/g, function(letter) {
					return letter.toUpperCase();
				});
				origField = $(this).siblings('.text-info').text();
				input = $('<input id="attribute" type="text" value="' + origField + '" placeholder="' + placeholder + '" />');
				$(this).siblings('.text-info').text('').append(input);
				input.select();
				break;
			case 'tel':
				origField = $(this).siblings('.text-info').text();
				input = $('<input id="attribute" type="tel" value="' + origField + '" placeholder="Phone" />');
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
					$('#attribute').remove();
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
				input = $('<textarea class="new-offer-desc-textarea" id="attribute" placeholder="Full Description" />')
				origField = $(this).siblings('.text-info-wrapper').children('.text-info').text();
				$(this).siblings('.text-info-wrapper').children('.text-info').text('').append(input);
				input.text(origField);
				input.select();
				break;
			case 'hours':
				var i = 0;
				var j = 0;
				$('.savehours').show();
				$('.left .opening-hours li span.opentime').each(function() {
					$(this).replaceWith( '<input name="acf_fields[opening_hours][' + i + '][open]" id="venu-open" class="timepickerstart">' );
					startTimePicker('timepickerstart', moment($(this).text(), ["h:mm A"]).format("HH:mm") );
					i++;
				});
				$('.left .opening-hours li span.closetime').each(function() {
					$(this).replaceWith( '<input name="acf_fields[opening_hours][' + j + '][open]" id="venu-close" class="timepickerend">' );
					startTimePicker('timepickerend', moment($(this).text(), ["h:mm A"]).format("HH:mm") );
					j++;
				});
				$('.left .opening-hours .list-hours').each(function() {
					$(this).children('.fa-plus-circle').show();
					if( $(this).children('li').length > 0 && $(this).find('.closed-day').length < 1 ) {
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
		var currentVenue = $('.venue-details').attr('data-venue-id');
		var elem = $(this);
		var path = '';
		if(elem.attr('data-offer-id') ) {
			path = 'offer';
			notpath = 'event';
		} else if( elem.attr('data-event-id') ) {
			path = 'event';
			notpath = 'offer';
		}
	
		function deleteOffer(elem) {
			var useElem = elem;
			var post_id;
			if( !!useElem.attr('data-offer-id') ) {
				post_id = useElem.attr('data-offer-id');
				path = 'offer';
			} else if( !!useElem.attr('data-event-id') ) {
				post_id = useElem.attr('data-event-id');
				path = 'event';
			}			
			useElem.closest('.'+ path + '-item').remove();
			$.ajax({
				method: "DELETE",
				url: POST_SUBMITTER.root + 'wp/v2/' + path + '/' + post_id,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', POST_SUBMITTER.nonce );
				},
				success: function() {
					var newid = 'cal' + Date.now();
					$('.monthly').empty().replaceWith('<div class="monthly" id="' + newid + '"></div>');
					drawCalendar(currentVenue, newid);
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
		$('.ui-dialog').find('.ui-dialog-titlebar').removeClass( 'eventBG offerBG, venueBG' );
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
	
	// CREATE NEW Offer
	$('.new-offer-save').on('click', function() {
		var currentVenue = $('.venue-details').attr('data-venue-id');
		var newOffer = $(this).closest('.offer-item');
		var offer_title = newOffer.find('label[for="offer-title"]').text();
		var offer_thumbnail = newOffer.find('img').attr('src');
		var offer_date = newOffer.find('label[for="offer-date"]').text();
		var offer_starttime = newOffer.find('label[for="offer-time"] .starttime').text();
		var offer_endtime = newOffer.find('label[for="offer-time"] .endtime').text();
		var offer_quantity = newOffer.find('label[for="offer-quantity"]').text();
		var offer_description = newOffer.find('.offer-description .text-info').text();
		var featured_media = newOffer.find('.left img').attr('data-image-id');
		var term_id = newOffer.find(".cat-select select").val();
		var tagIDs = getTagIDs(newOffer);
		
		function getTagIDs(elem) {
			var tagsArray = [];
			elem.find( ".venue-active-tags .venue-tag" ).each(function() {
				tagsArray.push( $(this).attr('data-tag-id') );
			});
			return tagsArray;
		}
		
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
			newOffer.find('.left img').attr('src','http://localhost/oot/wp-content/themes/oot/images/oot-placeholder-img.png');
			
			newOffer.closest('.accordion-content').toggle();
			
			var post_template = wp.template( 'oot-offer' );
			$('.current-offers .col-content').append( post_template( data ) );
			
			var offerData = {
				status: 'publish',
				featured_media: featured_media,
				title: offer_title,
				content: offer_description,
				categories: term_id,
				tags: tagIDs,
				acf_fields: {
					date: cleanDate,
					start_time: start,
					end_time: end,
					maximum_redeemable: offer_quantity,
					venue: currentVenue
				}
			};
			
			$.ajax({
				method: "POST",
				url: POST_SUBMITTER.root + 'wp/v2/offer/',
				data: offerData,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', POST_SUBMITTER.nonce );
				},
				success : function(){
					console.log(this);
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
			$('.ui-dialog').find('.ui-dialog-titlebar').removeClass( 'eventBG offerBG, venueBG' );
			$('.ui-dialog').find('.ui-dialog-titlebar').addClass('offerBG');
			$.each(errors, function( index, value ) {
				$('#dialog ul').append('<li>' + value + '</li>');
			});
		}
	});
	
	// CREATE NEW Event
	$('.new-event-save').on('click', function() {
		var currentVenue = $('.venue-details').attr('data-venue-id');
		var newEvent = $(this).closest('.event-item');
		var event_title = newEvent.find('label[for="event-title"]').text();
		var event_thumbnail = newEvent.find('img').attr('src');
		var event_date = $.trim( newEvent.find('label[for="event-date"]').text() );
		var event_starttime = newEvent.find('label[for="event-time"] .starttime').text();
		var event_endtime = newEvent.find('label[for="event-time"] .endtime').text();
		var event_price = newEvent.find('label[for="event-quantity"]').text();
		var event_price_num = event_price.replace(/[^\d\.]/g, '');
		var event_description = newEvent.find('.event-description .text-info').text();
		var event_repeat = newEvent.find('.event-description input[type="radio"]:checked').attr('data-value');
		var featured_media = newEvent.find('.left img').attr('data-image-id');
		var event_random_id = Date.now();
		var genDate = event_date.split('/');
		genDate = genDate[2] + '-' + genDate[1] + '-' + genDate[0];
		var acf_date = new Date(genDate);
		acf_date = acf_date.toString('yyyy-MM-dd 00:00:00');
		var term_id = newEvent.find(".cat-select select").val();
		var tagIDs = getTagIDs(newEvent);
		
		function getTagIDs(elem) {
			var tagsArray = [];
			elem.find( ".venue-active-tags .venue-tag" ).each(function() {
				tagsArray.push( $(this).attr('data-tag-id') );
			});
			return tagsArray;
		}
		
				
		if(event_title != 'Event Title' && event_date != 'Select event date' && event_starttime != null && event_endtime != null && event_price != 'Enter redeem amount' && event_description != 'Full Description + T&C' && featured_media ) {				
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
				event_price_string: event_price,
				event_description: event_description,
				event_random_id: event_random_id
			}
			
			newEvent.find('label[for="event-title"] .text-info').text('Event Title');
			newEvent.find('label[for="event-date"] .text-info').text('Select event date');
			newEvent.find('label[for="event-time"] .text-info').text('Select event time');
			newEvent.find('label[for="event-quantity"] .text-info').text('Enter redeem amount');
			newEvent.find('.event-description .text-info').text('Full Description + T&C');
			newEvent.find('.left img').attr('src','http://localhost/oot/wp-content/themes/oot/images/oot-placeholder-img.png');
			
			newEvent.closest('.accordion-content').toggle();
						
			var post_template = wp.template( 'oot-event' );
			$('.upcoming-events .col-content').append( post_template( data ) );
			$( '#event-' + event_random_id ).find('#' + event_random_id + event_repeat).prop('checked', true);
			
			var eventData = {
				status: 'publish',
				featured_media: featured_media,
				title: event_title,
				content: event_description,
				categories: term_id,
				tags: tagIDs,
				acf_fields: {
					date: cleanDate,
					start_time: start,
					end_time: end,
					ticket_price_string: event_price_num,
					repeat_event: event_repeat,
					venue: currentVenue
				}
			};
			
			$.ajax({
				method: "POST",
				url: POST_SUBMITTER.root + 'wp/v2/event/',
				data: eventData,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', POST_SUBMITTER.nonce );
				},
				success: function() {
					var newid = 'cal' + Date.now();
					$('.monthly').empty().replaceWith('<div class="monthly" id="' + newid + '"></div>');
					drawCalendar(currentVenue, newid);
				}
			});			

		} else {
			var errors = [];
			if( event_title && event_title.indexOf('Event Title') > -1) { errors.push('Title') }
			if( event_date && event_date.indexOf('Select event date') > -1) { errors.push('Date') }
			if( !event_starttime && !event_endtime) { errors.push('Start & End Time') }
			if( event_price && event_price.indexOf('Enter redeem amount') > -1) { errors.push('Quantity Redeemable') }
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
			$('.ui-dialog').find('.ui-dialog-titlebar').removeClass( 'eventBG offerBG, venueBG' );
			$('.ui-dialog').find('.ui-dialog-titlebar').addClass('eventBG');
			$('#dialog').find('ul').empty();
			$.each(errors, function( index, value ) {
				$('#dialog ul').append('<li>' + value + '</li>');
			});
		}
	});
	
	$('.left .opening-hours .list-hours').on('click', '.fa-plus-circle', function() {
		console.log('there are ' + $(this).parent().find('li').length );
		if( $(this).parent().children('li').length == 1 ) {
			$(this).parent().children('.closed-day').last().remove();
			//$(this).parent().prepend('<li><input name="acf_fields[open]" id="venu-open" class="timepickerstart"> - <input name="acf_fields[close]" id="venu-close" class="timepickerend"></li>');
			$('<li><input name="acf_fields[open]" id="venu-open" class="timepickerstart"> - <input name="acf_fields[close]" id="venu-close" class="timepickerend"></li>').insertBefore( $(this).siblings('.fa-minus-circle') );
			startTimePicker('timepickerstart', '00:00' );
			startTimePicker('timepickerend', '00:00' );
			$(this).siblings('.fa-minus-circle').show();
		} else {
			//$(this).parent().prepend('<li><input name="acf_fields[open]" id="venu-open" class="timepickerstart"> - <input name="acf_fields[close]" id="venu-close" class="timepickerend"></li>');
			$('<li><input name="acf_fields[open]" id="venu-open" class="timepickerstart"> - <input name="acf_fields[close]" id="venu-close" class="timepickerend"></li>').insertBefore( $(this).siblings('.fa-minus-circle') );
			startTimePicker('timepickerstart', '00:00' );
			startTimePicker('timepickerend', '00:00' );
		}
		$('.timepickerstart, .timepickerend').focus(function() {
			$('.wickedpicker').css({'display': 'none'});
		});
	});
	
	$('.left .opening-hours .list-hours').on('click', '.fa-minus-circle', function() {
		if( $(this).parent().children('li').length <= 1 ) {
			$(this).parent().children('li').last().remove();
			$(this).parent().prepend('<li class="closed-day">Closed</li>');
			$(this).hide();
		} else {
			$(this).parent().children('li').last().remove();
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
	
	$('.current-offers').on('click', '.offer-item .editoffer', function() {
		$(this).hide();
		var thisOffer = $(this).closest('.offer-item');
		thisOffer.find('.left .frontend-button').show();
		thisOffer.find('.offer-details .edit').show();
		thisOffer.find('label[for="offer-title"] .edit').show();
		if( thisOffer.find('.offer-description').hasClass('visible') ) {
			thisOffer.find('.offer-description').removeClass('visible');
		} else {
			thisOffer.find('.offer-description').removeClass('visible');
			thisOffer.find('.offer-description').addClass('visible');
		}
	});
	
	// UPDATE Existing Offer
	$('.col-content').on('click', '.offer-item .offer-description .save', function() {
		var currentVenue = $('.venue-details').attr('data-venue-id');
		var thisOffer = $(this).closest('.offer-item');
		var offerID = $(this).closest('.offer-item').attr('data-offer-id');
		var offer_title = thisOffer.find('label[for="offer-title"]').text();
		var offer_thumbnail = thisOffer.find('img').attr('src');
		var offer_date = thisOffer.find('label[for="offer-date"]').text();
		var offer_starttime = thisOffer.find('label[for="offer-time"] .starttime').text();
		var offer_endtime = thisOffer.find('label[for="offer-time"] .endtime').text();
		var offer_quantity = thisOffer.find('label[for="offer-quantity"]').text();
		var offer_description = thisOffer.find('.offer-description .text-info').text();
		var featured_media = thisOffer.find('.left img').attr('data-image-id');
		var term_id = thisOffer.find(".cat-select select").val();
		var tagIDs = getTagIDs(thisOffer);
		
		function getTagIDs(elem) {
			var tagsArray = [];
			elem.find( ".venue-active-tags .venue-tag" ).each(function() {
				tagsArray.push( $(this).attr('data-tag-id') );
			});
			return tagsArray;
		}
		
		if(offer_title != 'Offer Title' && offer_date != 'Select offer date' && offer_starttime != null && offer_endtime != null && offer_quantity != 'Enter redeem amount' && offer_description != 'Full Description + T&C' && featured_media ) {				
			var cleanDate = offer_date.replace(/\s+/g, '');
			cleanDate = cleanDate.split('/');
			cleanDate = cleanDate[2] + '-' + cleanDate[1] + '-' + cleanDate[0];

			var start = ConvertTimeformat("24", offer_starttime);
			var end = ConvertTimeformat("24", offer_endtime);

			var offerData = {
				status: 'publish',
				featured_media: featured_media,
				title: offer_title,
				content: offer_description,
				categories: term_id,
				tags: tagIDs,
				acf_fields: {
					date: cleanDate,
					start_time: start,
					end_time: end,
					maximum_redeemable: offer_quantity,
					venue: currentVenue
				}
			};
			
			$.ajax({
				method: "PUT",
				url: POST_SUBMITTER.root + 'wp/v2/offer/' + offerID,
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
		}		
		
		thisOffer.find('.left .frontend-button').hide();
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
	
	$('.upcoming-events').on('click', '.event-item .editevent', function() {
		$(this).hide();
		var thisEvent = $(this).closest('.event-item');
		thisEvent.find('.event-details .edit').show();
		thisEvent.find('.left .frontend-button').show();
		thisEvent.find('label[for="event-title"] .edit').show();
		if( thisEvent.find('.event-description').hasClass('visible') ) {
			thisEvent.find('.event-description').removeClass('visible');
		} else {
			thisEvent.find('.event-description').removeClass('visible');
			thisEvent.find('.event-description').addClass('visible');
		}
	});
	
	// UPDATE Existing Event
	$('.col-content').on('click', '.event-item .event-description .save', function() {
		var currentVenue = $('.venue-details').attr('data-venue-id');
		var thisEvent = $(this).closest('.event-item');
		var eventID = $(this).closest('.event-item').attr('data-event-id');
		var event_title = thisEvent.find('label[for="event-title"]').text();
		var event_thumbnail = thisEvent.find('img').attr('src');
		var event_date = thisEvent.find('label[for="event-date"]').text();
		var event_starttime = thisEvent.find('label[for="event-time"] .starttime').text();
		var event_endtime = thisEvent.find('label[for="event-time"] .endtime').text();
		var event_price = thisEvent.find('label[for="event-quantity"]').text();
		var event_price_num = event_price.replace(/[^\d\.]/g, '');
		var event_description = thisEvent.find('.event-description .text-info').text();
		var event_repeat = thisEvent.find('.event-description input[type="radio"]:checked').attr('data-value');
		var featured_media = thisEvent.find('.left img').attr('data-image-id');
		var term_id = thisEvent.find(".cat-select select").val();
		var tagIDs = getTagIDs(thisEvent);
		
		function getTagIDs(elem) {
			var tagsArray = [];
			elem.find( ".venue-active-tags .venue-tag" ).each(function() {
				tagsArray.push( $(this).attr('data-tag-id') );
			});
			return tagsArray;
		}
		
		if(event_title != 'Event Title' && event_date != 'Select event date' && event_starttime != null && event_endtime != null && event_price != 'Enter redeem amount' && event_description != 'Full Description + T&C' && featured_media ) {				
			var cleanDate = event_date.replace(/\s+/g, '');
			cleanDate = cleanDate.split('/');
			cleanDate = cleanDate[2] + '-' + cleanDate[1] + '-' + cleanDate[0];

			var start = ConvertTimeformat("24", event_starttime);
			var end = ConvertTimeformat("24", event_endtime);

			var eventData = {
				status: 'publish',
				featured_media: featured_media,
				title: event_title,
				content: event_description,
				categories: term_id,
				tags: tagIDs,
				acf_fields: {
					date: cleanDate,
					start_time: start,
					end_time: end,
					ticket_price_string: event_price,
					repeat_event: event_repeat,
					venue: currentVenue
				}
			};
			
			$.ajax({
				method: "PUT",
				url: POST_SUBMITTER.root + 'wp/v2/event/' + eventID,
				data: eventData,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', POST_SUBMITTER.nonce );
				},
				success: function() {
					var newid = 'cal' + Date.now();
					$('.monthly').empty().replaceWith('<div class="monthly" id="' + newid + '"></div>');
					drawCalendar(currentVenue, newid);
				}
			});
		} else {
			var errors = [];
			if( event_title && event_title.indexOf('Event Title') > -1) { errors.push('Title') }
			if( event_date && event_date.indexOf('Select event date') > -1) { errors.push('Date') }
			if( !event_starttime && !event_endtime) { errors.push('Start & End Time') }
			if( event_price && event_price.indexOf('Enter redeem amount') > -1) { errors.push('Quantity Redeemable') }
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
			$('#dialog').find('ul').empty();
			$.each(errors, function( index, value ) {
				$('#dialog ul').append('<li>' + value + '</li>');
			});
		}		
		
		thisEvent.find('.left .frontend-button').hide();
		thisEvent.find('.editevent').show();
		thisEvent.find('.event-details .edit').hide();
		$('.upcoming-events .col-content label[for="event-title"] .edit').hide();
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
		$.getJSON(site_url + '/wp-json/wp/v2/tags', function(data) {
			console.log( data );
		});
		$('.tag-search-field').val("");
		var toHTML = $(this);
		console.log( toHTML.html( toHTML.text() ) );
		$(this).parent().siblings('.venue-active-tags').append( '<span class="venue-tag" data-tag-id="' + $(this).attr('data-term-id') + '" data-tag="' + $(this).text() + '">' + $(this).text() + '<i class="fa fa-close del" aria-hidden="true"></i></span>' );
		$(this).remove();
	});
	
	$('.tag-result').on('click', '.no-match-tag', function() {
		$(this).parent().siblings('.venue-active-tags').append( '<span class="venue-tag" data-tag="' + $(this).parent().siblings('.tag-search-field').val() + '">' + $(this).parent().siblings('.tag-search-field').val() + '<i class="fa fa-close del" aria-hidden="true"></i></span>' );
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
		var venue_image = $(".venue-img img").attr('src');
		var name = $("label[for='venue-title'] .text-info").text();
		var address_1 = $("label[for='venue-address-1'] .text-info").text();
		var address_2 = $("label[for='venue-address-2'] .text-info").text();
		var post_code = $("label[for='venue-post-code'] .text-info").text();
		var city = $("label[for='venue-city'] .text-info").text();
		var phone = $("label[for='venue-phone'] .text-info").text();
		var website = $("label[for='venue-website'] .text-info").attr('data-orig-url');
		var twitter = $("label[for='venue-twitter'] .text-info").text();
		var facebook = $("label[for='venue-facebook'] .text-info").text();
		var description = $(".venue-details .text-info-wrapper .text-info").text();
		var term_id = $('.venue-details').find(".cat-select select").val();
		var catname = $(".cat-select select").find(":selected").text();
		var openinghours = getOpeningHours();
		var tags = getTags();
		var tagIDs = getTagIDs();
		console.log(tagIDs);
		
		if(venue_image.indexOf("104x104") > -1 || venue_image.indexOf("oot-placeholder-img.png") > -1 ) {
			// do nothing
		} else {
			var featured_media = $(".venue-img img").attr('data-image-id');
		}
		
		function getTags() {
			var tagsArray = [];
			$('.venue-details').find( ".venue-active-tags .venue-tag" ).each(function() {
				tagsArray.push( $(this).text() );
			});
			return tagsArray;
		}
		
		function getTagIDs() {
			var tagsArray = [];
			$('.venue-details').find( ".venue-active-tags .venue-tag" ).each(function() {
				tagsArray.push( $(this).attr('data-tag-id') );
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
		
		// Generate opening hours
		/*
		function getOpeningHours() {
			var weekDays = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
			var dayObject = [];
			var times = [];
			var i = 0;
			
			$('.left .opening-hours tr').each(function(index) {
				$(this).children('td').children('ul').children('li').each(function() {
					var open_t = $(this).children('span.opentime').text();
					var close_t = $(this).children('span.closetime').text();
					//open_t = moment(open_t, ["h:mm A"]).format("HH:mm:ss");
					//close_t = moment(close_t, ["h:mm A"]).format("HH:mm:ss");
					times.push({ open: open_t, close: close_t });
				});
				dayObject[weekDays[index]] = times;
				times = [];
				i++;
			});
			return dayObject;
		}
		*/
		
		// Generate opening hours for ajax update
		function getOpeningHours() {
			var weekDays = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
			var dayObject = {};
			var times = [];
			var i = 0;
			
			$('.left .opening-hours tr').each(function(index) {
				$(this).children('td').children('ul').children('li').each(function() {
					var open_t = $(this).children('span.opentime').text();
					var close_t = $(this).children('span.closetime').text();
					open_t = moment(open_t, ["h:mm A"]).format("HH:mm:ss");
					close_t = moment(close_t, ["h:mm A"]).format("HH:mm:ss");
					times.push({ open: open_t, close: close_t });
				});
				dayObject[weekDays[index]] = times;
				times = [];
				i++;
			});
			return dayObject;
		}
		
		var finalVenuData = {
			status: 'publish',
			title: name,
			content: description,
			featured_media: featured_media,
			categories: term_id,
			tags: tagIDs,
			acf_fields: {
				website: website,
				phone: phone,
				address_1: address_1,
				address_2: address_2,
				city: city,
				post_code: post_code,
				twitter: twitter,
				facebook: facebook
			}			
		};
		
		console.log(finalVenuData);
				
		/* DATA TO MATCH REST API ENDPOINT
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
		*/
		//console.log(finalVenuData);

		var valid = 'true';		
		if(valid) {
			console.log(openinghours);
			$.ajax({
				method: "POST",
				url: site_url + '/wp-admin/admin-ajax.php',
				data: {
					"action": "set_times",
					"openinghours": getOpeningHours()
				},
				success: function( response ) {
					console.log( response );
				},
				fail: function( response ) {
					console.log( "Can't set hours" );
				}
			});
			$.ajax({
				method: "PUT",
				url: POST_SUBMITTER.root + 'wp/v2/venue/' + post_id,
				data: finalVenuData,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', POST_SUBMITTER.nonce );
				},
				success: function( response ) {
					console.log( response );
					$('#dialog').dialog({
						title: 'Venue Saved',
						buttons: {
							ok: function() {
								$( this ).dialog( "close" );
							}
						}
					});
					$('#dialog').find('p').text(name + ' has been saved. Click ok to continue editing.');
					$('#dialog').find('ul').empty();
					$('.ui-dialog').find('.ui-dialog-titlebar').removeClass( 'eventBG, offerBG, venueBG' );
					$('.ui-dialog').find('.ui-dialog-titlebar').addClass('venueBG');
					//alert( POST_SUBMITTER.success );
				},
				fail: function( response ) {
					console.log( response );
					alert( POST_SUBMITTER.failure );
				}
			});			
		}
	});
});