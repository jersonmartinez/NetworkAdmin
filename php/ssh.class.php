<?php
	class ConnectSSH {

		private $ip_host;
		private $username;
		private $password;

		private $connect;
		private $stream;

		private $local_path = "/var/www/html/NetworkAdmin/php/";
		private $remote_path;

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

		public function getDHCP(){
			$RL[] = "ls -l"; 
			array_push($RL, "cat /etc/network/interfaces");
			
			return $this->RunLines(implode("\n", $RL));
		}

		public function getDHCPMemState(){
			$filename = "getDHCPMemState.sh";
			$ActionArray[] = "MEMORIA=($(free -m | grep 'Mem' | cut -d ':' -f2))";
			array_push($ActionArray, 'echo "${MEMORIA[0]},${MEMORIA[1]},${MEMORIA[2]},${MEMORIA[3]},${MEMORIA[4]},${MEMORIA[5]},"');
			
			$inputfile = file_put_contents("./".$filename, implode("\n", $ActionArray));

			if ($inputfile === false)
				die("El script <b>".$filename."</b>, no se ha podido crear.");

			@chmod($filename, 0777);

			$scp = ssh2_scp_send($this->connect, "./".$filename, "/home/".$this->username."/".$filename, 0777);

			if (!$scp)
				die("Error al intentar enviar el script <b>".$filename."</b> al host con IP <b>".$this->ip_host."</b>");

			if (!unlink($filename))
				die("El script <b>".$filename."</b> no se ha podido eliminar del sistema local.");

			$RL[] = $this->remote_path.$filename;
			array_push($RL, "rm -rf ".$this->remote_path.$filename);

			return $this->RunLines(implode("\n", $RL));
		}
	}

	echo (new ConnectSSH("192.168.100.2", "network", "123"))->getDHCPMemState();
?>