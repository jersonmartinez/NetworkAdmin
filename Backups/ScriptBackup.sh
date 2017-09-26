#!/usr/bin/env bash

##################################################################
# Facha actual con formato (24-sep-2017)
CurrentDate=$(date +"%d-%b-%Y")

# Fecha actual con otro formato (17092017)
CurrentDateFormat=$(date +"%d%m%Y")

# Tiempo actual (1550) Horas y minutos
CurrentDateTime=$(date +"%H%M")
##################################################################

#Dirección IP y nombre de usuario
IPAddress=$1
username="network"

# Nombre del host
HostName=$(ssh $username@$IPAddress hostname)
##################################################################

# Ruta absoluta donde se agregarán todos los respaldos
PathAbsolute="Backups"

# Almacena una ruta absoluta formateada (/Backups/17-sep-2017/http_17092017_1550)
DirStorage=$(printf "/%s/%s/%s_%s_%s" $PathAbsolute $CurrentDate $HostName $CurrentDateFormat $CurrentDateTime)
DirCompact=$(printf "%s_%s_%s" $HostName $CurrentDateFormat $CurrentDateTime)

##################################################################
#Crea los directorios que conforman la ruta $DirStorage
function CreateDirs(){
	# Si la ruta no existe, la crea, directorios y subdirectorios
	[ ! -d $DirStorage ] && mkdir -p ${DirStorage}

	# Se asignan permisos recursivo 777 a $PathAdsolute | Silencioso -f
	chmod 0777 -R -f $(dirname $(dirname $DirStorage))
}

##################################################################
#Verifica si el servicio está activo en el sistema
function VerifyService(){
	#Se verifica el estado del servicio, se le pasa por parámetro el nombre del servicio
	PackService=$(ssh $username@$IPAddress ps ax | grep -v grep | grep ${1})

    if [[ $PackService != '' ]]; then
	  	echo "Well"
	  else 
	  	echo "Bad"
    fi
}

##################################################################
#Crear respaldo del servicio apache2
function BackupApache(){
	#Se verifica que el servicio esté activo
	Service="apache"

	#Se extrae el valor retornado en segundo plano para verificar
	if [ `VerifyService apache2` == "Well" ]; then
		#Un array que como elementos contiene las rutas absolutas a Apache
		PathApacheArray=("/usr/lib/apache2" "/etc/apache2" "/usr/share/apache2" "/etc/ssl/" "/usr/sbin/apache2" "/usr/sbin/apache2ctl" "/usr/sbin/apachectl")

		[ ! -d $DirStorage/$Service/ ] && mkdir -p $DirStorage/$Service/

		#Se recorre el array con el objetivo de validar, crear y copiar
		for PathApache in ${PathApacheArray[*]}; do
			#Se verifica que el directorio exista
		    if [ -d $PathApache ]; then
		    	#Se almacena en una nueva variable local, el nombre del directorio donde se encuentra alojado
		    	local PathFolder=$(basename $(dirname $PathApache))
		    	#Se crea un directorio en el directorio de respaldo con el nombre extraído
		    	mkdir $DirStorage/$Service/$PathFolder
		    	#Ocurridos los pasos anteriores, se procede a realizar la copia de la información
		    	# cp -rf $PathApache/ $DirStorage/$Service/$PathFolder

		    	scp -C -r $username@$IPAddress:$PathApache $DirStorage/$Service/$PathFolder

		    	#Se verifica si es un fichero
		    elif [ -f $PathApache ]; then
		    	#Se crea el directorio donde se almacenarán los binarios
		    	[ ! -d $DirStorage/$Service/sbin/ ] && mkdir -p $DirStorage/$Service/sbin/
		    	#Se copia el fichero al directorio anteriormente creado
		    	# cp $PathApache $DirStorage/$Service/sbin/

		    	scp -C -r $username@$IPAddress:$PathApache $DirStorage/$Service/sbin/
		    else
		    	#En caso de que no sea un directorio
		    	echo "No se reconoce el fichero " $PathApache
		    fi
		done

		echo -e "\n\e[0;32mLa copia de seguridad de Apache se ha realizado con éxito.\e[0;37m\n"
	else
		ShowErrors apache2 "El servicio no existe"
	fi
}

