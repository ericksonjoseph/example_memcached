#!/bin/bash
# Download Elasticsearch
wget -O - http://packages.elasticsearch.org/GPG-KEY-elasticsearch | sudo apt-key add -
echo "deb https://packages.elastic.co/elasticsearch/2.x/debian stable main" | sudo tee -a /etc/apt/sources.list.d/elasticsearch-2.x.list

# Install Elasticsearch
sudo apt-get update
sudo apt-get install elasticsearch -y

# Configure Elasticsearch
cp /global/provision/elasticsearch.yml /etc/elasticsearch/elasticsearch.yml

# Start Elastic
service elasticsearch start

# Download Logstash
wget https://download.elastic.co/logstash/logstash/packages/debian/logstash_2.3.2-1_all.deb

# Install Logstash
dpkg -i logstash_2.3.2-1_all.deb
# Or: Logstash with plugins
#wget https://download.elastic.co/logstash/logstash/logstash-all-plugins-2.3.1.zip

# Configure Logstash
cp /global/provision/logstash.conf /etc/logstash/conf.d/logstash.conf

# Start Logstash
/opt/logstash/bin/logstash -f /etc/logstash/conf.d/logstash.conf

# Download Kibana
wget https://download.elastic.co/kibana/kibana/kibana-4.5.1-linux-x64.tar.gz

# Install Kibana
tar -xvf kibana-4.5.1-linux-x64.tar.gz -C /opt
mv /opt/kibana-4.5.1-linux-x64.tar.gz /opt/kibana

# Start Kibana
/opt/kibana/bin/kibana
