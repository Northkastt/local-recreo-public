#!/bin/bash

dbUser="root"
dbName="Recreo"
cleanFile="clean-data.sql"
virtualHostFile="/etc/nginx/sites-enabled/recreo.public"
tagVersion="1.0.0"
tempFolder="temp-recreo-public-v$tagVersion"

echo "Truncating and deleting schema data"
mysql -u $dbUser -h localhost -p'1qsYm_R00tP4$4wd' $dbName < $cleanFile

echo "done"

#echo "Please specify the tag version you want to install: (ie. 1.0.0): "
#read tagVersion

#curl -u 'kadosh.ivan@gmail.com' -L -o "recreom-v$tagVersion.zip" https://github.com/Northkastt/recreo-questionnaire-jquerymobile/archive/v$tagVersion.zip

echo "Installing Recreo Public $tagVersion"

mkdir $tempFolder

unzip local-recreo-public-v$tagVersion.zip -d $tempFolder

chown -R npadmin:www-data $tempFolder
chgrp -R www-data $tempFolder
chmod -R g+wxrs $tempFolder

echo "Creating files"

mkdir /usr/share/nginx/recreo.public/
rsync -av --exclude 'setup' $tempFolder/local-recreo-public-$tagVersion/* /usr/share/nginx/recreo.public/

echo "Creating virtual host"
touch $virtualHostFile
cat virtual_host >> $virtualHostFile

echo "Deleting temp files"

rm -r $tempFolder

echo "Recreo Public $tagVersion has been installed"