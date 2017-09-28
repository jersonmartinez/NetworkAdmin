<?php
	include ("ssh.class.php");

	$check_apache 	= @$_POST['check_apache'];
	$check_mysql 	= @$_POST['check_mysql'];
	$check_dns		= @$_POST['check_dns'];
	$check_dhcp 	= @$_POST['check_dhcp'];
	$check_data 	= @$_POST['check_data'];
	$path_dirdata 	= @$_POST['path_dirdata'];

	$ip_address 	= @$_POST['text_CS_IPAddress'];
	$username	 	= @$_POST['text_CS_Username'];
	
	$arguments = array();

	if ($check_apache == "yes")
		array_push($arguments, "-apache");

	if ($check_dhcp == "yes")
		array_push($arguments, "-dhcp");

	if ($check_dns == "yes")
		array_push($arguments, "-dns");

	if ($check_mysql == "yes")
		array_push($arguments, "-mysql");

	if ($check_data == "yes")
		array_push($arguments, "-data:".$path_dirdata);

	$ConnectDB = new mysqli("127.0.0.1", "root", "root", "monitorizador");
	$password = $ConnectDB->query("SELECT * FROM host WHERE ip_address='".$ip_address."' AND username='".$username."' ORDER BY id DESC LIMIT 1;")->fetch_array(MYSQLI_ASSOC)['password'];

	$Me = new ConnectSSH($ip_address, $username, $password);

	echo "Resultado: ".$Me->CreateBackup(implode(" ", $arguments));
	// echo $Me->RunLines("ls");

	// $salida = system('./prueba.sh');

	// stream_set_blocking($salida, true);
 //    while ($buf = fread($salida, 4096))
 //        $data .= $buf;
    
 //    fclose($salida)
 //    echo "Datos: ".$data;

?>