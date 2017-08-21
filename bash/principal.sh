#!/bin/bash
ssh network@192.168.100.2 'bash -s' < dhcp.sh

# if [[ $1 == 'dhcp' ]]; then
# 	ssh network@192.168.100.2 'bash -s' < dhcp.sh > read_dhcp.txt
# elif [[ $1 == 'dns' ]]; then
# 	ssh network@192.168.100.3 'bash -s' < dns.sh > read_dns.txt
# elif [[ $1 == 'web' ]]; then
# 	ssh network@192.168.100.4 'bash -s' < web.sh > read_web.txt
# else
# 	echo "El parámetro es incorrecto"
# fi