#!/bin/sh
# Deploys the Ruby on Rails app
cd /var/www/WhyJustRun2
sudo -u whyjustrun git pull && bundle install
service apache2 restart
