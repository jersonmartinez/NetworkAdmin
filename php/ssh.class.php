<?php
	class ConnectSSH {

		private $ip_host;
		private $username;
		private $password;

		private $connect;
		private $stream;

		private $local_path = "/var/www/html/NetworkAdmin/php/";
		private $remote_path;
		private $filename;

		function __construct($ip_host, $username, $password){
			if (!function_exists("ssh2_connect")) 
        		die("La función ssh2_connect no existe.");

        	if(!($this->connect = ssh2_connect($ip_host, 22))){
		        echo "Falló: No se ha podido conectar al host.\n";
		    } else {
		        
		        if(!ssh2_auth_password($this->connect, $username, $password)) {
		            echo "Falló: Autenticación invalida\n";
		        } else {
					$this->ip_host 		= $ip_host;
					$this->username 	= $username;
					$this->password 	= $password;
					$this->remote_path = "/home/".$username."/";
		        }
		    }
		}

		public function RunLines($RL){
			if(!($this->stream = ssh2_exec($this->connect, $RL)))
		        return "Falló: El comando no se ha podido ejecutar.";

			stream_set_blocking($this->stream, true);
            while ($buf = fread($this->stream, 4096))
                $data .= $buf;
            
            if (fclose($this->stream))
            	return "<pre>".$data."</pre>";
		}

		public function writeFile($Instructions, $filename){
			$inputfile = file_put_contents($this->local_path.$filename, implode("\n", $Instructions));

			if ($inputfile === false)
				die("El script <b>".$filename."</b>, no se ha podido crear.");

			@chmod($this->local_path.$filename, 0777);
		
			return true;
		}

		public function sendFile($filename){
			$scp = ssh2_scp_send($this->connect, $this->local_path.$filename, $this->remote_path.$filename, 0777);

			if (!$scp)
				die("Error al intentar enviar el script <b>".$filename."</b> al host con IP <b>".$this->ip_host."</b>");

			if (!unlink($this->local_path.$filename))
				die("El script <b>".$filename."</b> no se ha podido eliminar del sistema local.");

			return true;
		}

		public function getMemoryState(){
			$filename = "getMemoryState.sh";
			$ActionArray[] = "MEMORIA=($(free -m | grep 'Mem' | cut -d ':' -f2))";
			array_push($ActionArray, 'echo "${MEMORIA[0]},${MEMORIA[1]},${MEMORIA[2]},${MEMORIA[3]},${MEMORIA[4]},${MEMORIA[5]},"');
			
			$RL[] = $this->remote_path.$filename;
			array_push($RL, "rm -rf ".$this->remote_path.$filename);

			if ($this->writeFile($ActionArray, $filename) && $this->sendFile($filename))
				return $this->RunLines(implode("\n", $RL));

			return false;
		}

		public function getDiskUsage(){
			$filename = "getDiskUsage.sh";

			$ActionArray[] = "INTERFACES=($(ifconfig -a -s | awk {'print $1'}))";
			array_push($ActionArray, 'echo "="');
			array_push($ActionArray, 'NUM_INTER=${#INTERFACES[*]}');
			array_push($ActionArray, 'for (( i = 1; i < $NUM_INTER ; i++ )); do');
			array_push($ActionArray, '	DIRECCION_IP=$(ifconfig ${INTERFACES[$i]} | grep "inet " | cut -d " " -f10)');
			array_push($ActionArray, '	if [[ $DIRECCION_IP != "" ]]; then');
			array_push($ActionArray, '		echo "${INTERFACES[$i]},$DIRECCION_IP,"');
			array_push($ActionArray, '	else');
			array_push($ActionArray, '		echo "${INTERFACES[$i]},No tiene ip asignada"');
			array_push($ActionArray, '	fi');
			array_push($ActionArray, 'done');
			array_push($ActionArray, 'echo "="');
			
			$RL[] = $this->remote_path.$filename;
			array_push($RL, "rm -rf ".$this->remote_path.$filename);

			if ($this->writeFile($ActionArray, $filename) && $this->sendFile($filename))
				return $this->RunLines(implode("\n", $RL));

			return false;
		}

		public function getNetworkInterfaces(){
			$filename = "getNetworkInterfaces.sh";

			$ActionArray[] = "INTERFACES=($(ifconfig -a -s | awk {'print $1'}))";
			array_push($ActionArray, 'echo "="');
			array_push($ActionArray, 'NUM_INTER=${#INTERFACES[*]}');
			array_push($ActionArray, 'for (( i = 1; i < $NUM_INTER ; i++ )); do');
			array_push($ActionArray, '	DIRECCION_IP=$(ifconfig ${INTERFACES[$i]} | grep "inet " | cut -d " " -f10)');
			array_push($ActionArray, '	if [[ $DIRECCION_IP != "" ]]; then');
			array_push($ActionArray, '		echo "${INTERFACES[$i]},$DIRECCION_IP,"');
			array_push($ActionArray, "	else");
			array_push($ActionArray, '		echo "${INTERFACES[$i]},No tiene ip asignada"');
			array_push($ActionArray, '	fi');
			array_push($ActionArray, 'done');
			array_push($ActionArray, 'echo "="');
			
			$RL[] = $this->remote_path.$filename;
			array_push($RL, "rm -rf ".$this->remote_path.$filename);

			if ($this->writeFile($ActionArray, $filename) && $this->sendFile($filename))
				return $this->RunLines(implode("\n", $RL));

			return false;
		}

		public function getOpenPorts(){
			$filename = "getOpenPorts.sh";

			$ActionArray[] = "PORT_TCP=($(netstat -pltona | grep 'tcp ' | awk {'print $4 ,$1'} | cut -d ':' -f2))";
			array_push($ActionArray, "PORT_TCP6=($(netstat -pltona | grep 'tcp6' | awk {'print $4 ,$1'} | cut -d ':' -f4))");
			array_push($ActionArray, 'echo "${PORT_TCP[*]} ${PORT_TCP6[*]},"');
			array_push($ActionArray, "PORT_UDP=($(netstat -pluona | grep 'udp ' | awk {'print $4 ,$1'} | cut -d ':' -f2))");
			array_push($ActionArray, "PORT_UDP6=($(netstat -pluona | grep 'udp ' | awk {'print $4 ,$1'} | cut -d ':' -f4))");
			array_push($ActionArray, 'echo "${PORT_UDP[*]} ${PORT_UDP6[*]}"');
			array_push($ActionArray, 'echo "="');
			
			$RL[] = $this->remote_path.$filename;
			array_push($RL, "rm -rf ".$this->remote_path.$filename);

			if ($this->writeFile($ActionArray, $filename) && $this->sendFile($filename))
				return $this->RunLines(implode("\n", $RL));

			return false;
		}

		public function getNetworkConnections(){
			$filename = "getNetworkConnections.sh";

			$ActionArray[] = "PROTO=$(netstat -putona | grep -e tcp -e udp | awk {'print $1'})";
			array_push($ActionArray, "DIR_LOCAL=$(netstat -putona | grep -e tcp -e udp | awk {'print $4'})");
			array_push($ActionArray, "DIR_REMOTA=$(netstat -putona | grep -e tcp -e udp | awk {'print $5'})");
			array_push($ActionArray, "ESTADO=$(netstat -putona | grep -e tcp -e udp | awk {'print $6'})");
			array_push($ActionArray, "TEMP1=$(netstat -putona | grep -e tcp -e udp | awk {'print $7'})");
			array_push($ActionArray, 'echo "${PROTO[*]} | "');
			array_push($ActionArray, 'echo "${DIR_LOCAL[*]} | "');
			array_push($ActionArray, 'echo "${DIR_REMOTA[*]} | "');
			array_push($ActionArray, 'echo "${ESTADO[*]} | "');
			array_push($ActionArray, 'echo "${TEMP1[*]} | "');
			array_push($ActionArray, 'echo "="');
			
			$RL[] = $this->remote_path.$filename;
			array_push($RL, "rm -rf ".$this->remote_path.$filename);

			if ($this->writeFile($ActionArray, $filename) && $this->sendFile($filename))
				return $this->RunLines(implode("\n", $RL));

			return false;
		}

		public function getUsersConnected(){
			$filename = "getUsersConnected.sh";

			$ActionArray[] = "USUARIOS=($(who | cut -d ' ' -f1))";
			array_push($ActionArray, 'for i in ${USUARIOS[*]}; do');
			array_push($ActionArray, '	echo "$i ,"');
			array_push($ActionArray, 'done');
			array_push($ActionArray, 'echo "="');
			
			$RL[] = $this->remote_path.$filename;
			array_push($RL, "rm -rf ".$this->remote_path.$filename);

			if ($this->writeFile($ActionArray, $filename) && $this->sendFile($filename))
				return $this->RunLines(implode("\n", $RL));

			return false;
		}

		public function getDHCPShowAssignIP(){
			$filename = "getDHCPShowAssignIP.sh";

			$ActionArray[] = "MES=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'print $1'})";
			array_push($ActionArray, "DIA=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'print $2'})");
			array_push($ActionArray, "HORA=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'print $3'})");
			array_push($ActionArray, "IP=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'print $8'})");
			array_push($ActionArray, "MAC=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'print $10'})");
			array_push($ActionArray, "INTERFAZ=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'print $NF'})");
			array_push($ActionArray, 'echo "${MES[*]} | "');
			array_push($ActionArray, 'echo "${DIA[*]} | "');
			array_push($ActionArray, 'echo "${HORA[*]} | "');
			array_push($ActionArray, 'echo "${IP[*]} | "');
			array_push($ActionArray, 'echo "${MAC[*]} | "');
			array_push($ActionArray, 'echo "${INTERFAZ[*]} | "');
			array_push($ActionArray, 'echo "="');
			
			$RL[] = $this->remote_path.$filename;
			array_push($RL, "rm -rf ".$this->remote_path.$filename);

			if ($this->writeFile($ActionArray, $filename) && $this->sendFile($filename))
				return $this->RunLines(implode("\n", $RL));

			return false;
		}

		public function getDNSFileZones(){
			$filename = "getDNSFileZones.sh";

			$ActionArray[] = "ZONAS=($(cat /etc/bind/named.conf.local | grep 'file' | awk {'print $2'} | tr -d '\";'))";
			array_push($ActionArray, 'CANT_ZONAS=${#ZONAS[*]}');
			array_push($ActionArray, 'for (( i = 0; i < $CANT_ZONAS; i++ )); do');
			array_push($ActionArray, '	DOMINIO=$(cat ${ZONAS[$i]} | grep "SOA" | awk {"print $4"} | sed "s/.$//g")');
			array_push($ActionArray, '	TRADUC=$(cat ${ZONAS[$i]} | grep -e "IN" | tail -n1 | awk "! /$DOMINIO/ {print $1}")');
			array_push($ActionArray, '	IP=$(cat ${ZONAS[$i]} | grep "IN" | tail -n1 | awk "! /$DOMINIO/ {print $4}")');
			array_push($ActionArray, '	echo " ${ZONAS[$i]},$DOMINIO,${TRADUC[*]}.$DOMINIO,${IP[*]}"');
			array_push($ActionArray, "done");
			array_push($ActionArray, 'echo "="');
			
			$RL[] = $this->remote_path.$filename;
			array_push($RL, "rm -rf ".$this->remote_path.$filename);

			if ($this->writeFile($ActionArray, $filename) && $this->sendFile($filename))
				return $this->RunLines(implode("\n", $RL));

			return false;
		}

		public function getHTTPVirtualHost(){
			$filename = "getHTTPVirtualHost.sh";

			$ActionArray[] = "SITIOS=$(ls /etc/apache2/sites-available/)";
			array_push($ActionArray, 'for i in ${SITIOS[*]}; do');
			array_push($ActionArray, '	NAME_SERVER=$(cat /etc/apache2/sites-available/$i | grep "ServerName" | cut -d " " -f2 | tail -n1)');
			array_push($ActionArray, '	SITE_ENABLE=$(ls /etc/apache2/sites-enabled/ | grep $i)');
			array_push($ActionArray, '	if [[ $SITE_ENABLE == "" && $NAME_SERVER == "" ]]; then');
			array_push($ActionArray, '		echo "$i,No identificado,No habilitado"');
			array_push($ActionArray, '	else');
			array_push($ActionArray, '		echo "$i,$NAME_SERVER,Habilitado"');
			array_push($ActionArray, '	fi');
			array_push($ActionArray, 'done');
			array_push($ActionArray, 'echo "="');
			
			$RL[] = $this->remote_path.$filename;
			array_push($RL, "rm -rf ".$this->remote_path.$filename);

			if ($this->writeFile($ActionArray, $filename) && $this->sendFile($filename))
				return $this->RunLines(implode("\n", $RL));

			return false;
		}
	}

	echo (new ConnectSSH("192.168.100.4", "network", "123"))->getHTTPVirtualHost();
?>