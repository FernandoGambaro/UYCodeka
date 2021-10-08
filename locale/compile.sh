#!/bin/bash
#set -x #echo on

# Compile translation
# Do after translation

for FILE in *.po
do  
  LANG=`echo $FILE | cut -c1-2`
  DIR=`find $LANG\_?? -maxdepth 0`
  msgfmt $FILE -o $DIR/LC_MESSAGES/messages.mo
  echo "$FILE >> $DIR/LC_MESSAGES/messages.mo"
done

#sudo service apache2 restart
