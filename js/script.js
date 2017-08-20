function start_service(name_service){
	$("#service").val(name_service);
	$("#FormService").submit();
}

function get_service(name_service, action){
	if (name_service == "dhcp"){

		if (action == "memoria")
			GetDHCPMemoria();
		else if (action == "discos")
			GetDHCPDiscos();
		else if (action == "interfaces")
			GetDHCPInterfaces();
		else if (action == "puertos")
			GetDHCPDPuertos();
		else if (action == "estado")
			GetDHCPEstado();
		else if (action == "usuarios")
			GetDHCPUsuarios();
		else if (action == "asignacion")
			GetDHCPAsignacion();

	} else if (name_service == "dns"){

		if (action == "memoria")
			GetDNSMemoria();
		else if (action == "discos")
			GetDNSDiscos();
		else if (action == "interfaces")
			GetDNSInterfaces();
		else if (action == "puertos")
			GetDNSDPuertos();
		else if (action == "estado")
			GetDNSEstado();
		else if (action == "usuarios")
			GetDNSUsuarios();
		else if (action == "zonas")
			GetDNSZonas();

	} else if (name_service == "web"){

		if (action == "memoria")
			GetWEBMemoria();
		else if (action == "discos")
			GetWEBDiscos();
		else if (action == "interfaces")
			GetWEBInterfaces();
		else if (action == "puertos")
			GetWEBDPuertos();
		else if (action == "estado")
			GetWEBEstado();
		else if (action == "usuarios")
			GetWEBUsuarios();
		else if (action == "sitios")
			GetWEBSitios();

	}
}