@echo on
echo Starting servers...
set PHP_FCGI_MAX_REQUESTS=0
cd c:\web\bin\nginx\
c:\web\bin\RunHiddenConsole.exe c:\web\bin\nginx\nginx.exe
c:\web\bin\RunHiddenConsole.exe c:\web\bin\php\php-cgi.exe -b 127.0.0.1:9123 -c c:/web/bin/php/php.ini
cd ..