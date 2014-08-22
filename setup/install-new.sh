#!/bin/bash

dbUser="root"
dbName="Recreo4"
dbFile="recreo_2015.sql.gz"
dbUnzippedFile="dbFile.sql"
cleanFile="clean-data.sql"

mysql -u $dbUser -h localhost -p'1qsYm_R00tP4$4wd' -Bse "CREATE DATABASE $dbName"
echo "Database $dbName has been created"

echo "Unzipping database file"

gunzip -c $dbFile > $dbUnzippedFile

echo "Creating schema and inserting initial data"
mysql -u $dbUser -h localhost -p'1qsYm_R00tP4$4wd' $dbName < $dbUnzippedFile

echo "done"

tagVersion = "1.0.0"

#echo "Please specify the tag version you want to install: (ie. 1.0.0): "
#read tagVersion

#curl -u 'kadosh.ivan@gmail.com' -L -o "recreom-v$tagVersion.zip" https://github.com/Northkastt/recreo-questionnaire-jquerymobile/archive/v$tagVersion.zip

echo "Installing Recreo Public $tagVersion"

tempFolder = "temp-recreo-public-v$tagVersion"

unzip "local-recreo-public-v$tagVersion.zip" -d $tempFolder

chown -R npadmin:www-data $tempFolder
chgrp -R www-data $tempFolder
chmod -R g+wxrs $tempFolder

echo "Creating files"

rsync -av "$tempFolder/local-recreo-public-$tagVersion/* /usr/share/nginx/recreo.public/

mkdir /usr/share/nginx/recreo.public/

echo "Creating virtual host"
touch /etc/nginx/sites-enabled/recreo.public
cat virtual_host.txt >> /etc/nginx/sites-enabled/recreo.public

echo "Deleting temp files"

rm -r $tempFolder

echo "Recreo Public $tagVersion has been installed"