##################################################################
#Crear respaldo del servicio apache2
function BackupMySQL(){
	#Se verifica que el servicio esté activo
	Service="mysql"

	#Se extrae el valor retornado en segundo plano para verificar
	if [ `VerifyService mysql` == "Well" ]; then
		#Un array que como elementos contiene las rutas absolutas a Apache
		PathMySQLArray=("/usr/lib/mysql/" "/etc/mysql" "/usr/share/mysql")

		[ ! -d $DirStorage/$Service/ ] && mkdir -p $DirStorage/$Service/

		#Se recorre el array con el objetivo de validar, crear y copiar
		for PathMySQL in ${PathMySQLArray[*]}; do
			#Se verifica que el directorio exista
		    if [ -d $PathMySQL ]; then
		    	#Se almacena en una nueva variable local, el nombre del directorio donde se encuentra alojado
		    	local PathFolder=$(basename $(dirname $PathMySQL))
		    	#Se crea un directorio en el directorio de respaldo con el nombre extraído
		    	mkdir $DirStorage/$Service/$PathFolder
		    	#Ocurridos los pasos anteriores, se procede a realizar la copia de la información
		    	# cp -rf $PathMySQL/ $DirStorage/$Service/$PathFolder

		    	scp -C -r $username@$IPAddress:$PathMySQL $DirStorage/$Service/$PathFolder
		    fi
		done

		ArrayFilesMySQL=($(ssh $username@$IPAddress ls -d /usr/bin/mysql*))
		for PathFileMySQL in ${ArrayFilesMySQL[*]}; do
			#Se verifica que el directorio exista
		   	[ ! -d $DirStorage/$Service/bin/ ] && mkdir -p $DirStorage/$Service/bin/
			scp -C -r $username@$IPAddress:$PathFileMySQL $DirStorage/$Service/bin/
		done

		echo -e "\n\e[0;32mLa copia de seguridad de MySQL se ha realizado con éxito.\e[0;37m\n"
	else
		ShowErrors mysql "El servicio no existe"
	fi
}

##################################################################
# Variables globales
# -------------------------------------------------------
date_dir=$(date +%d-%b-%Y)
date_file=$(date +%d-%m-%Y)
hour=$(date +%H-%M)
dhcp="dhcp"
dns="dns"

# Array de directorios a respaldar del servicio DHCP
dirDHCP=("/etc/dhcp/"
    "/usr/sbin/dhcpd"
    "/etc/init.d/isc-dhcp-server"
)

# Array de directorios a respaldar del servicio DNS
dirDNS=("/etc/bind/"
    "/usr/bin/bind9-config"
    "/etc/init.d/bind9"
    "/etc/defaul/bind9"
)

##################################################################
# Función para realizar la copia del servicio dhcp
function backupDHCP() {
    paquete=$(ssh $username@$IPAddress dpkg -l | grep isc-dhcp-server)
    if [[ $paquete != '' ]]; then
        if [[ ! -d /BackUP ]]; then
            mkdir /BackUP
        fi
        chmod -R 777 /BackUP/
        if [[ ! -d /BackUP/$date_dir ]]; then
            mkdir /BackUP/$date_dir
        fi
        if [[ ! -d /BackUP/$date_dir/$dhcp ]]; then
            mkdir /BackUP/$date_dir/$dhcp
        fi

        # Respaldo de los archivos del servicio DHCP
        for dir_remoto in ${dirDHCP[*]}; do
            scp -C -r $username@$IPAddress:$dir_remoto /BackUP/$date_dir/$dhcp/
        done

        if [[ $? -eq 0 ]]; then
            echo -e "\n\e[0;32mBacKUP realizado con éxito!"
            echo -e "\e[0;37m"
        else
            echo -e "\n\e[0;31mOcurrio un error durante el BacKUP\n"
            echo -e "\e[0;37m"
        fi      
    else
        echo -e "\n\e[0;31mEl servicio no está instalado en el servidor"
        echo -e "Se debe instalar el paquete isc-dhcp-server\n"
        echo -e "\e[0;37m"
    fi
}

