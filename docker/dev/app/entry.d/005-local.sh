#!/bin/sh

echo "Local entry point..."

# Use the dev road runner configuration
cp /site/.rr.dev.yaml /site/rr.yaml

# Ensure any folders needed are created
echo " - Creating xdebug folders if needed"
if [ ! -d /site/var/xdebug ]; then mkdir -p /site/var/xdebug; fi

# Ensure that the packages are installed and the autoloader is generated
echo " - Installing composer packages"
if [ ! -d /site/vendor ]; then composer install --no-interaction --no-progress; fi
