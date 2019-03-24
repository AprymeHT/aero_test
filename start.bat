@echo off
echo Starting servers...
set PHP_FCGI_MAX_REQUESTS=0
set SRVPATH=C:\nginx
net start MySQL
start /D%SRVPATH% C:\nginx\nginx.exe
