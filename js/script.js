function start_service(name_service){
	$("#service").val(name_service);
	$("#FormService").submit();
}

function get_service(name_service, action){
	if (name_service == "dhcp"){

		GetDHCP();

	}// } else if (name_service == "dns"){

		

	// } else if (name_service == "web"){

		
	// }
}

function MyModal(){
	$('#MyModal').modal('toggle');
}

function GetDHCP(){
	$.ajax({
	    url: "php/GetDHCP.php",
	    success: function(data){
	    	if (data == "OK"){

	    	}
	    }
  	});
}