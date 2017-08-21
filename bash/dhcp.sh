#!/bin/bash

#----------	Estado actual de la MEMORIA ------------

function estadoMemoria() {
	MEMORIA=($(free -m | grep 'Mem' | cut -d ':' -f2))

	#echo -e "\nEstado actual de la memoria"
	#echo "-----------------------------------"
	#echo "${MEMORIA[0]"
	#echo "Memoria usada: ${MEMORIA[1]}"
	#echo "Memoria libre: ${MEMORIA[2]}"
	#echo "Memoria compartida: ${MEMORIA[3]}"
	#echo "Búfer/caché : ${MEMORIA[4]}"
	#echo "Memoria disponible: ${MEMORIA[5]}"
	#echo "-----------------------------------"	
	echo "${MEMORIA[0]},${MEMORIA[1]},${MEMORIA[2]},${MEMORIA[3]},${MEMORIA[4]},${MEMORIA[5]},"
}

#--------- Uso de los discos duros ---------------

function usoDiscosDuros() {
	DISCO=($(df -PH | grep sda | cut -d '/' -f3))

	#echo -e "\nUso del disco duro"
	#echo "-----------------------------------"
	#echo "Tamaño del disco: ${DISCO[1]}"
	#echo "Disco usado: ${DISCO[2]}"
	#echo "Disponible: ${DISCO[3]}"
	#echo "Porcentaje de uso: ${DISCO[4]}"
	#echo "-----------------------------------"
	echo "${DISCO[1]},${DISCO[2]},${DISCO[3]},${DISCO[4]},"
}

#-------- Interfaces de red y direcciones ip colocadas en ellas ---------

function interfacesRed() {
	#INTERFACES=($(ifconfig -a | grep mtu | cut -d ':' -f1))
	INTERFACES=($(ifconfig -a -s | awk {'print $1'}))
	#echo -e "\nInterfaces de red y sus direcciones IP"
	#echo "---------------------------------------"
	echo "="
	NUM_INTER=${#INTERFACES[*]}
	for (( i = 1; i < $NUM_INTER ; i++ )); do
		DIRECCION_IP=$(ifconfig ${INTERFACES[$i]} | grep 'inet ' | cut -d ' ' -f10)
		if [[ $DIRECCION_IP != "" ]]; then
			echo "${INTERFACES[$i]},$DIRECCION_IP,"
		else
			echo "${INTERFACES[$i]},No tiene ip asignada"
		fi
	done
	echo "="
	#echo "---------------------------------------"
}

#--------- Puertos que se encuentran abiertos -----------

function puertosAbiertos() {
	PORT_TCP=($(netstat -pltona | grep 'tcp ' | awk {'print $4 ,$1'} | cut -d ':' -f2))
	PORT_TCP6=($(netstat -pltona | grep 'tcp6' | awk {'print $4 ,$1'} | cut -d ':' -f4))
	#echo -e "\nPuertos abiertos TCP"
	#echo "------------------------------"
	echo "${PORT_TCP[*]} ${PORT_TCP6[*]},"

	#echo -e "\nPuertos abiertos UDP"
	PORT_UDP=($(netstat -pluona | grep 'udp ' | awk {'print $4 ,$1'} | cut -d ':' -f2))
	PORT_UDP6=($(netstat -pluona | grep 'udp ' | awk {'print $4 ,$1'} | cut -d ':' -f4))
	echo "${PORT_UDP[*]} ${PORT_UDP6[*]}"
	#netstat -pltona | grep 'tcp ' | awk {'print $4 $1'} | cut -d ':' -f2
	#netstat -pltona | grep 'tcp6' | awk {'print $4 $1'} | cut -d ':' -f4
	#netstat -pluona | grep 'udp ' | awk {'print $4 $1'} | cut -d ':' -f2
	#netstat -pluona | grep 'udp ' | awk {'print $4 $1'} | cut -d ':' -f4
	echo "="
}

#----------Estado de las conexiones de red------------

function statusConectionsNetwork() {
	#echo -e "\nEstado de las conexions de red"
	#echo "------------------------------------"
	#netstat -putona | grep -e udp -e tcp
	PROTO=$(netstat -putona | grep -e tcp -e udp | awk {'print $1'})
	DIR_LOCAL=$(netstat -putona | grep -e tcp -e udp | awk {'print $4'})
	DIR_REMOTA=$(netstat -putona | grep -e tcp -e udp | awk {'print $5'})
	ESTADO=$(netstat -putona | grep -e tcp -e udp | awk {'print $6'})
	TEMP1=$(netstat -putona | grep -e tcp -e udp | awk {'print $7'})
	# TEMP2=$(netstat -putona | grep -e tcp -e udp | awk {'print $8'})

	echo "${PROTO[*]} | "
	echo "${DIR_LOCAL[*]} | "
	echo "${DIR_REMOTA[*]} | "
	echo "${ESTADO[*]} | "
	echo "${TEMP1[*]} | "
	# echo "${TEMP2} |"
	echo "="
}

#--------- Usuarios del sistema indicando cuales estan logueados actualmente -----------

function usuariosConectados() {
	USUARIOS=($(who | cut -d ' ' -f1))
	#echo -e "\nUsuarios logueados actualmente"
	#echo "---------------------------------------"
	#echo "Número de usuarios conectados: ${#USUARIOS[*]}"
	# echo "${#USUARIOS[*]}"
	for i in ${USUARIOS[*]}; do
		echo "$i ,"
	done
	echo "="
}

#Servidor DHCP: Interfaces e las que está asignado IPs y las asignaciones realizadas

function asignacionesRealizadas() {
	#echo -e "\ninterfaces en las que está asignado IP y las asignacionese realizadas"
	#echo "------------------------------------------------------------------------"
	#STATUS_DHCP=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'$4="";$5="";$6="";$7="";print'})
	#echo "${STATUS_DHCP[*]}"
	#service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'$4="";$5="";$6="";$7="";print'}
	#service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'$4="";$5="";$6="";$7="";print'}
	MES=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'print $1'})
	DIA=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'print $2'})
	HORA=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'print $3'})
	IP=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'print $8'})
	MAC=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'print $10'})
	INTERFAZ=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'print $NF'})
	
	echo "${MES[*]} | "
	echo "${DIA[*]} | "
	echo "${HORA[*]} | "
	echo "${IP[*]} | "
	echo "${MAC[*]} | "
	echo "${INTERFAZ[*]} | "
	echo "="
}

# Definición de funciones
estadoMemoria
usoDiscosDuros
interfacesRed
puertosAbiertos
statusConectionsNetwork
usuariosConectados
asignacionesRealizadas