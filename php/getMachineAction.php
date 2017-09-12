<?php
	function Fatality($ArrayEstado){
		$contadorSecond = 0;
		for ($i=0; $i < count($ArrayEstado); $i++) { 
			$Second = explode(PHP_EOL, $ArrayEstado[$i]);

			$contadorSecond = count($Second);
			return $contadorSecond;
		}
	}

	include ("ssh.class.php");

	$ip_address = $_POST['MakeQueryIP'];
	$username 	= $_POST['MakeQueryUsername'];
	$action 	= $_POST['MakeQueryAction'];

	$ConnectDB = new mysqli("127.0.0.1", "root", "root", "monitorizador");
	$password = $ConnectDB->query("SELECT * FROM host WHERE ip_address='".$ip_address."' AND username='".$username."' ORDER BY id DESC LIMIT 1;")->fetch_array(MYSQLI_ASSOC)['password'];

	$CN = new ConnectSSH($ip_address, $username, $password);

	if (!$CN->connect)
		die("Fail");

	if ($action == "MemoriaDisco"){
		?>
			<h3 ondblclick="javascript: CloseModal();">Memoria | Discos Duros</h3>
		<?php
			$ArrayContent 		= explode(",", $CN->getMemoryState()); 
			$ArrayContentTwo 	= explode(",", $CN->getDiskUsage()); 
		?>

		<div>
			<p>Estado actual de la memoria</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 33%;"><b>Memoria total</b></td>
					<td style="width: 33%;"><b>Memoria utilizada</b></td>
					<td style="width: 33%;"><b>Memoria libre</b></td>
				</tr>

				<tr>
					<td><?php echo $ArrayContent[0]."MB"; ?></td>
					<td><?php echo $ArrayContent[1]."MB"; ?></td>
					<td><?php echo $ArrayContent[2]."MB"; ?></td>
				</tr>
			</table>
			<br/>
			<table style="width: 100%;">
				<tr>
					<td style="width: 33%;"><b>Memoria compartida</b></td>
					<td style="width: 33%;"><b>Búfer/Caché</b></td>
					<td style="width: 33%;"><b>Memoria disponible</b></td>
				</tr>

				<tr>
					<td><?php echo $ArrayContent[3]."MB"; ?></td>
					<td><?php echo $ArrayContent[4]."MB"; ?></td>
					<td><?php echo $ArrayContent[5]."MB"; ?></td>
				</tr>
			</table>

			<br/>
			<p>Uso de Discos Duros</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 30%;"><b>Tamaño del disco</b></td>
					<td style="width: 25%;"><b>Disco usado</b></td>
					<td style="width: 20%;"><b>Disponible</b></td>
					<td style="width: 25%;"><b>% en uso</b></td>
				</tr>

				<tr>
					<td><?php echo $ArrayContentTwo[0]; ?></td>
					<td><?php echo $ArrayContentTwo[1]; ?></td>
					<td><?php echo $ArrayContentTwo[2]; ?></td>
					<td><?php echo $ArrayContentTwo[3]; ?></td>
				</tr>
			</table>
			<br>
		<?php
	} else if ($action == "Interfaces"){
		$ArrayIntIP = explode(",", explode("=", $CN->getNetworkInterfaces())[1]);
		?>
			<h3 ondblclick="javascript: CloseModal();">Interfaces de Red | IP</h3>
			<div>

			<p>Interfaces de red y direcciones IP colocadas en ella</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 30%;"><b>Nombre de la interfaz</b></td>
					<td style="width: 25%;"><b>Dirección IP</b></td>
				</tr>

				<?php
					$i = 0;
					foreach ($ArrayIntIP as $value) {

						?>
							<tr>
								<td><?php echo $ArrayIntIP[$i++]; ?></td>
								<td><?php echo $ArrayIntIP[$i++]; ?></td>
							</tr>
						<?php
					}
				?>

			</table>
			<br>
		<?php
	} else if ($action == "Puertos"){
		$ArrayPuerto = explode(",", explode("=", $CN->getOpenPorts())[1]);
		?>
			<h3 ondblclick="javascript: CloseModal();">Puertos</h3>
			<div>
			<p>Puertos que se encuentran abiertos</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 50%;"><b>Número de puerto</b></td>
					<td style="width: 50%;"><b>Protocolo</b></td>
				</tr>

				<?php
					for ($i=0; $i < count($ArrayPuerto); $i++) { 
						$Firts = explode(" ", $ArrayPuerto[$i]);

						for ($j=0; $j < count($Firts); $j++) { 
							?>
								<tr>
									<td><?php echo $Firts[$j]; ?></td>
									<td><?php echo $Firts[$j+1]; $j++; ?></td>
								</tr>
							<?php
						}
					}
				?>
			</table>
			<br>
		<?php
	} else if ($action == "Estado"){
		$ArrayEstado = explode("|", explode("=", $CN->getNetworkConnections())[0]);
		echo $ArrayEstado;
		?>
		<h3 ondblclick="javascript: CloseModal();">Estado</h3>
		<div>
			<p>Estados de las conexiones de red</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr style="width: 100%;">
					<td><b>Protocolo</b></td>
					<td><b>Dirección Local</b></td>
					<td><b>Dirección Remota</b></td>
					<td><b>Estado</b></td>
					<td><b>Temporizador</b></td>
				</tr>

				<?php
					$contadorSecond = 0;
					for ($i=0; $i < count($ArrayEstado); $i++) { 
						$Second = explode(PHP_EOL, $ArrayEstado[$i]);

						$contadorSecond = count($Second);
						for ($j=0; $j < count($Second); $j++) { 
							$Cero[$i][$j] = $Second[$j];
						}
					}

					for ($i=0; $i < Fatality($ArrayEstado); $i++) { 
						?>
							<tr>
						<?php
						for ($j=0; $j < count($ArrayEstado); $j++) { 
							?>
								<td><?php echo $Cero[$j][$i]; ?></td>
							<?php
						}
						?>
							</tr>
						<?php
					}

				?>

			</table>
			<br>
		<?php
	} else if ($action == "Usuarios"){
		$ArrayUsuario = explode(",", explode("=", $CN->getUsersConnected())[0]); 
		?>
		<h3 ondblclick="javascript: CloseModal();">Usuarios</h3>
		<div>
			<p>Usuarios del sistema indicando cuales están logueados actualmente</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 50%;"><b>Cantidad de usuarios</b></td>
					<td style="width: 50%;"><b>Nombre de usuario</b></td>
				</tr>

				<?php
					for ($i=0; $i < count($ArrayUsuario) - 1; $i++) { 
						?>
							<tr>
								<td><?php echo $i+1; ?></td>
								<td><?php echo $ArrayUsuario[$i]; ?></td>
							</tr>
						<?php
					}
				?>
			</table>
			<br>
		<?php
	}
?>