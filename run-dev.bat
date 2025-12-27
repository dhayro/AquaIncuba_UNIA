@echo off
REM Script para ejecutar Laravel dev server con PHP 8.2
setlocal enabledelayedexpansion

REM Agregar PHP 8.2 al inicio del PATH
set PATH=C:\xampp8.2\php;%PATH%

REM Ejecutar el servidor
C:\xampp8.2\php\php.exe artisan serve

pause
