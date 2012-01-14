#!/bin/sh

CONFIG_DIR=/var/www/config/
LOCAL_CONFIG_DIR=../

for t in app/Config/core.php app/Config/email.php app/Config/database.php app/Config/bootstrap.php bin/settings.py
do
    rm ${LOCAL_CONFIG_DIR}${t}
    cp ${CONFIG_DIR}${t} ${LOCAL_CONFIG_DIR}${t}
done