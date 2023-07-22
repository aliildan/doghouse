#!/bin/bash

# Start the first process
echo "Running start.sh"
if [ ! -d "/app/vendor" ]; then
    echo "Installing composer dependencies"
    cd /app && composer install
fi
echo "Running migrations and user seed"
php artisan migrate --seed &
php-fpm  -F &
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start php-fpm: $status"
  exit $status
fi

# Start the second process
if [ ! -d "/app/node_modules" ]; then
    echo "Installing npm dependencies"
    cd /app && npm install
fi
npm start &
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start npm: $status"
  exit $status
fi

# Naive check runs checks once 5 minute to see if either of the processes exited.
while sleep 300; do
  ps aux |grep php-fpm |grep -q -v grep
  PROCESS_1_STATUS=$?
  ps aux |grep npm |grep -q -v grep
  PROCESS_2_STATUS=$?
  echo "PROCESS_1_STATUS: $PROCESS_1_STATUS"
  echo "PROCESS_2_STATUS: $PROCESS_2_STATUS"
done
