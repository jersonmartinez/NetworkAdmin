<?php
    if (!function_exists("ssh2_connect")) 
        die("La función ssh2_connect no existe.");
     
    if(!($con = ssh2_connect("192.168.100.2", 22))){
        echo "Falló: No se ha podido conectar al host.\n";
    } else {
        if(!ssh2_auth_password($con, "network", "123")) {
            echo "Falló: Autenticación invalida\n";         
        } else {             
            
            $commands = "ls -l\ntop -n1 -b";

            if(!($stream = ssh2_exec($con, $commands)) ){
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
?>