@echo off
cd /d "%~dp0"
set PHP_PATH=C:\xampp8.2\php\php.exe
%PHP_PATH% artisan serve
