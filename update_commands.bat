@echo off
echo Running Git Pull...
start git pull
echo Git Pull Completed.

echo Running npm install...
start npm install
echo npm install Completed.

echo Running Composer install...
start composer i
echo Composer install Completed.

echo Running PHP Artisan Migrate...
start /wait php artisan migrate:fresh
echo PHP Artisan Migrate Completed.

echo Running PHP Artisan Cache Clear...
start /wait php artisan cache:clear
echo PHP Artisan Cache Clear Completed.

echo Running PHP Artisan Route Cache...
start /wait php artisan route:cache
echo PHP Artisan Route Cache Completed.

echo All commands completed.
pause