##################################################################
function comprimirDHCP() {
    cd /BackUP/$date_dir/
    tar -czvf $namehost"_"$date_file"_"$hour.tar.gz $dhcp/
    rm -rf $dhcp
}

##################################################################
function backupDNS() {
    paquete=$(ssh $username@$IPAddress dpkg -l | grep 'bind9')
    if [[ $paquete != '' ]]; then
        if [[ ! -d /BackUP ]]; then
            mkdir /BackUP
        fi
        if [[ ! -d /BackUP/$date_dir ]]; then
            mkdir /BackUP/$date_dir
        fi
        if [[ ! -d /BackUP/$date_dir/$dns ]]; then
            mkdir /BackUP/$date_dir/$dns
        fi
        
        # Respaldo de los archivos del servicio DNS
        for dir_remoto in ${dirDNS[*]}; do
        	#Acá una observación, no es dns?
            scp -C -r $username@$IPAddress:$dir_remoto /BackUP/$date_dir/$dns/
        done

        if [[ $? -eq 0 ]]; then
            echo -e "\n\e[0;32mBacKUP realizado con éxito!"
            echo -e "\e[0;37m"
        else
            echo -e "\n\e[0;31mOcurrio un error durante el BacKUP\n"
            echo -e "\e[0;37m"
        fi      
    else
        echo -e "\n\e[0;31mEl servicio no está instalado el servidor"
        echo "Se debe instalar el paquete bind9\n"
        echo -e "\e[0;37m"
    fi
}

##################################################################
function comprimirDNS() {
    cd /BackUP/$date_dir/
    tar -czvf $namehost"_"$date_file"_"$hour.tar.gz $dns
    rm -rf $dns
}

##################################################################
function backupData() {
    if [[ ! -d /var/data ]]; then
        mkdir /var/data
    fi
    if [[ ! -d /var/data/$date_dir ]]; then
        mkdir /var/data/$date_dir
    fi
    scp -C -r $username@$nameserver:$data /var/data/$date_dir/
    
    cd /var/data/$date_dir/
    tar -czvf $namehost"_"$date_file"_"$hour.tar.gz $dns
    rm -rf $dns
    ls /var/data/$date_dir
}

##################################################################
function CompressFiles(){
	if [ -d $DirStorage ]; then
		cd $DirStorage
		tar -czf $DirStorage.tar.gz *
		rm -rf ../$DirCompact/
	fi
}

##################################################################
#Muestra los errores que ocurran, recibe parámetros con el servicio y mensaje
function ShowErrors(){
	echo -e "+---------------------------------------------------------+"
	echo -e "+ Script en ejecución:\e[0;31m" $0 "\e[0;37m| Servicio:\e[0;31m" ${1} "\e[0;37m"
	echo -e "+---------------------------------------------------------+"
	echo -e "+ Mensaje de Error:\e[0;31m" ${2} "\e[0;37m"
	echo -e "+---------------------------------------------------------+"
}

##################################################################
#Se llaman las funciones
if [ $# == 0 ]; then
	echo "Por favor, pase los parámetros necesarios. [IP][-Services ...]"
else
	CreateDirs
	LA=$@
	ListArguments=(${LA// / })
fi

CONTADOR=1
while [  $CONTADOR -lt $# ]; do

	if [ $# == 1 ]; then
		echo "Agregue los servicios que quiere hacer copia de seguridad."
	elif [ $# -gt 1 ]; then
		
		case ${ListArguments[$CONTADOR]} in
		    "-apache")
		        BackupApache
		    ;;
		    "-mysql")
		        BackupMySQL
		    ;;
		    "-dhcp")
		        backupDHCP
        		comprimirDHCP
		    ;;
		    "-dns")
		        backupDNS
        		comprimirDNS
		    ;;
		    "-data")
		        backupData
		    ;;
		    *)
		        echo "El parámetro: "${ListArguments[$CONTADOR]} "es desconocido"
		    ;;
		esac

	fi

 	# echo "El contador es" $CONTADOR "y el valor es: "${ListArguments[$CONTADOR]}
 	let CONTADOR=CONTADOR+1 
done

CompressFiles