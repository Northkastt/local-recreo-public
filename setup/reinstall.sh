#!/bin/bash

dbUser="root"
dbName="Recreo3"
cleanFile="clean-data.sql"


echo "Truncating data and removing some tables"
mysql -u $dbUser -h localhost -p'1qsYm_R00tP4$4wd' $dbName < $cleanFile

echo "done"