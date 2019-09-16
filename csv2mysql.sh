#!/bin/sh
# pass in the file name as an argument: ./mktable filename.csv
# https://stackoverflow.com/questions/9998596/create-mysql-table-directly-from-csv-file-using-the-csv-storage-engine

echo "create table $1 ( "
head -1 $1 | sed -e 's/,/ varchar(255),\n/g'
echo " varchar(255) );"

