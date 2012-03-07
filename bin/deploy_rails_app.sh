#!/bin/sh
# Deploys the Ruby on Rails app
cd /var/www/WhyJustRun2
sudo -u whyjustrun git pull
sudo bundle install && sudo service apache2 restart
