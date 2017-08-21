function archivosZonas() {
	ZONAS=($(cat /etc/bind/named.conf.local | grep 'file' | awk {'print $2'} | tr -d '";'))
	CANT_ZONAS=${#ZONAS[*]}
	echo $CANT_ZONAS
	for (( i = 0; i < $CANT_ZONAS; i++ )); do
		#N=$(expr $i +1)
		#echo "Archivo de zona: $N"
		#echo $ZONAS[$i]
		DOMINIO=$(cat ${ZONAS[$i]} | grep 'SOA' | awk {'print $4'} | sed 's/.$//g')
		#echo $DOMINIO
		TRADUC=$(cat ${ZONAS[$i]} | grep -e 'IN' | tail -n1 | awk '! /$DOMINIO/ {print $1}') 
		IP=$(cat ${ZONAS[$i]} | grep 'IN' | tail -n1 | awk '! /$DOMINIO/ {print $4}')
		echo "${ZONAS[$i]},$DOMINIO,${TRADUC[*]}.$DOMINIO,${IP[*]}"
	done
}