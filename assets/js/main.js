var bg_x = 0;
jQuery(document).ready( function($){

	$( '#More-domains' ).hide();
	$( '#Add-caption' ).click(function(){
		$( this ).toggleClass( 'invert' );
		$( 'i', this ).toggleClass( 'invert' );
		$( '#More-domains' ).toggle();
		$( '.more', '#Add-domains' ).toggle();
	});

	$("input, textarea", "#Offer").keydown( function(){
		$(this).removeClass("error");
	});

	// validate and process form here
	$("button", "#Offer").click(function() {
		var f_name = $("#f_name").val();
		var f_url = $("#f_url").val();
		var f_email = $("#f_email").val();
		var f_subscribe = $("#f_subscribe").is(':checked') ? 'yes' : 'no';
		var f_offer = $("#f_offer").val();
		
		if (f_name == "") {
			$("#f_name").focus().addClass( 'error' );
			$("#error_name").show();
			return false;
		}
		if (f_email == "") {
			$("#f_email").focus().addClass( "error" );
			$("#error_email").show();
			return false;
		}
		if (f_offer == "") {
			$("#f_offer").focus().addClass( "error" );
			$("#error_offer").show();
			return false;
		}
		var theData = { name: f_name, addr: f_url, email: f_email, subscribe: f_subscribe, offer: f_offer };  
		$.ajax({ type: "POST",  url:  "sendthedata.php", data: theData, success: function( result ) { 
			if( result == 'success' )
				$("#Offer").fadeOut( 200, function(){ $("#msg").fadeIn(500); } ); 
			else {  
				$("i", "#error_msg").html( result );
				$("#Offer").fadeOut( 200, function(){ $("#error_msg").fadeIn(500); } ); 
			}
		}});
	});

	$( "#new_offer, #new_try" ).click(function(){
		$("#f_offer").val("");
		$("#msg, #error_msg").fadeOut( 200, function(){ $("#Offer").fadeIn(500); } ); 
	});

});