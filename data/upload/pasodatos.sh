#!/bin/bash
#################################################################################################
#                                  __     __         ____       ____ 						#
#                                |      \/     |     / ___|     / ___|						#
#                               |    | \/|   |    | |              | |    						#
#                              |    |     |   |    | |___      | |___ 						#
#                             |_ |     |_ |     \____|    \____|.com.uy					#
#												#
# Copyright (C) 2018 Fernano Gámbaro <fgambaro@adinet.com.uy> - Uruguay				#
#	17/01/2018 										#
# Este material se distibuye tal cual sin garantías ni obligaciones de ningún tipo		#
# Ud. lo puede redistribuir y/o modificar segun los terminos de "GNU General Public License"	#
# como los publica "Free Software Foundation" inclusive la version 2 o posterior		#
#################################################################################################
#
# Programas necesarios para el funcionamiento
# $ sudo apt-get install imagemagick ghostscript pdftk pdfjam exactimage
#
# Para usarlo desde líne de comando ./pasodatos.sh <nombre archivo . rar> <medio de pago> <número de cuetna
#
Archivo="$1"
Mediopa="$2"
Cta="$3"


if [ "$Archivo" != "" ]; then

#Defino variables para actualizar base de datos
user="codeka"
password="kpAbv7xW33KRHmkl"
Fecha=`date +%F`


Empleado=""
Apellido=""
CI=""
Liquidacion=""
Liquidacionanio=""
LIQ=""

#Para poder trabajar con alias
shopt -s expand_aliases

