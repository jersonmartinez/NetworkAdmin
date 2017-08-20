#!/bin/bash

ssh network@192.168.100.2 'bash -s' < dhcp.sh
#ssh network@192.168.100.3 'bash -s' < dns.sh
#ssh network@192.168.100.4 'bash -s' < http.sh