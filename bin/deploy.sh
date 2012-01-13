#!/bin/sh
# usage: ./deploy TYPE DIR
# TYPE = live | (anything else)
#
# Be sure to edit the following:
# _MY_SVN_DIR_ -- the base SVN dir for this project
# _MY_LIVE_PATH_ ex: /home/ippatsu/japanesetesting.com/public
# _MY_DEV_PATH_ ex: /home/ippatsu/beta.japanesetesting.com/public
if [ ! -e app ]; then
   echo "ERROR: run deploy.sh one directory up"
   exit 0
fi

# Set as Life or Dev
#if [ "$1" != "live" ]; then
#echo "Setting DEVsite"
#TARGETP="_MY_DEV_PATH_"
#else
#echo "Setting LIVE"
#TARGETP="/var/www/whyjustrun"
TARGETP="./"
#fi

# Export Data
#svn export --force svn://127.0.0.1/_MY_SVN_DIR_/$2  $TARGETP/$2
svn update $TARGETP

# Write Version to File
svn info file:///var/svn/whyjustrun/live > $TARGETP/version.txt
svn info file:///var/svn/whyjustrun/live

# Update Config File
#OLD="Configure::write('debug', 2);"
#NEW="Configure::write('debug', 0);"
#CONFIGFILE="$TARGETP/app/config/core.php"

#sed -i "s/$OLD/$NEW/g" $CONFIGFILE
#echo "Reset Config File: $CONFIGFILE"

# Clearout Cake Model Cache
rm $TARGETP/app/tmp/cache/models/cake*
rm $TARGETP/app/tmp/cache/persistent/cake*
rm $TARGETP/app/tmp/cache/views/cake*
echo "Cleared out Cache"
chmod 777 $TARGETP/app/tmp/cache
chmod 777 $TARGETP/app/tmp/cache/*


exit 0

