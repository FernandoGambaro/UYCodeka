#!/bin/bash

# Prepare translation 
# Do before translation

xgettext --from-code=UTF-8 -o messages.pot ../*.php 

for FILE in *.po
do
  echo $FILE
  msgmerge -U $FILE messages.pot 
done
