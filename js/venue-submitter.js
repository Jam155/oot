jQuery( document ).ready( function ( $ ) {
    $( '#venue-submission-form' ).on( 'submit', function(e) {
        e.preventDefault();
		var google_lat, google_lng = "";
        var title = $( '#venue-submission-title' ).val();
        var excerpt = $( '#venue-submission-excerpt' ).val();
        var content = $( '#venue-submission-content' ).val();
		var address = $( '#venue-submission-address' ).val();
        var status = 'pending';
		
		var urlAddress = address.replace(/[,\s]+|[,\s]+/g, "+");
		var mapAPI = "https://maps.googleapis.com/maps/api/geocode/json?address=" + urlAddress + "&key=AIzaSyA8mAsUvZUSPeEQuVaJ6ENHm-Kh4QB5RQk";
		
		
		$.getJSON(mapAPI, function (data) {
			//for(var i=0;i<data.results.length;i++) {
				console.log(data.results[0].geometry.location.lat);
				console.log(data.results[0].geometry.location.lng);
				google_lat = data.results[0].geometry.location.lat;
				google_lng = data.results[0].geometry.location.lng;
			//}
		

		console.log(google_lat);
		console.log(google_lng);
		
        var data = {
            title: title,
            excerpt: excerpt,
            content: content,
			status: status,
			acf_fields: {
				address: address,
				lat: google_lat,
				lng: google_lng		
			}
        };

        $.ajax({
            method: "POST",
            url: POST_SUBMITTER.root + 'wp/v2/venue',
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

} );