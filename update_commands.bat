@echo off
echo Running Git Pull...
start /wait git pull
echo Git Pull Completed.

echo Running npm update...
start npm update
echo npm update Completed.

echo Running Composer update...
start composer update
echo Composer update Completed.

echo Running PHP Artisan Migrate...
start /wait php artisan migrate:fresh --seed
echo PHP Artisan Migrate Completed.

echo Running PHP Artisan Cache Clear...
start /wait php artisan cache:clear
echo PHP Artisan Cache Clear Completed.

echo Running PHP Artisan Route Cache...
start /wait php artisan route:cache
echo PHP Artisan Route Cache Completed.

echo All commands completed.
pause
