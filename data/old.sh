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
#Defino variables para actualizar base de datos
user="reciboslogo"
password="logo[423]"
segunda=`expr 0`
Fecha=`date +%F`

Archivorar="escalagrises300dpi.rar"



function extraigodatos() {
clave=("liquidación" "liquidacién" "liquidacion")

	for(( x=0;x<=9;x++)) {

	echo ${clave[$x]}

	Liquidacion=`cat $file.txt | sed -n 's/.*${clave[$x]} \([0-9]*\/[0-9]*\).*/\1/p' | awk -F'/' '{print $1}'`
	Liquidacion=`echo "${Liquidacion}"  | cut -d ";" -f 1`
	
	Liquidacionanio=`cat $file.txt | sed -n 's/.*${clave[$x]} \([0-9]*\/[0-9]*\).*/\1/p' | awk -F'/' '{print $2}'`
	Liquidacionanio=`echo "${Liquidacionanio}"  | cut -d ";" -f 1`

	}
}

clear

unrar e $Archivorar -y
for f in *\ *; do mv "$f" "${f// /_}"; done

for file in *.png
do
echo "Procesando: " $file

################################################################################################################
#####################      Convierto PNG a HTML para extraer datos 			   #####################

convert -quiet -monochrome -normalize -density 330 "$file" "$file.jpg"
#convert "$file.jpg" "$file.png"
FileImage="$file"

echo "processing $file ..."
if [ -f "$file-0.jpg" ]; then
file="$file-0"
fi
convert "$file.jpg" "$file.pnm"
unpaper --overwrite "$file.pnm" "unpaper-$file"
ppm2tiff "unpaper-$file" "prepared-$file.tif" && rm "unpaper-$file"

tesseract "prepared-$file.tif" "$file" 

########################################             FIN                ########################################
#Analizo el texto
CI=`cat $file.txt | sed -n 's/.*CI: \([0-9][0-9]*\).*/\1/p'`
if [ "$CI" == "" ]; then
CI=`cat $file.txt | sed -n 's/.*Cl: \([0-9][0-9]*\).*/\1/p'`
fi

Empleado=`cat $file.txt | sed -n 's/.*Empleado: \([0-9][0-9]*\).*/\1/p'`

Liquidacion=`cat $file.txt | sed -n 's/.*liquidación \([0-9]*\/[0-9]*\).*/\1/p' | awk -F'/' '{print $1}'`
Liquidacion=`echo "${Liquidacion}"  | cut -d ";" -f 1`
if [ "$Liquidacion" == "" ]; then
Liquidacion=`cat $file.txt | sed -n 's/.*liquidacién \([0-9]*\/[0-9]*\).*/\1/p' | awk -F'/' '{print $1}'`
Liquidacion=`echo "${Liquidacion}"  | cut -d ";" -f 1`
fi

Liquidacionanio=`cat $file.txt | sed -n 's/.*liquidación \([0-9]*\/[0-9]*\).*/\1/p' | awk -F'/' '{print $2}'`
Liquidacionanio=`echo "${Liquidacionanio}"  | cut -d ";" -f 1`
if [ "$Liquidacionanio" == "" ]; then
Liquidacionanio=`cat $file.txt | sed -n 's/.*liquidacién \([0-9]*\/[0-9]*\).*/\1/p' | awk -F'/' '{print $2}'`
Liquidacionanio=`echo "${Liquidacionanio}"  | cut -d ";" -f 1`
fi

Liquidacion=`cat $file.txt | sed -n 's/.*liquidacion \([0-9]*\/[0-9]*\).*/\1/p' | awk -F'/' '{print $1}'`
Liquidacion=`echo "${Liquidacion}"  | cut -d ";" -f 1`
if [ "$Liquidacion" == "" ]; then
Liquidacion=`cat $file.txt | sed -n 's/.*liquidacién \([0-9]*\/[0-9]*\).*/\1/p' | awk -F'/' '{print $1}'`
Liquidacion=`echo "${Liquidacion}"  | cut -d ";" -f 1`
fi


  #Extraigo CI
#  Num_Txt_CI=`expr index "$Txt_CI" ':'`
#  Txt_CI_Aux=`echo ${Txt_CI:$Num_Txt_CI}`
#  Num_Txt_CI_Aux=`expr index "$Txt_CI_Aux" ' '`

#  CI=`echo ${Txt_CI:$Num_Txt_CI:$Num_Txt_CI_Aux}`

#rm  "$file.pnm" "prepared-$file.tif" "$file.jpg" "$file.txt" >/dev/null

if [ "$Liquidacionanio" != "" ]; then
	if [ "$Liquidacion" != "" ]; then
		if [ "$CI" != "" ]; then
		mv $file procesados/$CI-$Liquidacion-$Liquidacionanio.png
		ArchivoFinal="procesados/$CI-$Liquidacion-$Liquidacionanio.png"
		fi
	fi
fi
 echo "-"
  echo "Archivo $file - tiene la CI:$CI del empleado: $Empleado, liquidacion Mes: $Liquidacion, año: $Liquidacionanio"
 echo "-"

if [ "$CI" != ""  ]; then
	if [ "$ArchivoFinal" != '' ]; then
	echo "INSERT INTO \`recibos\` (\`ci\`, \`empleado\`, \`mes\`, \`anio\`, \`fecha\`, \`archivo\`) VALUES ('${CI}', '${Empleado}', '${Liquidacion}', '${Liquidacionanio}', '${Fecha}', '${ArchivoFinal}') ;"  | mysql -u $user --password=$password -D "logocel"
	echo "Guardo datos"
	echo "-"
	fi
fi

done
