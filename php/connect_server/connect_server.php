<?php
	function Conexion(){
		return new mysqli("localhost", "root", "", "Syslog");
	}

	function KnowSeverityCode($Severity){
		switch ($Severity) {
			case 'alert':
				$CodeSeverity = 1;
				break;
			case 'crit':
				$CodeSeverity = 2;
				break;
			case 'err':
				$CodeSeverity = 3;
				break;
			case 'warning':
				$CodeSeverity = 4;
				break;
			case 'notice':
				$CodeSeverity = 5;
				break;
			case 'info':
				$CodeSeverity = 6;
				break;
			case 'debug':
				$CodeSeverity = 7;
				break;
			default:
				$CodeSeverity = 0;
				break;
		}

		return $CodeSeverity;
	}

	function KnowSeverityString($CodeSeverity){
		switch ($CodeSeverity) {
			case 1:
				$StringSeverity = 'alert';
				break;
			case 2:
				$StringSeverity = 'crit';
				break;
			case 3:
				$StringSeverity = 'err';
				break;
			case 4:
				$StringSeverity = 'warning';
				break;
			case 5:
				$StringSeverity = 'notice';
				break;
			case 6:
				$StringSeverity = 'info';
				break;
			case 7:
				$StringSeverity = 'debug';
				break;
			default:
				$StringSeverity = 0;
				break;
		}

		return $StringSeverity;
	}

	function KnowFacilityString($CodeFacility){
		switch ($CodeFacility) {
			case 20:
				$StringFacility = 'local4';
				break;
			case 21:
				$StringFacility = 'local5';
				break;
			case 22:
				$StringFacility = 'local6';
				break;
			case 23:
				$StringFacility = 'local7';
				break;
			default:
				$StringFacility = 0;
				break;
		}

		return $StringFacility;
	}

	function KnowFacilityCode($Facility, $Severity){
		switch ($Facility) {
			case 'dhcp':
				$CodeFacility = 20;
				break;
			case 'dns':
				$CodeFacility = 21;
				break;
			case 'web':
				if (KnowSeverityCode($Severity) != 3)
					$CodeFacility = 22;
				else 
					$CodeFacility = 23;
				break;
			default:
				$CodeFacility = 0;
				break;
		}

		return $CodeFacility;
	}

	function LoadData($Connection, $Facility, $Severity, $End){

		$Start = $End - 10;

		if ($Start < 0)
			$Start = 0;

		$CodeSeverity = KnowSeverityCode($Severity);
		$CodeFacility = KnowFacilityCode($Facility, $Severity);

		if ($CodeFacility != false && $CodeSeverity != false){
			$Result = $Connection->query("SELECT * FROM SystemEvents WHERE Facility='".$CodeFacility."' AND Priority='".$CodeSeverity."' ORDER BY ID DESC LIMIT ".$Start.",".$End.";");
			
			if ($Result->num_rows > 0){
				$ArrayData = [];
				while ($Row = $Result->fetch_array(MYSQLI_ASSOC)) {
					$ArrayData[] = [
						'ID' 			=> $Row['ID'],
						'ReceivedAt' 	=> $Row['ReceivedAt'],
						'Facility' 		=> $Row['Facility'],
						'Priority' 		=> $Row['Priority'],
						'Message' 		=> $Row['Message'], 
						'SysLogTag' 	=> $Row['SysLogTag']
					];

				}

				return $ArrayData;
			}
		}

		return false;
	}

	if (Conexion()->connect_error)
		die("La conexión ha fallado!");

?>