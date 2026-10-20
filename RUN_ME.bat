@echo off
cd /d "%~dp0"
set PHP=C:\xampp\php\php.exe
if not exist "%PHP%" ( echo XAMPP not found in C:\xampp - install XAMPP first. & pause & exit /b )
"%PHP%" -r "exit(PHP_VERSION_ID < 80200 ? 1 : 0);"
if errorlevel 1 ( echo Your XAMPP PHP is older than 8.2. Install a newer XAMPP. & pause & exit /b )
"%PHP%" -m | findstr /i "intl" >nul
if errorlevel 1 ( echo PHP intl extension is off. Open C:\xampp\php\php.ini, change ";extension=intl" to "extension=intl", save, run this again. & pause & exit /b )
echo Make sure MySQL is STARTED in the XAMPP Control Panel.
"%PHP%" spark db:create sunsonsolar
"%PHP%" spark migrate
"%PHP%" spark db:seed UserSeeder
start http://localhost:8080
"%PHP%" spark serve
pause