function extraigodatos() {
clave=("liquidación" "liquidacién" "liquidacion" "nquidacién" "llquidacion" "quuidacion" "quuidacion" "liquidaciOn" "quuidacién")

	for(( x=0;x<=8;x++)) {
		if [ "$Liquidacion" == "" ]; then
			alias a="cat ${1}.txt | sed -n 's/.*${clave[$x]} \([0-9]*\/[0-9]*\).*/\1/p' | awk -F'/' '{print \$1}'"
			Liquidacion=`a`
			unalias a
			Liquidacion=`echo "${Liquidacion}"  | cut -d ";" -f 1`
		fi

clave=("Enero" "Febrero" "Marzo" "Abril" "Mayo" "Junio" "Julio" "Agosto" "Stiembre")
		
		if [ "$Liquidacionanio" == "" ]; then
			alias a="cat ${1}.txt | sed -n 's/.*${clave[$x]} \([0-9]*\/[0-9]*\).*/\1/p' | awk -F'/' '{print \$2}'"
			Liquidacionanio=`a`
			unalias a
			Liquidacionanio=`echo "${Liquidacionanio}"  | cut -d ";" -f 1`
		fi
	}

#clave=("Empleado:" "Empleadoi" "EmpleadO:" "Emplead:" "Empleedo:" "Emmeado:" "Emplmo:" "Empleaao:")

clave=("Nombres;" "Nombres:")
	for(( x=0;x<=7;x++)) {
		if [ "$Empleado" == "" ]; then
			alias a="cat ${1}.txt | sed -n 's/.*${clave[$x]} \(.*\).*/\1/p' | tr -d '[:space:]'"
			Empleado=`a`
			unalias a
	   fi
   }

clave=("Apellxdos." "Apellidos:")
	for(( x=0;x<=7;x++)) {
		if [ "$Apellido" == "" ]; then
			alias a="cat ${1}.txt | sed -n 's/.*${clave[$x]} \(.*\).*/\1/p' | tr -d '[:space:]'"
			Apellido=`a`
			unalias a
	   fi
   }

Empleado=$Empleado" "$Apellido

clave=("CI:" "Cl:" "ct:" "on:" "el:" "c1:" "or:" "1:1:" "en:" "C1:" "Cl:" "C.l." "C.I.")

	for(( x=0;x<=9;x++)) {
		if [ "$CI" == "" ]; then
			alias a="cat ${1}.txt | sed -n 's/.*${clave[$x]} \([0-9][0-9]*\).-*/\1/p'"
			CI=`a`
			unalias a
		fi
	}
CI=${CI:0:7}

clave=("Liq.:" "liq.:")

	for(( x=0;x<=1;x++)) {
		if [ "$LIQ" == "" ]; then
		echo "cat ${1}.txt | sed -n 's/.*${clave[$x]} \([A-Z][a-z]*\)'$\*'/\1/p'"
			alias a="cat ${1}.txt | sed -n 's/.*${clave[$x]} \([A-Z][a-z]*\)'$\*'/\1/p'"
			LIQ=`a`
			unalias a
		fi
	}


echo "Liquidacion "$Liquidacion
echo "año "$Liquidacionanio
echo "nº empleado "$Empleado
echo "ci "$CI
echo "Liq: '"${LIQ}"'"
echo ""
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
if [[ "${Archivo#*.}" == "pdf" ]]; then
pdftoppm $Archivo $Archivo -png >/dev/null
fi

#Comentar la siguiente líne para pruebas
#for f in *\ *; do mv "$f" "${f// /_}"; done

#for FILE in *.png ; do NEWFILE=`echo $FILE | sed 's/ /_/g'` ; mv "$FILE" $NEWFILE ; done

for file in *.png
do


echo "Procesando: " $file

################################################################################################################
#####################      Convierto PNG a HTML para extraer datos 			   #####################
#convert -resample 400x400 -quality 100 -density 500 "$file" "$file.jpg" >/dev/null

convert -resize 1275x1650 $file $file  >/dev/null
convert -colorspace Gray -depth 8 -resample 200x200 "$file" "$file.jpg" >/dev/null
textcleaner -g -e none -f 10 -o 5 "$file.jpg" "$file.jpg"

FileImage="$file"
#Descomentar la siguiente líne para pruebas	
echo "processing $file ..."

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

#convert -background '#0008' -fill white -gravity center -size 350 -pointsize 25 caption:"Medio de pago:  ${Mediopago}" $file +swap -geometry +0+0 -gravity center -composite $file
#convert -background '#0008' -fill white -gravity center -size 350 -pointsize 25 caption:"Cta# ${Cta}" $file +swap -geometry +0+50 -gravity center -composite $file

#Comentar la siguiente líne para pruebas	
#rm  "$file.pnm" "prepared-$file.tif" "$file.jpg" >/dev/null
# "$file.txt"

if [ "$Liquidacionanio" != "" ]; then
	if [ "$Liquidacion" != "" ]; then
		if [ "$CI" != "" ]; then
		
		## Busco los datos para el usuario del recibo
		echo 'SELECT `mediopago` FROM `usuarios` WHERE `ci` LIKE "'${CI}'" ;'
		Mediopago=''
		query='SELECT `mediopago` FROM `usuarios` WHERE `ci` LIKE "'${CI}'" ;'
		Mediopago=`echo "$query" | mysql -u $user --password=$password -s -D "logocel"`
		echo "Medio pago " $Mediopago
		if [ "$Mediopago" != "" ]; then
			convert -background '#0008' -fill white -size 280 -pointsize 14 caption:"Medio de pago:  ${Mediopago}" $file +swap -geometry +400+58 -composite $file
		fi
		
		echo 'SELECT `cta` FROM `usuarios` WHERE `ci` LIKE "'${CI}'" ;'
		Cta=''
		query='SELECT `cta` FROM `usuarios` WHERE `ci` LIKE "'${CI}'" ;'
		Cta=`echo "$query" | mysql -u $user --password=$password -s -D "logocel"`
		echo "Cuenta " $Cta
		if [ "$Cta" != "" ]; then
			convert -background '#0008' -fill white -size 280 -pointsize 14 caption:"Cta# ${Cta}" $file +swap -geometry +400+75 -composite $file
		fi		

		if [ "$Mediopa" != "" ]; then
			convert -background '#0008' -fill white -size 280 -pointsize 14 caption:"Ref# ${Mediopa}" $file +swap -geometry +400+92 -composite $file
		fi			

#Comentar la siguiente líne para pruebas	
		
		rm "$file.txt" >/dev/null
		tpoliq="${LIQ//[[:space:]]/}"
		convert $file -crop 1240x870+0+0 $file
		mv -u $file /uycodeka/data/procesados/$CI-$Liquidacion-$Liquidacionanio-$tpoliq.png >/dev/null
		ArchivoFinal="procesados/$CI-$Liquidacion-$Liquidacionanio-$tpoliq.png"
		fi
	fi
fi
#Descomentar las siguientea líneas para pruebas	

 echo "-^-"
  echo "Archivo $file - tiene la CI:$CI del empleado: $Empleado, liquidacion Mes: $Liquidacion, año: $Liquidacionanio"
 echo "-^-"

if [ "$CI" != ""  ]; then
	if [ "$ArchivoFinal" != '' ]; then
#	echo 'SELECT `mes` FROM `recibos` WHERE `ci`="'${CI}'" AND `archivo`="'${ArchivoFinal}'" AND `tipoliq`="'${LIQ}'" ;'
# comentar las siguientes líneas para pruebas
	query='SELECT `mes` FROM `recibosSueldos` WHERE `ci`="'${CI}'" AND `archivo`="'${ArchivoFinal}'" AND `tipoliq`="'${LIQ}'" ;'
	echo "$query" | mysql -u $user --password=$password -D "logocel" >tmps.txt
	RESULTADO=`cat tmps.txt | sed -e 's/mes//g'`
		if [ "${RESULTADO}" == "" ]; then
		
#Descomentar la siguiente líne para pruebas	
		echo "No existe ->" $RESULTADO
		RESULTADO=`expr $RESULTADO`
#Comentar la siguiente líne para pruebas
		rm "tmps.txt" >/dev/null

#Comentar la siguiente líne para pruebas
		
		echo "INSERT INTO \`recibosSueldos\` (\`ci\`, \`empleado\`, \`mes\`, \`anio\`, \`fecha\`, \`tipoliq\`, \`mediopago\`, \`cta\`, \`archivo\`) VALUES ('${CI}', '${Empleado}', '${Liquidacion}', '${Liquidacionanio}', '${Fecha}', '${LIQ}', '${Mediopago}','${Cta}', '${ArchivoFinal}') ;"  | mysql -u $user --password=$password -D "logocel"
		echo "Guardo datos"
	#	echo " "
		fi
	
	fi
fi

Empleado=""
Apellido=""
CI=""
Liquidacion=""
Liquidacionanio=""
LIQ=""

done


fi
