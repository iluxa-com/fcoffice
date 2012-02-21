@echo off

pause

SET PHP_EXE=C:\AppServ\php5\php.exe
SET PROJECT_PATH=E:\wwwroot\alice\redis_new
SET TABLE_NAME=item_data
SET COMMAND_PREFIX=mysql -h192.168.0.222 -P3306 -uplayer_devel -ptest_devel@fhdbv5! -Dalice_main

echo parse csv file ...
%PHP_EXE% -f "%PROJECT_PATH%\shell\%TABLE_NAME%_parser.php" > "%PROJECT_PATH%\install\%TABLE_NAME%.sql"

echo current row count ...
%COMMAND_PREFIX% -e"SELECT COUNT(*) FROM %TABLE_NAME%;"

echo clear old data ...
%COMMAND_PREFIX% -e"DELETE FROM %TABLE_NAME%;"

echo current row count ...
%COMMAND_PREFIX% -e"SELECT COUNT(*) FROM %TABLE_NAME%;"

echo import new data ...
%COMMAND_PREFIX% < "%PROJECT_PATH%\install\%TABLE_NAME%.sql"

echo current row count ...
%COMMAND_PREFIX% -e"SELECT COUNT(*) FROM %TABLE_NAME%;"

pause