<?php
	class ConnectSSH {

		private $ip_host;
		private $username;
		private $password;

		private $connect;

		function __construct($ip_host, $username, $password){

			if (!function_exists("ssh2_connect")) 
        		die("La función ssh2_connect no existe.");

        	if (!$this->connect = ssh2_connect($ip_host, 22)){

        	}

        	if(!($this->connect = ssh2_connect($ip_host, 22))){
		        echo "Falló: No se ha podido conectar al host.\n";
		    } else {
		        if(!ssh2_auth_password($this->connect, $username, $password)) {
		            echo "Falló: Autenticación invalida\n";         
		        } else {             
		            
		            $commands = "ls -l\ntop -n1 -b";

		            if(!($stream = ssh2_exec($this->connect, $commands)) ){
		                echo "Falló: El comando no se ha podido ejecutar\n";
		            } else{
		                stream_set_blocking($stream, true);

		                while ($buf = fread($stream, 4096))
		                    $data .= $buf;

		                ?>
		                    <pre>
		                        <?php echo $data; ?>
		                    </pre>
		                <?php
		             
		                fclose($stream);
		            }
		        }
		    }



			$this->ip_host = $ip_host;
			$this->username = $username;
			$this->password = $password;



		}

	}
?>