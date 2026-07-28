@echo off
setlocal

set "MYSQL=D:\xampp\mysql\bin\mysql.exe"
set "DB_NAME=yiigrysxa_dauandaquasudung"
set "DB_USER=yiigrysxa_dauandaquasudung"
set "DB_PASS=ZZrY7Dq7LP"
set "SQL_FILE=%~dp0yiigrysxa_dauandaquasudung.sql"
set "LOCAL_URL=http://localhost/dauandaquasudung"

if not exist "%MYSQL%" (
  echo Cannot find mysql.exe at "%MYSQL%".
  exit /b 1
)

if not exist "%SQL_FILE%" (
  echo Cannot find SQL dump at "%SQL_FILE%".
  exit /b 1
)

echo Creating database and local user...
"%MYSQL%" -u root --default-character-set=utf8mb4 -e "CREATE USER IF NOT EXISTS '%DB_USER%'@'localhost' IDENTIFIED BY '%DB_PASS%'; ALTER USER '%DB_USER%'@'localhost' IDENTIFIED BY '%DB_PASS%'; DROP DATABASE IF EXISTS %DB_NAME%; CREATE DATABASE %DB_NAME% CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; GRANT ALL PRIVILEGES ON %DB_NAME%.* TO '%DB_USER%'@'localhost'; FLUSH PRIVILEGES;"
if errorlevel 1 exit /b 1

echo Importing "%SQL_FILE%" with utf8mb4...
"%MYSQL%" -u root --default-character-set=utf8mb4 %DB_NAME% < "%SQL_FILE%"
if errorlevel 1 exit /b 1

echo Applying local WordPress URL and permalink settings...
"%MYSQL%" -u root --default-character-set=utf8mb4 %DB_NAME% -e "UPDATE bz_options SET option_value='%LOCAL_URL%' WHERE option_name IN ('siteurl','home'); UPDATE bz_options SET option_value='/%%postname%%/' WHERE option_name='permalink_structure'; UPDATE bz_options SET option_value='' WHERE option_name IN ('category_base','tag_base'); DELETE FROM bz_options WHERE option_name='rewrite_rules';"
if errorlevel 1 exit /b 1

echo Done. Open %LOCAL_URL%/
