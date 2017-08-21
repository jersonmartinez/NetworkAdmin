function start_service(name_service){
	$("#service").val(name_service);
	$("#FormService").submit();

	// get_service(name_service);
}

function MyModal(){
	$('#MyModal').modal('toggle');
}

function GetDHCP(){
	$.ajax({
	    url: "php/GetDHCP.php",
	    success: function(data){
	    	$(".windows_modals").html(data);
	    }
  	});
}