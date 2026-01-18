#!/bin/bash

# Wait for services script
# سكريبت انتظار الخدمات

set -e

usage() {
    echo "Usage: wait_for_services.sh host:port [host:port] ... [-- command args]"
    echo "Example: wait_for_services.sh db:5432 redis:6379 -- python manage.py runserver"
}

if [ $# -eq 0 ]; then
    usage
    exit 1
fi

TIMEOUT=60
QUIET=0

# Parse arguments
SERVICES=()
COMMAND=()
FOUND_SEPARATOR=0

for arg in "$@"; do
    if [ "$arg" = "--" ]; then
        FOUND_SEPARATOR=1
        continue
    fi
    
    if [ $FOUND_SEPARATOR -eq 0 ]; then
        SERVICES+=("$arg")
    else
        COMMAND+=("$arg")
    fi
done

# Function to wait for a single service
wait_for_service() {
    local service=$1
    local host=$(echo $service | cut -d: -f1)
    local port=$(echo $service | cut -d: -f2)
    
    echo "Waiting for $service..."
    
    for i in $(seq 1 $TIMEOUT); do
        if nc -z "$host" "$port" > /dev/null 2>&1; then
            echo "$service is available!"
            return 0
        fi
        sleep 1
    done
    
    echo "Timeout waiting for $service"
    return 1
}

# Wait for all services
echo "Waiting for services to be ready..."
for service in "${SERVICES[@]}"; do
    wait_for_service "$service"
done

echo "All services are ready!"

# Execute command if provided
if [ ${#COMMAND[@]} -gt 0 ]; then
    echo "Executing command: ${COMMAND[*]}"
    exec "${COMMAND[@]}"
fi