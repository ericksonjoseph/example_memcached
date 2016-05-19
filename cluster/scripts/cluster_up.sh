#!/bin/bash

# Set working dir
cd $(dirname $0)

# Do this from the VM after starting all redis nodes
./redis-trib.rb create --replicas 1 $(cat ../machines/nodes.txt | xargs echo)
