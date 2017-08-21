#!/bin/bash

if [[ $1 == 'dhcp' ]]; then
	ssh network@192.168.100.2 'bash -s' < dhcp.sh
elif [[ $1 == 'dns' ]]; then
	ssh network@192.168.100.3 'bash -s' < dns.sh
elif [[ $1 == 'web' ]]; then
	ssh network@192.168.100.4 'bash -s' < web.sh
else
	echo "El parámetro es incorrecto"
fi