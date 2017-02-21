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
		
        var data = {
            title: title,
            content: content,
			status: status,
			acf_fields: {
				date: date,
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