#!/bin/bash
./redis-trib.rb create --replicas 1 $(cat ../machines/nodes.txt | xargs echo)
