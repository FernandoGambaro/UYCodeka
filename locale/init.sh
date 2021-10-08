#!/bin/bash
set -x #echo on

# Initialize

#xgettext --package-name=UYCODEKA --package-version=1.22.7.19  --from-code=UTF-8 -o messages.pot ../*.php 
cd ..
find . -iname "*.php" | egrep -v "class|ZZ-*|excel|MySQL*|vendor|jpgraph|ayuda|barcode|fpdf|mpdf60|js3|phpjob|tmp|fotos|estilos|cron|funciones" | xargs xgettext --package-name=UYCODEKA --package-version=1.22.7.19  --from-code=UTF-8 -o locale/messages.pot

cd locale
#echo "messages.pot"
