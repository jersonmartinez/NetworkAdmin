function start_service(name_service){
	$("#service").val(name_service);
	$("#FormService").submit();
}

function CloseModal(){
	$(".md-close").click();
}

setTimeout(function(){
	$(".cn-button").click();
	$(".cn2-button").click();
}, 700);

function LoadInfo(Facility, Severity){
	$("#service_name").val(Facility);
	$("#severity_level").val(Severity);

	$.ajax({
	    url: "php/LoadData.php",
	    type: "POST",
	    data: $("#FormLoadData").serialize(),
	    success: function(data){
	    	$(".WriteNewModal").html(data);
	    	setTimeout(function(){
	    		$("#ClickModalTen").click();
	    	}, 200);
	    }
	});
}