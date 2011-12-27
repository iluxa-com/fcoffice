@echo off

SET HIDEC_PATH=E:\WebSite\logincheck\hidec.exe
SET SCRIPT_PATH=E:\WebSite\logincheck

cd /D %SCRIPT_PATH%
%HIDEC_PATH% php logincheck.php
:%HIDEC_PATH% php test.php