<?php
	include ("connect_server/connect_server.php");
	
	$Facility = $_POST['service_name'];
	$Severity = $_POST['severity_level'];
?>

<h3 ondblclick="javascript: CloseModal();">Monitorización de Logs (<?php echo strtoupper($Facility)." | ".ucwords($Severity); ?>)</h3>

<div>
	<p>Gestión centralizada de Logs producidos por este servicio</p>
	<table ondblclick="javascript: CloseModal();" style="width: 100%;">
		<tr>
			<td style="width: 20%;"><b>Fecha</b></td>
			<td style="width: 55%;"><b>Mensaje</b></td>
			<td style="width: 10%;"><b>Facilidad</b></td>
			<td style="width: 10%;"><b>Severidad</b></td>
		</tr>

		<?php
			$getData = LoadData(Conexion(), $Facility, $Severity, 10);
			if (!is_bool($getData)){

				foreach ($getData as $value) {
					?>
						<tr>
							<td><?php echo $value['ReceivedAt']; ?></td>
							<td title="<?php echo $value['Message']; ?>">
								<?php 
									if (strlen($value['Message']) > 76)
										echo substr($value['Message'], 0, 76)."..."; 
									else
										echo $value['Message'];
								?>
							</td>
							<td>
								<?php 
									$KnowFacilityString = KnowFacilityString($value['Facility']);
									if (is_string($KnowFacilityString))
										echo $KnowFacilityString;
									else
										echo strtoupper($Facility);
								?>
							</td>
							<td>
								<?php 
									$KnowSeverityString = KnowSeverityString($value['Priority']);
									if (is_string($KnowSeverityString))
										echo $KnowSeverityString;
									else
										echo strtoupper($Facility);
								?>
							</td>
						</tr>
					<?php
	    		}
			}
		?>
	</table>
	<br>
	
</div>