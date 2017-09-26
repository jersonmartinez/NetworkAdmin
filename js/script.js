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

	if ($("#name_service").val() == ""){
		start_service('dhcp');
	}

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

function LoadTrackingNetwork(){
	$("#btn_LoadTrackingNetwork").html("RASTREANDO...");
	$.ajax({
	    url: "php/TrackingNetwork.php",
	    success: function(data){
	    	$(".WriteTrackingNetwork").html(data);
			$("#btn_LoadTrackingNetwork").html("SONDEO DE RED");
	    	setTimeout(function(){
	    		$("#SondeoModal").click();
	    	}, 200);
	    }
	});
}

function ClickModal(){
	$("#new_machine").click();
}

function MakeClick(){
	$("#MakeClick").click();
}

function SwapIP(value){
	$("#ClickTrackingNetwork").click();
	$("#chg_ip_address").val(value);
	MakeClick();
}

function SendDataNewHost(){
	$("#add_ip_address").val($("#chg_ip_address").val());
	$("#add_username").val($("#chg_username").val());
	$("#add_password").val($("#chg_password").val());

	$.ajax({
	    url: "php/EnterDataNewHost.php",
	    type: "POST",
	    data: $("#FormAddNewHost").serialize(),
	    success: function(data){
	    	if (data == "OK"){
	    		$.ajax({
				    url: "php/ShowDataNewHost.php",
				    success: function(data){
				    	$(".ContainerMachines").html(data);
				    }
				});
	    		$("#ClickCloseNow").click();
	    	} else {
	    		alert("Ha ocurrido un error");
	    	}
	    }
	});
}

function MakeQuery(ip_address, username, action){
	$("#MakeQueryIP").val(ip_address);
	$("#MakeQueryUsername").val(username);
	$("#MakeQueryAction").val(action);

	$.ajax({
	    url: "php/getMachineAction.php",
	    type: "POST",
	    data: $("#FormMakeQuery").serialize(),
	    success: function(data){
	    	if (data == "Fail"){
	    		alert("No hay conexión con el host, por favor, active el host y vuelva a intentarlo.");
	    	} else {
	    		if (action == "MemoriaDisco"){
		    		$(".WriteMemoriaDiscos").html(data);
		    		$("#MakeClickMemoriaDisco").click();
	    		} else if (action == "Interfaces"){
	    			$(".WriteInterfaces").html(data);
		    		$("#MakeClickInterfaces").click();
	    		} else if (action == "Puertos"){
	    			$(".WritePuertos").html(data);
		    		$("#MakeClickPuertos").click();
	    		} else if (action == "Estado"){
	    			$(".WriteEstado").html(data);
		    		$("#MakeClickEstado").click();
	    		} else if (action == "Usuarios"){
	    			$(".WriteUsuarios").html(data);
		    		$("#MakeClickUsuarios").click();
	    		}
	    	}
	    }
	});
}

function ChangeValueCheckBox(value){
	if (value.checked){
		$(value).val("yes");
	} else {
		$(value).val("no");
	}
}

function ExecServices(){
	$("#BtnExecServices").html("Aplicando copia de seguridad...");

	$.ajax({
	    url: "php/CreateBackup.php",
	    type: "POST",
	    data: $("#FormCheckServices").serialize(),
	    success: function(data){
			$("#BtnExecServices").html("¡Realizar Backup!");
    		setTimeout(function(){
	    		$("#ClickCloseNowExecServices").click();
	    	}, 1000);
	    }
	});
}

function CheckDirData(value){
	if (value.checked){
		$(value).val("yes");
		document.getElementById('path_dirdata').disabled = false;
	} else {
		document.getElementById('path_dirdata').disabled = true;
		$(value).val("no");
	}
}

function MakeBackup(ip_address, username){
	$("#text_CS_IPAddress").val(ip_address);
	$("#text_CS_Username").val(username);

	$("#MakeClickBackup").click();
}