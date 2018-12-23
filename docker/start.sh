#!/bin/bash
set -xe

# Detect the host IP
export DOCKER_BRIDGE_IP=$(ip ro | grep default | cut -d' ' -f 3)

source docker/utils/init-owner.sh owner

./docker/utils/wait-for-it.sh database:3306 -s -t 60 -- sudo -u $owner ./docker/install.sh

# Start Apache with the right permissions after removing pre-existing PID file
rm -f /var/run/apache2/apache2.pid
exec docker/apache/start_safe_perms -DFOREGROUND