#!/bin/bash
#################################################################################################
#                                  __     __         ____       ____ 						#
#                                |      \/     |     / ___|     / ___|						#
#                               |    | \/|   |    | |              | |    						#
#                              |    |     |   |    | |___      | |___ 						#
#                             |_ |     |_ |     \____|    \____|.com.uy					#
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
Archivo="$1"
Mediopa="$2"
Cta="$3"


if [ "$Archivo" != "" ]; then

#Defino variables para actualizar base de datos
user="reciboslogo"
password="logo[423]"
Fecha=`date +%F`


Empleado=""
CI=""
Anio=''
Liquidacion=""
Liquidacionanio=""
LIQ=""

#Para poder trabajar con alias
shopt -s expand_aliases

function extraigodatos() {

clave=("Ene a Dic-" "Ene a Die-")

	for(( x=0;x<=1;x++)) {
		if [ "$Anio" == "" ]; then
		#echo "cat ${1}.txt | sed -n '/${clave[$x]}/p'  | awk -F'-' '{print \$2}' | awk -F' ' '{print \$1}'"
			alias a="cat ${1}.txt | sed -n '/${clave[$x]}/p'  | awk -F'-' '{print \$2}' | awk -F' ' '{print \$1}'"
			Anio=`a`
			unalias a
		fi
	}
			Anio=20${Anio}
			
clave=("CI" "Cl" "ct" "on" "el" "c1" "or" "1:1" "en" "C1")

	for(( x=0;x<=9;x++)) {
		if [ "$CI" == "" ]; then
			alias a="cat ${1}.txt | sed -n 's/.*${clave[$x]} \([0-9][0-9]*\).-*/\1/p'"
			CI=`a`
			unalias a
		fi
	}
CI=${CI:0:7}

#echo "Liquidacion "$Liquidacion
#echo "año "$Liquidacionanio
#echo "nº empleado "$Empleado
#echo "ci "$CI
#echo "Fecha: "$Fecha
#echo ""
}

clear

if [ ! -f "${Archivo}" ]; then
exit 1
fi

if [[ "${Archivo#*.}" == "rar" ]]; then
unrar e $Archivo -y >/dev/null
fi
if [[ "${Archivo#*.}" == "zip" ]]; then
unzip -o -j $Archivo  >/dev/null
fi
#Comentar la siguiente líne para pruebas
for f in *\ *; do mv "$f" "${f// /_}"; done

#for FILE in *.png ; do NEWFILE=`echo $FILE | sed 's/ /_/g'` ; mv "$FILE" $NEWFILE ; done

for file in *.png
do


#echo "Procesando: " $file

################################################################################################################
#####################      Convierto PNG a HTML para extraer datos 			   #####################
#convert -resample 400x400 -quality 100 -density 500 "$file" "$file.jpg" >/dev/null

convert -resize 1240x1754 $file $file  >/dev/null
convert -colorspace Gray -depth 8 -resample 300x300 "$file" "$file.jpg" >/dev/null
textcleaner -g -e none -f 10 -o 5 "$file.jpg" "$file.jpg"

FileImage="$file"
#Descomentar la siguiente líne para pruebas	
#echo "processing $file ..."

if [ -f "$file-0.jpg" ]; then
file="$file-0"
fi
convert "$file.jpg" "$file.pnm" >/dev/null
unpaper --overwrite "$file.pnm" "unpaper-$file" >/dev/null
convert "unpaper-$file" "prepared-$file.tif" && rm "unpaper-$file" >/dev/null
#ppm2tiff "unpaper-$file" "prepared-$file.tif" && rm "unpaper-$file" >/dev/null

tesseract "prepared-$file.tif" "$file" -psm 6 config textord_heavy_nr 1 -l spa > /dev/null 2>&1

convert "$file" -page A4  "$file" >/dev/null
########################################             FIN                ########################################
#Analizo el texto

extraigodatos $file


		if [ "$CI" != "" ]; then
		
#Comentar la siguiente líne para pruebas	
		
		rm "$file.txt" >/dev/null
		//convert $file -crop 1240x870+0+0 $file
		mv -u $file /reciboslogo/data/procesados/$CI-$Anio-IRPF.png >/dev/null
		ArchivoFinal="procesados/$CI-$Anio-IRPF.png"
		fi
		
#Elimino los archivos temporales
rm  "$file.pnm" "prepared-$file.tif" "$file.jpg" >/dev/null

#Descomentar laa siguientea líneas para pruebas	
# echo "-^-"
#  echo "Archivo $file - tiene la CI:$CI Año: $Anio"
# echo "-^-"

if [ "$CI" != ""  ]; then
	if [ "$ArchivoFinal" != '' ]; then
	
	#echo 'SELECT `anio` FROM `recibos` WHERE `ci`="'${CI}'" AND `archivo`="'${ArchivoFinal}'" AND `tipoliq`="Resumen IRPF " ;'
	query='SELECT `anio` FROM `recibos` WHERE `ci`="'${CI}'" AND `archivo`="'${ArchivoFinal}'" AND `tipoliq`="Resumen IRPF" ;'
	echo "$query" | mysql -u $user --password=$password -D "logocel" >tmps.txt
	RESULTADO=`cat tmps.txt | sed -e 's/anio//g'`
		if [ "${RESULTADO}" == "" ]; then
			
		#echo 'SELECT `empleado` FROM `empleados` WHERE `ci` LIKE "'${CI}'" ;'
		Empleado=''
		query='SELECT `empleado` FROM `empleados` WHERE `ci` LIKE "'${CI}'" ;'
		Empleado=`echo "$query" | mysql -u $user --password=$password -s -D "logocel"`		
#Descomentar la siguiente líne para pruebas	
		#echo "Empleado ->" $Empleado
		#RESULTADO=`expr $RESULTADO`
#Comentar la siguiente líne para pruebas
	rm "tmps.txt" >/dev/null

#Comentar la siguiente líne para pruebas
		echo "INSERT INTO \`recibos\` (\`ci\`, \`empleado\`, \`mes\`, \`anio\`, \`fecha\`, \`tipoliq\`, \`mediopago\`, \`cta\`, \`archivo\`) VALUES ('${CI}', '${Empleado}', '', '${Anio}', '${Fecha}', 'Resumen IRPF', '', '', '${ArchivoFinal}') ;"  | mysql -u $user --password=$password -D "logocel"		
		#echo "Guardo datos"
		#echo " "
		fi
	
	fi
fi

Empleado=""
CI=""
Anio=""
done

fi
