#!/bin/bash

GROUP="docker"
CONTAINER_BASENAME="flash"
DOCKER_NETWORK="aa_gateway"
RUNNING_CONTAINER=`docker ps --filter name=${CONTAINER_BASENAME} -aq`

if ! [[ -z $RUNNING_CONTAINER ]]; then
    docker stop $RUNNING_CONTAINER && docker rm $RUNNING_CONTAINER
fi

#cp config/autoload/development.local.php.dist config/autoload/development.local.php
ln -sf environment/development.env .env
docker-compose build

# Create docker network
if ! [[ `docker network list | grep -w $DOCKER_NETWORK | awk -F' ' '{print $2}'` =~ $DOCKER_NETWORK ]]; then
    docker network create $DOCKER_NETWORK
fi

docker-compose up