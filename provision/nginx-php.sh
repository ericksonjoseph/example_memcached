#!/bin/bash
DIRECTORY=/global/provision/downloads/phpredis
echo "export TERM=xterm" >> ~/.bashrc

# the DEBS env variable doesnt seem to be working
sudo apt-get install -y php5-memcached telnet tmux htop php5-dev
cp /global/provision/redis.ini /etc/php5/fpm/conf.d/
cp /global/provision/redis.ini /etc/php5/cli/conf.d/

# Install the phpredis extension
if [ ! -d "$DIRECTORY" ]; then
    git clone https://github.com/phpredis/phpredis /global/provision/downloads/phpredis
fi
cd $DIRECTORY
phpize
./configure
make && make install
