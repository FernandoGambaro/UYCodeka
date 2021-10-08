#!/bin/bash
#################################################################################################
#                                 __  __   ____   ____ 						#
#                                |  \/  | / ___| / ___|						#
#                                | |\/| || |    | |    						#
#                                | |  | || |___ | |___ 						#
#                                |_|  |_| \____| \____|.com.uy					#
#												#
# Copyright (C) 2016 Fernano Gámbaro <fgambaro@adinet.com.uy> - Uruguay				#
#	5/02/2016 										#
# Este material se distibuye tal cual sin garantías ni obligaciones de ningún tipo		#
# Ud. lo puede redistribuir y/o modificar segun los terminos de "GNU General Public License"	#
# como los publica "Free Software Foundation" inclusive la version 2 o posterior		#
#################################################################################################
#
# Programas necesarios para el funcionamiento
# $ sudo apt-get install imagemagick ghostscript pdftk pdfjam exactimage
#
#
Archivorar="$1"


if [ "$Archivorar" != "" ]; then

#Defino variables para actualizar base de datos
user="reciboslogo"
password="logo[423]"
segunda=`expr 0`
Fecha=`date +%F`


Empleado=""
CI=""
Liquidacion=""
Liquidacionanio=""

#Para poder trabajar con alias
shopt -s expand_aliases

function extraigodatos() {
clave=("liquidación" "liquidacién" "liquidacion")

	for(( x=0;x<=2;x++)) {
		if [ "$Liquidacion" == "" ]; then
			alias a="cat ${1}.txt | sed -n 's/.*${clave[$x]} \([0-9]*\/[0-9]*\).*/\1/p' | awk -F'/' '{print \$1}'"
			Liquidacion=`a`
			unalias a
			Liquidacion=`echo "${Liquidacion}"  | cut -d ";" -f 1`
		fi
		if [ "$Liquidacionanio" == "" ]; then
			alias a="cat ${1}.txt | sed -n 's/.*${clave[$x]} \([0-9]*\/[0-9]*\).*/\1/p' | awk -F'/' '{print \$2}'"
			Liquidacionanio=`a`
			unalias a
			Liquidacionanio=`echo "${Liquidacionanio}"  | cut -d ";" -f 1`
		fi
	}

clave=("Empleado:" "Empleadoi" "EmpleadO:" "Emplead:")

	for(( x=0;x<=3;x++)) {
		if [ "$Empleado" == "" ]; then
			alias a="cat ${1}.txt | sed -n 's/.*${clave[$x]} \([0-9][0-9]*\).*/\1/p'"
			Empleado=`a`
			unalias a
	   fi
   }

clave=("CI:" "Cl:")

	for(( x=0;x<=1;x++)) {
		if [ "$CI" == "" ]; then
			alias a="cat ${1}.txt | sed -n 's/.*${clave[$x]} \([0-9][0-9]*\).*/\1/p'"
			CI=`a`
			unalias a
		fi
	}
CI=${CI:0:7}
}

clear

unrar e $Archivorar -y >/dev/null
for f in *\ *; do mv "$f" "${f// /_}"; done

for file in *.png
do
#echo "Procesando: " $file

################################################################################################################
#####################      Convierto PNG a HTML para extraer datos 			   #####################

convert -quiet -monochrome -normalize -density 330 "$file" "$file.jpg" >/dev/null
FileImage="$file"

#echo "processing $file ..."
if [ -f "$file-0.jpg" ]; then
file="$file-0"
fi
convert "$file.jpg" "$file.pnm" >/dev/null
unpaper --overwrite "$file.pnm" "unpaper-$file" >/dev/null
ppm2tiff "unpaper-$file" "prepared-$file.tif" && rm "unpaper-$file" >/dev/null

tesseract "prepared-$file.tif" "$file" > /dev/null 2>&1

convert "$file" -page A4  "$file" >/dev/null
########################################             FIN                ########################################
#Analizo el texto

extraigodatos $file

rm  "$file.pnm" "prepared-$file.tif" "$file.jpg" "$file.txt" >/dev/null

if [ "$Liquidacionanio" != "" ]; then
	if [ "$Liquidacion" != "" ]; then
		if [ "$CI" != "" ]; then
		mv -u $file ../procesados/$CI-$Liquidacion-$Liquidacionanio.png >/dev/null
		ArchivoFinal="procesados/$CI-$Liquidacion-$Liquidacionanio.png"
		fi
	fi
fi
# echo "-^-"
#  echo "Archivo $file - tiene la CI:$CI del empleado: $Empleado, liquidacion Mes: $Liquidacion, año: $Liquidacionanio"
# echo "-^-"

if [ "$CI" != ""  ]; then
	if [ "$ArchivoFinal" != '' ]; then
	echo "INSERT INTO \`recibos\` (\`ci\`, \`empleado\`, \`mes\`, \`anio\`, \`fecha\`, \`archivo\`) VALUES ('${CI}', '${Empleado}', '${Liquidacion}', '${Liquidacionanio}', '${Fecha}', '${ArchivoFinal}') ;"  | mysql -u $user --password=$password -D "logocel"
#	echo "Guardo datos"
#	echo " "
	fi
fi

Empleado=""
CI=""
Liquidacion=""
Liquidacionanio=""

done

chmod ugo=rwx *.png
rm "*.png" >/dev/null

fi