#Fecha actual (17-sep-2017)
# declare -i CurrentDate
# declare -i CurrentDateFormat
# declare -i CurrentDateTime
# declare -i PathAbsolute
# 
# 
# 
#Comprobar si apache está corriendo.
#ps -e | grep "apache2"
#ps aux | grep httpd
#netstat -ntpl | grep apache2
#service --status-all | grep -Fq 'apache2'
#
#
#
#function isPositive()
# {
# 	(($1 > 0)) && return 0 || return 1;
# }
 
# isPositive 0 && {
# 	echo -e "Es positivo";
# }
# 
# 
# 
# function suma()
# {
# 	#((resultado = $1 + $2))
# 	let "resultado=$1 + $2"
# 	return $resultado;
# }
 
# suma 2 3
# echo -e "Resultado = $?";
# 
# 
# # # definimos un array de valores
# 		valores=("primero" "segundo" "tercero")
# 		# añadimos un nuevo valor en la posicion 3 del array
# 		valores[3]="quarto"
# 		# añadimos un nuevo valor en la posicion 5 del array
# 		valores[5]="quinto"

# 		printf "\nCantidad de valores dentro del array\n"
# 		printf "   %s\n" ${#valores[*]}