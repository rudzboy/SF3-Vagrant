#!/bin/bash

export HTTP_PROXY=http://10.0.0.1:3128
export HTTPS_PROXY=http://10.0.0.1:3128

echo "Acquire::http::Proxy \"http://10.0.0.1:3128\";" > /etc/apt/apt.conf

apt-get update

echo "Installation of puppet"
apt-get install -y puppet
