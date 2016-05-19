#!/bin/bash

sleep 0.5

if [ -z $1 ]; then
    echo "Please pass the number of nodes you want"
    exit 1
fi

# Set working dir
cd $(dirname $0)

# Remove all curent machines
rm -r ../machines/*

host="127.0.0.1"

# Create machine setups
for i in `seq 0 $(($1-1))`; do

    port=700$i

    echo "creating dir ../machines/$port"
    mkdir ../machines/$port

    redis_config_file=../machines/$port/redis.conf
    echo "creating redis config file at $redis_config_file"

    echo "port $port" >> $redis_config_file
    echo "cluster-enabled yes" >> $redis_config_file
    echo "cluster-config-file nodes.conf" >> $redis_config_file
    echo "cluster-node-timeout 5000" >> $redis_config_file
    echo "appendonly yes" >> $redis_config_file

    #Log the location of this node on the network
    echo "adding $host:$port to nodes.txt file"
    echo "$host:$port" >> ../machines/nodes.txt
done
