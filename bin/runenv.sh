#!/bin/bash

GROUP="docker"
CONTAINER_BASENAME="flash"
DOCKER_NETWORK="aa_gateway"
RUNNING_CONTAINER=`docker ps --filter name=${CONTAINER_BASENAME} -aq`

#Create docker group
if ! grep -q $GROUP /etc/group; then
    sudo groupadd $GROUP
fi

#Add user to docker group and apply immediately
if ! groups $USER | grep &>/dev/null "\b${GROUP}\b"; then
    sudo usermod -aG $GROUP $USER
    sudo newgrp $GROUP
fi

# Increate vm.max_map_count
if ! [[ `sysctl vm.max_map_count | awk -F' ' '{print $3}'` =~ 262144 ]]; then
    sudo sysctl -w vm.max_map_count=262144
fi

if ! [[ -z $RUNNING_CONTAINER ]]; then
    docker stop $RUNNING_CONTAINER && docker rm $RUNNING_CONTAINER
fi

ln -sf environment/development.env .env
docker-compose build

# Create docker network
if ! [[ `docker network list | grep -w $DOCKER_NETWORK | awk -F' ' '{print $2}'` =~ $DOCKER_NETWORK ]]; then
    docker network create $DOCKER_NETWORK
fi

docker-compose up