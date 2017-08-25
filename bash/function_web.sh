function sitiosVirtuales() {
	SITIOS=$(ls /etc/apache2/sites-available/)
	#CANT=${#SITIOS[*]}
	#echo "Cantidad de sitios virtuales,$CANT"
	for i in ${SITIOS[*]}; do
		NAME_SERVER=$(cat /etc/apache2/sites-available/$i | grep 'ServerName' | cut -d ' ' -f2 | tail -n1)
		SITE_ENABLE=$(ls /etc/apache2/sites-enabled/ | grep $i)
		if [[ $SITE_ENABLE == "" && $NAME_SERVER == "" ]]; then
			echo "$i,Sin nombre,Sitio no habilitado"
		else
			echo "$i,$NAME_SERVER,Sitio habilitado"
		fi
	done
	echo "="
}