#!/bin/sh
set -e

if [ "${1}" = "-D" ]; then
    echo "Staring execution of entry points..."
    # Execute entry point scripts
    for file in /app/entry.d/*; do
        echo "Executing ${file}..."
        /bin/bash "$file"
    done

    # start supervisord and services
    echo "Starting supervisord..."
    exec /usr/bin/supervisord -n -c /etc/supervisord.conf
else
    "$@"
fi
