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
ip_address=$1
username="network"

# Nombre del host
HostName=$(ssh $username@$ip_address hostname)
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
	PackService=$(ssh $username@$ip_address ps ax | grep -v grep | grep ${1})

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

		    	scp -C -r $username@$ip_address:$PathApache $DirStorage/$Service/$PathFolder

		    	#Se verifica si es un fichero
		    elif [ -f $PathApache ]; then
		    	#Se crea el directorio donde se almacenarán los binarios
		    	[ ! -d $DirStorage/$Service/sbin/ ] && mkdir -p $DirStorage/$Service/sbin/
		    	#Se copia el fichero al directorio anteriormente creado
		    	# cp $PathApache $DirStorage/$Service/sbin/

		    	scp -C -r $username@$ip_address:$PathApache $DirStorage/$Service/sbin/
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

		    	scp -C -r $username@$ip_address:$PathMySQL $DirStorage/$Service/$PathFolder
		    fi
		done

		ArrayFilesMySQL=($(ssh $username@$ip_address ls -d /usr/bin/mysql*))
		for PathFileMySQL in ${ArrayFilesMySQL[*]}; do
			#Se verifica que el directorio exista
		   	[ ! -d $DirStorage/$Service/bin/ ] && mkdir -p $DirStorage/$Service/bin/
			scp -C -r $username@$ip_address:$PathFileMySQL $DirStorage/$Service/bin/
		done

		echo -e "\n\e[0;32mLa copia de seguridad de MySQL se ha realizado con éxito.\e[0;37m\n"
	else
		ShowErrors mysql "El servicio no existe"
	fi
}

##################################################################
function CompressFiles(){
	cd $DirStorage
	tar -czf $DirStorage.tar.gz *
	rm -rf ../$DirCompact/
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
CreateDirs
BackupApache
BackupMySQL
CompressFiles