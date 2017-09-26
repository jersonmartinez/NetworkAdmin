#!/bin/bash
FILE=servicios.txt
TIME='date +%Y-%m-%d_%H-%m-%S'

while read linea; do

if ps ax | grep -v grep | grep $linea > /dev/null
    then
        echo "Servicio $linea esta ejecutandose"
    else
        echo "El Servicio $linea ,estaba DETENIDO, a las $TIME" >> Servicios.log
    service $linea start
    echo "Validar el estado del servicio, si esta down, puede ser iniciado con:  service $linea status, estaba down a las $TIME" | mail -s "Servicio $linea Detenido " usuario@dominio.com
fi

done < "$FILE"
