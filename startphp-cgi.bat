@ECHO OFF
ECHO Starting PHP FastCGI...
set PATH=C:\PHP7.2;%PATH%
C:\PHP7.2\php-cgi.exe -b 127.0.0.1:9123