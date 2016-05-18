export TERM=xterm
sudo apt-get update
# Install all the stuff that usually comes in the containers
sudo apt-get install nginx php5-fpm ruby-full redis-server -y

# Now apply regular provisioning
gem install redis
source /global/provision/nginx-php.sh

# Container available = default.conf (this will be the generated default.conf)
# Container enabled = default.conf -> default.conf, default -> default
# Container will not work. Will not have needed default at all (not writing through dangling symlink)
# Vagrant available = default
# Vagrant enabled = default -> default
# This works for vagrant machine (will have good default & will work with only the default)
cp /global/provision/default /etc/nginx/sites-enabled/default

# Restart nginx to apply conf changes
service nginx restart
