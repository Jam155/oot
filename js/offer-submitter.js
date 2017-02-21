jQuery( document ).ready( function ( $ ) {
    $( '#offer-submission-form' ).on( 'submit', function(e) {
        e.preventDefault();
		var google_lat, google_lng = "";
        var title = $( '#offer-submission-title' ).val();
        var content = $( '#offer-submission-content' ).val();
		var date = $( '#offer-submission-date' ).val();
		var start = $( '#offer-submission-start' ).val();
		var end = $( '#offer-submission-end' ).val();
		var maximum_redeemable = $( '#offer-submission-redeemable' ).val();
        var status = 'pending';
		
		start = start.replace(/\s:\s/,':');
		end = end.replace(/\s:\s/,':');
		
		function ConvertTimeformat(format, str) {
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
		
		start = ConvertTimeformat("24", start);
		end = ConvertTimeformat("24", end);
		
		//console.log(date);
		console.log(start);
		console.log(end);
		
		var date = date.split('/');
		var date = date[2] + date[1] + date[0];
		
        var data = {
            title: title,
            content: content,
			status: status,
			acf_fields: {
				date: newDate,
				start_time: start,
				end_time: end,
				maximum_redeemable: maximum_redeemable
			}
        };

        $.ajax({
            method: "POST",
            url: POST_SUBMITTER.root + 'wp/v2/offer',
            data: data,
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
		
	});

});